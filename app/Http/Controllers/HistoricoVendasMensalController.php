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
use lotecweb\Models\Revendedor;

class HistoricoVendasMensalController extends StandardController
{
    protected $model;
    protected $nameView = 'dashboard.historicovendasmensal';
    protected $data;
    protected $title = ' Histórico de Vendas Mensal';
    protected $redirectCad = '/admin/contatos/cadastrar';
    protected $redirectEdit = '/admin/contatos/editar';
    protected $route = '/admin/contatos';
    public $data_inicial;
    public $data_fim;

    public function __construct(
        Usuario $usuario,
        Usuario_ven $usuario_ven,
        Vendedor $vendedor,
        Revendedor $revendedor,
        Request $request)
    {
        $this->request = $request;
        $this->usuario = $usuario;
        $this->usuario_ven = $usuario_ven;
        $this->vendedor = $vendedor;
        $this->revendedor = $revendedor;


    }


    public function indexhistorico($ideven, $id)
    {
        $p_ideven = $ideven;
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

        $data = $this->retornaHistoricoVendas($ideven);

        $title = $this->title;

        $baseAll = $this->retornaBasesAll($idusu);

        $ideven_default = $this->returnWebControlData($idusu);
        $p_ideven = $ideven;


        //limpa a sessão
        // if (session()->has('idCobra'))
         //   session()->forget('idCobra');

        return view("{$this->nameView}",compact('idusu',
            'user_base', 'user_bases', 'usuario_lotec', 'vendedores', 'menus', 'categorias', 'data','title',
            'baseAll', 'ideven', 'ideven_default', 'menuMaisUsu','p_ideven'));
    }

    
    public function index2($ideven){

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

        $title = $this->title;

        $baseAll = $this->retornaBasesAll($idusu);

        $ideven_default = $this->returnWebControlData($idusu);

        $reven = $this->retornaRevendedor($ideven);
    
        $data = $this->retornaHistoricoVendas($ideven);
        $p_ideven = $ideven;
       // $sel_revendedor = $this->request->get('sel_revendedor');

         //retorna os revendedores no combobox
         $data_movcax = $this->retornaRevendedorHistoricoVendas($ideven);

        return view("{$this->nameView}",compact('idusu',
            'user_base', 'user_bases', 'usuario_lotec', 'vendedores', 'menus', 'categorias', 'data','title',
            'baseAll', 'ideven', 'ideven_default', 'menuMaisUsu','p_ideven','data_movcax' ));
    }

    public function indexGo($ideven) {

        $reven = $this->retornaRevendedor($ideven);
        $idusu = Auth::user()->idusu;

        $user_base = $this->retornaBase($idusu);

        $user_bases = $this->retornaBases($idusu);

        $usuario_lotec = $this->retornaUserLotec($idusu);

        $vendedores = $this->retornaBasesUser($idusu);

        $menus = $this->retornaMenu($idusu);
        $menuMaisUsu = $this->retornaMenuMaisUsu($idusu);

        $categorias = $this->retornaCategorias($menus);


        $sel_revendedor = $this->request->get('sel_revendedor');
        
        $data = $this->retornaHistoricoParameter($ideven,$sel_revendedor);

        $p_ideven = $ideven;
        $title = $this->title;

        $baseAll = $this->retornaBasesAll($idusu);

        //referente aos IDEVEN
        $valor = $this->request->get('sel_vendedor');

        //retorna os revendedores no combobox
        $data_movcax = $this->retornaRevendedorHistoricoVendas($ideven);
      

        if (isset($valor)){
            $ideven2 = implode(",", $valor);
       ;
        } else{

            $valor = $this->retornaBasesPadrao($idusu);

            $ideven2  = $valor;
        }

        $dados = $this->request->get('sel_options');

     

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

      //  $cobradores = $this->retornaCobrador($ideven2);
        
      $ideven2 =  explode(",", $ideven2);

                return view("{$this->nameView}",compact('idusu',
            'user_base', 'user_bases', 'usuario_lotec', 'vendedores', 'menus', 'categorias', 'data','title',
                    'baseAll','ideven2', 'var_despesas','in_ativos', 'ideven_default', 'menuMaisUsu','sel_revendedor','p_ideven','data_movcax'));
    }

   





