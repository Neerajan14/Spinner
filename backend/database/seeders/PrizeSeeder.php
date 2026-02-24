<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PrizeSeeder extends Seeder
{
public function run()
{
    \App\Models\Prize::insert([
        ['label' => 'Pen',      'weight' => 65,   'price' => '$5',  'active' => true, 'created_at' => now(), 'updated_at' => now()],
        ['label' => 'Diary',    'weight' => 8.75, 'price' => '$15', 'active' => true, 'created_at' => now(), 'updated_at' => now()],
        ['label' => 'Stickers', 'weight' => 8.75, 'price' => '$8',  'active' => true, 'created_at' => now(), 'updated_at' => now()],
        ['label' => 'Key Ring', 'weight' => 8.75, 'price' => '$12', 'active' => true, 'created_at' => now(), 'updated_at' => now()],
        ['label' => 'Lanyard',  'weight' => 8.75, 'price' => '$10', 'active' => true, 'created_at' => now(), 'updated_at' => now()],
    ]);
}
}
