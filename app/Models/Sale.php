<?php
/**
 * @package    Controller
 * @author     Rupert Brasil Lustosa <rupertlustosa@gmail.com>
 * @date       03/03/2020 10:10:33
 */

declare(strict_types=1);

namespace App\Models;

use App\Traits\CreationDataTrait;
use Illuminate\Database\Eloquent\Model;


class Sale extends Model
{

    use CreationDataTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sale',
        'date',
        'value',
        'fuel_pump',
        'fuel_pump_nozzle',
        'attendant',
        'document_number',
        'name',
        'birth',
        'phone',
        'item_identification',
        'item_name',
        'item_quantity',
        'item_unit_price',
    ];

    protected $dates = ['date', 'synced_at'];

    # Accessors & Mutators

    # Relationships

    # Methods
}
