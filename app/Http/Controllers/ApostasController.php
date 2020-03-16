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

class ApostasController extends StandardController
{
    protected $model;
    protected $nameView = 'dashboard.apostas';
    protected $data;
    protected $title = 'Transmissões';
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

    public function index2($ideven)  {


        if (Auth::user()->idusu == 1000){

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


        $idusu = Auth::user()->idusu;

        $user_base = $this->retornaBase($idusu);

        $user_bases = $this->retornaBases($idusu);

        $usuario_lotec = $this->retornaUserLotec($idusu);

        $vendedores = $this->retornaBasesUser($idusu);

        $menus = $this->retornaMenu($idusu);
        $menuMaisUsu = $this->retornaMenuMaisUsu($idusu);

        $categorias = $this->retornaCategorias($menus);

        $data = $this->retornaApostas($ideven);

        $title = $this->title;

        $baseAll = $this->retornaBasesAll($idusu);

       

        $ideven_default = $this->returnWebControlData($idusu);

        $valores = $baseAll;

        
        foreach ($valores as $val){

            if ($val->ideven == $ideven_default) {
                $baseNome  = $val->nombas;
                $idbase = $val->idbase;
                $vendedorNome = $val->nomven;
                $idvendedor = $val->idven;
            }
        }
        $revendedoresAposta = $this->retornaRevendedores($idbase, $idvendedor);

      
        return view("{$this->nameView}",compact('idusu',
            'user_base', 'user_bases', 'usuario_lotec', 'vendedores', 'menus', 'categorias', 'data','title', 'baseAll', 'ideven', 'ideven_default', 'menuMaisUsu','revendedoresAposta'));
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
        $pule = $this->request->get('n_pule');
        $nomeReven = $this->request->input('teste');
    

        if ($nomeReven != "") {
            $data = $this->retornaApostasReven($nomeReven);
       } elseif ($pule != "") {
           $data = $this->retornaApostasPule($pule); 
       } else {
           $data = $this->retornaApostasParameter();
       }

     //   if (empty($pule)){
      //      $data = $this->retornaApostasParameter();
     //   } else{
     //       $data = $this->retornaApostasPule($pule);
     //   }

        $title = $this->title;

        $baseAll = $this->retornaBasesAll($idusu);


        $valor = $this->request->get('sel_vendedor');


        if (isset($valor)){
            $ideven2 = $valor;
        } else{
            $valor = $this->retornaBasesPadrao($idusu);
            $ideven2  = $valor + $idusu;

        }


        $dados = $this->request->get('sel_options');
        $in_ativos = '';



        if (isset($dados)){
            if (in_array(1, $dados)) {
                $despesas = 'SIM';
            }else {
                $despesas = 'NAO';}

            if (in_array(2, $dados)) {
                $in_ativos = 'SIM';
            } else
                $in_ativos = 'NAO';
        }


        $ideven_default = $this->returnWebControlData($idusu);
       

                return view("{$this->nameView}",compact('idusu',
            'user_base', 'user_bases', 'usuario_lotec', 'vendedores', 'menus', 'categorias', 'data','title',
                    'baseAll','ideven2', 'in_ativos', 'ideven_default', 'menuMaisUsu'));
    }

    public function retornaApostas($ideven){

        $p_query = '';

        $idusu = Auth::user()->idusu;
        $admin = Usuario::where('idusu', '=', $idusu)->first();

        if ($admin->inadim != 'SIM'){
            $p = $this->retornaBasepeloIdeven($ideven);
            $p_query = "AND APOSTA_PALPITES.IDBASE = '$p->idbase'
                AND APOSTA_PALPITES.IDVEN = '$p->idven'";
        }


        $datIni = date ("Y/m/d");
        $datFim = date ("Y/m/d");

        $this->data_inicial = $datIni;
        $this->data_fim = $datFim;

        //data atual menos um dia
        $inicio = $datIni; // data inicio menos um dia
        $parcelas = 1;
        $data_termino = new DateTime($inicio);
        $data_termino->sub(new DateInterval('P'.$parcelas.'D'));
        $datAnt = $data_termino->format('Y/m/d');



        $data = DB::select (

            "SELECT SUM(APOSTA_PALPITES.VLRPALP) AS VLRPALP,
              APOSTA.NUMPULE, APOSTA.DATGER, APOSTA.HORGER, APOSTA.DATENV, APOSTA.HORENV, APOSTA.SITAPO,
              REVENDEDOR.IDEREVEN, REVENDEDOR.NOMREVEN, REVENDEDOR.CIDREVEN, VENDEDOR.NOMVEN, VENDEDOR.IDEVEN AS IDEVEN, '$datIni' AS DATAINI, '$datFim' AS DATAFIM
              FROM APOSTA
              INNER JOIN APOSTA_PALPITES ON 
                         APOSTA_PALPITES.IDBASE  = APOSTA.IDBASE  AND
                         APOSTA_PALPITES.IDVEN   = APOSTA.IDVEN   AND
                         APOSTA_PALPITES.IDREVEN = APOSTA.IDREVEN AND
                         APOSTA_PALPITES.IDTER   = APOSTA.IDTER   AND
                         APOSTA_PALPITES.IDAPO   = APOSTA.IDAPO   AND
                         APOSTA_PALPITES.NUMPULE = APOSTA.NUMPULE
              INNER JOIN REVENDEDOR ON 
                         REVENDEDOR.IDBASE = APOSTA.IDBASE AND
                         REVENDEDOR.IDVEN = APOSTA.IDVEN AND
                         REVENDEDOR.IDREVEN = APOSTA.IDREVEN
              INNER JOIN VENDEDOR ON 
                         VENDEDOR.IDBASE = APOSTA.IDBASE AND
                         VENDEDOR.IDVEN = APOSTA.IDVEN
              WHERE
              APOSTA.DATENV BETWEEN '$datIni' AND '$datFim'

              $p_query
              
            GROUP BY
              APOSTA.NUMPULE, APOSTA.DATGER, APOSTA.HORGER, APOSTA.DATENV, APOSTA.HORENV, APOSTA.SITAPO,
              REVENDEDOR.IDEREVEN, REVENDEDOR.NOMREVEN, REVENDEDOR.CIDREVEN, VENDEDOR.NOMVEN, VENDEDOR.IDEVEN
            ORDER BY APOSTA.DATENV DESC, APOSTA.HORENV DESC
     "

        );


        return $data;
    }


    public function retornaApostasPule($pule){


        $data = DB::select (

            "SELECT SUM(APOSTA_PALPITES.VLRPALP) AS VLRPALP,
              APOSTA.NUMPULE, APOSTA.DATGER, APOSTA.HORGER, APOSTA.DATENV, APOSTA.HORENV, APOSTA.SITAPO,
              REVENDEDOR.IDEREVEN, REVENDEDOR.NOMREVEN, REVENDEDOR.CIDREVEN, VENDEDOR.NOMVEN, VENDEDOR.IDEVEN AS IDEVEN,
              APOSTA.DATAPO
              FROM APOSTA
              INNER JOIN APOSTA_PALPITES ON 
                         APOSTA_PALPITES.IDBASE  = APOSTA.IDBASE  AND
                         APOSTA_PALPITES.IDVEN   = APOSTA.IDVEN   AND
                         APOSTA_PALPITES.IDREVEN = APOSTA.IDREVEN AND
                         APOSTA_PALPITES.IDTER   = APOSTA.IDTER   AND
                         APOSTA_PALPITES.IDAPO   = APOSTA.IDAPO   AND
                         APOSTA_PALPITES.NUMPULE = APOSTA.NUMPULE
              INNER JOIN REVENDEDOR ON 
                         REVENDEDOR.IDBASE = APOSTA.IDBASE AND
                         REVENDEDOR.IDVEN = APOSTA.IDVEN AND
                         REVENDEDOR.IDREVEN = APOSTA.IDREVEN
              INNER JOIN VENDEDOR ON 
                         VENDEDOR.IDBASE = APOSTA.IDBASE AND
                         VENDEDOR.IDVEN = APOSTA.IDVEN
              WHERE
               APOSTA.NUMPULE = '$pule'
            GROUP BY
              APOSTA.NUMPULE, APOSTA.DATGER, APOSTA.HORGER, APOSTA.DATENV, APOSTA.HORENV, APOSTA.SITAPO,
              REVENDEDOR.IDEREVEN, REVENDEDOR.NOMREVEN, REVENDEDOR.CIDREVEN, VENDEDOR.NOMVEN, VENDEDOR.IDEVEN,
              APOSTA.DATAPO
            ORDER BY APOSTA.DATENV DESC, APOSTA.HORENV DESC
     "

        );


        return $data;
    }

    public function retornaApostasReven($nomeReven){

        $datIni = $this->request->get('datIni');
        $datFim = $this->request->get('datFim');


        if (empty($datFim) || empty($datIni)) {
            $datIni = date ("Y/m/d");
            $datFim = date ("Y/m/d");
        } else {
            //Converte data inicial de string para Date(y/m/d)
            $datetimeinicial = new DateTime();
            $newDateInicial = $datetimeinicial->createFromFormat('d/m/Y', $datIni);

            $datIni = $newDateInicial->format('Y/m/d');

            //Converte data final de string para Date(y/m/d)
            $datetimefinal = new DateTime();
            $newDateFinal = $datetimefinal->createFromFormat('d/m/Y', $datFim);

            $datFim = $newDateFinal->format('Y/m/d');

        }

        $data = DB::select (

            "SELECT SUM(APOSTA_PALPITES.VLRPALP) AS VLRPALP,
              APOSTA.NUMPULE, APOSTA.DATGER, APOSTA.DATAPO, APOSTA.HORGER, APOSTA.DATENV, APOSTA.HORENV, APOSTA.SITAPO,
              REVENDEDOR.IDEREVEN, REVENDEDOR.NOMREVEN, REVENDEDOR.CIDREVEN, VENDEDOR.NOMVEN, VENDEDOR.IDEVEN AS IDEVEN
              
              FROM APOSTA
              INNER JOIN APOSTA_PALPITES ON 
                         APOSTA_PALPITES.IDBASE  = APOSTA.IDBASE  AND
                         APOSTA_PALPITES.IDVEN   = APOSTA.IDVEN   AND
                         APOSTA_PALPITES.IDREVEN = APOSTA.IDREVEN AND
                         APOSTA_PALPITES.IDTER   = APOSTA.IDTER   AND
                         APOSTA_PALPITES.IDAPO   = APOSTA.IDAPO   AND
                         APOSTA_PALPITES.NUMPULE = APOSTA.NUMPULE 
                        
              INNER JOIN REVENDEDOR ON 
                         REVENDEDOR.IDBASE = APOSTA.IDBASE AND
                         REVENDEDOR.IDVEN = APOSTA.IDVEN AND
                         REVENDEDOR.IDREVEN = APOSTA.IDREVEN
              INNER JOIN VENDEDOR ON 
                         VENDEDOR.IDBASE = APOSTA.IDBASE AND
                         VENDEDOR.IDVEN = APOSTA.IDVEN
              WHERE
              APOSTA.DATENV BETWEEN '$datIni' AND '$datFim' AND
              REVENDEDOR.NOMREVEN = '$nomeReven'


              GROUP BY
              APOSTA.NUMPULE, APOSTA.DATGER, APOSTA.HORGER, APOSTA.DATENV, APOSTA.HORENV, APOSTA.SITAPO,APOSTA.DATCAN,
              REVENDEDOR.IDEREVEN, REVENDEDOR.NOMREVEN, REVENDEDOR.CIDREVEN, VENDEDOR.NOMVEN, VENDEDOR.IDEVEN,APOSTA.DATAPO
              
            ORDER BY APOSTA.DATENV DESC, APOSTA.HORENV DESC
           
            
     "

        );

      //  dd($data);
        return $data;
    }

    public function viewPule($ideven){


        if (Auth::user()->idusu == 1000){

            $data = $this->vendedor
                ->select('ideven')
                ->get();

            $palavra = "";


            $c = 0;
            foreach ($data as $key){

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



        $idusu = Auth::user()->idusu;

        $user_base = $this->retornaBase($idusu);

        $user_bases = $this->retornaBases($idusu);

        $usuario_lotec = $this->retornaUserLotec($idusu);

        $vendedores = $this->retornaBasesUser($idusu);

        $menus = $this->retornaMenu($idusu);
        $menuMaisUsu = $this->retornaMenuMaisUsu($idusu);

        $categorias = $this->retornaCategorias($menus);

        $data = '';

        $title = 'Visualizar Aposta';

        $baseAll = $this->retornaBasesAll($idusu);

        $ideven_default = $this->returnWebControlData($idusu);

        return view("dashboard.view_aposta",compact('idusu',
            'user_base', 'user_bases', 'usuario_lotec', 'vendedores', 'menus', 'categorias', 'data','title',
            'baseAll', 'ideven', 'ideven_default', 'menuMaisUsu'));
    }

    public function viewPuleGo($ideven){


//
//        if (Auth::user()->idusu == 1000){
//
//            $data = $this->vendedor
//                ->select('ideven')
//                ->get();
//
//            $palavra = "";
//
//
//            $c = 0;
//            foreach ($data as $key){
//
//                $palavra = $palavra.$key['ideven'];
//                if ($c < count($data)-1){
//                    $palavra = $palavra.",";
//                }
//                $c++;
//
//
//            }
//            $ideven = $palavra;
//
//
//
//        } else{
//            $ideven = $ideven;
//        }


        $idusu = Auth::user()->idusu;

        $user_base = $this->retornaBase($idusu);

        $user_bases = $this->retornaBases($idusu);

        $usuario_lotec = $this->retornaUserLotec($idusu);

        $vendedores = $this->retornaBasesUser($idusu);

        $menus = $this->retornaMenu($idusu);
        $menuMaisUsu = $this->retornaMenuMaisUsu($idusu);

        $categorias = $this->retornaCategorias($menus);

        $title = 'Visualizar Aposta';

        $baseAll = $this->retornaBasesAll($idusu);

        $data = $this->retornaPuleArray($ideven);

        $ideven_default = $this->returnWebControlData($idusu);

        return view("dashboard.view_aposta",compact('idusu',
            'user_base', 'user_bases', 'usuario_lotec', 'vendedores', 'menus', 'categorias', 'data','title',
            'baseAll', 'ideven', 'ideven_default', 'menuMaisUsu'));
    }

    public function cancelPule($ideven){


        if (Auth::user()->idusu == 1000){

            $data = $this->vendedor
                ->select('ideven')
                ->get();

            $palavra = "";


            $c = 0;
            foreach ($data as $key){

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



        $idusu = Auth::user()->idusu;

        $user_base = $this->retornaBase($idusu);

        $user_bases = $this->retornaBases($idusu);

        $usuario_lotec = $this->retornaUserLotec($idusu);

        $vendedores = $this->retornaBasesUser($idusu);

        $menus = $this->retornaMenu($idusu);
        $menuMaisUsu = $this->retornaMenuMaisUsu($idusu);

        $categorias = $this->retornaCategorias($menus);

        $data = '';

        $title = 'Cancelar Aposta';

        $baseAll = $this->retornaBasesAll($idusu);

        $ideven_default = $this->returnWebControlData($idusu);

        $valores = $baseAll;

        
        foreach ($valores as $val){

            if ($val->ideven == $ideven_default) {
                $baseNome  = $val->nombas;
                $idbase = $val->idbase;
                $vendedorNome = $val->nomven;
                $idvendedor = $val->idven;
            }
        }

        $revendedoresAposta = $this->retornaRevendedores($idbase, $idvendedor);

     //   dd($revendedoresAposta);


        return view("dashboard.view_aposta",compact('idusu',
            'user_base', 'user_bases', 'usuario_lotec', 'vendedores', 'menus', 'categorias', 'data','title',
            'baseAll', 'ideven', 'ideven_default', 'menuMaisUsu','revendedoresAposta'));
    }

    public function cancelPuleGo($ideven){



        if (Auth::user()->idusu == 1000){

            $data = $this->vendedor
                ->select('ideven')
                ->get();

            $palavra = "";


            $c = 0;
            foreach ($data as $key){

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


        $idusu = Auth::user()->idusu;

        $user_base = $this->retornaBase($idusu);

        $user_bases = $this->retornaBases($idusu);

        $usuario_lotec = $this->retornaUserLotec($idusu);

        $vendedores = $this->retornaBasesUser($idusu);

        $menus = $this->retornaMenu($idusu);
        $menuMaisUsu = $this->retornaMenuMaisUsu($idusu);

        $categorias = $this->retornaCategorias($menus);

        $title = 'Cancelar Aposta';

        $baseAll = $this->retornaBasesAll($idusu);

        $data = $this->retornaPuleArray($ideven);

        $ideven_default = $this->returnWebControlData($idusu);


        return view("dashboard.view_aposta",compact('idusu',
            'user_base', 'user_bases', 'usuario_lotec', 'vendedores', 'menus', 'categorias', 'data','title',
            'baseAll', 'ideven', 'ideven_default', 'menuMaisUsu'));
    }

    public function cancelarPule($ideven){



        if (Auth::user()->idusu == 1000){

            $data = $this->vendedor
                ->select('ideven')
                ->get();

            $palavra = "";


            $c = 0;
            foreach ($data as $key){

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


        $idusu = Auth::user()->idusu;

        $user_base = $this->retornaBase($idusu);

        $user_bases = $this->retornaBases($idusu);

        $usuario_lotec = $this->retornaUserLotec($idusu);

        $vendedores = $this->retornaBasesUser($idusu);

        $menus = $this->retornaMenu($idusu);
        $menuMaisUsu = $this->retornaMenuMaisUsu($idusu);

        $categorias = $this->retornaCategorias($menus);

        $title = 'Cancelar Aposta';

        $baseAll = $this->retornaBasesAll($idusu);

       // $data = $this->retornaPuleArray($ideven);

        $ideven_default = $this->returnWebControlData($idusu);

        $pule = $this->request->get('n_pule');
        $nomeReven = $this->request->input('teste');
    

        if ($nomeReven != "") {
            $dataCancelar = $this->retornaApostasReven($nomeReven);
       } elseif ($pule != "") {
           $dataCancelar = $this->retornaApostasPule($pule); 
       } else {
           $dataCancelar = $this->retornaApostasParameter();
       }
     //  dd("Passei aqui");

        return view("dashboard.view_aposta",compact('idusu',
            'user_base', 'user_bases', 'usuario_lotec', 'vendedores', 'menus', 'categorias', 'title',
            'baseAll', 'ideven', 'ideven_default', 'menuMaisUsu','dataCancelar'));
    }


    /**
     * @return mixed
     */
    public function retornaApostasParameter(){

        $datIni = $this->request->get('datIni');
        $datFim = $this->request->get('datFim');


        if (empty($datFim) || empty($datIni)) {
            $datIni = date ("Y/m/d");
            $datFim = date ("Y/m/d");
        } else {
            //Converte data inicial de string para Date(y/m/d)
            $datetimeinicial = new DateTime();
            $newDateInicial = $datetimeinicial->createFromFormat('d/m/Y', $datIni);

            $datIni = $newDateInicial->format('Y/m/d');

            //Converte data final de string para Date(y/m/d)
            $datetimefinal = new DateTime();
            $newDateFinal = $datetimefinal->createFromFormat('d/m/Y', $datFim);

            $datFim = $newDateFinal->format('Y/m/d');

        }
            $inicio = $datIni; // data inicio menos um dia
            $parcelas = 1;
            $data_termino = new DateTime($inicio);
            $data_termino->sub(new DateInterval('P' . $parcelas . 'D'));
            $datAnt = $data_termino->format('Y/m/d');


        $codigo = Auth::user()->idusu;

        //referente aos IDEVEN
        $valor = $this->request->get('sel_vendedor');
        if ($valor == NULL){
            $valor = $this->retornaBasesPadrao($codigo);
//            dd($valor);
            $p = $valor;
        } else {

            //Construi a string com base no array do select via form
            $p = implode(",", $valor);
        }

//        dd($valor);


        $data = DB::select (

            "SELECT SUM(APOSTA_PALPITES.VLRPALP) AS VLRPALP,
              APOSTA.NUMPULE, APOSTA.DATGER, APOSTA.HORGER, APOSTA.DATENV,APOSTA.DATCAN, APOSTA.HORENV, APOSTA.SITAPO,
              REVENDEDOR.IDEREVEN, REVENDEDOR.NOMREVEN, REVENDEDOR.CIDREVEN, VENDEDOR.NOMVEN, VENDEDOR.IDEVEN, '$datIni' AS DATAINI, '$datFim' AS DATAFIM
              FROM APOSTA
              INNER JOIN APOSTA_PALPITES ON 
                         APOSTA_PALPITES.IDBASE  = APOSTA.IDBASE  AND
                         APOSTA_PALPITES.IDVEN   = APOSTA.IDVEN   AND
                         APOSTA_PALPITES.IDREVEN = APOSTA.IDREVEN AND
                         APOSTA_PALPITES.IDTER   = APOSTA.IDTER   AND
                         APOSTA_PALPITES.IDAPO   = APOSTA.IDAPO   AND
                         APOSTA_PALPITES.NUMPULE = APOSTA.NUMPULE
              INNER JOIN REVENDEDOR ON 
                         REVENDEDOR.IDBASE = APOSTA.IDBASE AND
                         REVENDEDOR.IDVEN = APOSTA.IDVEN AND
                         REVENDEDOR.IDREVEN = APOSTA.IDREVEN
              INNER JOIN VENDEDOR ON 
                         VENDEDOR.IDBASE = APOSTA.IDBASE AND
                         VENDEDOR.IDVEN = APOSTA.IDVEN
              WHERE
              
              APOSTA.DATENV BETWEEN '$datIni' AND '$datFim'
              AND VENDEDOR.IDEVEN in ($p)
              
              
            GROUP BY
              APOSTA.NUMPULE, APOSTA.DATGER, APOSTA.HORGER, APOSTA.DATENV, APOSTA.HORENV, APOSTA.SITAPO,APOSTA.DATCAN,
              REVENDEDOR.IDEREVEN, REVENDEDOR.NOMREVEN, REVENDEDOR.CIDREVEN, VENDEDOR.NOMVEN, VENDEDOR.IDEVEN
            ORDER BY APOSTA.DATENV DESC, APOSTA.HORENV DESC
            
     "

        );

        return $data;
    }


    public function retornaPuleArray($ideven){

        $pule = $this->request->get('numpule');

        $p_query = '';


        if (Auth::user()->idusu != 1000){
            $p = $this->retornaBasepeloIdeven($ideven);
            $p_query = "AND APOSTA_PALPITES.IDBASE = '$p->idbase'
                AND APOSTA_PALPITES.IDVEN = '$p->idven'";
        }

        $data = DB::select (" 
           SELECT APOSTA_PALPITES.IDBASE, APOSTA_PALPITES.IDVEN, APOSTA_PALPITES.IDREVEN,
            APOSTA_PALPITES.IDTER, APOSTA_PALPITES.IDAPO, APOSTA_PALPITES.NUMPULE,
            APOSTA_PALPITES.SEQPALP, APOSTA_PALPITES.DATAPO,APOSTA_PALPITES.IDMENU,
            APOSTA_PALPITES.IDTIPOAPO,APOSTA_PALPITES.IDLOT,APOSTA_PALPITES.IDHOR,
            APOSTA_PALPITES.IDCOL,APOSTA_PALPITES.VLRPALP,APOSTA_PALPITES.PALP1,
            APOSTA_PALPITES.PALP2,APOSTA_PALPITES.PALP3,APOSTA_PALPITES.PALP4,
            APOSTA_PALPITES.PALP5,APOSTA_PALPITES.PALP6,APOSTA_PALPITES.PALP7,
            APOSTA_PALPITES.PALP8,APOSTA_PALPITES.PALP9,APOSTA_PALPITES.PALP10,
            APOSTA_PALPITES.PALP11,APOSTA_PALPITES.PALP12,APOSTA_PALPITES.PALP13,
            APOSTA_PALPITES.PALP14,APOSTA_PALPITES.PALP15,APOSTA_PALPITES.SITAPO,
            APOSTA_PALPITES.VLRCOM,APOSTA_PALPITES.VLRPRESEC,APOSTA_PALPITES.VLRPREMOL,
            APOSTA_PALPITES.VLRPRE,APOSTA_PALPITES.COLMOTDES,APOSTA_PALPITES.VLRPRESMJ,
            APOSTA_PALPITES.VLRPALPF,APOSTA_PALPITES.VLRPALPD,APOSTA_PALPITES.VLRPREPAG,
            APOSTA_PALPITES.DATENV, APOSTA_PALPITES.HORENV,APOSTA_PALPITES.INCOMB,
            APOSTA_PALPITES.VLRCOTACAO,APOSTA_PALPITES.DATCAN,APOSTA_PALPITES.HORCAN,
            APOSTA_PALPITES.SITPRE,APOSTA_PALPITES.DATLIBPRE,APOSTA_PALPITES.HORLIBPRE,
            APOSTA_PALPITES.DATLIMPRE, APOSTA_PALPITES.INATRASADO, APOSTA_PALPITES.INSORPRO,
            APOSTA_PALPITES.INFODESC, APOSTA_PALPITES.PALP16,APOSTA_PALPITES.PALP17,
            APOSTA_PALPITES.PALP18,APOSTA_PALPITES.PALP19,APOSTA_PALPITES.PALP20,
            APOSTA_PALPITES.PALP21,APOSTA_PALPITES.PALP22,APOSTA_PALPITES.PALP23,
            APOSTA_PALPITES.PALP24,APOSTA_PALPITES.PALP25,APOSTA_PALPITES.PRELIBMANUAL,
            APOSTA_PALPITES.NUMAUT,APOSTA_PALPITES.VLR_AUX,
            TIPO_APOSTA.DESTIPOAPO,
            COLOCACOES.DESCOL,
            LOTERIAS.DESLOT,
            HOR_APOSTA.DESHOR,
            REVENDEDOR.NOMREVEN,
            VENDEDOR.NOMVEN
            FROM APOSTA_PALPITES
                INNER JOIN REVENDEDOR ON REVENDEDOR.IDBASE = APOSTA_PALPITES.IDBASE AND
                            REVENDEDOR.IDVEN = APOSTA_PALPITES.IDVEN AND
                            REVENDEDOR.IDREVEN = APOSTA_PALPITES.IDREVEN
                INNER JOIN VENDEDOR ON VENDEDOR.IDBASE = APOSTA_PALPITES.IDBASE AND
                             VENDEDOR.IDVEN = APOSTA_PALPITES.IDVEN
                INNER JOIN LOTERIAS ON LOTERIAS.IDLOT = APOSTA_PALPITES.IDLOT
                INNER JOIN HOR_APOSTA ON HOR_APOSTA.IDLOT = APOSTA_PALPITES.IDLOT AND
                            HOR_APOSTA.IDHOR = APOSTA_PALPITES.IDHOR
                INNER JOIN TIPO_APOSTA ON TIPO_APOSTA.IDTIPOAPO = APOSTA_PALPITES.IDTIPOAPO
                INNER JOIN COLOCACOES ON COLOCACOES.IDCOL = APOSTA_PALPITES.IDCOL
                WHERE
                APOSTA_PALPITES.NUMPULE = '$pule'
                
            $p_query
                
        ");

        //dd($data);
        return $data;
        

    }


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

            return $data;
        }

    }


    public function retornaBasesUser($id){


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

    public function retornaPule($pule, $ideven){

        $p = $this->retornaBasepeloIdeven($ideven);

//        $datini = date ("Y/m/d");
//        $datfim = date ("Y/m/d");

        $data = DB::select (" 
           SELECT APOSTA_PALPITES.IDBASE, APOSTA_PALPITES.IDVEN, APOSTA_PALPITES.IDREVEN,
            APOSTA_PALPITES.IDTER, APOSTA_PALPITES.IDAPO, APOSTA_PALPITES.NUMPULE,
            APOSTA_PALPITES.SEQPALP, APOSTA_PALPITES.DATAPO,APOSTA_PALPITES.IDMENU,
            APOSTA_PALPITES.IDTIPOAPO,APOSTA_PALPITES.IDLOT,APOSTA_PALPITES.IDHOR,
            APOSTA_PALPITES.IDCOL,APOSTA_PALPITES.VLRPALP,APOSTA_PALPITES.PALP1,
            APOSTA_PALPITES.PALP2,APOSTA_PALPITES.PALP3,APOSTA_PALPITES.PALP4,
            APOSTA_PALPITES.PALP5,APOSTA_PALPITES.PALP6,APOSTA_PALPITES.PALP7,
            APOSTA_PALPITES.PALP8,APOSTA_PALPITES.PALP9,APOSTA_PALPITES.PALP10,
            APOSTA_PALPITES.PALP11,APOSTA_PALPITES.PALP12,APOSTA_PALPITES.PALP13,
            APOSTA_PALPITES.PALP14,APOSTA_PALPITES.PALP15,APOSTA_PALPITES.SITAPO,
            APOSTA_PALPITES.VLRCOM,APOSTA_PALPITES.VLRPRESEC,APOSTA_PALPITES.VLRPREMOL,
            APOSTA_PALPITES.VLRPRE,APOSTA_PALPITES.COLMOTDES,APOSTA_PALPITES.VLRPRESMJ,
            APOSTA_PALPITES.VLRPALPF,APOSTA_PALPITES.VLRPALPD,APOSTA_PALPITES.VLRPREPAG,
            APOSTA_PALPITES.DATENV, APOSTA_PALPITES.HORENV,APOSTA_PALPITES.INCOMB,
            APOSTA_PALPITES.VLRCOTACAO,APOSTA_PALPITES.DATCAN,APOSTA_PALPITES.HORCAN,
            APOSTA_PALPITES.SITPRE,APOSTA_PALPITES.DATLIBPRE,APOSTA_PALPITES.HORLIBPRE,
            APOSTA_PALPITES.DATLIMPRE, APOSTA_PALPITES.INATRASADO, APOSTA_PALPITES.INSORPRO,
            APOSTA_PALPITES.INFODESC, APOSTA_PALPITES.PALP16,APOSTA_PALPITES.PALP17,
            APOSTA_PALPITES.PALP18,APOSTA_PALPITES.PALP19,APOSTA_PALPITES.PALP20,
            APOSTA_PALPITES.PALP21,APOSTA_PALPITES.PALP22,APOSTA_PALPITES.PALP23,
            APOSTA_PALPITES.PALP24,APOSTA_PALPITES.PALP25,APOSTA_PALPITES.PRELIBMANUAL,
            APOSTA_PALPITES.NUMAUT,APOSTA_PALPITES.VLR_AUX,
            TIPO_APOSTA.DESTIPOAPO,
            COLOCACOES.DESCOL,
            LOTERIAS.DESLOT,
            HOR_APOSTA.DESHOR,
            REVENDEDOR.NOMREVEN,
            VENDEDOR.NOMVEN
            FROM APOSTA_PALPITES
                INNER JOIN REVENDEDOR ON REVENDEDOR.IDBASE = APOSTA_PALPITES.IDBASE AND
                            REVENDEDOR.IDVEN = APOSTA_PALPITES.IDVEN AND
                            REVENDEDOR.IDREVEN = APOSTA_PALPITES.IDREVEN
                INNER JOIN VENDEDOR ON VENDEDOR.IDBASE = APOSTA_PALPITES.IDBASE AND
                             VENDEDOR.IDVEN = APOSTA_PALPITES.IDVEN
                INNER JOIN LOTERIAS ON LOTERIAS.IDLOT = APOSTA_PALPITES.IDLOT
                INNER JOIN HOR_APOSTA ON HOR_APOSTA.IDLOT = APOSTA_PALPITES.IDLOT AND
                            HOR_APOSTA.IDHOR = APOSTA_PALPITES.IDHOR
                INNER JOIN TIPO_APOSTA ON TIPO_APOSTA.IDTIPOAPO = APOSTA_PALPITES.IDTIPOAPO
                INNER JOIN COLOCACOES ON COLOCACOES.IDCOL = APOSTA_PALPITES.IDCOL
                WHERE
                APOSTA_PALPITES.NUMPULE = '$pule'
            
                AND APOSTA_PALPITES.IDBASE = '$p->idbase'
                AND APOSTA_PALPITES.IDVEN = '$p->idven'
        ");


     //  dd($data);

        return json_encode($data);
    }

    public function cancelAposta($ideven){

        $id = $ideven;

        $error1 = 'PULE JÁ CANCELADA';

        $dados = $this->request->except('_token','idlot','idhor','dataaposta');

        $dataAtual = strtotime (date ("Y-m-d"));
        $horaAtual = new DateTime();
        $horaAtual = $horaAtual->format('H:i:s');
        $horaAtual = strtotime ($horaAtual);

        $dataAposta = $this->request->get('dataaposta');
        $dataAposta = strtotime($dataAposta);
        $idLot = $this->request->get('idlot');
        $idHor = $this->request->get('idhor');

        if($dataAposta < $dataAtual){
            $data_result = 'Data limite excedida!';
        } else {
            $data_result = '';
        }

        $horlimite = DB::select (" 
            SELECT HORLIM FROM HOR_APOSTA WHERE IDLOT = $idLot and IDHOR = $idHor
        ");

        $horlim = new DateTime($horlimite[0]->horlim);

        $horlim = $horlim->format('H:i:s');
        $horlim = strtotime ($horlim);

        if($horaAtual > $horlim){
            $hora_result = 'Hora limite excedida!';
        } else {
            $hora_result = '';
        }

        $pule = $this->request->get('numpule');

        $pesquisa = DB::select (" 
            SELECT NUMPULE FROM CANCELAR_APOSTA WHERE NUMPULE = $pule
        ");

        if($dataAposta < $dataAtual){
            $data_result ='';
            $resultado = $data_result;
        }else{
            $resultado = $data_result.$hora_result;
        }
        

        if (empty($resultado)){

            $insert = DB::table('CANCELAR_APOSTA')->insert($dados);

            if ($insert){
                return 1;
            } else {
                return 0;
            }
        } else {

            return $resultado;
        }


    }

    
    public function cancelarAposta($ideven){
        $id = $ideven;

        $error1 = 'PULE JÁ CANCELADA';

        $dados = $this->request->except('_token','idlot','idhor','dataaposta');

        $dataAtual = strtotime (date ("Y-m-d"));
        $horaAtual = new DateTime();
        $horaAtual = $horaAtual->format('H:i:s');
        $horaAtual = strtotime ($horaAtual);

        $dataAposta = $this->request->get('dataaposta');
        $dataAposta = strtotime($dataAposta);
        $idLot = $this->request->get('idlot');
        $idHor = $this->request->get('idhor');

        if($dataAposta < $dataAtual){
            $data_result = 'Erro: Data limite excedida';
        } else {
            $data_result = '';
        }

        $horlimite = DB::select (" 
            SELECT HORLIM FROM HOR_APOSTA WHERE IDLOT = $idLot and IDHOR = $idHor
        ");

        $horlim = new DateTime($horlimite[0]->horlim);

        $horlim = $horlim->format('H:i:s');
        $horlim = strtotime ($horlim);

        if($horaAtual > $horlim){
            $hora_result = ' Erro: Hora limite excedida';
        } else {
            $hora_result = '';
        }

        $pule = $this->request->get('numpule');

        $pesquisa = DB::select (" 
            SELECT NUMPULE FROM CANCELAR_APOSTA WHERE NUMPULE = $pule
        ");

        $resultado = $data_result.$hora_result;

        if (empty($resultado)){

            $insert = DB::table('CANCELAR_APOSTA')->insert($dados);

            if ($insert){
                return 1;
            } else {
                return 0;
            }
        } else {

            return $resultado;
        }


    }


    public function consutaPuleCancel($ideven)
    {


        $idusu = Auth::user()->idusu;

        $user_base = $this->retornaBase($idusu);

        $user_bases = $this->retornaBases($idusu);

        $usuario_lotec = $this->retornaUserLotec($idusu);

        $vendedores = $this->retornaBasesUser($idusu);

        $menus = $this->retornaMenu($idusu);
        $menuMaisUsu = $this->retornaMenuMaisUsu($idusu);

        $categorias = $this->retornaCategorias($menus);

        $pule = $this->request->get('n_pule');
     

        if (empty($pule)){
            $data = $this->retornaApostasParameter();
        } else{
            $data = $this->retornaApostasPule($pule);
        }


        $title = $this->title;

        $baseAll = $this->retornaBasesAll($idusu);


        $valor = $this->request->get('sel_vendedor');

        if (isset($valor)){

            $ideven2 = $valor;
        } else{

            $valor = $this->retornaBasesPadrao($idusu);

            $ideven2  = $valor;


        }



        $dados = $this->request->get('sel_options');
        $in_ativos = '';



        if (isset($dados)){
            if (in_array(1, $dados)) {
                $despesas = 'SIM';
            }else {
                $despesas = 'NAO';}

            if (in_array(2, $dados)) {
                $in_ativos = 'SIM';
            } else
                $in_ativos = 'NAO';
        }

        $ideven_default = $this->returnWebControlData($idusu);

                return view("dashboard.view_aposta",compact('idusu',
            'user_base', 'user_bases', 'usuario_lotec', 'vendedores', 'menus', 'categorias', 'data','title',
                    'baseAll','ideven2', 'in_ativos', 'ideven_default', 'menuMaisUsu'));
    }




    public function retornaPuleInstantanea($pule){


        $data = DB::select (

            "SELECT SUM(APOSTA_PALPITES.VLRPALP) AS VLRPALP,
            APOSTA.IDBASE, APOSTA.IDVEN, APOSTA.IDREVEN,SORTEIO_INSTANTANEA.NUMPULE,APOSTA.HORGER, APOSTA.DATGER,
            APOSTA.HORENV,APOSTA.DATENV,APOSTA.SITAPO,VENDEDOR.NOMVEN,REVENDEDOR.CIDREVEN,
            SORTEIO_INSTANTANEA.SORTEIO, SORTEIO_INSTANTANEA.DATSOR, SORTEIO_INSTANTANEA.HORSOR,
            SORTEIO_INSTANTANEA.STATUS, SORTEIO_INSTANTANEA.IN_PREMIO, SORTEIO_INSTANTANEA.VLRPREMIO,REVENDEDOR.NOMREVEN,
            VENDEDOR.IDEVEN AS IDEVEN
               FROM APOSTA
              INNER JOIN APOSTA_PALPITES ON 
                         APOSTA_PALPITES.IDBASE  = APOSTA.IDBASE
                         AND APOSTA_PALPITES.IDVEN   = APOSTA.IDVEN  
                         AND APOSTA_PALPITES.IDREVEN = APOSTA.IDREVEN 
                         AND APOSTA_PALPITES.IDTER   = APOSTA.IDTER   
                         AND APOSTA_PALPITES.IDAPO   = APOSTA.IDAPO   
                         AND APOSTA_PALPITES.NUMPULE = APOSTA.NUMPULE
              INNER JOIN SORTEIO_INSTANTANEA ON
                         SORTEIO_INSTANTANEA.NUMPULE = APOSTA.NUMPULE
              INNER JOIN REVENDEDOR ON 
                         REVENDEDOR.IDBASE = APOSTA.IDBASE 
                         AND REVENDEDOR.IDVEN = APOSTA.IDVEN 
                         AND REVENDEDOR.IDREVEN = APOSTA.IDREVEN
              INNER JOIN VENDEDOR ON 
                         VENDEDOR.IDBASE = APOSTA.IDBASE 
                         AND VENDEDOR.IDVEN = APOSTA.IDVEN
              
               WHERE
               APOSTA.NUMPULE = '$pule'
               
            GROUP BY
              APOSTA.NUMPULE, APOSTA.DATGER, APOSTA.HORGER, APOSTA.DATENV, APOSTA.HORENV, APOSTA.SITAPO,
              REVENDEDOR.IDEREVEN, REVENDEDOR.NOMREVEN, REVENDEDOR.CIDREVEN, VENDEDOR.NOMVEN, VENDEDOR.IDEVEN,
              APOSTA.IDBASE, APOSTA.IDVEN, APOSTA.IDREVEN,SORTEIO_INSTANTANEA.NUMPULE,
              SORTEIO_INSTANTANEA.SORTEIO, SORTEIO_INSTANTANEA.DATSOR, SORTEIO_INSTANTANEA.HORSOR,
              SORTEIO_INSTANTANEA.STATUS, SORTEIO_INSTANTANEA.IN_PREMIO, SORTEIO_INSTANTANEA.VLRPREMIO,REVENDEDOR.NOMREVEN
              ORDER BY SORTEIO_INSTANTANEA.DATSOR DESC, SORTEIO_INSTANTANEA.HORSOR DESC
     "

        );


      // dd($data);

        return json_encode($data);
    }

    

    public function retornaTransmissoesReven($ideven){

        $revenSelecionado = $this->request->input('teste');
        $datIni = $this->request->get('datIni');
        $datFim = $this->request->get('datFim');
      
       /// dd($revenSelecionado);

      if (empty($datFim) || empty($datIni)) {
        $datIni = date ("Y/m/d");
        $datFim = date ("Y/m/d");
    } else {
        //Converte data inicial de string para Date(y/m/d)
        $datetimeinicial = new DateTime();
        $newDateInicial = $datetimeinicial->createFromFormat('d/m/Y', $datIni);

        $datIni = $newDateInicial->format('Y/m/d');

        //Converte data final de string para Date(y/m/d)
        $datetimefinal = new DateTime();
        $newDateFinal = $datetimefinal->createFromFormat('d/m/Y', $datFim);

        $datFim = $newDateFinal->format('Y/m/d');

    }
  //  dd($datIni, $datFim );

        $p_query = '';


        if (Auth::user()->idusu != 1000){
            $p = $this->retornaBasepeloIdeven($ideven);
            $p_query = "AND APOSTA_PALPITES.IDBASE = '$p->idbase'
                AND APOSTA_PALPITES.IDVEN = '$p->idven'";
        }

        $data = DB::select (

            "SELECT SUM(APOSTA_PALPITES.VLRPALP) AS VLRPALP,
            APOSTA.NUMPULE, APOSTA.DATGER, APOSTA.HORGER, APOSTA.DATENV,APOSTA.DATCAN, APOSTA.HORENV, APOSTA.SITAPO,
            REVENDEDOR.IDEREVEN, REVENDEDOR.NOMREVEN, REVENDEDOR.CIDREVEN, VENDEDOR.NOMVEN, VENDEDOR.IDEVEN, '$datIni' AS DATAINI, '$datFim' AS DATAFIM
            FROM APOSTA
            INNER JOIN APOSTA_PALPITES ON 
                       APOSTA_PALPITES.IDBASE  = APOSTA.IDBASE  AND
                       APOSTA_PALPITES.IDVEN   = APOSTA.IDVEN   AND
                       APOSTA_PALPITES.IDREVEN = APOSTA.IDREVEN AND
                       APOSTA_PALPITES.IDTER   = APOSTA.IDTER   AND
                       APOSTA_PALPITES.IDAPO   = APOSTA.IDAPO   AND
                       APOSTA_PALPITES.NUMPULE = APOSTA.NUMPULE
            INNER JOIN REVENDEDOR ON 
                       REVENDEDOR.IDBASE = APOSTA.IDBASE AND
                       REVENDEDOR.IDVEN = APOSTA.IDVEN AND
                       REVENDEDOR.IDREVEN = APOSTA.IDREVEN
            INNER JOIN VENDEDOR ON 
                       VENDEDOR.IDBASE = APOSTA.IDBASE AND
                       VENDEDOR.IDVEN = APOSTA.IDVEN
            WHERE
            
            APOSTA.DATENV = '$datIni'  AND
            REVENDEDOR.NOMREVEN = '$revenSelecionado' 
            
            
          GROUP BY
            APOSTA.NUMPULE, APOSTA.DATGER, APOSTA.HORGER, APOSTA.DATENV, APOSTA.HORENV, APOSTA.SITAPO,APOSTA.DATCAN,
            REVENDEDOR.IDEREVEN, REVENDEDOR.NOMREVEN, REVENDEDOR.CIDREVEN, VENDEDOR.NOMVEN, VENDEDOR.IDEVEN
          ORDER BY APOSTA.DATENV DESC, APOSTA.HORENV DESC


                
        ");

       // dd($data);
        return $data;
  
    }

    public function retornaRevendedores($idbase, $idven){
        $reven = DB::select(" 
             SELECT IDBASE, IDVEN, IDREVEN, IDEREVEN, NOMREVEN
                 FROM REVENDEDOR
                     WHERE
                         IDBASE = '$idbase' AND
                         IDVEN = '$idven' AND
                         SITREVEN = 'ATIVO'
                     ORDER BY NOMREVEN 
        ");

        
        return $reven;
    }

    

}
