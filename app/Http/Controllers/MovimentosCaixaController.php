<?php

namespace lotecweb\Http\Controllers;

use DateInterval;
use DateTime;
use Illuminate\Http\Request;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use lotecweb\Http\Requests;
use lotecweb\Models\Cobrador;
use lotecweb\Models\Movimento_caixa;
use lotecweb\Models\Revendedor;
use lotecweb\Models\Usuario;
use lotecweb\Models\Usuario_ven;
use lotecweb\Models\Vendedor;

class MovimentosCaixaController extends StandardController
{
    protected $model;
    protected $nameView = 'dashboard.movimentoscaixa';
    protected $data;
    protected $title = 'Movimentos de Caixa';
    protected $redirectCad = '/admin/contatos/cadastrar';
    protected $redirectEdit = '/admin/contatos/editar';
    protected $route = '/admin/movimentoscaixa';
    public $data_inicial;
    public $data_fim;

    public function __construct(
        Usuario $usuario,
        Usuario_ven $usuario_ven,
        Vendedor $vendedor,
        Revendedor $revendedor,
        Cobrador $cobrador,
        Movimento_caixa $movimento_caixa,
        Request $request)
    {
        $this->request = $request;
        $this->usuario = $usuario;
        $this->usuario_ven = $usuario_ven;
        $this->vendedor = $vendedor;
        $this->revendedor = $revendedor;
        $this->cobrador = $cobrador;
        $this->movimmento_caixa = $movimento_caixa;


    }

    public function index2($ideven)
    {

        $reven = $this->retornaRevendedor($ideven);

        $cobrador = $this->retornaCobrador($ideven);

        $idusu = Auth::user()->idusu;

        $user_base = $this->retornaBase($idusu);

        $user_bases = $this->retornaBases($idusu);

        $usuario_lotec = $this->retornaUserLotec($idusu);

        $vendedores = $this->retornaBasesUser($idusu);

        $menus = $this->retornaMenu($idusu);
        $menuMaisUsu = $this->retornaMenuMaisUsu($idusu);

        $categorias = $this->retornaCategorias($menus);

        $data = $this->retornaMovimentosCaixa($ideven);

        $data_movcax = $this->retornaRevendedorMovCx($ideven);

        $title = $this->title;

        $baseAll = $this->retornaBasesAll($idusu);

        $p_ideven = $ideven;

        $teste = $this->returnIdevenQuery($ideven);


        $p = $this->retornaBasepeloIdeven($ideven);

        $ideven_default = $this->returnWebControlData($idusu);




        return view("{$this->nameView}",compact('idusu',
            'user_base', 'user_bases', 'usuario_lotec', 'vendedores', 'menus', 'categorias', 'data','title',
            'baseAll', 'reven', 'cobrador', 'p_ideven', 'data_movcax','p', 'ideven_default', 'menuMaisUsu'));
    }

    public function indexGo($ideven)
    {


        $reven = $this->retornaRevendedor($ideven);

        $cobrador = $this->retornaCobrador($ideven);

        $sel_revendedor = $this->request->get('sel_revendedor');
        $sel_revendedor2 = $this->request->get('sel_revendedor2');

     //   dd($sel_revendedor2);

        $sel_cobrador = $this->request->get('sel_cobrador');

        $idusu = Auth::user()->idusu;

        $user_base = $this->retornaBase($idusu);

        $user_bases = $this->retornaBases($idusu);

        $usuario_lotec = $this->retornaUserLotec($idusu);

        $vendedores = $this->retornaBasesUser($idusu);

        $menus = $this->retornaMenu($idusu);
        $menuMaisUsu = $this->retornaMenuMaisUsu($idusu);

        $categorias = $this->retornaCategorias($menus);

        $data = $this->retornaMovimentosCaixaParameter($ideven, $sel_revendedor, $sel_cobrador);

        $data_movcax = $this->retornaRevendedorMovCx($ideven);

        $title = $this->title;

        $baseAll = $this->retornaBasesAll($idusu);

        $p_ideven = $ideven;


        $p = $this->retornaBasepeloIdeven($ideven);

   //     dd($sel_revendedor);

        $ideven_default = $this->returnWebControlData($idusu);


        return view("{$this->nameView}",compact('idusu',
            'user_base', 'user_bases', 'usuario_lotec', 'vendedores', 'menus', 'categorias', 'data','title',
            'baseAll', 'reven', 'cobrador', 'p_ideven','sel_revendedor', 'sel_revendedor2', 'sel_cobrador','data_movcax', 'p', 'ideven_default', 'menuMaisUsu'));
    }

