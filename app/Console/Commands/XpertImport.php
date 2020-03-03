<?php

namespace App\Console\Commands;

use App\Services\SaleService;
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

        for ($x = 1; $x <= 3; $x++) {

            $item = $products[rand(0, 3)];

            $sale = Str::uuid();
            $fuel_pump = rand(1, 9);
            $fuel_pump_nozzle = rand(1, 9);
            $attendant = '001.101.153-09';
            $client = $customers[rand(0, (count($customers) - 1))];;
            $item_quantity = rand(10, 30);
            $item_identification = $item['identification'];
            $item_name = $item['name'];
            $item_unit_price = $item['unit_price'];
            $value = $item_unit_price * $item_quantity;

            (new SaleService())->create([
                'sale' => $sale,
                'date' => now(),
                'value' => $value,
                'fuel_pump' => $fuel_pump,
                'fuel_pump_nozzle' => $fuel_pump_nozzle,
                'attendant' => $attendant,
                'client' => $client,
                'item_identification' => $item_identification,
                'item_name' => $item_name,
                'item_quantity' => $item_quantity,
                'item_unit_price' => $item_unit_price,
            ]);

            sleep((2 + $x));
        }
    }
}