    public function retornaHistoricoVendas($ideven){
       
        $sel_revendedor = $this->request->get('sel_revendedor');

        //$data = $this->retornaHistoricoParameter($ideven,$sel_revendedor);
        
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

        $str_idreven = '';


        if ($sel_revendedor != NULL){
           
            $str_idreven = "AND REVENDEDOR.IDEREVEN = ".$sel_revendedor;

        }else{
            $str_idreven = 0;
        }
          //  dd($str_idreven);
           
       //  $idereven = $this->returnIdReven();
        //$dados = DB::table('REVENDEDOR')->where('idereven', $id)->first();;

        $str_idbase = '';
        $str_idven = '';

        $p = $this->retornaBasepeloIdeven($ideven);

        //retornar o idven

        $str_idbase = "AND RESUMO_CAIXA.IDBASE = ".$p->idbase;
        $str_idven = "AND RESUMO_CAIXA.IDVEN = ".$p->idven ;


        $data = DB::select (
            " SELECT RESUMO_CAIXA.IDBASE, RESUMO_CAIXA.IDVEN, RESUMO_CAIXA.IDREVEN,
                     RESUMO_CAIXA.VLRDEVANT, RESUMO_CAIXA.VLRDEVATU,
                     RESUMO_CAIXA.VLRVEN, RESUMO_CAIXA.VLRCOM, RESUMO_CAIXA.VLRLIQBRU,
                     RESUMO_CAIXA.VLRPREMIO, RESUMO_CAIXA.VLRRECEB, RESUMO_CAIXA.IDCOBRA,
                     RESUMO_CAIXA.VLRPAGOU, RESUMO_CAIXA.VLR_AUX, RESUMO_CAIXA.VEN_RECARGA, RESUMO_CAIXA.COM_RECARGA,
                     RESUMO_CAIXA.DESPESA AS DESPESAS,
                     RESUMO_CAIXA.DATMOV,
                     REVENDEDOR.NOMREVEN,
                     REVENDEDOR.IDEREVEN
                    
                     FROM RESUMO_CAIXA
                        

                     INNER JOIN REVENDEDOR ON REVENDEDOR.IDBASE = RESUMO_CAIXA.IDBASE AND
                                REVENDEDOR.IDVEN = RESUMO_CAIXA.IDVEN AND
                                REVENDEDOR.IDREVEN = RESUMO_CAIXA.IDREVEN



                     INNER JOIN VENDEDOR ON VENDEDOR.IDBASE = REVENDEDOR.IDBASE AND
                                VENDEDOR.IDVEN = REVENDEDOR.IDVEN
                        WHERE
                            RESUMO_CAIXA.IDBASE <> 99999999
                            AND RESUMO_CAIXA.DATMOV BETWEEN '$datIni' AND '$datFim'
                          
                            $str_idbase
                            $str_idven
                            
                            AND REVENDEDOR.IDEREVEN = $str_idreven
             
                    "
         
        );
      //  dd($str_idreven);
       return $data;
    }


