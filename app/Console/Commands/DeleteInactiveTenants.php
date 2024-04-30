<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;

class DeleteInactiveTenants extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-inactive-tenants';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $inactiveTenants = Tenant::whereDoesntHave('users.experiences', function ($query) {
            $query->where('created_at', '>=', now()->subYear());
        })->get();

        foreach ($inactiveTenants as $tenant) {
            $tenant->delete();  // Ensure proper cascading is set up in your models
        }

        return 0;
    }

}
