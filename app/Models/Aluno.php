<?php

namespace lotecweb\Models;

use Illuminate\Database\Eloquent\Model;

class Aluno extends Model
{
    protected $fillable = [
        'name',
        'cell',
        'cep',
        'rua',
        'bairro',
        'cidade',
        'uf',
        'ibge',
        'curso_info',
        'curso_ingl',
        'escolaridade',
        'in_teste',
    ];

    public $rules = [
        'name'  => 'required|min:3|max:150',
        'cell'  => 'required|min:11|unique:alunos',
        'cep'   => 'required',
    ];
}