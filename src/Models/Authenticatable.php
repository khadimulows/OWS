<?php

namespace Pitangent\Workflow\Models;

use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Auth\Authenticatable as AuthenticatableBase;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

abstract class Authenticatable extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract, JWTSubject
{
    use AuthenticatableBase, Authorizable, CanResetPassword, MustVerifyEmail, Notifiable;

    /**
     * @var FILTER_KEYS array
     */
    CONST FILTER_KEYS = [];

    /**
     * @var CREATE_VALIDATIONS array
     */
    CONST CREATE_VALIDATIONS = [];

    /**
     * @var UPDATE_VALIDATIONS array
     */
    CONST UPDATE_VALIDATIONS = [];

    public function getId() : int {
        return $this->{$this->primaryKey};
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
