<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Validator;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Client extends Model implements AuthenticatableContract, AuthorizableContract {

    use Authenticatable, Authorizable;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'activated' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'client_id',
        'client_secret',
        'redirect_uri',
        'activated',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'client_secret',
    ];


    public static function getValidator(array $parameters): \Illuminate\Contracts\Validation\Validator {
        return Validator::make($parameters, [
            'name' => 'sometimes|required|alpha_dash|max:255',
            'redirect_uri' => 'sometimes|required|url|max:255',
            'activated' => 'sometimes|required|boolean',
        ]);
    }
}
