<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $roles = array(
            array(
                "id" => 1,
                "name" => "Administrador",
            ),
            array(
                "id" => 2,
                "name" => "Usuário",
            ),
        );


        foreach ($roles as $item) {

            Role::create($item);
        }
    }
}
