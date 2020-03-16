<?php

namespace lotecweb\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use lotecweb\Http\Requests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

use Illuminate\Routing\Controller as BaseController;
use lotecweb\Models\Cobrador;
use lotecweb\Models\Revendedor;
use lotecweb\Models\Usuario;
use lotecweb\Models\Usuario_ven;
use lotecweb\Models\Vendedor;
use lotecweb\User;


class StandardController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $request;
    protected $totalPorPagina = 15;


    public function __construct(

        Usuario $usuario,
        User $user,
        Revendedor $revendedor,
        Vendedor $vendedor,
        Cobrador $cobrador,
        Usuario_ven $usuario_ven,
        Request $request


    )
    {

        $this->request = $request;
        $this->usuario = $usuario;
        $this->user = $user;
        $this->revendedor = $revendedor;
        $this->vendedor = $vendedor;
        $this->cobrador = $cobrador;
        $this->usuario_ven = $usuario_ven;



    }

    public function index()
    {  //esta função chama a home


        $idusu = Auth::user()->idusu;

        $user_base = $this->retornaBase($idusu);

        $user_bases = $this->retornaBases($idusu);

        $usuario_lotec = $this->retornaUserLotec($idusu);

//        $vendedores = $this->retornaUsuarioVen($idusu, $user_base->pivot_idbase);

        $vendedores = $this->retornaBasesAll($idusu);

        $menus = $this->retornaMenu($idusu);
        $menuMaisUsu = $this->retornaMenuMaisUsu($idusu);

        $categorias = $this->retornaCategorias($menus);


        $title = $this->title;

        $admin = Usuario::where('idusu', '=', $idusu)->first();

        if ($admin->inadim != 'SIM'){
            $validaMesalidade = $this->validarMensalidade($idusu);
        }else{
            $validaMesalidade = null;
        }

        $consultaMensagemSis = $this->consultaMensagemSis();
        
        if ($admin->inadim != 'SIM') {

            $select_ideven = $this->request->get('select_ideven');
            $valor = $select_ideven;



            if ($valor != Null) {

                $ideven_default = $this->storeWebControlData($valor);

            } else {

                $dados = $this->returnWebControlData($idusu);

                if ($dados != Null) {

                    $ideven_default = $dados;
//                    dd($ideven_default.'c');

                } else {
                    $ideven_default = $this->returnBaseIdvenDefault($idusu);

                    $ideven_default = $ideven_default->ideven;

                }

            }

        } else {
            $ideven_default = 0;
        }

        return view("{$this->nameView}",compact('idusu','user_base', 'user_bases', 'usuario_lotec', 'vendedores', 'menus', 'categorias','title', 'validaMesalidade', 'ideven_default', 'menuMaisUsu', 'consultaMensagemSis'));
    }


    public function index3()
    {


        $idusu = Auth::user()->idusu;

        $user_base = $this->retornaBase($idusu);

        $user_bases = $this->retornaBases($idusu);

        $usuario_lotec = $this->retornaUserLotec($idusu);

//        $vendedores = $this->retornaUsuarioVen($idusu, $user_base->pivot_idbase);

        $vendedores = $this->retornaBasesAll($idusu);

        $menus = $this->retornaMenu($idusu);
        $menuMaisUsu = $this->retornaMenuMaisUsu($idusu);

        $categorias = $this->retornaCategorias($menus);

//        $data = $this->model;

        $title = $this->title;

        $admin = Usuario::where('idusu', '=', $idusu)->first();

        if ($admin->inadim != 'SIM'){
            $validaMesalidade = $this->validarMensalidade($idusu);
        }


        if (Auth::user()->idusu != 1000) {

            $select_ideven = $this->request->get('select_ideven');
            $valor = $select_ideven;



            if ($valor != Null) {

                $ideven_default = $this->storeWebControlData($valor);

//                dd($ideven_default);


            } else {

                $dados = $this->returnWebControlData($idusu);

                if ($dados != Null) {

                    $ideven_default = $dados;

                } else {
                    $ideven_default = $this->returnBaseIdvenDefault($idusu);


                }

            }


        } else {
            $ideven_default = 0;
        }


        return view("{$this->nameView}",compact('idusu',
            'user_base', 'user_bases', 'usuario_lotec', 'vendedores', 'menus', 'categorias','title', 'validaMesalidade', 'ideven_default', 'menuMaisUsu'));// , 'consultaMensagemSis'
    }


    public function delete($id)
    {
        $item = $this->model->find($id);

        $deleta = $item->delete();

        return redirect($this->route);
    }

    public function pesquisar()
    {
        $palavraPesquisa = $this->request->get('pesquisar');

        $data = $this->model->where('nome', 'LIKE', "%$palavraPesquisa%")->paginate(10);

        return view("{$this->nameView}.index", compact('data'));
    }

    public function retornaBase($idUsu){

        //retorna apenas o primeiro registro

        $data = DB::table('USUARIO_BASE')
            ->select('USUARIO_BASE.*', 'USUARIO_BASE.IDBASE AS PIVOT_IDBASE')
            ->join('BASE', 'USUARIO_BASE.IDBASE','=','BASE.IDBASE')
            ->where('USUARIO_BASE.IDUSU', '=', $idUsu)
            ->first();
        return $data;
    }

    public function retornaBases($idUsu){

        $data = DB::table('USUARIO_BASE')
            ->select('USUARIO_BASE.*','BASE.*')
            ->join('BASE', 'USUARIO_BASE.IDBASE','=','BASE.IDBASE')
            ->join('USUARIO', 'USUARIO_BASE.IDUSU', '=', 'USUARIO.IDUSU')
            ->where('USUARIO_BASE.IDUSU', '=', $idUsu)
            ->get();

        //pegar do usuarioven

        return $data;
    }

    public function retornaUserLotec($idUsu){
        $data = $this->usuario
            ->where('idusu', '=', $idUsu)
            ->first();

        return $data;
    }
    public function retornaUsuarioVen($idUsu, $idBase){
        //retorna o vendedor viculado ao usuario e a base
        dd($idBase);

        if ((!empty($idUsu)) && (!empty($idBase))){
            $data = DB::table('USUARIO_VEN')
                ->select('USUARIO_VEN.*', 'VENDEDOR.NOMVEN', 'VENDEDOR.IDVEN as PIVOT_IDVEN')
                ->join('VENDEDOR', 'USUARIO_VEN.IDVEN', '=', 'VENDEDOR.IDVEN')
                ->join('USUARIO', 'USUARIO_VEN.IDUSU', '=', 'USUARIO.IDUSU')
                ->where([
                    ['USUARIO_VEN.IDUSU', '=', $idUsu],
                    ['VENDEDOR.IDBASE', '=', $idBase]
                ])->get();

            return $data;

        } else {
            return 1;
        }



    }

    public function gerarUser(){
        $usuario_lotec = $this->usuario->get();


        foreach ($usuario_lotec as $ul){

//            dd($ul->idusu);

            $insert =  $this->user->create([
                'name' => $ul->logusu,
                'email' => strtolower(str_replace(" ","",$ul->logusu)).'@lotec.com',
                'password' => bcrypt($ul->senusu),
                'idusu' => $ul->idusu,
                'role' => \lotecweb\User::ROLE_ADMIN,

            ]);

            if ($insert)

                echo strtolower(trim($ul->logusu))."@lotec.com - OK! <br>";

            else
                return strtolower(trim($ul->logusu))."@lotec.com - Falha! <br>";
        }


    }

    public function retornaMenu($idUsu){
        $menus = DB::table('MENU_ACTION')
            ->join('USUARIO_MENU_ACTION', 'MENU_ACTION.IDACT', '=', 'USUARIO_MENU_ACTION.IDACT')
            ->where([
                ['USUARIO_MENU_ACTION.IDUSU', '=', $idUsu],
                ['USUARIO_MENU_ACTION.INLIB', '=', 'SIM'],
                ['MENU_ACTION.INWEB', '=', 'SIM'],
            ])
            ->orderBy('MENU_ACTION.ORDEM_CAT', 'asc')
            ->orderBy('MENU_ACTION.ORDEM_MENU', 'asc')
            ->get();


        return $menus;
    }


    public function retornaMenuMaisUsu($idUsu){
        $menus = DB::table('MENU_ACTION')
            ->join('USUARIO_MENU_ACTION', 'MENU_ACTION.IDACT', '=', 'USUARIO_MENU_ACTION.IDACT')
            ->where([
                ['USUARIO_MENU_ACTION.IDUSU', '=', $idUsu],
                ['USUARIO_MENU_ACTION.INLIB', '=', 'SIM'],
                ['MENU_ACTION.INWEB', '=', 'SIM'],
                ['MENU_ACTION.IN_MAISUSU', '=', 'SIM'],
            ])
            ->orderBy('MENU_ACTION.ORDEM_MAIS_USU', 'asc')
            ->get();


        return $menus;
    }


    public function retornaCategorias($menus){

        $categorias = array();

        $cat = '';

        foreach($menus as $data)
        {
            if($cat == $data->catact)
            {} else {
                $categorias[] = $data->catact;
                $cat = $data->catact;
            }
        }


        return $categorias;
    }

    public function loadData(){

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

    public function addUserWeb(){
        $usuario_lotec = $this->usuario->get();

        return view('dashboard.admin.index', compact('usuario_lotec'));
    }


    public function retornaRevendedor($id){


        $idbase = $this->retornaBasepeloIdeven($id);

        $idbase = ($idbase != Null) ? $idbase->idbase : 0;


//        dd($idbase);

        $data = $this->revendedor
            ->where('idbase','=',$idbase)
            ->orderby('nomreven')
            ->get();

        return $data;

    }

    public function retornaBasepeloIdeven($ideven){
        $data = $this->vendedor
            ->where('ideven','=', $ideven)
            ->first();

        return $data;
    }

    public function retornaCobrador($id){

        $id = $this->retornaBasepeloIdeven($id);

//        dd($id);

        if ($id != Null){
            $data = $this->cobrador
                ->select('idbase', 'idven', 'idcobra', 'nomcobra')
                ->where([
                    ['sitcobra', '=', 'ATIVO'],
                    ['idbase', '=', $id->idbase],
                    ['idven', '=', $id->idven]
                ])
                ->orderby('nomcobra')
                ->get();
        } else {
            $data = '';
        }



        return $data;
    }

    public function returnUsuarioDesktop(){

        $idusu = Auth::user()->idusu;

        $user_base = $this->retornaBase($idusu);

        $user_bases = $this->retornaBases($idusu);

        $usuario_lotec = $this->retornaUserLotec($idusu);

        $vendedores = $this->retornaBasesAll($idusu);

        $menus = $this->retornaMenu($idusu);
        $menuMaisUsu = $this->retornaMenuMaisUsu($idusu);

        $categorias = $this->retornaCategorias($menus);

        $title = 'Manager Usuário Desktop';



        $inadmin = $this->usuario
            ->where('idusu', '=', $idusu)
            ->first();
        if ($inadmin->inadim == "SIM"){
            $data = $this->usuario->all();
            $path = "dashboard.admin.usuario";
        } else {
            $data = "";
            $path = "errors.403";
        }

        return view("$path", compact('idusu',
            'user_base', 'user_bases', 'usuario_lotec', 'vendedores', 'menus', 'categorias', 'data','title', 'menuMaisUsu'));
    }


    public function returnUsuarioWeb(){

        $idusu = Auth::user()->idusu;

        $user_base = $this->retornaBase($idusu);

        $user_bases = $this->retornaBases($idusu);

        $usuario_lotec = $this->retornaUserLotec($idusu);

        $vendedores = $this->retornaBasesAll($idusu);

        $menus = $this->retornaMenu($idusu);
        $menuMaisUsu = $this->retornaMenuMaisUsu($idusu);

        $categorias = $this->retornaCategorias($menus);

        $title = 'Manager Usuário Web';

        $inadmin = $this->usuario
            ->where('idusu', '=', $idusu)
            ->first();
        if ($inadmin->inadim == "SIM"){
            $data = $this->user->all();
            $path = "dashboard.admin.uweb";
        } else {
            $data = "";
            $path = "errors.403";
        }


        return view("$path", compact('idusu',
            'user_base', 'user_bases', 'usuario_lotec', 'vendedores', 'menus', 'categorias', 'data','title', 'menuMaisUsu'));
    }

    public function createUsuarioWeb($id){

        $idusu = Auth::user()->idusu;

        $user_base = $this->retornaBase($idusu);

        $user_bases = $this->retornaBases($idusu);

        $usuario_lotec = $this->retornaUserLotec($idusu);

        $vendedores = $this->retornaBasesAll($idusu);

        $menus = $this->retornaMenu($idusu);
        $menuMaisUsu = $this->retornaMenuMaisUsu($idusu);

        $categorias = $this->retornaCategorias($menus);

        $title = 'Criar Usuário Web';








        $inadmin = $this->usuario
            ->where('idusu', '=', $idusu)
            ->first();
        if ($inadmin->inadim == "SIM"){
            $data = $this->usuario
                ->where('idusu', '=', $id)
                ->first();

            $usuarioWeb = $this->user
                ->where('idusu', '=', $id)
                ->first();

            $path = "dashboard.admin.createuweb";
        } else {
            $data = "";
            $usuarioWeb = "";
            $path = "errors.403";
        }

        return view("$path", compact('idusu',
            'user_base', 'user_bases', 'usuario_lotec', 'vendedores', 'menus', 'categorias', 'data','title', 'usuarioWeb', 'menuMaisUsu'));
    }


    public function updateUsuarioWeb(){

        $dadosForm = $this->request->all();


        $idusu = $this->request->get('idusu');
        $id = $this->request->get('id');

        $validator = validator($dadosForm, $this->user->rulesEdit);

        if ($validator->fails()) {

            return redirect("/admin/manager/web/create/$idusu")
                ->withErrors($validator)
                ->withInput();
        }

        $item = $this->user->find($id);


        $dadosForm['password'] = bcrypt($dadosForm['password']);

        $update = $item->update($dadosForm);

        if ($update)

            return redirect("/admin/manager/desktop/");

        else
            return redirect("/admin/manager/web/create/$id")
                ->withErrors(['errors'=> 'Falha ao Editar'])
                ->withInput();
    }

    public function insertUsuarioWeb(){


        $dadosForm = $this->request->all();

        dd($dadosForm);
        $idusu = $this->request->get('idusu');
        $id = $this->request->get('id');

        $validator = validator($dadosForm, $this->user->rulesEdit);

        if ($validator->fails()) {

            return redirect("/admin/manager/web/create/$idusu")
                ->withErrors($validator)
                ->withInput();
        }


        $dadosForm['password'] = bcrypt($dadosForm['password']);

        $insert = $this->user->create($dadosForm);

        if ($insert)

            return redirect("/admin/manager/desktop/");

        else
            return redirect("/admin/manager/web/create/$id")
                ->withErrors(['errors'=> 'Falha ao Editar'])
                ->withInput();
    }
    public function WebGo(){


        $dadosForm = $this->request->all();

        dd($dadosForm);


    }

    public function validarMensalidade($idusu){

        $p = $this->returnBaseIdvenDefault($idusu);
         $d = date ("Y-m-d");



        $data = DB::table('cobranca')
                    ->select('datven','datpro' )
                    ->where([
                        ['idbase','=', $p->idbase],
                        ['idven', '=', $p->idven],
                        ['sitcob', '=', 'ABERTO']
                    ])
                    ->orderby('idcob','asc')
                    ->first();

        return $data;
    }

    public function consultaMensagemSis(){

        $datIni = $this->request->get('datIni');
        if ($datIni == ''){
            $datIni = date ("Y/m/d");
        } else {

            //Converte data inicial de string para Date(y/m/d)
            $datetimeinicial = new DateTime();
            $newDateInicial = $datetimeinicial->createFromFormat('d/m/Y', $datIni);

            $datIni = $newDateInicial->format('Y/m/d');
            dd($datIni);
        }
    

        $data = DB::table('msgsis')
                    ->select('idmsg','msg','sitmsg','datmsg','datval' )
                    ->where([
                    
                        ['sitmsg', '=', 'ATIVO'],
                        ['datval', '>=', $datIni],
                        
                    ])
                    ->orderby('idmsg','DESC')
                    ->first();

        return $data;
    }


    public function returnBaseIdvenDefault($idusu){



        $data = DB::table('usuario_ven')
                    ->where([
                        ['inpadrao', '=', 'SIM'],
                        ['idusu', '=', $idusu]
                    ])
            ->first();

        return $data;
    }


    public function webinsertGo(){

        $data = $this->request->all();


        return $data;
    }



    public function storeWebControlData($valor){

        $idusu = Auth::user()->idusu;

        $valor =  $valor;

        $last_valor = DB::table('webcontrol')
            ->where('idusu', '=', $idusu)
            ->first();

        $last_valor_var = ($last_valor != Null) ? $last_valor->valor : "";


        $dados =  [
            "idusu"     => $idusu,
            "valor"   => $valor,
            "last_valor"    => $last_valor_var,
        ];


        if ($last_valor == Null){
            $resultado = DB::table('WEBCONTROL')->insert($dados);

//            dd($resultado);

        } else {

            $resultado = DB::update(DB::RAW('update webcontrol set last_valor = '  .$last_valor_var. ',
                valor = '.$valor.'  where id ='. $last_valor->id));

//            dd($resultado);

        }

        $data = $this->returnWebControlData($idusu);


        return $data;


    }


    public function returnWebControlData($idusu){

        $data = DB::table('webcontrol')
            ->select('valor')
            ->where('idusu', $idusu)
            ->first();

//        dd($data);
        if ($data != Null){
            $valor = $data->valor;
        } else {
            $valor = 0;
        }

//        dd($valor.'teste');


        return $valor;

    }

    public function returnIdevenQuery($ideven){
        $idusu = Auth::user()->idusu;
        $admin = Usuario::where('idusu', '=', $idusu)->first();


        if ($admin->inadim == 'SIM'){

            $data = $this->vendedor
                ->select('ideven')
                ->get();

            $palavra = "";


            $c = 0;
            foreach ($data as $key){

//                echo  $key['ideven'].'\n';
//                echo $c.'\n';
                $palavra = $palavra.$key['ideven'];
                if ($c < count($data)-1){
                    $palavra = $palavra.",";
                }
                $c++;


            }
            $ideven = $palavra;





        } else{
            $ideven = $ideven;
        }

//        dd($ideven.'d');
        return $ideven;

    }

    public function retornaAdmin(){

        $valor = Usuario::where('idusu', '=', Auth::user()->idusu)->first();



        $data = $valor->inadim;

        return $data;

    }


}
