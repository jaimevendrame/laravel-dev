<?php

namespace lotecweb\Models;

use Illuminate\Database\Eloquent\Model;

class Movimento_caixa extends Model
{
    protected $table = 'MOVIMENTOS_CAIXA';

    protected $fillable = [
        'idbase',
        'idven',
        'idreven',
        'seqmov',
        'datmov',
        'hormov',
        'tipomov',
        'vlrmov',
        'saldoant',
        'saldoatu',
        'idusumov',
        'idcobra',
        'nomeusumov',
    ];

}
