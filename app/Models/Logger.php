<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logger extends Model {

    protected $table = 'data_logger';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'descripcion', 'status'
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