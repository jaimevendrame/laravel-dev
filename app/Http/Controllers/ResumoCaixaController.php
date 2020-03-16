<?php

namespace lotecweb\Http\Controllers;

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

class ResumoCaixaController extends StandardController
{
    protected $model;
    protected $nameView = 'dashboard.resumocaixa';
    protected $data;
    protected $title = 'Resumo Geral por Revendedor';
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

    public function retornaCobrador($ideven){
        
        $valor = $this->retornaAdmin();
       
        if ($valor != 'SIM') {

            if(strlen($ideven) > 4){

                $data = DB::select(" 
                SELECT COBRADOR.IDBASE, COBRADOR.IDVEN, COBRADOR.IDCOBRA, COBRADOR.NOMCOBRA
                   FROM COBRADOR
                   INNER JOIN VENDEDOR ON VENDEDOR.IDBASE = COBRADOR.IDBASE AND
                                          VENDEDOR.IDVEN = COBRADOR.IDVEN
                WHERE
                     VENDEDOR.IDEVEN IN ($ideven) AND
                     COBRADOR.SITCOBRA = 'ATIVO'
                  
                  ORDER BY COBRADOR.NOMCOBRA
            ");
                
            }
            else {

            $p = $this->retornaBasepeloIdeven($ideven);

            $data = DB::select(" 
                   SELECT COBRADOR.IDBASE, COBRADOR.IDVEN, COBRADOR.IDCOBRA, COBRADOR.NOMCOBRA
                  FROM COBRADOR
                   WHERE
                     COBRADOR.IDBASE = '$p->idbase'AND
                     COBRADOR.IDVEN = '$p->idven' AND
                     COBRADOR.SITCOBRA = 'ATIVO'
                     
                     ORDER BY COBRADOR.NOMCOBRA
        ");
        }

        } else {
            $data = DB::select(" 
                   SELECT COBRADOR.IDBASE, COBRADOR.IDVEN, COBRADOR.IDCOBRA, COBRADOR.NOMCOBRA
                  FROM COBRADOR
                   WHERE
                     COBRADOR.SITCOBRA = 'ATIVO'
                     ORDER BY COBRADOR.NOMCOBRA
            ");
        }

         return $data;
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

        $vendedores = $this->retornaBasesUser($idusu);

        $menus = $this->retornaMenu($idusu);
        $menuMaisUsu = $this->retornaMenuMaisUsu($idusu);

        $categorias = $this->retornaCategorias($menus);

        $data = $this->retornaResumoCaixa($ideven);

        $title = $this->title;

        $baseAll = $this->retornaBasesAll($idusu);

        $ideven_default = $this->returnWebControlData($idusu);

        $cobradores = $this->retornaCobrador($ideven);

        //limpa a sessão
         if (session()->has('idCobra'))
            session()->forget('idCobra');

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
            'user_base', 'user_bases', 'usuario_lotec', 'vendedores', 'menus', 'categorias', 'data','title',
            'baseAll', 'ideven', 'ideven_default', 'cobradores', 'menuMaisUsu','revendedoresAposta'));
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

        $data = $this->retornaResumoCaixaParameter();

        $title = $this->title;

        $baseAll = $this->retornaBasesAll($idusu);

        $valor = $this->request->get('sel_vendedor');
      

        if (isset($valor)){
            $ideven2 = implode(",", $valor);
        } else{

            $valor = $this->retornaBasesPadrao($idusu);

            $ideven2  = $valor;
        }

        $dados = $this->request->get('sel_options');

        $idCobra = $this->request->input('idcobra');
        if($idCobra <> null){
           session(['idCobra' => $idCobra]);
        } 

        $in_ativos = 'NAO';
        $var_despesas = 'NAO';

        if (isset($dados)){
            if (in_array(1, $dados)) {
                $var_despesas = 'SIM';
            }else {
                $var_despesas = 'NAO';}

            if (in_array(2, $dados)) {
                $in_ativos = 'SIM';
            } else
                $in_ativos = 'NAO';
        }

        $ideven_default = $this->returnWebControlData($idusu);

        $cobradores = $this->retornaCobrador($ideven2);
        $nomeReven = $this->request->input('teste');
       

      $ideven2 =  explode(",", $ideven2);

                return view("{$this->nameView}",compact('idusu',
            'user_base', 'user_bases', 'usuario_lotec', 'vendedores', 'menus', 'categorias', 'data','title',
                    'baseAll','ideven2', 'var_despesas','in_ativos', 'ideven_default','cobradores', 'menuMaisUsu'));
    }

    public function retornaResumoCaixa($ideven){


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

            "SELECT REVENDEDOR.NOMREVEN, REVENDEDOR.IDBASE, REVENDEDOR.IDVEN, REVENDEDOR.IDREVEN, REVENDEDOR.LIMCRED, '$datIni' AS DATAINI, '$datFim' AS DATAFIM,
        (SELECT SUM(RESUMO_CAIXA.VLRVEN)
          FROM RESUMO_CAIXA
           WHERE
            RESUMO_CAIXA.IDBASE  = REVENDEDOR.IDBASE AND
            RESUMO_CAIXA.IDVEN   = REVENDEDOR.IDVEN AND
            RESUMO_CAIXA.IDREVEN = REVENDEDOR.IDREVEN AND
            RESUMO_CAIXA.DATMOV BETWEEN '$datIni' AND '$datFim') AS VLRVEN,
        (SELECT SUM(RESUMO_CAIXA.VLRCOM)
          FROM RESUMO_CAIXA
           WHERE
            RESUMO_CAIXA.IDBASE  = REVENDEDOR.IDBASE AND
            RESUMO_CAIXA.IDVEN   = REVENDEDOR.IDVEN AND
            RESUMO_CAIXA.IDREVEN = REVENDEDOR.IDREVEN AND
            RESUMO_CAIXA.DATMOV BETWEEN '$datIni' AND '$datFim') AS VLRCOM,
        (SELECT SUM(RESUMO_CAIXA.VLRLIQBRU)
          FROM RESUMO_CAIXA
           WHERE
            RESUMO_CAIXA.IDBASE  = REVENDEDOR.IDBASE AND
            RESUMO_CAIXA.IDVEN   = REVENDEDOR.IDVEN AND
            RESUMO_CAIXA.IDREVEN = REVENDEDOR.IDREVEN AND
            RESUMO_CAIXA.DATMOV BETWEEN '$datIni' AND '$datFim') AS VLRLIQBRU,
        (SELECT SUM(RESUMO_CAIXA.VLRPREMIO)
          FROM RESUMO_CAIXA
           WHERE
            RESUMO_CAIXA.IDBASE  = REVENDEDOR.IDBASE AND
            RESUMO_CAIXA.IDVEN   = REVENDEDOR.IDVEN AND
            RESUMO_CAIXA.IDREVEN = REVENDEDOR.IDREVEN AND
            RESUMO_CAIXA.DATMOV BETWEEN '$datIni' AND '$datFim') AS VLRPREMIO,
        (SELECT SUM(RESUMO_CAIXA.VLRRECEB)
          FROM RESUMO_CAIXA
           WHERE
            RESUMO_CAIXA.IDBASE  = REVENDEDOR.IDBASE AND
            RESUMO_CAIXA.IDVEN   = REVENDEDOR.IDVEN AND
            RESUMO_CAIXA.IDREVEN = REVENDEDOR.IDREVEN AND
            RESUMO_CAIXA.DATMOV BETWEEN '$datIni' AND '$datFim') AS VLRRECEB,

        (SELECT SUM(RESUMO_CAIXA.VLRPAGOU)
          FROM RESUMO_CAIXA
           WHERE
            RESUMO_CAIXA.IDBASE  = REVENDEDOR.IDBASE AND
            RESUMO_CAIXA.IDVEN   = REVENDEDOR.IDVEN AND
            RESUMO_CAIXA.IDREVEN = REVENDEDOR.IDREVEN AND
            RESUMO_CAIXA.DATMOV BETWEEN '$datIni' AND '$datFim') AS VLRPAGOU,

        (SELECT SUM(RESUMO_CAIXA.VLRTRANSR)
          FROM RESUMO_CAIXA
           WHERE
            RESUMO_CAIXA.IDBASE  = REVENDEDOR.IDBASE AND
            RESUMO_CAIXA.IDVEN   = REVENDEDOR.IDVEN AND
            RESUMO_CAIXA.IDREVEN = REVENDEDOR.IDREVEN AND
            RESUMO_CAIXA.DATMOV BETWEEN '$datIni' AND '$datFim') AS VLRTRANSR,

        (SELECT SUM(RESUMO_CAIXA.VLRTRANSP)
          FROM RESUMO_CAIXA
           WHERE
            RESUMO_CAIXA.IDBASE  = REVENDEDOR.IDBASE AND
            RESUMO_CAIXA.IDVEN   = REVENDEDOR.IDVEN AND
            RESUMO_CAIXA.IDREVEN = REVENDEDOR.IDREVEN AND
            RESUMO_CAIXA.DATMOV BETWEEN '$datIni' AND '$datFim') AS VLRTRANSP,

        (SELECT RESUMO_CAIXA.VLRDEVATU
            FROM RESUMO_CAIXA
             WHERE
               RESUMO_CAIXA.IDBASE  = REVENDEDOR.IDBASE AND
               RESUMO_CAIXA.IDVEN   = REVENDEDOR.IDVEN AND
               RESUMO_CAIXA.IDREVEN = REVENDEDOR.IDREVEN AND
               RESUMO_CAIXA.DATMOV  = (SELECT MAX(RC.DATMOV)
                                        FROM RESUMO_CAIXA RC
                                         WHERE
                                          RC.IDBASE = RESUMO_CAIXA.IDBASE AND
                                          RC.IDVEN = RESUMO_CAIXA.IDVEN AND
                                          RC.IDREVEN = RESUMO_CAIXA.IDREVEN AND
                                          RC.DATMOV <= '$datAnt' )) AS VLRDEVANT,

        (SELECT RESUMO_CAIXA.VLRDEVATU
           FROM RESUMO_CAIXA
            WHERE
             RESUMO_CAIXA.IDBASE  = REVENDEDOR.IDBASE AND
             RESUMO_CAIXA.IDVEN   = REVENDEDOR.IDVEN AND
             RESUMO_CAIXA.IDREVEN = REVENDEDOR.IDREVEN AND
             RESUMO_CAIXA.DATMOV  = (SELECT MAX(RC.DATMOV)
                                        FROM RESUMO_CAIXA RC
                                         WHERE
                                          RC.IDBASE = RESUMO_CAIXA.IDBASE AND
                                          RC.IDVEN = RESUMO_CAIXA.IDVEN AND
                                          RC.IDREVEN = RESUMO_CAIXA.IDREVEN AND
                                          RC.DATMOV <= '$datFim' )) AS VLRDEVATU,

        (SELECT SUM(RESUMO_CAIXA.DESPESA)
          FROM RESUMO_CAIXA
           WHERE
            RESUMO_CAIXA.IDBASE  = REVENDEDOR.IDBASE AND
            RESUMO_CAIXA.IDVEN   = REVENDEDOR.IDVEN AND
            RESUMO_CAIXA.IDREVEN = REVENDEDOR.IDREVEN AND
            RESUMO_CAIXA.DATMOV BETWEEN '$datIni' AND '$datFim') AS DESPESAS,

        (SELECT MAX(RESUMO_CAIXA.DATMOV)
           FROM RESUMO_CAIXA
            WHERE
              RESUMO_CAIXA.IDBASE  = REVENDEDOR.IDBASE AND
              RESUMO_CAIXA.IDVEN   = REVENDEDOR.IDVEN AND
              RESUMO_CAIXA.IDREVEN = REVENDEDOR.IDREVEN AND
              RESUMO_CAIXA.VLRVEN > 0 AND
              RESUMO_CAIXA.DATMOV <= '$datFim' ) AS DATAULTVEN
   FROM
     REVENDEDOR
   INNER JOIN VENDEDOR ON VENDEDOR.IDBASE = REVENDEDOR.IDBASE AND
        VENDEDOR.IDVEN = REVENDEDOR.IDVEN
    WHERE
     REVENDEDOR.IDREVEN <> 99999999
     
     AND VENDEDOR.IDEVEN in ($ideven)
     
     AND REVENDEDOR.SITREVEN = 'ATIVO'
     
     
     ORDER BY REVENDEDOR.NOMREVEN DESC
     
     "

        );


        return $data;
    }

