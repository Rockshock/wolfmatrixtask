<?php

namespace Database\Seeders;

use App\Models\Ticket;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (range(1, 100) as $i) {
            Ticket::create([
                'code' => 'TICKETNo-' . str_pad($i, 4, '0', STR_PAD_LEFT),
            ]);
        }
    }
}
