<?php
/**
 * @package    Observers
 * @author     Rupert Brasil Lustosa <rupertlustosa@gmail.com>
 * @date       02/03/2020 18:59:03
 */

declare(strict_types=1);

namespace App\Observers;

use App\Models\Role;
use Auth;
use Illuminate\Support\Facades\Cache;

class SettingObserver
{

    /**
     * Handle the role "created" event.
     *
     * @param Role $role
     * @return void
     */
    public function created(Role $role)
    {

        Cache::forget('settings');
    }


    /**
     * Handle the role "updated" event.
     *
     * @param Role $role
     * @return void
     */
    public function updated(Role $role)
    {

        Cache::forget('settings');
    }


    /**
     * Handle the role "deleted" event.
     *
     * @param Role $role
     * @return void
     */
    public function deleted(Role $role)
    {

        Cache::forget('settings');
    }
}
