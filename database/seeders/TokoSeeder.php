<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Toko;

class TokoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tokoList = [
            ['kode_toko' => 'TK001', 'nama_toko' => 'Toko Sejahtera'],
            ['kode_toko' => 'TK002', 'nama_toko' => 'Toko Makmur'],
            ['kode_toko' => 'TK003', 'nama_toko' => 'Toko Maju Jaya'],
            ['kode_toko' => 'TK004', 'nama_toko' => 'Toko Berkah'],
            ['kode_toko' => 'TK005', 'nama_toko' => 'Toko Sentosa'],
            ['kode_toko' => 'TK006', 'nama_toko' => 'Toko Harapan'],
            ['kode_toko' => 'TK007', 'nama_toko' => 'Toko Abadi'],
            ['kode_toko' => 'TK008', 'nama_toko' => 'Toko Mandiri'],
            ['kode_toko' => 'TK009', 'nama_toko' => 'Toko Bersama'],
            ['kode_toko' => 'TK010', 'nama_toko' => 'Toko Gemilang'],
        ];

        foreach ($tokoList as $toko) {
            Toko::updateOrCreate(
                ['kode_toko' => $toko['kode_toko']],
                $toko
            );
        }
    }
}
