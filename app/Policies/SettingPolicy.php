<?php
/**
 * @package    Controller
 * @author     Rupert Brasil Lustosa <rupertlustosa@gmail.com>
 * @date       10/03/2020 10:50:31
 */

declare(strict_types=1);

namespace App\Policies;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SettingPolicy
{

    use HandlesAuthorization;

    /**
     * Determine whether the user can view any setting.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {

        return true;
    }

    /**
     * Determine whether the user can create setting.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {

        return true;
    }

    /**
     * Determine whether the user can update the setting.
     *
     * @param User $user
     * @param Setting $setting
     * @return mixed
     */
    public function update(User $user, Setting $setting)
    {

        return true;
    }

    /**
     * Determine whether the user can delete the setting.
     *
     * @param User $user
     * @param Setting $setting
     * @return mixed
     */
    public function delete(User $user, Setting $setting)
    {

        return true;
    }
}
