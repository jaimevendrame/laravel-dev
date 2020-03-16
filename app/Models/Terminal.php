<?php

namespace lotecweb\Models;

use Illuminate\Database\Eloquent\Model;


class Terminal extends Model
{




    protected $table = 'TERMINAL';

    public $timestamps = false;


    protected $fillable = [
        'SITTER', 'DATALT', 'IDUSUALT',
    ];

    protected $guarded = ['UPDATED_AT'];
}