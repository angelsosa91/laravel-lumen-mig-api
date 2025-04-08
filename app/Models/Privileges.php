<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Privileges extends Model {

    protected $table = 'privileges';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'id_rol', 'id_module', 'access', 'status', 'read', 'write', 'update', 'delete'
    ];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    /*
    protected $hidden = [
        'password',
    ];
    */
}