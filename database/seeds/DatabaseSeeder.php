<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $this->call(\Database\Seeders\create_global_permissionsSeeder::class);
            $this->call(\Database\Seeders\create_initial_admin::class);
    }
}