    public function retornaMovimentosCaixa($ideven){

        $str_idbase = '';
        $str_idven = '';
        $idusu = Auth::user()->idusu;
        $admin = Usuario::where('idusu', '=', $idusu)->first();

        if ($admin->inadim != 'SIM'){
            $p = $this->retornaBasepeloIdeven($ideven);

            //retornar o idven

            $str_idbase = "AND MOVIMENTOS_CAIXA.IDBASE = ".$p->idbase;
            $str_idven = "AND MOVIMENTOS_CAIXA.IDVEN = ".$p->idven ;

        }

        $datIni = date ("Y/m/d");
        $datFim = date ("Y/m/d");

        $this->data_inicial = $datIni;
        $this->data_fim = $datFim;


        $data = DB::select ("
        SELECT MOVIMENTOS_CAIXA.*, REVENDEDOR.NOMREVEN, COBRADOR.NOMCOBRA, '$datIni' AS DATAINI, '$datFim' AS DATAFIM, MOVIMENTOS_CAIXA.SEQORDEM AS SEQORDEM
        FROM MOVIMENTOS_CAIXA
        INNER JOIN REVENDEDOR ON
                  REVENDEDOR.IDBASE = MOVIMENTOS_CAIXA.IDBASE AND
                  REVENDEDOR.IDVEN = MOVIMENTOS_CAIXA.IDVEN AND
                  REVENDEDOR.IDREVEN = MOVIMENTOS_CAIXA.IDREVEN
        LEFT JOIN COBRADOR ON
                  COBRADOR.IDBASE = MOVIMENTOS_CAIXA.IDBASE AND
                  COBRADOR.IDVEN = MOVIMENTOS_CAIXA.IDVEN AND
                  COBRADOR.IDCOBRA = MOVIMENTOS_CAIXA.IDCOBRA
        WHERE 
            MOVIMENTOS_CAIXA.DATMOV BETWEEN '$datIni' AND '$datFim'
            
            $str_idbase
            $str_idven
            
        "

        );

//    dd($data);
        return $data;
    }

    /**
     * @return mixed
     */
    public function retornaMovimentosCaixaParameter($ideven, $sel_revendedor, $sel_cobrador){

        $str_idbase = '';
        $str_idven = '';

        if (Auth::user()->idusu <> 1000){
            $p = $this->retornaBasepeloIdeven($ideven);

            //retornar o idven

            $str_idbase = "AND MOVIMENTOS_CAIXA.IDBASE = ".$p->idbase;
            $str_idven = "AND MOVIMENTOS_CAIXA.IDVEN = ".$p->idven ;

        }

        $str_idreven = "";


        if ($sel_revendedor != NULL){

            $str_idreven = " AND MOVIMENTOS_CAIXA.IDREVEN = ".$sel_revendedor;
        }

        $str_idcobra = "";


        if ($sel_cobrador != NULL){

            $str_idcobra = " AND MOVIMENTOS_CAIXA.IDCOBRA = ".$sel_cobrador;
        }




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
//            dd($valor);
            $p = $valor;
        } else {

            //Construi a string com base no array do select via form
            $p = implode(",", $valor);
        }

//        dd($valor);



