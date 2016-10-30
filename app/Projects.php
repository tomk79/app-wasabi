<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Projects extends Model
{
    use SoftDeletes;

    /**
     * 日付へキャストする属性
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    // protected $table = 'projects';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = ['user_id', 'name', 'account'];

}
