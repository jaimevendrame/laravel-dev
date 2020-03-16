<?php

namespace lotecweb\Models;

use Illuminate\Database\Eloquent\Model;

class base extends Model
{
    protected $table = 'BASE';



    /**
     * Many To Many
     * The usuario that belong to the base.
     */
//    public function usuarios()
//    {
//        return $this->belongsToMany('lotecweb\Models\Usuario', 'usuario_base', 'idbase', 'idusu');
//    }
}
