<?php

namespace lotecweb\Http\Controllers;

use Carbon\Carbon;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use lotecweb\Http\Requests;
use lotecweb\Models\Usuario;
use lotecweb\Models\Usuario_ven;
use lotecweb\Models\Vendedor;

class ConsultaResultadoInstantaneaController extends ApostasController
{
    protected $model;
    protected $nameView = 'dashboard.consultaresultadoinstantanea';
    protected $data;
    protected $title = 'Consulta Resultado Instantânea';
    protected $redirectCad = '/admin/contatos/cadastrar';
    protected $redirectEdit = '/admin/contatos/editar';
    protected $route = '/admin/contatos';
    public $data_inicial;
    public $data_fim;

    public function __construct(
        Usuario $usuario,
        Usuario_ven $usuario_ven,
        Vendedor $vendedor,
        Request $request){
        $this->request = $request;
        $this->usuario = $usuario;
        $this->usuario_ven = $usuario_ven;
        $this->vendedor = $vendedor;

    }

    public function index2($ideven){


        $idusu = Auth::user()->idusu;
        $user_base = $this->retornaBase($idusu);
        $user_bases = $this->retornaBases($idusu);
        $usuario_lotec = $this->retornaUserLotec($idusu);
        $vendedores = $this->retornaBasesUser($idusu);
        $menus = $this->retornaMenu($idusu);
        $menuMaisUsu = $this->retornaMenuMaisUsu($idusu);
        $categorias = $this->retornaCategorias($menus);
        $data = $this->retornaApostasInstantane($ideven);
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

    public function indexGo($ideven){


        $idusu = Auth::user()->idusu;
        $user_base = $this->retornaBase($idusu);
        $user_bases = $this->retornaBases($idusu);
        $usuario_lotec = $this->retornaUserLotec($idusu);
        $vendedores = $this->retornaBasesUser($idusu);
        $menus = $this->retornaMenu($idusu);
        $menuMaisUsu = $this->retornaMenuMaisUsu($idusu);
        $categorias = $this->retornaCategorias($menus);
        $pule = $this->request->get('n_pule');
        //$nomeReven = $this->request->input('nome_reven');
        $nomeReven = $this->request->input('teste');
        $statusInst = $this->request->get('sel_status');

        $statuint = implode(",", $statusInst);
        
       // dd($nomeReven, $pule, $statu);

        if ($nomeReven != "") {
             $data = $this->retornaApostasInstantaneaReven($nomeReven);
        } elseif ($pule != "") {
            $data = $this->retornaApostasInstantaneaPule($pule);
        } elseif ($statuint != "SIM,NAO") {
            $data = $this->retornaApostasInstantaneaPremio($statuint);
        } else {
            $data = $this->retornaApostasInstantaneaParameter();
        }


        $title = $this->title;
        $ideven_default = $this->returnWebControlData($idusu);
        $baseAll = $this->retornaBasesAll($idusu);
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

        //referente aos IDEVEN
        $valor = $this->request->get('sel_vendedor');
//         dd($valor);

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

        
        
                return view("{$this->nameView}",compact('idusu',
            'user_base', 'user_bases', 'usuario_lotec', 'vendedores', 'menus', 'categorias', 'data','title',
                    'baseAll','ideven2', 'in_ativos', 'ideven_default', 'menuMaisUsu','revendedoresAposta'));
    }

    
    public function retornaApostasInstantane($ideven){

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
                APOSTA.IDBASE, APOSTA.IDVEN, APOSTA.IDREVEN,SORTEIO_INSTANTANEA.NUMPULE,APOSTA.HORGER, APOSTA.DATGER,
                APOSTA.HORENV,APOSTA.DATENV,APOSTA.SITAPO,VENDEDOR.NOMVEN,REVENDEDOR.CIDREVEN,
                SORTEIO_INSTANTANEA.SORTEIO, SORTEIO_INSTANTANEA.DATSOR, SORTEIO_INSTANTANEA.HORSOR,
                SORTEIO_INSTANTANEA.STATUS, SORTEIO_INSTANTANEA.IN_PREMIO, SORTEIO_INSTANTANEA.VLRPREMIO,REVENDEDOR.NOMREVEN,
                VENDEDOR.IDEVEN AS IDEVEN
         FROM APOSTA
         INNER JOIN APOSTA_PALPITES ON 
                         APOSTA_PALPITES.IDBASE  = APOSTA.IDBASE  AND
                         APOSTA_PALPITES.IDVEN   = APOSTA.IDVEN   AND
                         APOSTA_PALPITES.IDREVEN = APOSTA.IDREVEN AND
                         APOSTA_PALPITES.IDTER   = APOSTA.IDTER   AND
                         APOSTA_PALPITES.IDAPO   = APOSTA.IDAPO   AND
                         APOSTA_PALPITES.NUMPULE = APOSTA.NUMPULE
         INNER JOIN SORTEIO_INSTANTANEA ON SORTEIO_INSTANTANEA.NUMPULE = APOSTA.NUMPULE
         INNER JOIN REVENDEDOR ON REVENDEDOR.IDBASE = APOSTA.IDBASE 
             AND REVENDEDOR.IDVEN = APOSTA.IDVEN 
             AND REVENDEDOR.IDREVEN = APOSTA.IDREVEN
             AND APOSTA.IDBASE <> 999999
             AND SORTEIO_INSTANTANEA.DATSOR  BETWEEN '$datIni' AND '$datFim'
             INNER JOIN VENDEDOR ON 
                         VENDEDOR.IDBASE = APOSTA.IDBASE AND
                         VENDEDOR.IDVEN = APOSTA.IDVEN
            $p_query
            GROUP BY
              APOSTA.NUMPULE, APOSTA.DATGER, APOSTA.HORGER, APOSTA.DATENV, APOSTA.HORENV, APOSTA.SITAPO,
              REVENDEDOR.IDEREVEN, REVENDEDOR.NOMREVEN, REVENDEDOR.CIDREVEN, VENDEDOR.NOMVEN, VENDEDOR.IDEVEN,
              APOSTA.IDBASE, APOSTA.IDVEN, APOSTA.IDREVEN,SORTEIO_INSTANTANEA.NUMPULE,
              SORTEIO_INSTANTANEA.SORTEIO, SORTEIO_INSTANTANEA.DATSOR, SORTEIO_INSTANTANEA.HORSOR,
              SORTEIO_INSTANTANEA.STATUS, SORTEIO_INSTANTANEA.IN_PREMIO, SORTEIO_INSTANTANEA.VLRPREMIO,REVENDEDOR.NOMREVEN
              ORDER BY SORTEIO_INSTANTANEA.DATSOR DESC, SORTEIO_INSTANTANEA.HORSOR DESC
         "


        );
//dd($data);
        return $data;
    }
    

