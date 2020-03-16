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

class mensagemRecebidaController extends StandardController
{
    protected $model;
    protected $nameView = 'dashboard.mensagemrecebida';
    protected $data;
    protected $title = 'Mensagens Recebidas';
    protected $redirectCad = '/admin/contatos/cadastrar';
    protected $redirectEdit = '/admin/contatos/editar';
    protected $route = '/admin/contatos';
    public $data_inicial;
    public $data_fim;

    public function __construct(
        Usuario $usuario,
        Usuario_ven $usuario_ven,
        Vendedor $vendedor,
        Request $request)
    {
        $this->request = $request;
        $this->usuario = $usuario;
        $this->usuario_ven = $usuario_ven;
        $this->vendedor = $vendedor;


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

        $admin = Usuario::where('idusu', '=', $idusu)->first();
    
      //  dd($tipoSenha);
      
        if ($admin->inadim == 'SIM'){


            $data= '';
            $individual ='';
 
 
         } else{


            $msgEnv = $this->retornamensagemrecebida($ideven);
            $msgVisualizada = $this->retornaMensagemVisualizada($ideven);
        
         }

        
 

        $ideven_default = $this->returnWebControlData($idusu);

        return view("{$this->nameView}",compact('idusu',
            'user_base', 'user_bases', 'usuario_lotec', 'vendedores', 'menus', 'categorias', 'title',
            'baseAll', 'ideven', 'ideven_default', 'menuMaisUsu','msgEnv','msgVisualizada'));
    }

    public function indexGo($ideven)
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

        $admin = Usuario::where('idusu', '=', $idusu)->first();
    
      //  dd($tipoSenha);
      $seqmensagem =  $this->request->input('seq_msg');
        if ($admin->inadim == 'SIM'){


            $data= '';
            $individual ='';
 
 
         } else{


            $msgEnv = $this->retornamensagemrecebida($ideven);
            $msgVisualizada = $this->retornaMensagemVisualizada($ideven);
            $confirma = $this->confirmaMensagemGo($ideven,$seqmensagem);
        
         }

        
 

        $ideven_default = $this->returnWebControlData($idusu);

        return view("{$this->nameView}",compact('idusu',
            'user_base', 'user_bases', 'usuario_lotec', 'vendedores', 'menus', 'categorias', 'title',
            'baseAll', 'ideven', 'ideven_default', 'menuMaisUsu','msgEnv','msgVisualizada','confirma'));
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

        if ($id == 1000){
            $data = DB::table('VENDEDOR')
                ->orderBy('NOMVEN', 'asc')
                ->get();
            return $data;

        } else{

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


    



    public function retornamensagemrecebida($ideven){

        $p = $this->retornaBasepeloIdeven($ideven);

        $data = DB::select (" SELECT MSGTERMINAL_REC.*, REVENDEDOR.NOMREVEN

                              FROM MSGTERMINAL_REC
                              INNER JOIN REVENDEDOR ON REVENDEDOR.IDBASE = MSGTERMINAL_REC.IDBASE AND
                                                       REVENDEDOR.IDVEN = MSGTERMINAL_REC.IDVEN AND
                                                       REVENDEDOR.IDREVEN = MSGTERMINAL_REC.IDREVEN
                                WHERE
                                MSGTERMINAL_REC.INVISUALIZADO = 'NAO' AND
                                REVENDEDOR.IDVEN = '$p->idven'
                    
                                ");

//dd($data);
        return $data;

    }


    public function retornaMensagemVisualizada($ideven){

        $p = $this->retornaBasepeloIdeven($ideven);

        $data = DB::select (" SELECT MSGTERMINAL_REC.*, REVENDEDOR.NOMREVEN

                              FROM MSGTERMINAL_REC
                              INNER JOIN REVENDEDOR ON REVENDEDOR.IDBASE = MSGTERMINAL_REC.IDBASE AND
                                                       REVENDEDOR.IDVEN = MSGTERMINAL_REC.IDVEN AND
                                                       REVENDEDOR.IDREVEN = MSGTERMINAL_REC.IDREVEN
                                WHERE
                                MSGTERMINAL_REC.INVISUALIZADO = 'SIM' AND
                                REVENDEDOR.IDVEN = '$p->idven'
                    
                                ");

//dd($data);
        return $data;

    }


    public function confirmaMensagemGo($idven,$seqmensagem){
 //dd($seqmensagem);
     

            $dados_array = [    
                "invisualizado" =>  "SIM",
            ];
          //  dd($dados_array);
            $update = DB::table('MSGTERMINAL_REC')->where([
                ['seqmsg', '=', $seqmensagem],
               
            ])->update($dados_array);

                

            if($update)
            return redirect("/admin/mensagemrecebida/$idven")
                 ->with(['success'=>' Confirmado com sucesso!']);
        else
            return redirect("/admin/mensagemrecebida/$idven")
               ->withErrors(['errors' => 'Falha ao atualizar'])
               ->withInput();


    }


}


