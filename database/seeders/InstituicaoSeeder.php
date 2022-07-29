<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InstituicaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $values = json_decode(file_get_contents(__DIR__ . '/../instituicao.json'), true);

        foreach ($values as $value ) {
            DB::table('instituicoes')->insert([
                'id' => (string) Str::uuid(),
                'nome' => $value['nome'],
                'email' => $value['email'],
                'responsavel' => $value['responsavel'],
                'telefone_contato' => $value['telefone_contato'],
                'cpf_cnpj' => $value['cpf_cpnj'],
                'tipo_documento' => $value['tipo_documento'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
