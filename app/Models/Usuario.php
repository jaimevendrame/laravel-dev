<?php

namespace lotecweb\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'USUARIO';

    public $variavel = '';

    protected $fillable = [
        'NOMUSU' => 'nome',
        'LOGUSU' => 'login'

    ];



    /**
     * Many To Many
     * The base that belong to the usuario.
     */
//    public function bases()
//    {
//        return $this->belongsToMany('lotecweb\Models\Base', 'usuario_base', 'idusu', 'idbase');
//    }
}
