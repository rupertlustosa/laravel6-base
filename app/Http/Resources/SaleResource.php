<?php
/**
 * @package    Resources
 * @author     Rupert Brasil Lustosa <rupertlustosa@gmail.com>
 * @date       09/12/2019 10:25:33
 */

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'sale' => (string)$this->sale,
            'date' => (string)$this->date,
            'value' => (string)$this->value,
            'fuel_pump' => (string)$this->fuel_pump,
            'fuel_pump_nozzle' => (string)$this->fuel_pump_nozzle,
            'attendant' => (string)$this->attendant,
            'client' => (string)$this->client,
            'item_identification' => (string)$this->item_identification,
            'item_quantity' => (string)$this->item_quantity,
            'item_unit_price' => (string)$this->item_unit_price,
        ];
    }
}
