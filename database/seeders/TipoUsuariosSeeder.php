<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TipoUsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $values = json_decode(file_get_contents(__DIR__ . '/../tipo-usuario.json'), true);

        foreach ($values as $value ) {
            DB::table('tipo_usuarios')->insert([
                'id' => (string) Str::uuid(),
                'nome' => $value['nome'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
