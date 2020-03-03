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

class RoleObserver
{

    /**
     * Handle the role "creating" event.
     *
     * @param Role $role
     * @return void
     */
    public function creating(Role $role)
    {

        $role->user_creator_id = Auth::id();
        //$role->user_updater_id = \Auth::id();
    }


    /**
     * Handle the role "updating" event.
     *
     * @param Role $role
     * @return void
     */
    public function updating(Role $role)
    {

        $role->user_updater_id = Auth::id();
    }


    /**
     * Handle the role "deleting" event.
     *
     * @param Role $role
     * @return void
     */
    public function deleting(Role $role)
    {

        $role->user_eraser_id = Auth::id();
        $role->timestamps = false;
        $role->save();
    }
}
