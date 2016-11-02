<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectMembers extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'project_members';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'project_id', 'authority'];

}