    /**
     * @return mixed
     */
    public function retornaResumoCaixaParameter(){

        $dados = $this->request->get('sel_options');
        $nomeReven = $this->request->input('teste');
      //  dd($nomeReven);
        $in_ativos = '';

        if (isset($dados)){
            if (in_array(1, $dados)) {
                $despesas = 'SIM';
            }

            if (in_array(2, $dados)) {
                $in_ativos = 'SIM';
            } else
                $in_ativos = 'NAO';
        }


        if ($in_ativos == 'SIM'){
            $p_in_ativo = '';
        } else {
            $p_in_ativo = "AND REVENDEDOR.SITREVEN = 'ATIVO'";
        }

        $idCobra = $this->request->get('idcobra');
      //  dd($idCobra);

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
        } else {

            //Construi a string com base no array do select via form
            $p = implode(",", $valor);

        }


$vaSelect = "SELECT REVENDEDOR.NOMREVEN, REVENDEDOR.IDBASE, REVENDEDOR.IDVEN, REVENDEDOR.IDREVEN, REVENDEDOR.LIMCRED, '$datIni' AS DATAINI, '$datFim' AS DATAFIM,
        (SELECT SUM(RESUMO_CAIXA.VLRVEN)
          FROM RESUMO_CAIXA
           WHERE
            RESUMO_CAIXA.IDBASE  = REVENDEDOR.IDBASE AND
            RESUMO_CAIXA.IDVEN   = REVENDEDOR.IDVEN AND
            RESUMO_CAIXA.IDREVEN = REVENDEDOR.IDREVEN AND
            RESUMO_CAIXA.DATMOV BETWEEN '$datIni' AND '$datFim') AS VLRVEN,
        (SELECT SUM(RESUMO_CAIXA.VLRCOM)
          FROM RESUMO_CAIXA
           WHERE
            RESUMO_CAIXA.IDBASE  = REVENDEDOR.IDBASE AND
            RESUMO_CAIXA.IDVEN   = REVENDEDOR.IDVEN AND
            RESUMO_CAIXA.IDREVEN = REVENDEDOR.IDREVEN AND
            RESUMO_CAIXA.DATMOV BETWEEN '$datIni' AND '$datFim') AS VLRCOM,
        (SELECT SUM(RESUMO_CAIXA.VLRLIQBRU)
          FROM RESUMO_CAIXA
           WHERE
            RESUMO_CAIXA.IDBASE  = REVENDEDOR.IDBASE AND
            RESUMO_CAIXA.IDVEN   = REVENDEDOR.IDVEN AND
            RESUMO_CAIXA.IDREVEN = REVENDEDOR.IDREVEN AND
            RESUMO_CAIXA.DATMOV BETWEEN '$datIni' AND '$datFim') AS VLRLIQBRU,
        (SELECT SUM(RESUMO_CAIXA.VLRPREMIO)
          FROM RESUMO_CAIXA
           WHERE
            RESUMO_CAIXA.IDBASE  = REVENDEDOR.IDBASE AND
            RESUMO_CAIXA.IDVEN   = REVENDEDOR.IDVEN AND
            RESUMO_CAIXA.IDREVEN = REVENDEDOR.IDREVEN AND
            RESUMO_CAIXA.DATMOV BETWEEN '$datIni' AND '$datFim') AS VLRPREMIO,
        (SELECT SUM(RESUMO_CAIXA.VLRRECEB)
          FROM RESUMO_CAIXA
           WHERE
            RESUMO_CAIXA.IDBASE  = REVENDEDOR.IDBASE AND
            RESUMO_CAIXA.IDVEN   = REVENDEDOR.IDVEN AND
            RESUMO_CAIXA.IDREVEN = REVENDEDOR.IDREVEN AND
            RESUMO_CAIXA.DATMOV BETWEEN '$datIni' AND '$datFim') AS VLRRECEB,

