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

class ApostasPremiadaController extends StandardController
{
    protected $model;
    protected $nameView = 'dashboard.apostaspremiadas';
    protected $data;
    protected $title = 'Apostas Premiadas';
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
        //pegar loteria paramter
       

//        $vendedores = $this->retornaUsuarioVen($idusu, $user_base->pivot_idbase);

        $vendedores = $this->retornaBasesUser($idusu);

        $menus = $this->retornaMenu($idusu);
        $menuMaisUsu = $this->retornaMenuMaisUsu($idusu);

        $categorias = $this->retornaCategorias($menus);

        $data = $this->retornaApostas($ideven);

       // dd($data);

        $title = $this->title;

    
        $ideven_default = $this->returnWebControlData($idusu);
     //   $semana = $this->returnLoteriaDia();
        $loterias = $this->returnLoterias();
     //   dd($semana);

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
       // dd($baseAll, $ideven);
        $revendedores = $this->retornaRevendedores($idbase, $idvendedor);
        $modalidade = $this->retornaModalidades($idbase, $idvendedor);
        
//dd($revendedores);
        return view("{$this->nameView}",compact('idusu',
            'user_base', 'user_bases', 'usuario_lotec', 'vendedores', 'menus', 'categorias', 'data','title',
            'baseAll', 'ideven', 'ideven_default', 'menuMaisUsu','loterias','revendedores'));
    }

    public function indexGo() {


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

        $p_situacao =  $this->request->get('group1');

        $idlot = $this->request->get('sel_loterias');

       /// dd($idlot);

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
       // dd($baseAll, $ideven);
        $revendedores = $this->retornaRevendedores($idbase, $idvendedor);
      //  $semana = $this->returnLoteriaDia();

        $modlist = $this->request->get('mod');
      //  dd($modlist);
      $loterias = $this->returnLoterias();

    
                return view("{$this->nameView}",compact('idusu',
            'user_base', 'user_bases', 'usuario_lotec', 'vendedores', 'menus', 'categorias', 'data','title',
                    'baseAll','ideven2','in_ativos', 'p_situacao', 'ideven_default', 'menuMaisUsu', 'revendedores', 'modlist','loterias'));
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



        $datIni = date ("Y/m/1");//retorna o primeiro dia do mês corrente
        $datFim = date ("Y/m/t");//retorna o último dia mês corrente


        $this->data_inicial = $datIni;
        $this->data_fim = $datFim;

        //data atual menos um dia
        $inicio = $datIni; // data inicio menos um dia
        $parcelas = 1;
        $data_termino = new DateTime($inicio);
        $data_termino->sub(new DateInterval('P'.$parcelas.'D'));
        $datAnt = $data_termino->format('Y/m/d');

        $dataAtual = date ("Y-m-d");


        $data = DB::select (

            "SELECT APOSTA_PALPITES.IDBASE, APOSTA_PALPITES.IDVEN, APOSTA_PALPITES.IDREVEN, '$datIni' AS DATAINI, '$datFim' AS DATAFIM,
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
                    APOSTA_PALPITES.NUMAUT,APOSTA_PALPITES.VLR_AUX,VENDEDOR.IDEVEN,
                    REVENDEDOR.NOMREVEN,
                    HOR_APOSTA.DESHOR,
                    TIPO_APOSTA.DESTIPOAPO,
                    COLOCACOES.DESCOL, '' as inSel
                    FROM APOSTA_PALPITES
                    INNER JOIN REVENDEDOR ON REVENDEDOR.IDBASE = APOSTA_PALPITES.IDBASE AND
                                               REVENDEDOR.IDVEN = APOSTA_PALPITES.IDVEN AND
                                               REVENDEDOR.IDREVEN = APOSTA_PALPITES.IDREVEN
                    INNER JOIN HOR_APOSTA ON HOR_APOSTA.IDLOT = APOSTA_PALPITES.IDLOT AND
                                              HOR_APOSTA.IDHOR = APOSTA_PALPITES.IDHOR
                    INNER JOIN TIPO_APOSTA ON TIPO_APOSTA.IDTIPOAPO = APOSTA_PALPITES.IDTIPOAPO
                    INNER JOIN COLOCACOES ON COLOCACOES.IDCOL = APOSTA_PALPITES.IDCOL
                    INNER JOIN VENDEDOR ON VENDEDOR.IDBASE = APOSTA_PALPITES.IDBASE AND
                                             VENDEDOR.IDVEN = APOSTA_PALPITES.IDVEN
                      WHERE
                      APOSTA_PALPITES.SEQPALP <> 999999
                      AND APOSTA_PALPITES.SITAPO = 'PRE'
                      
                      $p_query
                      
                      AND APOSTA_PALPITES.SITPRE = 'BLO'
                      AND APOSTA_PALPITES.DATLIMPRE >= '$dataAtual'
                         "

        );

        return $data;
    }


    public function retornaApostasPule($pule){


        $data = DB::select (

            "SELECT SUM(APOSTA_PALPITES.VLRPALP) AS VLRPALP,
              APOSTA.NUMPULE, APOSTA.DATGER, APOSTA.HORGER, APOSTA.DATENV, APOSTA.HORENV, APOSTA.SITAPO,
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
               APOSTA.NUMPULE = '$pule'
            GROUP BY
              APOSTA.NUMPULE, APOSTA.DATGER, APOSTA.HORGER, APOSTA.DATENV, APOSTA.HORENV, APOSTA.SITAPO,
              REVENDEDOR.IDEREVEN, REVENDEDOR.NOMREVEN, REVENDEDOR.CIDREVEN, VENDEDOR.NOMVEN, VENDEDOR.IDEVEN
            ORDER BY APOSTA.DATENV DESC, APOSTA.HORENV DESC
     "

        );


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

        return view("dashboard.view_aposta",compact('idusu',
            'user_base', 'user_bases', 'usuario_lotec', 'vendedores', 'menus', 'categorias', 'data','title',
            'baseAll', 'ideven', 'ideven_default', 'menuMaisUsu'));
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


    /**
     * @return mixed
     */
    public function retornaApostasParameter(){

        $p_tipo = $this->request->get('group1');
        $datIni = $this->request->get('datIni');
        $datFim = $this->request->get('datFim');
        $nr_aposta = $this->request->get('nr_pule');
        $revenSelecionado = $this->request->input('teste');
        $idlot = $this->request->get('sel_lotodia');
        $modlist = $this->request->get('mod');
      // dd($modlist);
        
       $modalidadeTeste = $this->request->get('modalidades');
    //   dd($modalidadeTeste);

        $s_query_aposta = '';   
        $s_query_reven = '';
        $s_query_loteria = '';
        $s_query_modalidade = '';

        $dataAtual = date ("Y-m-d");



        if (!empty($modalidadeTeste)){
            $s_query_modalidade = "AND TIPO_APOSTA.DESTIPOAPO LIKE UPPER ('%$modalidadeTeste%')";

        }

        if (!empty($idlot)){
            $lot = implode(",", $idlot);
            $s_query_loteria = "AND HOR_APOSTA.DESHOR IN ('$lot')";

        }

        if (!empty($revenSelecionado)){
            $s_query_reven = "AND REVENDEDOR.NOMREVEN = '$revenSelecionado'";

        }

        if (!empty($nr_aposta)){
            $s_query_aposta = "AND APOSTA_PALPITES.NUMPULE = '$nr_aposta'";
        }

        if (empty($datFim) || empty($datIni)) {
            $datIni = date ("Y/m/1");
            $datFim = date ("Y/m/t");
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

        $s_query_blo = '';
        if ($p_tipo == '0'){
            $s_bloq = 'BLO';
           
            $s_query_blo = "AND APOSTA_PALPITES.SITPRE = '$s_bloq'
            AND APOSTA_PALPITES.DATLIMPRE >= '$dataAtual'";


            } elseif  ($p_tipo == '1'){
                $s_sitpre = 'BLO';
                $s_query_blo = "AND APOSTA_PALPITES.SITPRE = '$s_sitpre'
                                AND APOSTA_PALPITES.DATLIMPRE < '$dataAtual'
                                AND APOSTA_PALPITES.DATLIMPRE BETWEEN '$datIni' AND '$datFim'";
            } elseif ($p_tipo =='2'){
                $s_sitpre = 'LIB';
                $s_query_blo = "AND APOSTA_PALPITES.SITPRE = '$s_sitpre' AND APOSTA_PALPITES.DATLIBPRE BETWEEN '$datIni' AND '$datFim'";

            }


        $codigo = Auth::user()->idusu;

        //referente aos IDEVEN
        $valor = $this->request->get('sel_vendedor');
//        dd($valor);
        if ($valor == NULL){
            $valor = $this->retornaBasesPadrao($codigo);
//            dd($valor);
            $p = $valor;
        } else {

            //Construi a string com base no array do select via form
            $p = implode(",", $valor);
        }


        $data = DB::select (

            "SELECT APOSTA_PALPITES.IDBASE, APOSTA_PALPITES.IDVEN, APOSTA_PALPITES.IDREVEN, '$datIni' AS DATAINI, '$datFim' AS DATAFIM,
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
                    APOSTA_PALPITES.NUMAUT,APOSTA_PALPITES.VLR_AUX,VENDEDOR.IDEVEN,
                    REVENDEDOR.NOMREVEN,
                    HOR_APOSTA.DESHOR,
                    TIPO_APOSTA.DESTIPOAPO,
                    COLOCACOES.DESCOL, '' as inSel
                    FROM APOSTA_PALPITES
                    INNER JOIN REVENDEDOR ON REVENDEDOR.IDBASE = APOSTA_PALPITES.IDBASE AND
                                               REVENDEDOR.IDVEN = APOSTA_PALPITES.IDVEN AND
                                               REVENDEDOR.IDREVEN = APOSTA_PALPITES.IDREVEN
                    INNER JOIN HOR_APOSTA ON HOR_APOSTA.IDLOT = APOSTA_PALPITES.IDLOT AND
                                              HOR_APOSTA.IDHOR = APOSTA_PALPITES.IDHOR
                    INNER JOIN TIPO_APOSTA ON TIPO_APOSTA.IDTIPOAPO = APOSTA_PALPITES.IDTIPOAPO
                    INNER JOIN COLOCACOES ON COLOCACOES.IDCOL = APOSTA_PALPITES.IDCOL
                    INNER JOIN VENDEDOR ON VENDEDOR.IDBASE = APOSTA_PALPITES.IDBASE AND
                                             VENDEDOR.IDVEN = APOSTA_PALPITES.IDVEN
                      WHERE
                      APOSTA_PALPITES.SEQPALP <> 999999
                      AND APOSTA_PALPITES.SITAPO = 'PRE'
                  
                     $s_query_blo
                     AND VENDEDOR.IDEVEN in ($p)
                     
                     $s_query_aposta 
                     $s_query_reven
                     $s_query_loteria
                     $s_query_modalidade
                         "

        );
     //  dd($data);
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


//        dd($data);

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

    public function payBet() {

        $data = $this->request->get('dados');

        $data = explode(" ", $data, -1);

     //   dd($data);

        $data = array_chunk($data, 8);

        $dados = array();

        $x = 0;


        for ($i=0; $i<count($data); $i++){

            if ($this->searchPayBetReleased(
                $data[$i][0],
                $data[$i][1],
                $data[$i][2],
                $data[$i][3],
                $data[$i][4],
                $data[$i][5],
                $data[$i][6]
            ) == 0){
                $linhaMov =  [
                    "idbase"    => $data[$i][0],
                    "idven"     => $data[$i][1],
                    "idreven"   => $data[$i][2],
                    "idter"     => $data[$i][3],
                    "idapo"     => $data[$i][4],
                    "numpule"   => $data[$i][5],
                    "seqpalp"   => $data[$i][6],
                    "inpro"     => $data[$i][7],
                ];
                array_push($dados, $linhaMov);
            }

        }

//        dd(($dados[0]));

        for ($i=0; $i<count($dados); $i++){
            $insert = DB::table('LIBERAR_PREMIO')->insert($dados[$i]);

            if ($insert){
                $x = $x + 1;
            }

        }

        return $x;
    }

    public function searchPayBetReleased($idbase, $idven, $idreven, $idter, $idapo, $numpule, $seqpalp){

        $retorno = 0;
        $data = DB::table('LIBERAR_PREMIO')
            ->select('NUMPULE')
            ->where([
                ['IDBASE', '=', $idbase],
                ['IDVEN', '=', $idven],
                ['IDREVEN', '=', $idreven],
                ['IDTER', '=', $idter],
                ['IDAPO', '=', $idapo],
                ['NUMPULE', '=', $numpule],
                ['SEQPALP', '=', $seqpalp]
            ])->get();

        if(empty($data)){
            $retorno = 1;
            return $retorno;
        } else{
            $retorno = 0;
            return $retorno;
        }

    }


    public function returnLoteriaDia(){
        $w = date("w");


        switch ($w) {
            case 0:
                $dd = "DOMINGO";
                break;
            case 1:
                $dd = "SEGUNDA";
                break;
            case 2:
                $dd = "TERÇA";
                break;
            case 3:
                $dd = "QUARTA";
                break;
            case 4:
                $dd = "QUINTA";
                break;
            case 5:
                $dd = "SEXTA";
                break;
            case 6:
                $dd = "SABADO";
                break;
        }

        $data = DB::select(" SELECT HOR_APOSTA.IDLOT, HOR_APOSTA.IDHOR, HOR_APOSTA.DESHOR, HOR_APOSTA.IDEHOR, 'N' AS CHEK
                              FROM HOR_APOSTA
                              WHERE
                             HOR_APOSTA.DIASEM = '$dd' AND
                             HOR_APOSTA.SITHOR = 'ATIVO'
                             ORDER BY HOR_APOSTA.HORLIM, HOR_APOSTA.DESHOR DESC 
     ");

        return $data;
    }

    public function returnLoterias(){

        $idusu = Auth::user()->idusu;
        $ideven_default = $this->returnWebControlData($idusu);
        $baseAll = $this->retornaBasesAll($idusu);
        $valores = $baseAll;

        $datIni = date ("Y/m/1");//retorna o primeiro dia do mês corrente
        $datFim = date ("Y/m/t");//retorna o último dia mês corrente
            foreach ($valores as $val){

                if ($val->ideven == $ideven_default) {
                    $baseNome  = $val->nombas;
                    $idbase = $val->idbase;
                    $vendedorNome = $val->nomven;
                    $idvendedor = $val->idven;
                }
            }

      //     dd($idbase, $idvendedor);
        $data = DB::select(" SELECT DISTINCT  HOR_APOSTA.DESHOR,  VEN_HOR_APO.IDBASE, VEN_HOR_APO.IDVEN,  
                                VEN_HOR_APO.SITLIG
                            FROM VEN_HOR_APO
                            INNER JOIN HOR_APOSTA ON HOR_APOSTA.IDLOT = VEN_HOR_APO.IDLOT 
                            INNER JOIN VENDEDOR ON VENDEDOR.IDBASE = VEN_HOR_APO.IDBASE AND
										VENDEDOR.IDVEN = VEN_HOR_APO.IDVEN
                            WHERE
                            VEN_HOR_APO.IDBASE = '$idbase' AND
                            VEN_HOR_APO.IDVEN = '$idvendedor' AND
                            VEN_HOR_APO.SITLIG = 'ATIVO' AND
                            HOR_APOSTA.SITHOR = 'ATIVO' 
                           
                            ORDER BY HOR_APOSTA.DESHOR
                                                                            
                            ");
   // dd($data);
        return $data;
    }


    public function retornaRevendedores($idbase, $idven){
        $semana = DB::select(" 
             SELECT IDBASE, IDVEN, IDREVEN, IDEREVEN, NOMREVEN
                 FROM REVENDEDOR
                     WHERE
                         IDBASE = '$idbase' AND
                         IDVEN = '$idven' AND
                         SITREVEN = 'ATIVO'
                     ORDER BY NOMREVEN 
        ");

        
        return $semana;
    }

    public function retornaModalidades(){

        $idusu = Auth::user()->idusu;
        $ideven_default = $this->returnWebControlData($idusu);
        $baseAll = $this->retornaBasesAll($idusu);
        $valores = $baseAll;

      //  $datIni = date ("Y/m/1");//retorna o primeiro dia do mês corrente
     //   $datFim = date ("Y/m/t");//retorna o último dia mês corrente
            foreach ($valores as $val){

                if ($val->ideven == $ideven_default) {
                    $baseNome  = $val->nombas;
                    $idbase = $val->idbase;
                    $vendedorNome = $val->nomven;
                    $idvendedor = $val->idven;
                }
            }

        $modalidades = DB::select("SELECT DISTINCT TIPO_APOSTA.destipoapo
                                    FROM TIPO_APOSTA
                                

   ");
    //   dd($modalidades);
        return $modalidades;
      
    }

}

