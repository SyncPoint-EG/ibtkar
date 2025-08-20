<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddPaymentApprovalPermissionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:add-payment-approvals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds permissions for payment approvals and assigns them to super-admin.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Adding payment approval permissions...');

        $permissions = [
            'view_payment_approval',
            'accept_payment_approval',
            'reject_payment_approval',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
            $this->info("Permission '{$permissionName}' created or already exists.");
        }

        $role = Role::where('name', 'super-admin')->first();

        if ($role) {
            $role->givePermissionTo($permissions);
            $this->info('Permissions assigned to super-admin role.');
        } else {
            $this->warn('Super-admin role not found. Permissions not assigned.');
        }

        $this->info('Payment approval permissions process completed.');
    }
}