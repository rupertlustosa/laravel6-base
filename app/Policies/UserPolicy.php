<?php
/**
 * @package    Controller
 * @author     Rupert Brasil Lustosa <rupertlustosa@gmail.com>
 * @date       02/03/2020 19:01:44
 */

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\User as AuthUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{

    use HandlesAuthorization;

    /**
     * Determine whether the user can view any user.
     *
     * @param AuthUser $authUser
     * @return mixed
     */
    public function viewAny(AuthUser $authUser)
    {

        return true;
    }

    /**
     * Determine whether the user can create user.
     *
     * @param AuthUser $authUser
     * @return mixed
     */
    public function create(AuthUser $authUser)
    {

        return true;
    }

    /**
     * Determine whether the user can update the user.
     *
     * @param AuthUser $authUser
     * @param User $user
     * @return mixed
     */
    public function update(AuthUser $authUser, User $user)
    {

        return true;
    }

    /**
     * Determine whether the user can delete the user.
     *
     * @param AuthUser $authUser
     * @param User $user
     * @return mixed
     */
    public function delete(AuthUser $authUser, User $user)
    {

        return true;
    }

    public function profile(AuthUser $authUser, User $user)
    {
        return $authUser->id === $user->id;
    }
}
