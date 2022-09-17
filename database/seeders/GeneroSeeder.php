<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\Instituition\Entities\Instituicao;
use Modules\User\Entities\TipoUsuario;

class GeneroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $values = json_decode(file_get_contents(__DIR__ . '/../genero.json'), true);

        foreach ($values as $value ) {
            DB::table('generos')->insert([
                'id' => (string) Str::uuid(),
                'nome' => $value['genero'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
