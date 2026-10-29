<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Super admin for Filament panel (/admin).
     */
    public function run(): void
    {
        foreach (['super_admin', 'admin', 'staff', 'customer'] as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        $admin = User::withTrashed()->where('email', 'admin@noircollective.com')->first();

        if ($admin?->trashed()) {
            $admin->restore();
        }

        $admin = User::updateOrCreate(
            ['email' => 'admin@noircollective.com'],
            [
                'name' => 'Super Admin',
                'password' => 'password',
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        $admin->syncRoles(['super_admin']);

        $this->command?->info('Super Admin ready: admin@noircollective.com / password');
        $this->command?->info('Login at: '.url('/admin/login'));
    }
}
