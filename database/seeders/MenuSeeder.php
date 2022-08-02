<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\User\Entities\TipoUsuario;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $values = json_decode(file_get_contents(__DIR__ . '/../menu.json'), true);

        foreach ($values as $value ) {
            $id = (string) Str::uuid();
            $tipo = TipoUsuario::where('nome',$value['tipo_usuario'])->first();

            DB::table('menus')->insert([
                'id' => $id,
                'nome' => $value['nome'],
                'posicao' => $value['posicao'],
                'url' => $value['url'],
                'icon' => $value['icon'],
                'tipo_usuario_id' => $tipo->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            foreach ($value['submenus'] as $item){
                $subid = (string) Str::uuid();
                DB::table('menus')->insert([
                    'id' => $subid,
                    'menu_id' => $id,
                    'nome' => $item['nome'],
                    'posicao' => $item['posicao'],
                    'url' => $item['url'],
                    'icon' => $item['icon'],
                    'tipo_usuario_id' => $tipo->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
