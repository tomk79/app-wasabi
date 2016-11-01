<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class relayUsersXProjects extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'relay_users_x_projects';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'project_id', 'authority'];

}
