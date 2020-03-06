<?php

namespace App\Console\Commands;

use App\Services\SaleService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

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

        for ($x = 1; $x <= 3; $x++) {

            $item = $products[rand(0, 3)];

            $sale = Str::uuid();
            $fuel_pump = rand(1, 9);
            $fuel_pump_nozzle = rand(1, 9);
            $attendant = $attendants[rand(0, (count($attendants) - 1))];
            //$client = $customers[rand(0, (count($customers) - 1))];
            $client = null;
            $item_quantity = rand(10, 30);
            $item_identification = $item['identification'];
            $item_name = $item['name'];
            $item_unit_price = $item['unit_price'];
            $value = $item_unit_price * $item_quantity;
            $date = now()->format('Y-m-dh:i:s');

            $dataFromXpert = 'ABASTECIMENTO=(ID=' . $sale . ';DATAHORA=' . $date . 's;DURACAO=ssss;BOMBA=' . $fuel_pump . ';BICO=' . $fuel_pump_nozzle . ';PRECO=' . $item_unit_price . ';QUANTIDADE=' . $item_quantity . ';TOTAL=' . $value . ';TOTALVOL=vvvvvvvv.vvv;TOTALMON=mmmmmmmm.mmm;FUNCIONARIO=' . $attendant . ';CLIENTE=' . $client . ')';


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
                        'sale' => $sale,
                        'date' => $date,
                        'value' => $value,
                        'fuel_pump' => $fuel_pump,
                        'fuel_pump_nozzle' => $fuel_pump_nozzle,
                        'attendant' => $attendant,
                        'client' => null,
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

                sleep((2 + $x));
            } else {

                dd($dataFromXpert);
                //TODO mapear erro
            }
        }
    }
}