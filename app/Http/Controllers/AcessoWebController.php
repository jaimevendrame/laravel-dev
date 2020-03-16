<?php

namespace lotecweb\Http\Controllers;

use Carbon\Carbon;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use lotecweb\Http\Requests;
use lotecweb\Models\Usuario;
use lotecweb\Models\Usuario_ven;
use lotecweb\Models\Vendedor;
use lotecweb\User;

class AcessoWebController extends StandardController
{
    protected $model;
    protected $nameView = 'dashboard.acesso.desktop.index';
    protected $data;
    protected $title = 'Acesso Web';
    protected $redirectCad = '/admin/acesso/cadastrar';
    protected $redirectEdit = '/admin/acesso/editar';
    protected $route = '/admin/acesso';
    public $data_inicial;
    public $data_fim;

    public function __construct(
        Usuario $usuario,
        Usuario_ven $usuario_ven,
        Vendedor $vendedor,
        User $user,
        Request $request)
    {
        $this->request = $request;
        $this->usuario = $usuario;
        $this->usuario_ven = $usuario_ven;
        $this->vendedor = $vendedor;
        $this->user = $user;


    }

    public function indexDesktop()
    {

        $idusu = Auth::user()->idusu;

//        $inadmin = $this->usuario
//            ->where('idusu', '=', $idusu)
//            ->first();
//
//        if ($inadmin->inadim != "SIM"){
//            return view('errors.403');
//        }

        $user_base = $this->retornaBase($idusu);

        $user_bases = $this->retornaBases($idusu);

        $usuario_lotec = $this->retornaUserLotec($idusu);

        $vendedores = $this->retornaBasesAll($idusu);

//        dd($vendedores);

        $usuarioWeb = $this->user
            ->where('idusu', '=', $idusu)
            ->first();

        $menus = $this->retornaMenu($idusu);
        $menuMaisUsu = $this->retornaMenuMaisUsu($idusu);

        $categorias = $this->retornaCategorias($menus);

        $title = 'Criar Usuário Web';

        $data = Usuario::all();

        $ideven_default = $this->returnWebControlData($idusu);

        return view("dashboard.acesso.desktop.index",compact('data',
            'usuarioWeb', 'user_base', 'user_bases', 'usuario_lotec', 'vendedores', 'categorias', 'title', 'menus', 'ideven_default', 'menuMaisUsu'));
    }

    public function indexWeb()
    {

        $idusu = Auth::user()->idusu;

//        $inadmin = $this->usuario
//            ->where('idusu', '=', $idusu)
//            ->first();
//
//        if ($inadmin->inadim != "SIM"){
//            return view('errors.403');
//        }

        $usuarioWeb = $this->user
            ->where('idusu', '=', $idusu)
            ->first();

        $user_base = $this->retornaBase($idusu);

        $user_bases = $this->retornaBases($idusu);

        $usuario_lotec = $this->retornaUserLotec($idusu);

        $vendedores = $this->retornaBasesAll($idusu);

//        dd($vendedores);

        $menus = $this->retornaMenu($idusu);
        $menuMaisUsu = $this->retornaMenuMaisUsu($idusu);

        $categorias = $this->retornaCategorias($menus);

        $title = 'Criar Usuário Web';
        $data = User::all();


        $ideven_default = $this->returnWebControlData($idusu);


        return view("dashboard.acesso.web.index",compact('data',
            'usuarioWeb', 'user_base', 'user_bases', 'usuario_lotec', 'vendedores', 'categorias', 'title', 'menus', 'ideven_default', 'menuMaisUsu'));
    }

    public function create($id)
    {
        $idusu = Auth::user()->idusu;

        $user_base = $this->retornaBase($idusu);

        $user_bases = $this->retornaBases($idusu);

        $usuario_lotec = $this->retornaUserLotec($idusu);

        $vendedores = $this->retornaBasesAll($idusu);

        $menus = $this->retornaMenu($idusu);
        $menuMaisUsu = $this->retornaMenuMaisUsu($idusu);

        $categorias = $this->retornaCategorias($menus);

        $title = 'Criar Usuário Web';


        $data = Usuario::where('idusu',$id)->first();

//        dd($id);

        $usuarioWeb = User::where('idusu', $id)->first();

//        dd($usuarioWeb);

        $ideven_default = $this->returnWebControlData($idusu);


        return view("dashboard.acesso.web.cad-edit",compact('data', 'usuarioWeb', 'user_base',
            'user_bases', 'usuario_lotec', 'vendedores', 'categorias', 'title', 'menus', 'ideven_default', 'menuMaisUsu'));
    }