    public function retornaApostasInstantaneaPule($pule){

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

    $codigo = Auth::user()->idusu;
          //referente aos IDEVEN
    $valor = $this->request->get('sel_vendedor');
    if ($valor == NULL){
        $valor = $this->retornaBasesPadrao($codigo);
        $p = $valor;
    } else {
        $p = implode(",", $valor);
    }

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
               AND VENDEDOR.IDEVEN in ($p)
               AND SORTEIO_INSTANTANEA.DATSOR  BETWEEN '$datIni' AND '$datFim'
            GROUP BY
              APOSTA.NUMPULE, APOSTA.DATGER, APOSTA.HORGER, APOSTA.DATENV, APOSTA.HORENV, APOSTA.SITAPO,
              REVENDEDOR.IDEREVEN, REVENDEDOR.NOMREVEN, REVENDEDOR.CIDREVEN, VENDEDOR.NOMVEN, VENDEDOR.IDEVEN,
              APOSTA.IDBASE, APOSTA.IDVEN, APOSTA.IDREVEN,SORTEIO_INSTANTANEA.NUMPULE,
              SORTEIO_INSTANTANEA.SORTEIO, SORTEIO_INSTANTANEA.DATSOR, SORTEIO_INSTANTANEA.HORSOR,
              SORTEIO_INSTANTANEA.STATUS, SORTEIO_INSTANTANEA.IN_PREMIO, SORTEIO_INSTANTANEA.VLRPREMIO,REVENDEDOR.NOMREVEN
              ORDER BY SORTEIO_INSTANTANEA.DATSOR DESC, SORTEIO_INSTANTANEA.HORSOR DESC
     "

        );