        (SELECT SUM(RESUMO_CAIXA.VLRPAGOU)
          FROM RESUMO_CAIXA
           WHERE
            RESUMO_CAIXA.IDBASE  = REVENDEDOR.IDBASE AND
            RESUMO_CAIXA.IDVEN   = REVENDEDOR.IDVEN AND
            RESUMO_CAIXA.IDREVEN = REVENDEDOR.IDREVEN AND
            RESUMO_CAIXA.DATMOV BETWEEN '$datIni' AND '$datFim') AS VLRPAGOU,

        (SELECT SUM(RESUMO_CAIXA.VLRTRANSR)
          FROM RESUMO_CAIXA
           WHERE
            RESUMO_CAIXA.IDBASE  = REVENDEDOR.IDBASE AND
            RESUMO_CAIXA.IDVEN   = REVENDEDOR.IDVEN AND
            RESUMO_CAIXA.IDREVEN = REVENDEDOR.IDREVEN AND
            RESUMO_CAIXA.DATMOV BETWEEN '$datIni' AND '$datFim') AS VLRTRANSR,

        (SELECT SUM(RESUMO_CAIXA.VLRTRANSP)
          FROM RESUMO_CAIXA
           WHERE
            RESUMO_CAIXA.IDBASE  = REVENDEDOR.IDBASE AND
            RESUMO_CAIXA.IDVEN   = REVENDEDOR.IDVEN AND
            RESUMO_CAIXA.IDREVEN = REVENDEDOR.IDREVEN AND
            RESUMO_CAIXA.DATMOV BETWEEN '$datIni' AND '$datFim') AS VLRTRANSP,

