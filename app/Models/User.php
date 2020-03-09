<?php
/**
 * @package    Controller
 * @author     Rupert Brasil Lustosa <rupertlustosa@gmail.com>
 * @date       02/03/2020 19:01:44
 */

declare(strict_types=1);

namespace App\Models;

use App\Traits\CreationDataTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    use CreationDataTrait;
    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    # Accessors & Mutators

    # Relationships
    /**
     * The users that belong to the role.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class)->using(RoleUser::class);
    }

    # Others
    public function canLoginInPanel(): bool
    {

        return true;
    }
}