        return $data;
    }



public function retornaApostasInstantaneaParameter(){

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
        $p = $valor;
        dd($p);
    } else {

        //Construi a string com base no array do select via form
        $p = implode(",", $valor);
    }

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
           SORTEIO_INSTANTANEA.DATSOR  BETWEEN '$datIni' AND '$datFim'
           AND VENDEDOR.IDEVEN in ($p)
          
          
          GROUP BY
              APOSTA.NUMPULE, APOSTA.DATGER, APOSTA.HORGER, APOSTA.DATENV, APOSTA.HORENV, APOSTA.SITAPO,
              REVENDEDOR.IDEREVEN, REVENDEDOR.NOMREVEN, REVENDEDOR.CIDREVEN, VENDEDOR.NOMVEN, VENDEDOR.IDEVEN,
              APOSTA.IDBASE, APOSTA.IDVEN, APOSTA.IDREVEN,SORTEIO_INSTANTANEA.NUMPULE,
              SORTEIO_INSTANTANEA.SORTEIO, SORTEIO_INSTANTANEA.DATSOR, SORTEIO_INSTANTANEA.HORSOR,
              SORTEIO_INSTANTANEA.STATUS, SORTEIO_INSTANTANEA.IN_PREMIO, SORTEIO_INSTANTANEA.VLRPREMIO,REVENDEDOR.NOMREVEN
              ORDER BY SORTEIO_INSTANTANEA.DATSOR DESC, SORTEIO_INSTANTANEA.HORSOR DESC
        
 "

    );
  //  dd($data);
    return $data;
}


public function retornaApostasInstantaneaReven($nomeReven){

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

    $codigo = Auth::user()->idusu;

    //referente aos IDEVEN
    $valor = $this->request->get('sel_vendedor');
    if ($valor == NULL){
        $valor = $this->retornaBasesPadrao($codigo);
        $p = $valor;
    } else {
        $p = implode(",", $valor);
    }


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
               SORTEIO_INSTANTANEA.DATSOR  BETWEEN '$datIni' AND '$datFim'
              AND REVENDEDOR.NOMREVEN like '%$nomeReven%'
              AND VENDEDOR.IDEVEN in ($p)
              
            GROUP BY 
              APOSTA.NUMPULE, APOSTA.DATGER, APOSTA.HORGER, APOSTA.DATENV, APOSTA.HORENV, APOSTA.SITAPO,
              REVENDEDOR.IDEREVEN, REVENDEDOR.NOMREVEN, REVENDEDOR.CIDREVEN, VENDEDOR.NOMVEN, VENDEDOR.IDEVEN,
              APOSTA.IDBASE, APOSTA.IDVEN, APOSTA.IDREVEN,SORTEIO_INSTANTANEA.NUMPULE,
              SORTEIO_INSTANTANEA.SORTEIO, SORTEIO_INSTANTANEA.DATSOR, SORTEIO_INSTANTANEA.HORSOR,
              SORTEIO_INSTANTANEA.STATUS, SORTEIO_INSTANTANEA.IN_PREMIO, SORTEIO_INSTANTANEA.VLRPREMIO,REVENDEDOR.NOMREVEN
              ORDER BY SORTEIO_INSTANTANEA.DATSOR DESC, SORTEIO_INSTANTANEA.HORSOR DESC
     "
    );

//dd($data);
    return $data;
}

