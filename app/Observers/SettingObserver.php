<?php
/**
 * @package    Observers
 * @author     Rupert Brasil Lustosa <rupertlustosa@gmail.com>
 * @date       02/03/2020 18:59:03
 */

declare(strict_types=1);

namespace App\Observers;

use App\Models\Setting;
use Auth;
use Illuminate\Support\Facades\Cache;

class SettingObserver
{

    /**
     * Handle the role "created" event.
     *
     * @param Setting $setting
     * @return void
     */
    public function created(Setting $setting)
    {

        Cache::forget('settings');
    }


    /**
     * Handle the role "updated" event.
     *
     * @param Setting $setting
     * @return void
     */
    public function updated(Setting $setting)
    {

        Cache::forget('settings');
    }


    /**
     * Handle the role "deleted" event.
     *
     * @param Setting $setting
     * @return void
     */
    public function deleted(Setting $setting)
    {

        Cache::forget('settings');
    }
}
