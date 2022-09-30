<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\Instituition\Entities\Instituicao;
use Modules\User\Entities\Genero;
use Modules\User\Entities\TipoUsuario;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $values = json_decode(file_get_contents(__DIR__ . '/../usuario.json'), true);

        foreach ($values as $value ) {
            DB::table('users')->insert([
                'id' => (string) Str::uuid(),
                'tipo_usuario_id' => TipoUsuario::where('nome','admin')->first()->id,
                'instituicao_id' => Instituicao::where('cpf_cnpj',$value['cpf_cnpj'])->first()->id,
                'genero_id' => Genero::where('nome',$value['genero'])->first()->id,
                'nome' => $value['nome'],
                'email' => $value['email'],
                'password' => Hash::make('12345678'),
                'cpf_cnpj' => $value['cpf_cnpj'],
                'tipo_documento' => $value['tipo_documento'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
