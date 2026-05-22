<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DefaultStoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Store::updateOrCreate(
            ['domain' => 'localhost'],
            ['name' => 'Default Store']
        );

        \App\Models\Store::updateOrCreate(
            ['domain' => '127.0.0.1'],
            ['name' => 'Default Store 127']
        );
    }
}
