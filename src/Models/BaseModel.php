<?php

namespace Pitangent\Workflow\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
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
}
