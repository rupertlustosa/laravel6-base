<?php
/**
 * @package    Seeder
 * @author     Rupert Brasil Lustosa <rupertlustosa@gmail.com>
 * @date       10/03/2020 10:50:31
 */

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $settings = array(
            array(
                "id" => 1,
                "description" => "Número de bicos",
                "key" => "NOZZLE_NUMBER",
                "value" => "15",
            ),
            array(
                "id" => 2,
                "description" => "URL do Projeto WEB",
                "key" => "API_URL",
                "value" => "https://betaprogramadefidelidade.smartercode.com.br",
                //"value" => "http://dev.smartercode.programa-de-fidelidade",
            ),
        );

        foreach ($settings as $item) {

            Setting::create($item);
        }
    }
}
