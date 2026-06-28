<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Pemilih
            ['name' => 'voters.view',   'label' => 'Lihat Data Pemilih',  'group' => 'Pemilih'],
            ['name' => 'voters.create', 'label' => 'Tambah Pemilih',      'group' => 'Pemilih'],
            ['name' => 'voters.edit',   'label' => 'Edit Pemilih',        'group' => 'Pemilih'],
            ['name' => 'voters.delete', 'label' => 'Hapus Pemilih',       'group' => 'Pemilih'],
            ['name' => 'voters.import', 'label' => 'Import Pemilih',      'group' => 'Pemilih'],
            // Kandidat
            ['name' => 'candidates.view',   'label' => 'Lihat Kandidat',  'group' => 'Kandidat'],
            ['name' => 'candidates.create', 'label' => 'Tambah Kandidat', 'group' => 'Kandidat'],
            ['name' => 'candidates.edit',   'label' => 'Edit Kandidat',   'group' => 'Kandidat'],
            ['name' => 'candidates.delete', 'label' => 'Hapus Kandidat',  'group' => 'Kandidat'],
            // Voting
            ['name' => 'election.manage',  'label' => 'Kelola Pemilihan',  'group' => 'Voting'],
            ['name' => 'election.results', 'label' => 'Lihat Hasil',       'group' => 'Voting'],
            ['name' => 'election.export',  'label' => 'Export Hasil',      'group' => 'Voting'],
            // Pengguna
            ['name' => 'users.view',   'label' => 'Lihat Pengguna',  'group' => 'Pengguna'],
            ['name' => 'users.create', 'label' => 'Tambah Pengguna', 'group' => 'Pengguna'],
            ['name' => 'users.edit',   'label' => 'Edit Pengguna',   'group' => 'Pengguna'],
            ['name' => 'users.delete', 'label' => 'Hapus Pengguna',  'group' => 'Pengguna'],
            // Role & Akses
            ['name' => 'roles.view',   'label' => 'Lihat Role',   'group' => 'Role & Akses'],
            ['name' => 'roles.manage', 'label' => 'Kelola Role',  'group' => 'Role & Akses'],
            // Sistem
            ['name' => 'settings.view',   'label' => 'Lihat Pengaturan',   'group' => 'Sistem'],
            ['name' => 'settings.manage', 'label' => 'Kelola Pengaturan',  'group' => 'Sistem'],
            ['name' => 'logs.view',       'label' => 'Lihat Log Sistem',   'group' => 'Sistem'],
        ];

        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name' => $p['name']], $p);
        }

        $allIds = Permission::pluck('id');

        $roles = [
            [
                'name' => 'super-admin', 'label' => 'Super Admin',
                'description' => 'Akses penuh ke seluruh sistem.', 'is_system' => true,
                'permissions' => $allIds,
            ],
            [
                'name' => 'admin', 'label' => 'Admin',
                'description' => 'Kelola pemilih, kandidat, dan jadwal pemilihan.', 'is_system' => true,
                'permissions' => Permission::whereIn('name', [
                    'voters.view','voters.create','voters.edit','voters.delete','voters.import',
                    'candidates.view','candidates.create','candidates.edit','candidates.delete',
                    'election.manage','election.results','election.export',
                    'users.view','settings.view',
                ])->pluck('id'),
            ],
            [
                'name' => 'operator', 'label' => 'Operator',
                'description' => 'Input data pemilih dan kandidat, lihat hasil.', 'is_system' => false,
                'permissions' => Permission::whereIn('name', [
                    'voters.view','voters.create','voters.edit','voters.import',
                    'candidates.view','candidates.create','candidates.edit',
                    'election.results',
                ])->pluck('id'),
            ],
            [
                'name' => 'viewer', 'label' => 'Viewer',
                'description' => 'Hanya dapat melihat data tanpa mengubah apapun.', 'is_system' => false,
                'permissions' => Permission::whereIn('name', [
                    'voters.view','candidates.view','election.results',
                ])->pluck('id'),
            ],
            [
                'name' => 'alumni', 'label' => 'Alumni',
                'description' => 'Akun alumni — dapat mengikuti pemilihan.', 'is_system' => true,
                'permissions' => Permission::whereIn('name', [
                    'election.results',
                ])->pluck('id'),
            ],
        ];

        foreach ($roles as $r) {
            $perms    = $r['permissions'];
            $isSystem = $r['is_system'];
            unset($r['permissions']);
            $role = Role::firstOrCreate(['name' => $r['name']], $r);
            // Pastikan is_system selalu benar meski role sudah ada sebelumnya
            if ($role->is_system !== $isSystem) {
                $role->update(['is_system' => $isSystem]);
            }
            $role->permissions()->sync($perms);
        }
    }
}