        (SELECT RESUMO_CAIXA.VLRDEVATU
            FROM RESUMO_CAIXA
             WHERE
               RESUMO_CAIXA.IDBASE  = REVENDEDOR.IDBASE AND
               RESUMO_CAIXA.IDVEN   = REVENDEDOR.IDVEN AND
               RESUMO_CAIXA.IDREVEN = REVENDEDOR.IDREVEN AND
               RESUMO_CAIXA.DATMOV  = (SELECT MAX(RC.DATMOV)
                                        FROM RESUMO_CAIXA RC
                                         WHERE
                                          RC.IDBASE = RESUMO_CAIXA.IDBASE AND
                                          RC.IDVEN = RESUMO_CAIXA.IDVEN AND
                                          RC.IDREVEN = RESUMO_CAIXA.IDREVEN AND
                                          RC.DATMOV <= '$datAnt' )) AS VLRDEVANT,

        (SELECT RESUMO_CAIXA.VLRDEVATU
           FROM RESUMO_CAIXA
            WHERE
             RESUMO_CAIXA.IDBASE  = REVENDEDOR.IDBASE AND
             RESUMO_CAIXA.IDVEN   = REVENDEDOR.IDVEN AND
             RESUMO_CAIXA.IDREVEN = REVENDEDOR.IDREVEN AND
             RESUMO_CAIXA.DATMOV  = (SELECT MAX(RC.DATMOV)
                                        FROM RESUMO_CAIXA RC
                                         WHERE
                                          RC.IDBASE = RESUMO_CAIXA.IDBASE AND
                                          RC.IDVEN = RESUMO_CAIXA.IDVEN AND
                                          RC.IDREVEN = RESUMO_CAIXA.IDREVEN AND
                                          RC.DATMOV <= '$datFim' )) AS VLRDEVATU,