        $data = DB::select ("
        SELECT MOVIMENTOS_CAIXA.*, REVENDEDOR.NOMREVEN, COBRADOR.NOMCOBRA, '$datIni' AS DATAINI, '$datFim' AS DATAFIM, MOVIMENTOS_CAIXA.SEQORDEM AS SEQORDEM
        FROM MOVIMENTOS_CAIXA
        INNER JOIN REVENDEDOR ON
                  REVENDEDOR.IDBASE = MOVIMENTOS_CAIXA.IDBASE AND
                  REVENDEDOR.IDVEN = MOVIMENTOS_CAIXA.IDVEN AND
                  REVENDEDOR.IDREVEN = MOVIMENTOS_CAIXA.IDREVEN
        LEFT JOIN COBRADOR ON
                  COBRADOR.IDBASE = MOVIMENTOS_CAIXA.IDBASE AND
                  COBRADOR.IDVEN = MOVIMENTOS_CAIXA.IDBASE AND
                  COBRADOR.IDCOBRA = MOVIMENTOS_CAIXA.IDCOBRA
        WHERE 
            MOVIMENTOS_CAIXA.DATMOV BETWEEN '$datIni' AND '$datFim'
            
            $str_idbase
            $str_idven
            $str_idreven
            $str_idcobra
            
        "

        );



//        dd($data);

