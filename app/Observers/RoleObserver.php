<?php
/**
 * @package    Observers
 * @author     Rupert Brasil Lustosa <rupertlustosa@gmail.com>
 * @date       02/03/2020 17:07:25
 */

declare(strict_types=1);

namespace App\Observers;

use App\Models\Role;

class RoleObserver
{

    /**
     * Handle the role "creating" event.
     *
     * @param  \App\Models\Role  $role
     * @return void
     */
    public function creating(Role $role)
    {

        $role->user_creator_id = \Auth::id();
        //$role->user_updater_id = \Auth::id();
    }


    /**
     * Handle the role "updating" event.
     *
     * @param  \App\Models\Role  $role
     * @return void
     */
    public function updating(Role $role)
    {

        $role->user_updater_id = \Auth::id();
    }


    /**
     * Handle the role "deleting" event.
     *
     * @param  \App\Models\Role  $role
     * @return void
     */
    public function deleting(Role $role)
    {

        $role->user_eraser_id = \Auth::id();
        $role->timestamps = false;
        $role->save();
    }
}