        (SELECT SUM(RESUMO_CAIXA.DESPESA)
          FROM RESUMO_CAIXA
           WHERE
            RESUMO_CAIXA.IDBASE  = REVENDEDOR.IDBASE AND
            RESUMO_CAIXA.IDVEN   = REVENDEDOR.IDVEN AND
            RESUMO_CAIXA.IDREVEN = REVENDEDOR.IDREVEN AND
            RESUMO_CAIXA.DATMOV BETWEEN '$datIni' AND '$datFim') AS DESPESAS,

        (SELECT MAX(RESUMO_CAIXA.DATMOV)
           FROM RESUMO_CAIXA
            WHERE
              RESUMO_CAIXA.IDBASE  = REVENDEDOR.IDBASE AND
              RESUMO_CAIXA.IDVEN   = REVENDEDOR.IDVEN AND
              RESUMO_CAIXA.IDREVEN = REVENDEDOR.IDREVEN AND
              RESUMO_CAIXA.VLRVEN > 0 AND
              RESUMO_CAIXA.DATMOV <= '$datFim' ) AS DATAULTVEN
   FROM
     REVENDEDOR
   INNER JOIN VENDEDOR ON VENDEDOR.IDBASE = REVENDEDOR.IDBASE AND
        VENDEDOR.IDVEN = REVENDEDOR.IDVEN
    WHERE
     REVENDEDOR.IDREVEN <> 99999999
     