public function retornaApostasInstantaneaPremio($statuint){

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

    $codigo = Auth::user()->idusu;

    //referente aos IDEVEN
    $valor = $this->request->get('sel_vendedor');
    if ($valor == NULL){
        $valor = $this->retornaBasesPadrao($codigo);
        $p = $valor;
    } else {

        $p = implode(",", $valor);
    }

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
               SORTEIO_INSTANTANEA.DATSOR  BETWEEN '$datIni' AND '$datFim'
               AND SORTEIO_INSTANTANEA.IN_PREMIO like '%$statuint%'
               AND VENDEDOR.IDEVEN in ($p)
              
            GROUP BY 
              APOSTA.NUMPULE, APOSTA.DATGER, APOSTA.HORGER, APOSTA.DATENV, APOSTA.HORENV, APOSTA.SITAPO,
              REVENDEDOR.IDEREVEN, REVENDEDOR.NOMREVEN, REVENDEDOR.CIDREVEN, VENDEDOR.NOMVEN, VENDEDOR.IDEVEN,
              APOSTA.IDBASE, APOSTA.IDVEN, APOSTA.IDREVEN,SORTEIO_INSTANTANEA.NUMPULE,
              SORTEIO_INSTANTANEA.SORTEIO, SORTEIO_INSTANTANEA.DATSOR, SORTEIO_INSTANTANEA.HORSOR,
              SORTEIO_INSTANTANEA.STATUS, SORTEIO_INSTANTANEA.IN_PREMIO, SORTEIO_INSTANTANEA.VLRPREMIO,REVENDEDOR.NOMREVEN
              ORDER BY SORTEIO_INSTANTANEA.DATSOR DESC, SORTEIO_INSTANTANEA.HORSOR DESC
     "
    );

//dd($data);
    return $data;
}

public function retornaSorteioIntantanea($pule){

    $codigo = Auth::user()->idusu;
          //referente aos IDEVEN
    $valor = $this->request->get('sel_vendedor');
    if ($valor == NULL){
        $valor = $this->retornaBasesPadrao($codigo);
        $p = $valor;
    } else {
        $p = implode(",", $valor);
    }

        $data = DB::select (

            "SELECT 
            SORTEIO_INSTANTANEA.NUMPULE,APOSTA.HORGER, APOSTA.DATGER,
            
            SORTEIO_INSTANTANEA.SORTEIO, SORTEIO_INSTANTANEA.DATSOR, SORTEIO_INSTANTANEA.HORSOR,
            SORTEIO_INSTANTANEA.STATUS, SORTEIO_INSTANTANEA.IN_PREMIO, SORTEIO_INSTANTANEA.VLRPREMIO,REVENDEDOR.NOMREVEN,
            VENDEDOR.IDEVEN AS IDEVEN
               FROM APOSTA 
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
               AND VENDEDOR.IDEVEN in ($p)
            GROUP BY
              APOSTA.NUMPULE, APOSTA.DATGER, APOSTA.HORGER, APOSTA.DATENV, APOSTA.HORENV, APOSTA.SITAPO,
              REVENDEDOR.IDEREVEN, REVENDEDOR.NOMREVEN, REVENDEDOR.CIDREVEN, VENDEDOR.NOMVEN, VENDEDOR.IDEVEN,
              APOSTA.IDBASE, APOSTA.IDVEN, APOSTA.IDREVEN,SORTEIO_INSTANTANEA.NUMPULE,
              SORTEIO_INSTANTANEA.SORTEIO, SORTEIO_INSTANTANEA.DATSOR, SORTEIO_INSTANTANEA.HORSOR,
              SORTEIO_INSTANTANEA.STATUS, SORTEIO_INSTANTANEA.IN_PREMIO, SORTEIO_INSTANTANEA.VLRPREMIO,REVENDEDOR.NOMREVEN
              ORDER BY SORTEIO_INSTANTANEA.DATSOR DESC, SORTEIO_INSTANTANEA.HORSOR DESC
     "

        );

    //   dd($data);
        return View('dashboard.sorteioInstantanea', compact('data'));
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