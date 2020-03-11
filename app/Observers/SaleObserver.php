<?php
/**
 * @package    Observers
 * @author     Rupert Brasil Lustosa <rupertlustosa@gmail.com>
 * @date       03/03/2020 10:10:33
 */

declare(strict_types=1);

namespace App\Observers;

use App\Models\Sale;
use App\Services\SaleService;

class SaleObserver
{

    /**
     * Handle the sale "creating" event.
     *
     * @param Sale $sale
     * @return void
     */
    public function creating(Sale $sale)
    {

        //$sale->user_creator_id = \Auth::id();
    }


    /**
     * Handle the sale "updating" event.
     *
     * @param Sale $sale
     * @return void
     */
    public function updating(Sale $sale)
    {

        if (empty($sale->getOriginal('document_number')) && !empty($sale->document_number) && empty($sale->synced_at)) {

            (new SaleService())->syncToWeb($sale);
        }
    }

    /**
     * Handle the sale "deleting" event.
     *
     * @param Sale $sale
     * @return void
     */
    public function deleting(Sale $sale)
    {

    }
}