    public function createGo()
    {




        $dadosForm = $this->request->all();

//        dd($dadosForm);
        $idusu = $this->request->get('idusu');
        $id = $this->request->get('id');

        $validator = validator($dadosForm, $this->user->rulesEdit);

        if ($validator->fails()) {

            return redirect("/admin/acesso/web/create/$idusu")
                ->withErrors($validator)
                ->withInput();
        }


        $dadosForm['password'] = bcrypt($dadosForm['password']);

        $insert = $this->user->create($dadosForm);

        if ($insert)

            return redirect("/admin/acesso/desktop/{$idusu}");

        else
            return redirect("/admin/acesso/web/create/$idusu")
                ->withErrors(['errors'=> 'Falha ao Editar'])
                ->withInput();


    }

    public function update($id)
    {
        $dadosForm = $this->request->all();

//        dd($dadosForm);

        $idusu = $this->request->get('idusu');
//        $id = $this->request->get('id');

        $validator = validator($dadosForm, $this->user->rulesEdit);

        if ($validator->fails()) {

            return redirect("/admin/acesso/web/create/$idusu")
                ->withErrors($validator)
                ->withInput();
        }

        $item = $this->user->find($id);


        $dadosForm['password'] = bcrypt($dadosForm['password']);

        $update = $item->update($dadosForm);

        if ($update)

            return redirect("/admin/acesso/desktop/{$idusu}");

        else
            return redirect("/admin/acesso/web/create/$idusu")
                ->withErrors(['errors'=> 'Falha ao Editar'])
                ->withInput();

    }

    public function index2($ideven)
    {
        $idusu = Auth::user()->idusu;

        $user_base = $this->retornaBase($idusu);


        $user_bases = $this->retornaBases($idusu);

        $usuario_lotec = $this->retornaUserLotec($idusu);


        $vendedores = $this->retornaBasesUser($idusu);


        $menus = $this->retornaMenu($idusu);
        $menuMaisUsu = $this->retornaMenuMaisUsu($idusu);

        $categorias = $this->retornaCategorias($menus);


        $title = $this->title;

        $baseAll = $this->retornaBasesAll($idusu);


        if (Auth::user()->idusu == 1000){


           $data= '';



        } else{
            $data = $this->retornaSenhaDia($ideven);
        }

        $ideven_default = $this->returnWebControlData($idusu);
        $ideven_default = $ideven_default->valor;

        return view("{$this->nameView}",compact('idusu',
            'user_base', 'user_bases', 'usuario_lotec', 'vendedores', 'menus', 'categorias', 'data','title', 'baseAll', 'ideven', 'ideven_default', 'menuMaisUsu'));
    }



    /**
     * @return mixed
     */



    public function retornaBasesPadrao($id){


        $data = $this->usuario_ven

            ->select('ideven')

            ->where([
                ['idusu', '=', $id],
                ['inpadrao', '=', 'SIM']
            ])
            ->first();


        return $data->ideven;
    }



    public function retornaBasesAll($id){


        $data = $this->usuario_ven

//            ->select('USUARIO_VEN.*')
            ->join('VENDEDOR', [
                ['USUARIO_VEN.IDVEN','=','VENDEDOR.IDVEN'],
                ['USUARIO_VEN.IDBASE', '=', 'VENDEDOR.IDBASE']])
            ->join('BASE', 'USUARIO_VEN.IDBASE', '=', 'BASE.IDBASE')
            ->where([
                ['USUARIO_VEN.idusu', '=', $id]
            ])
            ->orderby('INPADRAO', 'DESC')
            ->get();


//        dd($data);

        return $data;
    }


    public function retornaBasesUser($id){


        $data = $this->usuario_ven

            ->join('VENDEDOR', [
                ['USUARIO_VEN.IDVEN','=','VENDEDOR.IDVEN'],
                ['USUARIO_VEN.IDBASE', '=', 'VENDEDOR.IDBASE']])
            ->join('BASE', 'USUARIO_VEN.IDBASE', '=', 'BASE.IDBASE')
            ->where([
                ['USUARIO_VEN.idusu', '=', $id]
            ])
            ->orderby('INPADRAO', 'DESC')
            ->get();


        return $data;
    }





        //Retorna Senha do Dia.


    public function retornaSenhaDia($ideven){

        $p = $this->retornaBasepeloIdeven($ideven);
        $datadia = date ("Y/m/d");

        $data = DB::select (" SELECT SENHA_DIA.BAIXA_CAIXA
                              FROM SENHA_DIA
                                WHERE
                                 SENHA_DIA.IDBASE = '$p->idbase' AND
                                 SENHA_DIA.IDVEN = '$p->idven' AND
                                 SENHA_DIA.DIA = '$datadia' ");

        return $data;

    }






}


