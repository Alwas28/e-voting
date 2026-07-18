<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class AlumniController extends Controller
{
    public function index(Request $request)
    {
        $query = Alumni::query();

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($qb) use ($q) {
                $qb->where('name', 'like', "%$q%")
                   ->orWhere('nim', 'like', "%$q%")
                   ->orWhere('email', 'like', "%$q%");
            });
        }

        if ($request->filled('faculty')) {
            $query->where('faculty', $request->faculty);
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        if ($request->filled('year')) {
            $query->where('graduation_year', $request->year);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $alumni      = $query->orderBy('name')->paginate(15)->withQueryString();
        $faculties   = Alumni::distinct()->orderBy('faculty')->pluck('faculty');
        $departments = Alumni::distinct()->orderBy('department')->pluck('department');
        $years       = Alumni::distinct()->orderByDesc('graduation_year')->pluck('graduation_year');

        $stats = [
            'total'    => Alumni::count(),
            'active'   => Alumni::where('is_active', true)->count(),
            'inactive' => Alumni::where('is_active', false)->count(),
            'faculties' => Alumni::distinct('faculty')->count('faculty'),
        ];

        return view('admin.alumni.index', compact('alumni', 'faculties', 'departments', 'years', 'stats'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nim'             => 'required|string|max:20|unique:alumni,nim',
            'name'            => 'required|string|max:100',
            'faculty'         => 'required|string|max:100',
            'department'      => 'required|string|max:100',
            'place_of_birth'  => 'nullable|string|max:100',
            'date_of_birth'   => 'nullable|date|before:today',
            'graduation_year' => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'ipk'             => 'nullable|numeric|min:0|max:4',
            'email'           => 'nullable|email|max:100',
            'phone'           => 'nullable|string|max:20',
            'address'         => 'nullable|string|max:255',
        ]);

        Alumni::create($data);

        return back()->with('success', 'Data alumni berhasil ditambahkan.');
    }

    public function update(Request $request, Alumni $alumnus)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:100',
            'faculty'         => 'required|string|max:100',
            'department'      => 'required|string|max:100',
            'place_of_birth'  => 'nullable|string|max:100',
            'date_of_birth'   => 'nullable|date|before:today',
            'graduation_year' => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'ipk'             => 'nullable|numeric|min:0|max:4',
            'email'           => 'nullable|email|max:100',
            'phone'           => 'nullable|string|max:20',
            'address'         => 'nullable|string|max:255',
            'is_active'       => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $alumnus->update($data);

        // Sync nama/fakultas/prodi ke kandidat yang terhubung
        Candidate::where('alumni_id', $alumnus->id)->update([
            'name'       => $data['name'],
            'faculty'    => $data['faculty'],
            'department' => $data['department'],
        ]);

        return back()->with('success', 'Data alumni berhasil diperbarui.');
    }

    public function destroy(Alumni $alumnus)
    {
        $alumnus->delete();
        return back()->with('success', 'Data alumni berhasil dihapus.');
    }

    public function toggleStatus(Alumni $alumnus)
    {
        $alumnus->update(['is_active' => !$alumnus->is_active]);
        $status = $alumnus->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Alumni \"{$alumnus->name}\" berhasil {$status}.");
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ], [
            'file.required' => 'Pilih file Excel terlebih dahulu.',
            'file.mimes'    => 'File harus berformat .xlsx, .xls, atau .csv.',
            'file.max'      => 'Ukuran file maksimal 5 MB.',
        ]);

        $spreadsheet = IOFactory::load($request->file('file')->getRealPath());
        $rows        = $spreadsheet->getActiveSheet()->toArray(null, true, true, false);

        // Skip header row
        array_shift($rows);

        $inserted = 0;
        $skipped  = 0;
        $errors   = [];

        foreach ($rows as $i => $row) {
            $rowNum = $i + 2;

            // Skip completely empty rows
            if (empty(array_filter($row, fn($v) => $v !== null && $v !== ''))) {
                continue;
            }

            $data = [
                'nim'             => trim((string) ($row[0] ?? '')),
                'name'            => trim((string) ($row[1] ?? '')),
                'faculty'         => trim((string) ($row[2] ?? '')),
                'department'      => trim((string) ($row[3] ?? '')),
                'place_of_birth'  => trim((string) ($row[4] ?? '')) ?: null,
                'date_of_birth'   => trim((string) ($row[5] ?? '')) ?: null,
                'graduation_year' => (int) ($row[6] ?? 0),
                'ipk'             => ($row[7] !== null && $row[7] !== '') ? (float) $row[7] : null,
                'email'           => trim((string) ($row[8] ?? '')) ?: null,
                'phone'           => trim((string) ($row[9] ?? '')) ?: null,
                'address'         => trim((string) ($row[10] ?? '')) ?: null,
            ];

            // Normalize date
            if ($data['date_of_birth']) {
                // PhpSpreadsheet may return date as Excel serial number
                if (is_numeric($data['date_of_birth'])) {
                    $data['date_of_birth'] = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(
                        (float) $data['date_of_birth']
                    )->format('Y-m-d');
                } else {
                    // Try to parse common date strings
                    try {
                        $data['date_of_birth'] = \Carbon\Carbon::parse($data['date_of_birth'])->format('Y-m-d');
                    } catch (\Exception) {
                        $data['date_of_birth'] = null;
                    }
                }
            }

            $validator = Validator::make($data, [
                'nim'             => 'required|string|max:20|unique:alumni,nim',
                'name'            => 'required|string|max:100',
                'faculty'         => 'required|string|max:100',
                'department'      => 'required|string|max:100',
                'place_of_birth'  => 'nullable|string|max:100',
                'date_of_birth'   => 'nullable|date|before:today',
                'graduation_year' => 'required|integer|min:1990|max:' . (date('Y') + 1),
                'ipk'             => 'nullable|numeric|min:0|max:4',
                'email'           => 'nullable|email|max:100',
                'phone'           => 'nullable|string|max:20',
                'address'         => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                $skipped++;
                $errors[] = "Baris {$rowNum} ({$data['nim']}): " . implode(', ', $validator->errors()->all());
                continue;
            }

            Alumni::create($data);
            $inserted++;
        }

        $message = "{$inserted} data alumni berhasil diimpor.";
        if ($skipped > 0) {
            $message .= " {$skipped} baris dilewati karena tidak valid.";
        }

        if ($inserted === 0 && $skipped > 0) {
            return back()
                ->with('error', $message)
                ->with('import_errors', array_slice($errors, 0, 20));
        }

        return back()
            ->with('success', $message)
            ->with('import_errors', $errors ? array_slice($errors, 0, 20) : null);
    }

    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Alumni');

        // Headers
        $headers = [
            'NIM', 'Nama Lengkap', 'Fakultas', 'Jurusan/Prodi',
            'Tempat Lahir', 'Tanggal Lahir (YYYY-MM-DD)',
            'Tahun Lulus', 'IPK (0.00-4.00)', 'Email', 'No. HP', 'Alamat',
        ];
        foreach ($headers as $col => $header) {
            $cell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 1) . '1';
            $sheet->setCellValue($cell, $header);
            $sheet->getStyle($cell)->getFont()->setBold(true);
            $sheet->getStyle($cell)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('1E40AF');
            $sheet->getStyle($cell)->getFont()->getColor()->setRGB('FFFFFF');
        }

        // Sample row
        $sample = [
            '2020001234', 'Budi Santoso', 'Fakultas Teknik', 'Teknik Informatika',
            'Kendari', '1998-05-15', '2022', '3.75', 'budi@email.com', '08123456789',
            'Jl. Contoh No. 1, Kendari',
        ];
        foreach ($sample as $col => $val) {
            $cell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 1) . '2';
            $sheet->setCellValue($cell, $val);
            $sheet->getStyle($cell)->getFont()->getColor()->setRGB('6B7280');
        }

        // Auto-width
        foreach (range(1, count($headers)) as $col) {
            $sheet->getColumnDimensionByColumn($col)->setAutoSize(true);
        }

        // Freeze header row
        $sheet->freezePane('A2');

        $writer   = new Xlsx($spreadsheet);
        $filename = 'template_import_alumni.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control'       => 'max-age=0',
        ]);
    }
}
