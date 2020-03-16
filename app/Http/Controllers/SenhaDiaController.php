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

class SenhaDiaController extends StandardController
{
    protected $model;
    protected $nameView = 'dashboard.senhadia';
    protected $data;
    protected $title = 'Senha do Dia';
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
        $tipoSenha = $this->tipoSenha($ideven);
      //  dd($tipoSenha);
      
        if ($admin->inadim == 'SIM'){


            $data= '';
            $individual ='';
 
 
         } else{
             $data = $this->retornaSenhaDia($ideven);
             $individual = $this->retornaSenhaDiaIndividual($ideven);
         }

        
 
        


//dd($tipoSenha);
//        if ($admin->inadim == 'SIM' ){

 //           $data= '';

  //          $individual = '';

  //      }
  //      else if($tipoSenha = "INDIVIDUAL"){
   //         $data = '';
  //          $individual = $this->retornaSenhaDiaIndividual($ideven);
   //     }else if($tipoSenha = "GERAL"){
   //         $data = $this->retornaSenhaDia($ideven);
   //         $individual ='';
   //     }

        $ideven_default = $this->returnWebControlData($idusu);

        return view("{$this->nameView}",compact('idusu',
            'user_base', 'user_bases', 'usuario_lotec', 'vendedores', 'menus', 'categorias', 'data','title',
            'baseAll', 'ideven', 'ideven_default', 'menuMaisUsu','individual','tipoSenha'));
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


    public function retornaSenhaDiaIndividual($ideven){

        $p = $this->retornaBasepeloIdeven($ideven);
        $datadia = date ("Y/m/d");

        $data = DB::select (" SELECT SENHA_DIA_COBRADOR.BAIXA_CAIXA, SENHA_DIA_COBRADOR.BAIXA_CAIXA,
                                     COBRADOR.NOMCOBRA, COBRADOR.IDECOBRA

                              FROM SENHA_DIA_COBRADOR
                              INNER JOIN COBRADOR ON COBRADOR.IDBASE = SENHA_DIA_COBRADOR.IDBASE AND
                                                     COBRADOR.IDVEN = SENHA_DIA_COBRADOR.IDVEN AND
                                                     COBRADOR.IDECOBRA = SENHA_DIA_COBRADOR.IDECOBRA
                                WHERE
                                SENHA_DIA_COBRADOR.IDBASE = '$p->idbase' AND
                                SENHA_DIA_COBRADOR.IDVEN = '$p->idven' AND
                                SENHA_DIA_COBRADOR.DIA = '$datadia' AND
                                COBRADOR.SITCOBRA = 'ATIVO'
                                ORDER BY COBRADOR.NOMCOBRA ");

        return $data;

    }


    public function tipoSenha($ideven){

        $p = $this->retornaBasepeloIdeven($ideven);
        
        $data = DB::select (" SELECT VENDEDOR.TIPO_SENHA_COBRADOR
                              FROM VENDEDOR
                              WHERE
                              IDBASE = '$p->idbase' AND
                              IDVEN = '$p->idven' 
                                ");

//dd($data);
        return $data;

    }




}


