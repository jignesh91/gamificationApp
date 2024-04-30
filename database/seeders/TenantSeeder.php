<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tenant;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Tenant::create([
            'name' => 'Default Tenant',
        ]);
        // Add more tenants as needed
    }
}
