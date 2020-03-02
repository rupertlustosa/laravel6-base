<?php
/**
 * @package    Seeder
 * @author     Rupert Brasil Lustosa <rupertlustosa@gmail.com>
 * @date       02/03/2020 17:07:25
 */

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $itens = [];

        foreach ($itens as $item) {

            \App\Models\Role::create($item);
        }
    }
}
		