        return $data;
    }


    public function retornaBasesPadrao($id){


        $data = $this->usuario_ven

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

    public function retornaApostaPremios($idven, $idbase, $idreven, $datini, $datfim){

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
           REVENDEDOR.NOMREVEN,
           HOR_APOSTA.DESHOR,
           TIPO_APOSTA.DESTIPOAPO,
           COLOCACOES.DESCOL, ' ' as inSel
      FROM APOSTA_PALPITES
      INNER JOIN REVENDEDOR ON REVENDEDOR.IDBASE = APOSTA_PALPITES.IDBASE AND
                               REVENDEDOR.IDVEN = APOSTA_PALPITES.IDVEN AND
                               REVENDEDOR.IDREVEN = APOSTA_PALPITES.IDREVEN
      INNER JOIN HOR_APOSTA ON HOR_APOSTA.IDLOT = APOSTA_PALPITES.IDLOT AND
                               HOR_APOSTA.IDHOR = APOSTA_PALPITES.IDHOR
      INNER JOIN TIPO_APOSTA ON TIPO_APOSTA.IDTIPOAPO = APOSTA_PALPITES.IDTIPOAPO
      INNER JOIN COLOCACOES ON COLOCACOES.IDCOL = APOSTA_PALPITES.IDCOL
      WHERE
          APOSTA_PALPITES.SITAPO = 'PRE'
      AND APOSTA_PALPITES.DATLIBPRE BETWEEN '$datini' AND '$datfim'
      AND APOSTA_PALPITES.IDBASE = $idbase
      AND APOSTA_PALPITES.IDVEN = $idven
      AND APOSTA_PALPITES.IDREVEN = $idreven
        ");


//        dd($data);

        return view('dashboard.apostapremiada', compact('data'));
    }

    public function retornaRevendedorMovCx($ideven){

        $p = $this->retornaBasepeloIdeven($ideven);


        if ($p != Null){

            $data = DB::select (" 
        SELECT REVENDEDOR.IDBASE, REVENDEDOR.IDVEN, REVENDEDOR.IDREVEN, REVENDEDOR.NOMREVEN,
               (SELECT RESUMO_CAIXA.VLRDEVATU
                    FROM RESUMO_CAIXA
                   WHERE
                     RESUMO_CAIXA.IDBASE = REVENDEDOR.IDBASE AND
                     RESUMO_CAIXA.IDVEN = REVENDEDOR.IDVEN AND
                     RESUMO_CAIXA.IDREVEN = REVENDEDOR.IDREVEN AND
                     RESUMO_CAIXA.DATMOV = (SELECT MAX(RC.DATMOV) 
                                               FROM RESUMO_CAIXA RC
                                                WHERE 
                                                 RC.IDBASE = RESUMO_CAIXA.IDBASE AND
                                                 RC.IDVEN = RESUMO_CAIXA.IDVEN AND
                                                 RC.IDREVEN = RESUMO_CAIXA.IDREVEN)) AS VLRDEVATU
          FROM REVENDEDOR
          WHERE
            REVENDEDOR.IDBASE = '$p->idbase' AND
            REVENDEDOR.IDVEN = '$p->idven' AND
            REVENDEDOR.SITREVEN = 'ATIVO'
            ORDER BY REVENDEDOR.NOMREVEN
        ");


        } else {

            $data = '';
        }








        return $data;
    }

    public function addCaixa()
    {
        $dadosForm = 'OK';

        return $dadosForm;

    }
    public function addCaixaGo()
    {
        $valor = $this->request->all();

//        dd($valor);


        //Dados retorno request view
        $idbase = $this->request->input('idbase');
        $idven =  $this->request->input('idven');
        $idreven =  $this->request->input('idreven');
        $tipomov =  $this->request->input('tipomov');
        $vlrmov =  $this->request->input('vlrmov');
        $saldoant =  $this->request->input('saldoatu');
        $saldoatu =  $this->request->input('saldoresul');
        $idusu =  $this->request->input('idusu');
        $idcobra =  $this->request->input('idcobra');
        $obsdes = $this->request->input('obsdes');




        $l = count($idbase);

        $x = 0;
        $y = 0;

        $dados = array();

        for ($i =0; $i < $l; $i++){

            $data_mov = date ("Y-m-d");
            $hora_mov = date ("Y-m-d H:i:s");
            $idtipo = 2;
            $insinc = 'NAO';



            $linhaMov =  [
                "idbase"    => $idbase[$i],
                "idven"     => $idven[$i],
                "idreven"   => $idreven[$i],
                "seqmov"    => $this->seqMov($idbase[$i], $idven[$i], $idreven[$i]),
                "datmov"    => $data_mov,
                "hormov"    => $hora_mov,
                "tipomov"   => $tipomov[$i],
                "vlrmov"    => floatval(str_replace(',','.', $vlrmov[$i])),
                "saldoant"    => floatval(str_replace(',','.', $saldoant[$i])),
                "saldoatu"    => floatval(str_replace(',','.', $saldoatu[$i])),
                "idusumov"  => $idusu[$i],
                "idcobra"   => $idcobra[$i],
                "nomeusumov"=>  $this->retornaUserLotec($idusu[$i])->nomusu,
                "insinc"    => $insinc,

            ];


            $linhaDesp = [
                "idbase"    => $idbase[$i],
                "idven"     => $idven[$i],
                "idreven"   => $idreven[$i],
                "datdes"    => $data_mov,
                "hordes"    => $hora_mov,
                "idtipo"   =>  $idtipo,
                "vlrdes"    => floatval(str_replace(',','.', $vlrmov[$i])),
                "idusucad"  => $idusu[$i],
                "seqdes"    => $this->seqDes($idbase[$i], $idven[$i]),
                "obsdes"    => $obsdes[$i],

            ];




            array_push($dados, $linhaMov);

            $insert = DB::table('MOVIMENTOS_CAIXA')->insert($linhaMov);


            if ($insert)

                $x = $x + 1;

            if ( $tipomov[$i] == 'DESPESA'){


                $insertDesp = DB::table('DESPESAS')->insert($linhaDesp);

                if ($insertDesp)

                    $y = $y + 1;
            }




        }

        return $x;



    }

    function seqMov($idbase, $idven, $idreven)
    {
        $data = DB::select (" 
        SELECT MAX(MOVIMENTOS_CAIXA.SEQMOV) AS SEQMOV
                  FROM MOVIMENTOS_CAIXA
                  WHERE
                  MOVIMENTOS_CAIXA.IDBASE = '$idbase' AND
                  MOVIMENTOS_CAIXA.IDVEN = '$idven' AND
                  MOVIMENTOS_CAIXA.IDREVEN = '$idreven'

        ");

        $p = $data[0]->seqmov;

        if ($p == null){
            return 1;
        } else {
            return $p + 1;
        }

    }

    function seqDes($idbase, $idven)
    {
        $data = DB::select (" 
        SELECT MAX(SEQDES) AS SEQDES
                  FROM DESPESAS
                  WHERE
                  IDBASE = '$idbase' AND
                  IDVEN = '$idven'

        ");

        $p = $data[0]->seqdes;

        if ($p == null){
            return 1;
        } else {
            return $p + 1;
        }

    }

}
