<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{

    use SoftDeletes;

    const ADMINISTRATOR = 1;
    const ATTENDANT = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    # Accessors & Mutators

    # Relationships
    /**
     * The users that belong to the role.
     */
    public function users()
    {
        return $this->belongsToMany(User::class)->using(RoleUser::class);
    }
}
