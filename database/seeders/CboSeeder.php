<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CboSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $values = json_decode(file_get_contents(__DIR__ . '/../cbo.json'), true);
        foreach ($values as $value ) {
            DB::table('cbos')->insert([
                'id' => (string) Str::uuid(),
                'codigo' => $value['codigo'],
                'nome' => $value['nome'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
