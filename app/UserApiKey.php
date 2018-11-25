<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserApiKey extends Model{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_api_keys';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['hash', 'user_id', 'name', 'authkey'];

}
