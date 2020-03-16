<?php

namespace lotecweb\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use lotecweb\Models\Aluno;
use lotecweb\Models\Movimento_caixa;
use lotecweb\Models\ResumoCaixa;
use lotecweb\Models\Revendedor;
use lotecweb\User;
use lotecweb\Models\Usuario;
use lotecweb\Models\Usuario_ven;
use lotecweb\Models\Base;

class HomeController extends StandardController
{
    protected $model;
    protected $nameView = 'home';
    protected $data;
    protected $title = 'Resumo Geral por Revendedor';
    protected $redirectCad = '/admin/contatos/cadastrar';
    protected $redirectEdit = '/admin/contatos/editar';
    protected $route = '/admin/contatos';

    public function __construct(
        User $user,
        Usuario $usuario,
        Revendedor $revendedor,
        ResumoCaixa $resumocaixa,
        Request $request)
    {
        $this->user = $user;
        $this->request = $request;
        $this->usuario = $usuario;
        $this->revendedor = $revendedor;
        $this->resumocaixa = $resumocaixa;
        $this->model = $this->$revendedor;


    }





}
