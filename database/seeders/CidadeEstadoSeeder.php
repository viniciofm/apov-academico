<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CidadeEstadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $values = json_decode(file_get_contents(__DIR__ . '/../estados-cidades.json'), true);

        foreach ($values as $value ) {
            $estadoId = (string) Str::uuid();
            DB::table('estados')->insert([
                'id' => $estadoId,
                'nome' => $value['nome'],
                'sigla' => $value['sigla'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($value['cidades'] as $item ) {
                DB::table('cidades')->insert([
                    'id' => (string) Str::uuid(),
                    'nome' => $item,
                    'estado_id' => $estadoId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