     AND VENDEDOR.IDEVEN in ($p)
     
     
    $p_in_ativo";

    if($nomeReven <> null){
        $vaSelect = $vaSelect." AND REVENDEDOR.NOMREVEN = '$nomeReven'";
    }

    if($idCobra <> null){
        $vaSelect = $vaSelect." AND REVENDEDOR.IDCOBRA = '$idCobra'";
    }
     
    
    $vaSelect = $vaSelect." ORDER BY REVENDEDOR.NOMREVEN DESC";

   // dd($vaSelect);

        $data = DB::select ($vaSelect);
     //   dd($data);

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

    public function retornaApostaPremios($idven, $idbase, $idreven, $datini, $datfim){

//        $datini = date ("Y/m/d");
//        $datfim = date ("Y/m/d");
  //  dd($idven, $idbase, $idreven, $datini, $datfim);

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


   //    dd($data);

        return view('dashboard.apostapremiada', compact('data'));
    }





    public function retornaTransmissoes($idven, $idbase, $idreven, $datini, $datfim){

        //        $datini = date ("Y/m/d");
        //        $datfim = date ("Y/m/d");
        
        $data = DB::select (


        "SELECT SUM(APOSTA_PALPITES.VLRPALP) AS VLRPALP,
              APOSTA.NUMPULE, APOSTA.DATGER, APOSTA.HORGER, APOSTA.DATENV, APOSTA.HORENV, APOSTA.SITAPO,
              REVENDEDOR.IDEREVEN, REVENDEDOR.NOMREVEN, REVENDEDOR.CIDREVEN, VENDEDOR.NOMVEN, VENDEDOR.IDEVEN AS IDEVEN, '$datini' AS DATAINI, '$datfim' AS DATAFIM
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
              APOSTA.DATENV BETWEEN '$datini' AND '$datfim'
                    AND APOSTA.IDBASE = $idbase
                    AND APOSTA.IDVEN = $idven
                    AND APOSTA.IDREVEN = $idreven


                    GROUP BY
              APOSTA.NUMPULE, APOSTA.DATGER, APOSTA.HORGER, APOSTA.DATENV, APOSTA.HORENV, APOSTA.SITAPO,
              REVENDEDOR.IDEREVEN, REVENDEDOR.NOMREVEN, REVENDEDOR.CIDREVEN, VENDEDOR.NOMVEN, VENDEDOR.IDEVEN
            ORDER BY APOSTA.DATENV DESC, APOSTA.HORENV DESC
     
                        ");
        
        
            //  dd($data);
        
                return view('dashboard.transmissaoresumo', compact('data'));
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
