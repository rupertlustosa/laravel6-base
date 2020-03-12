<?php

namespace App\Console\Commands;

use App\Services\SaleService;
use App\Services\SettingService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class XpertImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xpert:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $settings = (new SettingService())->settings();
        $place = $settings->where('key', 'PLACE_ID')->first();
        $place_id = $place->value;

        $products = [

            [
                'identification' => 1,
                'name' => 'Etanol',
                'unit_price' => 3.59,
            ],
            [
                'identification' => 2,
                'name' => 'Gasolina Aditivada',
                'unit_price' => 4.77,
            ],
            [
                'identification' => 3,
                'name' => 'Gasolina Comun',
                'unit_price' => 4.59,
            ],
            [
                'identification' => 4,
                'name' => 'Diesel S20',
                'unit_price' => 2.90,
            ],
        ];

        $customers = [
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '853.759.630-27',
            '335.748.660-11',
            '594.372.370-69',
            '885.240.320-57',
            '075.297.870-55',
            '825.465.400-09',
            '392.194.160-18',
            '476.804.760-20',
            '011.396.080-88',
            '828.738.120-79',
        ];

        $attendants = [
            123456,
            234567,
            345678,
            456789,
            101112,
        ];

        $fuel_pumps = [
            [
                'id' => 1,
                'nozzles' => [1, 2, 3],
            ],
            [
                'id' => 2,
                'nozzles' => [4, 5, 6],
            ],
            [
                'id' => 3,
                'nozzles' => [7, 8, 9],
            ],
            [
                'id' => 4,
                'nozzles' => [10, 11, 12],
            ]
        ];

        for ($x = 1; $x <= 8; $x++) {

            $item = $products[rand(0, 3)];

            $sale = rand(10, 99) . rand(10, 99) . rand(10, 99) . rand(10, 99);
            $fuel_pump = $fuel_pumps[rand(0, (count($fuel_pumps) - 1))];
            $nozzles = $fuel_pump['nozzles'];
            $fuel_pump_nozzle = $nozzles[rand(0, (count($nozzles) - 1))];
            //dd($fuel_pump, $nozzles, $fuel_pump_nozzle);
            $attendant = $attendants[rand(0, (count($attendants) - 1))];
            //$document_number = $customers[rand(0, (count($customers) - 1))];
            $document_number = null;
            $item_quantity = rand(10, 30);
            $item_identification = $item['identification'];
            $item_name = $item['name'];
            $item_unit_price = $item['unit_price'];
            $value = $item_unit_price * $item_quantity;
            $date = now()->format('Y-m-dh:i:s');

            $dataFromXpert = 'ABASTECIMENTO=(ID=' . $sale . ';DATAHORA=' . $date . 's;DURACAO=ssss;BOMBA=' . $fuel_pump['id'] . ';BICO=' . $fuel_pump_nozzle . ';PRECO=' . $item_unit_price . ';QUANTIDADE=' . $item_quantity . ';TOTAL=' . $value . ';TOTALVOL=vvvvvvvv.vvv;TOTALMON=mmmmmmmm.mmm;FUNCIONARIO=' . $attendant . ';CLIENTE=' . $document_number . ')';

            $dataIndexed = [];

            if (preg_match('!\(([^\)]+)\)!', $dataFromXpert, $content)) {

                $dataInParenthesis = $content[1];
                $partsOfDataInParenthesis = explode(';', $dataInParenthesis);

                /*
                 array:12 [
                      0 => "ID=526cdb47-f008-4937-93d7-21efd914565a"
                      1 => "DATAHORA=2020-03-0605:07:18s"
                      2 => "DURACAO=ssss"
                      3 => "BOMBA=5"
                      4 => "BICO=1"
                      5 => "PRECO=3.59"
                      6 => "QUANTIDADE=24"
                      7 => "TOTAL=86.16"
                      8 => "TOTALVOL=vvvvvvvv.vvv"
                      9 => "TOTALMON=mmmmmmmm.mmm"
                      10 => "FUNCIONARIO=345678"
                      11 => "CLIENTE="
                    ]
                 * */

                foreach ($partsOfDataInParenthesis as $fieldMapping) {

                    $partsOfFieldMapping = explode('=', $fieldMapping);
                    if (count($partsOfFieldMapping) == 2) {

                        $fieldName = $partsOfFieldMapping[0];
                        $fieldValue = $partsOfFieldMapping[1];

                        $dataIndexed[$fieldName] = $fieldValue;
                    }
                }

                if (
                    array_key_exists('ID', $dataIndexed)
                    && array_key_exists('DATAHORA', $dataIndexed)
                    && array_key_exists('BOMBA', $dataIndexed)
                    && array_key_exists('BICO', $dataIndexed)
                    && array_key_exists('PRECO', $dataIndexed)
                    && array_key_exists('QUANTIDADE', $dataIndexed)
                    && array_key_exists('TOTAL', $dataIndexed)
                    && array_key_exists('FUNCIONARIO', $dataIndexed)
                ) {

                    $sale = $dataIndexed['ID'];
                    $date = substr($dataIndexed['DATAHORA'], 0, 18);
                    $date = Carbon::createFromFormat('Y-m-dH:i:s', $date)->format('Y-m-d H:i:s');
                    $value = $dataIndexed['TOTAL'];
                    $fuel_pump = $dataIndexed['BOMBA'];
                    $fuel_pump_nozzle = $dataIndexed['BICO'];
                    $attendant = $dataIndexed['FUNCIONARIO'];
                    $item_quantity = $dataIndexed['QUANTIDADE'];
                    $item_unit_price = $dataIndexed['PRECO'];

                    (new SaleService())->create([
                        'place_id' => $place_id,
                        'sale' => $sale,
                        'date' => $date,
                        'value' => $value,
                        'fuel_pump' => $fuel_pump,
                        'fuel_pump_nozzle' => $fuel_pump_nozzle,
                        'attendant' => $attendant,
                        'document_number' => null,
                        'item_identification' => null,
                        'item_name' => null,
                        'item_quantity' => $item_quantity,
                        'item_unit_price' => $item_unit_price,
                    ]);

                } else {

                    //TODO mapear erro
                    dd($dataIndexed);
                    //TODO save in database invalid field
                }

                sleep((1 + $x));
            } else {

                dd($dataFromXpert);
                //TODO mapear erro
            }
        }
    }
}
