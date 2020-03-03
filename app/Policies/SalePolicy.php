<?php
/**
 * @package    Controller
 * @author     Rupert Brasil Lustosa <rupertlustosa@gmail.com>
 * @date       03/03/2020 10:10:33
 */

declare(strict_types=1);

namespace App\Policies;

use App\Models\Sale;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SalePolicy
{

    use HandlesAuthorization;

    /**
     * Determine whether the user can view any sale.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {

        return true;
    }

    /**
     * Determine whether the user can create sale.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {

        return false;
    }

    /**
     * Determine whether the user can update the sale.
     *
     * @param User $user
     * @param Sale $sale
     * @return mixed
     */
    public function update(User $user, Sale $sale)
    {

        return false;
    }

    /**
     * Determine whether the user can delete the sale.
     *
     * @param User $user
     * @param Sale $sale
     * @return mixed
     */
    public function delete(User $user, Sale $sale)
    {

        return false;
    }
}