    /**
     * @return mixed
     */
    public function retornaHistoricoParameter($ideven, $sel_revendedor){

        $str_idbase = '';
        $str_idven = '';

        if (Auth::user()->idusu <> 1000){
            $p = $this->retornaBasepeloIdeven($ideven);

            //retornar o idven

            $str_idbase = "AND RESUMO_CAIXA.IDBASE = ".$p->idbase;
            $str_idven = "AND RESUMO_CAIXA.IDVEN = ".$p->idven ;

        }

        $str_idreven = '';

        if ($sel_revendedor != NULL){
           
            $str_idreven = $sel_revendedor;

        }else{
            $str_idreven = 0;
        }
       // dd($sel_revendedor,$str_idreven);
    
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
    $data = DB::select (
        " SELECT RESUMO_CAIXA.IDBASE, RESUMO_CAIXA.IDVEN, RESUMO_CAIXA.IDREVEN,
        RESUMO_CAIXA.VLRDEVANT, RESUMO_CAIXA.VLRDEVATU,
        RESUMO_CAIXA.VLRVEN, RESUMO_CAIXA.VLRCOM, RESUMO_CAIXA.VLRLIQBRU,
        RESUMO_CAIXA.VLRPREMIO, RESUMO_CAIXA.VLRRECEB, RESUMO_CAIXA.IDCOBRA,
        RESUMO_CAIXA.VLRPAGOU, RESUMO_CAIXA.VLR_AUX, RESUMO_CAIXA.VEN_RECARGA, RESUMO_CAIXA.COM_RECARGA,
        RESUMO_CAIXA.DESPESA AS DESPESAS,
        RESUMO_CAIXA.DATMOV,
        REVENDEDOR.NOMREVEN,
        REVENDEDOR.IDEREVEN
    
        FROM RESUMO_CAIXA
        

        INNER JOIN REVENDEDOR ON REVENDEDOR.IDBASE = RESUMO_CAIXA.IDBASE AND
                REVENDEDOR.IDVEN = RESUMO_CAIXA.IDVEN AND
                REVENDEDOR.IDREVEN = RESUMO_CAIXA.IDREVEN


        INNER JOIN VENDEDOR ON VENDEDOR.IDBASE = REVENDEDOR.IDBASE AND
                VENDEDOR.IDVEN = REVENDEDOR.IDVEN
        WHERE
            RESUMO_CAIXA.IDBASE <> 99999999
            AND RESUMO_CAIXA.DATMOV BETWEEN '$datIni' AND '$datFim'
            
            $str_idbase
            $str_idven
            
            AND REVENDEDOR.IDEREVEN = $str_idreven "
            
            );
          //  dd($datIni,$datFim);
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

    public function retornaApostaPremios($idven, $idbase, $idreven,  $datmovano,$datmov){

     //   $datIni = $this->request->get('datIni');
     //   $datFim = $this->request->get('datFim');
   
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
              APOSTA_PALPITES.SITPRE,APOSTA_PALPITES.HORLIBPRE,APOSTA_PALPITES.DATLIBPRE,
              APOSTA_PALPITES.DATLIMPRE, APOSTA_PALPITES.INATRASADO, APOSTA_PALPITES.INSORPRO, 
              APOSTA_PALPITES.INFODESC, APOSTA_PALPITES.PALP16,APOSTA_PALPITES.PALP17,
              APOSTA_PALPITES.PALP18,APOSTA_PALPITES.PALP19,APOSTA_PALPITES.PALP20,
              APOSTA_PALPITES.PALP21,APOSTA_PALPITES.PALP22,APOSTA_PALPITES.PALP23,
              APOSTA_PALPITES.PALP24,APOSTA_PALPITES.PALP25,APOSTA_PALPITES.PRELIBMANUAL,
              APOSTA_PALPITES.NUMAUT,APOSTA_PALPITES.VLR_AUX,
              REVENDEDOR.NOMREVEN,
              HOR_APOSTA.DESHOR,
              TIPO_APOSTA.DESTIPOAPO,
              EXTRACT(month FROM  APOSTA_PALPITES.DATLIBPRE) || '-' || EXTRACT( YEAR FROM APOSTA_PALPITES.DATLIBPRE ) AS ANOMES,
              EXTRACT(month FROM APOSTA_PALPITES.DATLIBPRE) AS MES,
              EXTRACT(year FROM APOSTA_PALPITES.DATLIBPRE) AS ANO,
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

         AND EXTRACT(month FROM  APOSTA_PALPITES.DATLIBPRE) || '-' || EXTRACT( YEAR FROM APOSTA_PALPITES.DATLIBPRE ) =  REPLACE('$datmovano',' ','')
         
         AND APOSTA_PALPITES.IDBASE = $idbase
         AND APOSTA_PALPITES.IDVEN = $idven
         AND APOSTA_PALPITES.IDREVEN = $idreven
           ");
   
   
         //  dd($datmov);
  //dd($data,$datmovano,$datmov);
           return view('dashboard.apostapremiada', compact('data'));
       }

       
       
    public function retornaRevendedorHistoricoVendas($ideven){

        $p = $this->retornaBasepeloIdeven($ideven);


        if ($p != Null){

            $data = DB::select (" 
        SELECT REVENDEDOR.IDBASE, REVENDEDOR.IDVEN, REVENDEDOR.IDREVEN,REVENDEDOR.IDEREVEN,
         REVENDEDOR.NOMREVEN
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


    public function indexTestes($ideven) {

        $reven = $this->retornaRevendedor($ideven);
        $idusu = Auth::user()->idusu;

        $user_base = $this->retornaBase($idusu);

        $user_bases = $this->retornaBases($idusu);

        $usuario_lotec = $this->retornaUserLotec($idusu);

        $vendedores = $this->retornaBasesUser($idusu);

        $menus = $this->retornaMenu($idusu);
        $menuMaisUsu = $this->retornaMenuMaisUsu($idusu);

        $categorias = $this->retornaCategorias($menus);


        $sel_revendedor = $this->request->get('sel_revendedor');
        
        $data = $this->retornaTestes($ideven,$sel_revendedor);

        $p_ideven = $ideven;
        $title = $this->title;

        $baseAll = $this->retornaBasesAll($idusu);

        //referente aos IDEVEN
        $valor = $this->request->get('sel_vendedor');

        //retorna os revendedores no combobox
        $data_movcax = $this->retornaRevendedorHistoricoVendas($ideven);
      

        if (isset($valor)){
            $ideven2 = implode(",", $valor);
       ;
        } else{

            $valor = $this->retornaBasesPadrao($idusu);

            $ideven2  = $valor;
        }

        $dados = $this->request->get('sel_options');

     

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

      //  $cobradores = $this->retornaCobrador($ideven2);
        
      $ideven2 =  explode(",", $ideven2);

                return view("{$this->nameView}",compact('idusu',
            'user_base', 'user_bases', 'usuario_lotec', 'vendedores', 'menus', 'categorias', 'data','title',
                    'baseAll','ideven2', 'var_despesas','in_ativos', 'ideven_default', 'menuMaisUsu','sel_revendedor','p_ideven','data_movcax'));
    }


     /**
     * @return mixed
     */
    public function retornaTestes($ideven, $sel_revendedor){

        $str_idbase = '';
        $str_idven = '';

        if (Auth::user()->idusu <> 1000){
            $p = $this->retornaBasepeloIdeven($ideven);

            //retornar o idven

            $str_idbase = "AND RESUMO_CAIXA.IDBASE = ".$p->idbase;
            $str_idven = "AND RESUMO_CAIXA.IDVEN = ".$p->idven ;

        }

        $str_idreven = '';

        if ($sel_revendedor != NULL){
           
            $str_idreven = $sel_revendedor;

        }else{
            $str_idreven = 0;
        }
       // dd($sel_revendedor,$str_idreven);
    
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
    $data = DB::select(
       "SELECT EXTRACT(month FROM  RESUMO_CAIXA.DATMOV) AS datmov,
       EXTRACT(month FROM  RESUMO_CAIXA.DATMOV) || '-' || EXTRACT( YEAR FROM RESUMO_CAIXA.DATMOV )  AS datmovano,
      
       SUM(RESUMO_CAIXA.VLRVEN) AS vlrven,
       RESUMO_CAIXA.IDBASE,RESUMO_CAIXA.IDVEN,RESUMO_CAIXA.IDREVEN,SUM(RESUMO_CAIXA.VLRCOM) AS vlrcom,
       SUM(RESUMO_CAIXA.VLRLIQBRU) AS vlrliqbru,SUM(RESUMO_CAIXA.VLRPREMIO) AS vlrpremio,SUM(RESUMO_CAIXA.VLRRECEB) AS vlrreceb,
       SUM(RESUMO_CAIXA.VLRPAGOU)AS vlrpagou,SUM(RESUMO_CAIXA.VLR_AUX),
       SUM(RESUMO_CAIXA.DESPESA) AS DESPESAS,
       SUM(RESUMO_CAIXA.VLRDEVATU) AS vlrdevatu,
       REVENDEDOR.NOMREVEN

        FROM RESUMO_CAIXA


        INNER JOIN REVENDEDOR ON REVENDEDOR.IDBASE = RESUMO_CAIXA.IDBASE AND
            REVENDEDOR.IDVEN = RESUMO_CAIXA.IDVEN AND
            REVENDEDOR.IDREVEN = RESUMO_CAIXA.IDREVEN 

       WHERE RESUMO_CAIXA.IDBASE <> 99999999
       AND RESUMO_CAIXA.DATMOV BETWEEN  '$datIni' AND '$datFim'
       $str_idbase
       $str_idven
       AND REVENDEDOR.IDEREVEN = $str_idreven
        
      
       GROUP BY EXTRACT(month FROM RESUMO_CAIXA.DATMOV),RESUMO_CAIXA.IDBASE,RESUMO_CAIXA.IDVEN,RESUMO_CAIXA.IDREVEN,REVENDEDOR.NOMREVEN,EXTRACT(year FROM RESUMO_CAIXA.DATMOV)
                
       HAVING SUM(RESUMO_CAIXA.VLRVEN) <> 99999999 AND SUM(RESUMO_CAIXA.VLRCOM) <> 99999999
       ORDER BY EXTRACT(month FROM RESUMO_CAIXA.DATMOV)
        
        "

   );
    //dd($data);
    return $data;

    
    }




}
