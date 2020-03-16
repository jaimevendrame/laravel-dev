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

class DescargasEnviadasController extends StandardController
{
    protected $model;
    protected $nameView = 'dashboard.descargasenviadas';
    protected $data;
    protected $title = 'Descargas Enviadas';
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
        $ideven2 = $ideven;

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

        $descarga = $this->returnVendDesg($ideven2);

        $semana = $this->returnLoteriaDia();

        $loterias = $this->returnLoterias();

//        $data = $this->returnDescargasEnviadas($ideven);
        $data = $this->returnIndex($ideven);


        //referente aos IDEVEN
        $valor = $this->request->get('sel_vendedor');

        if (isset($valor)){
            $ideven2 = $valor;
        } else{
            $ideven2  = '';
            $ideven = $ideven;
        }

        //pegar loteria paramter
        $idlot = $this->request->get('sel_loterias');

        //pegar situação
        $idsit = $this->request->get('sel_situacao');

        //pegar vededor destino
        $idvendd = $this->request->get('sel_vendedord');

        //pegar palpites
        $palpite = $this->request->get('numpule');

        //pesquisar por horário loterias
        $idehor = $this->request->get('sel_lotodia');

        if ($idehor != Null){

            $idehor = $idehor;
        } else{

            $idehor  = '';

        }

        $ideven_default = $this->returnWebControlData($idusu);



        return view("{$this->nameView}",compact('idusu',
            'user_base', 'user_bases', 'usuario_lotec', 'vendedores', 'menus', 'categorias', 'data','title', 'baseAll', 'ideven','ideven2', 'idlot','idsit',
            'idehor','palpite','idvendd','descarga', 'semana', 'loterias', 'ideven_default', 'menuMaisUsu'));
    }

    public function indexGo($ideven)
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

        $title = $this->title;

        $baseAll = $this->retornaBasesAll($idusu);

        $descarga = $this->returnVendDesg($ideven);

        $semana = $this->returnLoteriaDia();

        $loterias = $this->returnLoterias();

        $data = $this->returnDescargasEnviadas($ideven);


        //referente aos IDEVEN
        $valor = $this->request->get('sel_vendedor');

        if (isset($valor)){
            $ideven2 = $valor;
        } else{
            $ideven2  = '';
            $ideven = $ideven;
        }

        //pegar loteria paramter
        $idlot = $this->request->get('sel_loterias');

        //pegar situação
        $idsit = $this->request->get('sel_situacao');

        //pegar vededor destino
        $idvendd = $this->request->get('sel_vendedord');

        //pegar palpites
        $palpite = $this->request->get('numpule');

        //pesquisar por horário loterias
        $idehor = $this->request->get('sel_lotodia');

        if ($idehor != Null){

            $idehor = $idehor;
        } else{

            $idehor  = '';

        }

        $ideven_default = $this->returnWebControlData($idusu);

        return view("{$this->nameView}",compact('idusu',
            'user_base', 'user_bases', 'usuario_lotec', 'vendedores', 'menus', 'categorias', 'data','title', 'baseAll', 'ideven','ideven2', 'idlot','idsit',
            'idehor','palpite','idvendd','descarga', 'semana', 'loterias', 'ideven_default', 'menuMaisUsu'));
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



    public function returnVendDesg($ideven){

        $p = $this->retornaBasepeloIdeven($ideven);


        if ($p != Null){
            $data = DB::select("SELECT VEN_DESC.IDBASEDESC, VEN_DESC.IDVENDESC, VENDEDOR.NOMVEN   
                            FROM VEN_DESC
                            INNER JOIN VENDEDOR ON VENDEDOR.IDBASE = VEN_DESC.IDBASEDESC AND
                            VENDEDOR.IDVEN = VEN_DESC.IDVENDESC
                            WHERE
                              VEN_DESC.SITLIG = 'ATIVO' AND
                              VEN_DESC.IDBASE = '$p->idbase' AND
                              VEN_DESC.IDVEN = '$p->idven' ");
        } else {
            $data = '';
        }



        return $data;
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
        $data = DB::select(" SELECT IDLOT, DESLOT FROM LOTERIAS ORDER BY IDLOT ASC");

        return $data;
    }

    public function returnDescargasEnviadas($ideven){

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



//        $var_cond = 3;
        //Pegar Data e Hora atual..
        $hora = date('H:i:s');
        $dat = date ("Y/m/d");

        $var_query_1 = '';
        $var_query_2 = '';
        $var_query_3 = '';
        $var_query_4 = '';
        $var_query_5 = '';
        $var_query_6 = '';


        $idusu = auth()->user()->idusu;
        $admin = Usuario::where('idusu', '=', $idusu)->first();


        $situacao = $this->request->get('sel_situacao');

        if (!empty($situacao)){
            $var_cond = $situacao;
        } else {
            $var_cond = 2;
        }
        if($var_cond != 3){


            if ($var_cond == 0 ){
                //Se situação = 0

                $var_query_1 = " AND APOSTA_DESCARGA.SITDES = 'EL' AND
                        ((((SELECT CONCAT(CONCAT(RPAD(EXTRACT(HOUR FROM HA.HORLIM),2,0),':'),
                            CONCAT(CONCAT(RPAD(EXTRACT(MINUTE FROM HA.HORLIM),2,0),':'),RPAD(EXTRACT(Second FROM HA.HORLIM),2,0)))
                        FROM HOR_APOSTA HA
                        WHERE
                        HA.IDLOT = APOSTA_PALPITES.IDLOT AND
                        HA.IDHOR = APOSTA_PALPITES.IDHOR) > '$hora')
                        AND (APOSTA_PALPITES.DATAPO = '$dat')) OR
                        (APOSTA_PALPITES.DATAPO > '$dat'))
                        AND APOSTA_PALPITES.DATENV between '$datIni' AND '$datFim'";

//                dd($var_query_1);

            } elseif ($var_cond == 1 ){
                //Se situação = 1
                $var_query_1 = "AND APOSTA_DESCARGA.SITDES = 'PRO' 
                        AND APOSTA_PALPITES.DATENV between '$datIni' AND '$datFim'";
//                dd($var_query_1);
            } elseif ($var_cond == 2 ){
                //Se situação = 2
                $var_query_1 = " AND APOSTA_DESCARGA.SITDES = 'EL' AND
                                  (
                                    (
                                        (
                                            (SELECT CONCAT
                                                (CONCAT
                                                    (RPAD
                                                        (EXTRACT
                                                            (HOUR FROM HA.HORLIM),
                                                        2,0),
                                                    ':'),
                                                  CONCAT
                                                    (CONCAT
                                                        (RPAD
                                                            (EXTRACT
                                                                (MINUTE FROM HA.HORLIM),
                                                            2,0),
                                                        ':'),
                                                        RPAD
                                                            (EXTRACT
                                                                (Second FROM HA.HORLIM),
                                                            2,0)
                                                    )
                                                  )
                                                  FROM HOR_APOSTA HA
                                                  WHERE
                                  HA.IDLOT = APOSTA_PALPITES.IDLOT AND
                                  HA.IDHOR = APOSTA_PALPITES.IDHOR) > '$hora')
                                  AND (APOSTA_PALPITES.DATAPO = '$dat')) OR
                                  (APOSTA_PALPITES.DATAPO > '$dat'))
                                  AND APOSTA_PALPITES.DATENV between '$datIni' AND '$datFim'";
//                dd($var_query_1);
            }
        } else {
            //Se não


            $var_query_1 = "AND APOSTA_PALPITES.DATENV between '$datIni' AND '$datFim'";
        }

        //Vendedor Origem selecionado por base
        //referente aos IDEVEN
        $valor = $this->request->get('sel_vendedor');
        if ($valor == NULL){
            $valor = $this->retornaBasepeloIdeven($ideven);
            $p = $valor;
            //Origem
            $idbaseo = $p->idbase;
            $idveno = $p->idven;

        } else {

            //Construi a string com base no array do select via form
            $var_string_ven = implode(",", $valor);

//            dd($var_string_ven);
        }

       if (empty($var_string_ven)) {
           if ($admin->inadim = 'NAO') {


               $var_query_2 = " AND APOSTA_DESCARGA.IDBASEO = '$idbaseo' 
                                AND APOSTA_DESCARGA.IDVENO  = '$idveno'";

           }
       } else {
           $var_query_2 = " AND VEN_O.IDEVEN IN ($var_string_ven) ";
       }

       //Vendedor Destino selecionado
        $vendedord = $this->request->get('sel_vendedord');
        if (!empty($vendedord)){
            $vendedord = str_split($vendedord, 4);

        }
        if ($vendedord == Null){
            $idbased = "";
            $idevend = "";
        } else {
            $idbased =  $vendedord[0];
            $idvend =  $vendedord[1];
        }

        if (!empty($idbased)){
            $var_query_3 = " AND APOSTA_DESCARGA.IDBASED = '$idbased'
                        AND APOSTA_DESCARGA.IDVEND  = '$idvend'";

//            dd($var_query_3);
        }

        //Pesquisar Palpite
        $palpite = $this->request->get('numpule');
//        dd($palpite);
        if (!empty($palpite)){
            $var_query_4 = " AND APOSTA_PALPITES.NUMPULE LIKE '%$palpite%'";
//            dd($var_query_4);
        }

        //pesquisar por horário loterias
        $var_hora_select = $this->request->get('sel_lotodia');

        if (!empty($var_hora_select)){
            $var_hora_select = implode(",", $var_hora_select);
            $var_query_5 = " AND HOR_APOSTA.IDEHOR IN ($var_hora_select) ";

//            dd($var_query_5);
        }
        //pesquisar por loteria selecionada
        $sel_loterias = $this->request->get('sel_loterias');
        $var_loteria_select = $sel_loterias;

        if (!empty($var_loteria_select)){
            $var_query_6 = " AND LOTERIAS.IDLOT = '$var_loteria_select'";
        }



        $data = DB::select(" /*+RULE*/
                    SELECT APOSTA_DESCARGA.IDBASE, APOSTA_DESCARGA.IDVEN, APOSTA_DESCARGA.IDREVEN,'$datIni' AS DATAINI, '$datFim' AS DATAFIM,
                    APOSTA_DESCARGA.IDTER, APOSTA_DESCARGA.IDAPO, APOSTA_DESCARGA.NUMPULE, 
                    APOSTA_DESCARGA.SEQPALP, APOSTA_DESCARGA.SEQDES, APOSTA_DESCARGA.IDBASED, 
                    APOSTA_DESCARGA.IDVEND, APOSTA_DESCARGA.IDBASEO, APOSTA_DESCARGA.IDVENO, 
                    APOSTA_DESCARGA.VLRPALPO, APOSTA_DESCARGA.VLRPALP, APOSTA_DESCARGA.VLRPALPF, 
                    APOSTA_DESCARGA.VLRPALPD, APOSTA_DESCARGA.VLRPRESEC, APOSTA_DESCARGA.VLRPREMOL, 
                    APOSTA_DESCARGA.VLRPRESMJ, APOSTA_DESCARGA.VLRPRE, APOSTA_DESCARGA.VLRPREPAG, 
                    APOSTA_DESCARGA.SITDES, APOSTA_DESCARGA.COLMOTDES, APOSTA_DESCARGA.COLPRE, 
                    APOSTA_DESCARGA.PERDESC, APOSTA_DESCARGA.DATAPO, APOSTA_DESCARGA.HORAPO, 
                    APOSTA_DESCARGA.IDTIPOAPO,APOSTA_DESCARGA.IDCOL, APOSTA_DESCARGA.DATENV, 
                    APOSTA_DESCARGA.HORENV, APOSTA_DESCARGA.GRUPDES, APOSTA_DESCARGA.INCOMB, 
                    APOSTA_DESCARGA.VLRCOTACAO, APOSTA_DESCARGA.IDMENU, APOSTA_DESCARGA.INFODESC, 
                    APOSTA_DESCARGA.TIPODESC, APOSTA_DESCARGA.VLRPALPSECO, APOSTA_DESCARGA.VLRPALPMOLHADO, 
                    APOSTA_DESCARGA.INVISU, APOSTA_DESCARGA.IDCOLDESC, VEN_O.IDEVEN,
                    VENDEDOR.NOMVEN, 
                    HOR_APOSTA.HORLIM, HOR_APOSTA.HORSOR, HOR_APOSTA.DESHOR, 
                    LOTERIAS.DESLOT, LOTERIAS.ABRLOT, 
                    TIPO_APOSTA.DESTIPOAPO, 
                    COLOCACOES.DESCOL, 
                    VEN_O.NOMVEN AS NOMVEM_O, 
                    APOSTA_PALPITES.VLRPALP, APOSTA_PALPITES.PALP1, APOSTA_PALPITES.PALP2, APOSTA_PALPITES.PALP3,  
                              APOSTA_PALPITES.PALP4, APOSTA_PALPITES.PALP5, APOSTA_PALPITES.PALP6, 
                              APOSTA_PALPITES.PALP7, APOSTA_PALPITES.PALP8, APOSTA_PALPITES.PALP9, 
                              APOSTA_PALPITES.PALP10, APOSTA_PALPITES.PALP11, APOSTA_PALPITES.PALP12, 
                              APOSTA_PALPITES.PALP13, APOSTA_PALPITES.PALP14, APOSTA_PALPITES.PALP15, 
                              APOSTA_PALPITES.PALP16, APOSTA_PALPITES.PALP17, APOSTA_PALPITES.PALP18, 
                              APOSTA_PALPITES.PALP19, APOSTA_PALPITES.PALP20, APOSTA_PALPITES.PALP21, 
                              APOSTA_PALPITES.PALP22, APOSTA_PALPITES.PALP23, APOSTA_PALPITES.PALP24, 
                              APOSTA_PALPITES.PALP25, 
                              APOSTA_PALPITES.VLRPALP AS AP_VLRPALP, APOSTA_PALPITES.VLRPRESEC AS AP_VLRPRESEC, 
                              APOSTA_PALPITES.VLRPREMOL AS AP_VLRPREMOL, APOSTA_PALPITES.VLRPRESMJ AS AP_VLRPRESMJ, 
                              APOSTA_PALPITES.VLRPALPF AS AP_VLRPALPF, APOSTA_PALPITES.VLRPALPD AS AP_VLRPALPD, APOSTA_PALPITES.IDLOT, APOSTA_PALPITES.IDHOR, 
                              APOSTA_PALPITES.VLRCOTACAO AS AP_VLRCOTACAO, 
                              VEN_TIPO_APO.VLRLIMDESC 
                              FROM APOSTA_DESCARGA 
                              INNER JOIN VENDEDOR ON VENDEDOR.IDBASE = APOSTA_DESCARGA.IDBASED AND 
                                                     VENDEDOR.IDVEN  = APOSTA_DESCARGA.IDVEND 
                              INNER JOIN VENDEDOR VEN_O ON VEN_O.IDBASE = APOSTA_DESCARGA.IDBASEO AND 
                                                           VEN_O.IDVEN  = APOSTA_DESCARGA.IDVENO   
                              INNER JOIN APOSTA_PALPITES ON APOSTA_PALPITES.IDBASE  = APOSTA_DESCARGA.IDBASE AND 
                                                            APOSTA_PALPITES.IDVEN   = APOSTA_DESCARGA.IDVEN AND 
                                                            APOSTA_PALPITES.IDREVEN = APOSTA_DESCARGA.IDREVEN AND 
                                                            APOSTA_PALPITES.IDTER   = APOSTA_DESCARGA.IDTER AND 
                                                            APOSTA_PALPITES.IDAPO   = APOSTA_DESCARGA.IDAPO AND 
                                                            APOSTA_PALPITES.NUMPULE = APOSTA_DESCARGA.NUMPULE AND 
                                                            APOSTA_PALPITES.SEQPALP = APOSTA_DESCARGA.SEQPALP 
                              INNER JOIN HOR_APOSTA ON HOR_APOSTA.IDLOT = APOSTA_PALPITES.IDLOT AND 
                                                         HOR_APOSTA.IDHOR = APOSTA_PALPITES.IDHOR 
                              INNER JOIN LOTERIAS ON LOTERIAS.IDLOT = APOSTA_PALPITES.IDLOT 
                              INNER JOIN TIPO_APOSTA ON TIPO_APOSTA.IDTIPOAPO = APOSTA_PALPITES.IDTIPOAPO 
                              INNER JOIN COLOCACOES ON COLOCACOES.IDCOL = APOSTA_PALPITES.IDCOL 
                              INNER JOIN VEN_TIPO_APO ON VEN_TIPO_APO.IDBASE = VEN_O.IDBASE AND 
                                                          VEN_TIPO_APO.IDVEN = VEN_O.IDVEN AND 
                                                          VEN_TIPO_APO.IDTIPOAPO = APOSTA_PALPITES.IDTIPOAPO 
                              WHERE 
                                  APOSTA_DESCARGA.IDBASE <> 999999 
                                  AND APOSTA_DESCARGA.SITDES <> 'CAN' 
                                  AND APOSTA_DESCARGA.VLRPALP > 0
                              $var_query_1                          
                              $var_query_2                          
                              $var_query_3                          
                              $var_query_4                          
                              $var_query_5                          
                              $var_query_6                          
        ");


        return $data;
    }


    public function returnIndex($ideven){

        $datIni = date ("Y/m/d");
        $datFim = date ("Y/m/d");

        $data = DB::select(" /*+RULE*/
                    SELECT APOSTA_DESCARGA.IDBASE, APOSTA_DESCARGA.IDVEN, APOSTA_DESCARGA.IDREVEN,'$datIni' AS DATAINI, '$datFim' AS DATAFIM,
                    APOSTA_DESCARGA.IDTER, APOSTA_DESCARGA.IDAPO, APOSTA_DESCARGA.NUMPULE, 
                    APOSTA_DESCARGA.SEQPALP, APOSTA_DESCARGA.SEQDES, APOSTA_DESCARGA.IDBASED, 
                    APOSTA_DESCARGA.IDVEND, APOSTA_DESCARGA.IDBASEO, APOSTA_DESCARGA.IDVENO, 
                    APOSTA_DESCARGA.VLRPALPO, APOSTA_DESCARGA.VLRPALP, APOSTA_DESCARGA.VLRPALPF, 
                    APOSTA_DESCARGA.VLRPALPD, APOSTA_DESCARGA.VLRPRESEC, APOSTA_DESCARGA.VLRPREMOL, 
                    APOSTA_DESCARGA.VLRPRESMJ, APOSTA_DESCARGA.VLRPRE, APOSTA_DESCARGA.VLRPREPAG, 
                    APOSTA_DESCARGA.SITDES, APOSTA_DESCARGA.COLMOTDES, APOSTA_DESCARGA.COLPRE, 
                    APOSTA_DESCARGA.PERDESC, APOSTA_DESCARGA.DATAPO, APOSTA_DESCARGA.HORAPO, 
                    APOSTA_DESCARGA.IDTIPOAPO,APOSTA_DESCARGA.IDCOL, APOSTA_DESCARGA.DATENV, 
                    APOSTA_DESCARGA.HORENV, APOSTA_DESCARGA.GRUPDES, APOSTA_DESCARGA.INCOMB, 
                    APOSTA_DESCARGA.VLRCOTACAO, APOSTA_DESCARGA.IDMENU, APOSTA_DESCARGA.INFODESC, 
                    APOSTA_DESCARGA.TIPODESC, APOSTA_DESCARGA.VLRPALPSECO, APOSTA_DESCARGA.VLRPALPMOLHADO, 
                    APOSTA_DESCARGA.INVISU, APOSTA_DESCARGA.IDCOLDESC, VEN_O.IDEVEN,
                    VENDEDOR.NOMVEN, 
                    HOR_APOSTA.HORLIM, HOR_APOSTA.HORSOR, HOR_APOSTA.DESHOR, 
                    LOTERIAS.DESLOT, LOTERIAS.ABRLOT, 
                    TIPO_APOSTA.DESTIPOAPO, 
                    COLOCACOES.DESCOL, 
                    VEN_O.NOMVEN AS NOMVEM_O, 
                    APOSTA_PALPITES.VLRPALP, APOSTA_PALPITES.PALP1, APOSTA_PALPITES.PALP2, APOSTA_PALPITES.PALP3,  
                              APOSTA_PALPITES.PALP4, APOSTA_PALPITES.PALP5, APOSTA_PALPITES.PALP6, 
                              APOSTA_PALPITES.PALP7, APOSTA_PALPITES.PALP8, APOSTA_PALPITES.PALP9, 
                              APOSTA_PALPITES.PALP10, APOSTA_PALPITES.PALP11, APOSTA_PALPITES.PALP12, 
                              APOSTA_PALPITES.PALP13, APOSTA_PALPITES.PALP14, APOSTA_PALPITES.PALP15, 
                              APOSTA_PALPITES.PALP16, APOSTA_PALPITES.PALP17, APOSTA_PALPITES.PALP18, 
                              APOSTA_PALPITES.PALP19, APOSTA_PALPITES.PALP20, APOSTA_PALPITES.PALP21, 
                              APOSTA_PALPITES.PALP22, APOSTA_PALPITES.PALP23, APOSTA_PALPITES.PALP24, 
                              APOSTA_PALPITES.PALP25, 
                              APOSTA_PALPITES.VLRPALP AS AP_VLRPALP, APOSTA_PALPITES.VLRPRESEC AS AP_VLRPRESEC, 
                              APOSTA_PALPITES.VLRPREMOL AS AP_VLRPREMOL, APOSTA_PALPITES.VLRPRESMJ AS AP_VLRPRESMJ, 
                              APOSTA_PALPITES.VLRPALPF AS AP_VLRPALPF, APOSTA_PALPITES.VLRPALPD AS AP_VLRPALPD, APOSTA_PALPITES.IDLOT, APOSTA_PALPITES.IDHOR, 
                              APOSTA_PALPITES.VLRCOTACAO AS AP_VLRCOTACAO, 
                              VEN_TIPO_APO.VLRLIMDESC 
                              FROM APOSTA_DESCARGA 
                              INNER JOIN VENDEDOR ON VENDEDOR.IDBASE = APOSTA_DESCARGA.IDBASED AND 
                                                     VENDEDOR.IDVEN  = APOSTA_DESCARGA.IDVEND 
                              INNER JOIN VENDEDOR VEN_O ON VEN_O.IDBASE = APOSTA_DESCARGA.IDBASEO AND 
                                                           VEN_O.IDVEN  = APOSTA_DESCARGA.IDVENO   
                              INNER JOIN APOSTA_PALPITES ON APOSTA_PALPITES.IDBASE  = APOSTA_DESCARGA.IDBASE AND 
                                                            APOSTA_PALPITES.IDVEN   = APOSTA_DESCARGA.IDVEN AND 
                                                            APOSTA_PALPITES.IDREVEN = APOSTA_DESCARGA.IDREVEN AND 
                                                            APOSTA_PALPITES.IDTER   = APOSTA_DESCARGA.IDTER AND 
                                                            APOSTA_PALPITES.IDAPO   = APOSTA_DESCARGA.IDAPO AND 
                                                            APOSTA_PALPITES.NUMPULE = APOSTA_DESCARGA.NUMPULE AND 
                                                            APOSTA_PALPITES.SEQPALP = APOSTA_DESCARGA.SEQPALP 
                              INNER JOIN HOR_APOSTA ON HOR_APOSTA.IDLOT = APOSTA_PALPITES.IDLOT AND 
                                                         HOR_APOSTA.IDHOR = APOSTA_PALPITES.IDHOR 
                              INNER JOIN LOTERIAS ON LOTERIAS.IDLOT = APOSTA_PALPITES.IDLOT 
                              INNER JOIN TIPO_APOSTA ON TIPO_APOSTA.IDTIPOAPO = APOSTA_PALPITES.IDTIPOAPO 
                              INNER JOIN COLOCACOES ON COLOCACOES.IDCOL = APOSTA_PALPITES.IDCOL 
                              INNER JOIN VEN_TIPO_APO ON VEN_TIPO_APO.IDBASE = VEN_O.IDBASE AND 
                                                          VEN_TIPO_APO.IDVEN = VEN_O.IDVEN AND 
                                                          VEN_TIPO_APO.IDTIPOAPO = APOSTA_PALPITES.IDTIPOAPO 
                              WHERE 
                                  APOSTA_DESCARGA.IDBASE <> 999999 
                                  AND APOSTA_DESCARGA.SITDES <> 'CAN' 
                                  AND APOSTA_DESCARGA.VLRPALP > 0
                                  AND APOSTA_PALPITES.DATENV between '$datIni' AND '$datFim'
                                  AND VEN_O.IDEVEN IN ($ideven) 
                                                        
        ");


        return $data;
    }


    public function returnInfoDescEnv($ideven, $idreven, $idter, $idapo, $numpule, $seqpalp){

        $datIni = date ("Y/m/d");
        $datFim = date ("Y/m/d");

        $data = DB::select(" /*+RULE*/
                    SELECT APOSTA_DESCARGA.IDBASE, APOSTA_DESCARGA.IDVEN, APOSTA_DESCARGA.IDREVEN,'$datIni' AS DATAINI, '$datFim' AS DATAFIM,
                    APOSTA_DESCARGA.IDTER, APOSTA_DESCARGA.IDAPO, APOSTA_DESCARGA.NUMPULE, 
                    APOSTA_DESCARGA.SEQPALP, APOSTA_DESCARGA.SEQDES, APOSTA_DESCARGA.IDBASED, 
                    APOSTA_DESCARGA.IDVEND, APOSTA_DESCARGA.IDBASEO, APOSTA_DESCARGA.IDVENO, 
                    APOSTA_DESCARGA.VLRPALPO, APOSTA_DESCARGA.VLRPALP, APOSTA_DESCARGA.VLRPALPF, 
                    APOSTA_DESCARGA.VLRPALPD, APOSTA_DESCARGA.VLRPRESEC, APOSTA_DESCARGA.VLRPREMOL, 
                    APOSTA_DESCARGA.VLRPRESMJ, APOSTA_DESCARGA.VLRPRE, APOSTA_DESCARGA.VLRPREPAG, 
                    APOSTA_DESCARGA.SITDES, APOSTA_DESCARGA.COLMOTDES, APOSTA_DESCARGA.COLPRE, 
                    APOSTA_DESCARGA.PERDESC, APOSTA_DESCARGA.DATAPO, APOSTA_DESCARGA.HORAPO, 
                    APOSTA_DESCARGA.IDTIPOAPO,APOSTA_DESCARGA.IDCOL, APOSTA_DESCARGA.DATENV, 
                    APOSTA_DESCARGA.HORENV, APOSTA_DESCARGA.GRUPDES, APOSTA_DESCARGA.INCOMB, 
                    APOSTA_DESCARGA.VLRCOTACAO, APOSTA_DESCARGA.IDMENU, APOSTA_DESCARGA.INFODESC, 
                    APOSTA_DESCARGA.TIPODESC, APOSTA_DESCARGA.VLRPALPSECO, APOSTA_DESCARGA.VLRPALPMOLHADO, 
                    APOSTA_DESCARGA.INVISU, APOSTA_DESCARGA.IDCOLDESC, 
                    VENDEDOR.NOMVEN, 
                    HOR_APOSTA.HORLIM, HOR_APOSTA.HORSOR, HOR_APOSTA.DESHOR, 
                    LOTERIAS.DESLOT, LOTERIAS.ABRLOT, 
                    TIPO_APOSTA.DESTIPOAPO, 
                    COLOCACOES.DESCOL, 
                    VEN_O.NOMVEN AS NOMVEM_O, 
                    APOSTA_PALPITES.VLRPALP, APOSTA_PALPITES.PALP1, APOSTA_PALPITES.PALP2, APOSTA_PALPITES.PALP3,  
                              APOSTA_PALPITES.PALP4, APOSTA_PALPITES.PALP5, APOSTA_PALPITES.PALP6, 
                              APOSTA_PALPITES.PALP7, APOSTA_PALPITES.PALP8, APOSTA_PALPITES.PALP9, 
                              APOSTA_PALPITES.PALP10, APOSTA_PALPITES.PALP11, APOSTA_PALPITES.PALP12, 
                              APOSTA_PALPITES.PALP13, APOSTA_PALPITES.PALP14, APOSTA_PALPITES.PALP15, 
                              APOSTA_PALPITES.PALP16, APOSTA_PALPITES.PALP17, APOSTA_PALPITES.PALP18, 
                              APOSTA_PALPITES.PALP19, APOSTA_PALPITES.PALP20, APOSTA_PALPITES.PALP21, 
                              APOSTA_PALPITES.PALP22, APOSTA_PALPITES.PALP23, APOSTA_PALPITES.PALP24, 
                              APOSTA_PALPITES.PALP25, 
                              APOSTA_PALPITES.VLRPALP AS AP_VLRPALP, APOSTA_PALPITES.VLRPRESEC AS AP_VLRPRESEC, 
                              APOSTA_PALPITES.VLRPREMOL AS AP_VLRPREMOL, APOSTA_PALPITES.VLRPRESMJ AS AP_VLRPRESMJ, 
                              APOSTA_PALPITES.VLRPALPF AS AP_VLRPALPF, APOSTA_PALPITES.VLRPALPD AS AP_VLRPALPD, APOSTA_PALPITES.IDLOT, APOSTA_PALPITES.IDHOR, 
                              APOSTA_PALPITES.VLRCOTACAO AS AP_VLRCOTACAO, 
                              VEN_TIPO_APO.VLRLIMDESC 
                              FROM APOSTA_DESCARGA 
                              INNER JOIN VENDEDOR ON VENDEDOR.IDBASE = APOSTA_DESCARGA.IDBASED AND 
                                                     VENDEDOR.IDVEN  = APOSTA_DESCARGA.IDVEND 
                              INNER JOIN VENDEDOR VEN_O ON VEN_O.IDBASE = APOSTA_DESCARGA.IDBASEO AND 
                                                           VEN_O.IDVEN  = APOSTA_DESCARGA.IDVENO   
                              INNER JOIN APOSTA_PALPITES ON APOSTA_PALPITES.IDBASE  = APOSTA_DESCARGA.IDBASE AND 
                                                            APOSTA_PALPITES.IDVEN   = APOSTA_DESCARGA.IDVEN AND 
                                                            APOSTA_PALPITES.IDREVEN = APOSTA_DESCARGA.IDREVEN AND 
                                                            APOSTA_PALPITES.IDTER   = APOSTA_DESCARGA.IDTER AND 
                                                            APOSTA_PALPITES.IDAPO   = APOSTA_DESCARGA.IDAPO AND 
                                                            APOSTA_PALPITES.NUMPULE = APOSTA_DESCARGA.NUMPULE AND 
                                                            APOSTA_PALPITES.SEQPALP = APOSTA_DESCARGA.SEQPALP 
                              INNER JOIN HOR_APOSTA ON HOR_APOSTA.IDLOT = APOSTA_PALPITES.IDLOT AND 
                                                         HOR_APOSTA.IDHOR = APOSTA_PALPITES.IDHOR 
                              INNER JOIN LOTERIAS ON LOTERIAS.IDLOT = APOSTA_PALPITES.IDLOT 
                              INNER JOIN TIPO_APOSTA ON TIPO_APOSTA.IDTIPOAPO = APOSTA_PALPITES.IDTIPOAPO 
                              INNER JOIN COLOCACOES ON COLOCACOES.IDCOL = APOSTA_PALPITES.IDCOL 
                              INNER JOIN VEN_TIPO_APO ON VEN_TIPO_APO.IDBASE = VEN_O.IDBASE AND 
                                                          VEN_TIPO_APO.IDVEN = VEN_O.IDVEN AND 
                                                          VEN_TIPO_APO.IDTIPOAPO = APOSTA_PALPITES.IDTIPOAPO 
                              WHERE 
                                  APOSTA_DESCARGA.IDBASE <> 999999 
                                  AND APOSTA_DESCARGA.SITDES <> 'CAN' 
                                  AND APOSTA_DESCARGA.VLRPALP > 0
                                  AND APOSTA_PALPITES.DATENV between '$datIni' AND '$datFim'
                                  AND VEN_O.IDEVEN IN ($ideven) 
                                  AND APOSTA_DESCARGA.IDREVEN = '$idreven'
                                  AND APOSTA_DESCARGA.IDTER = '$idter'
                                  AND APOSTA_DESCARGA.IDAPO = '$idapo'
                                  AND APOSTA_DESCARGA.NUMPULE = '$numpule'
                                  AND APOSTA_DESCARGA.SEQPALP = '$seqpalp'
                                                        
        ");


        return $data;
    }

    public function returnInfoAposta($numpule, $seqpalp){
        $data = DB::select(" 
                SELECT IDBASE,IDVEN,IDREVEN,IDTER,IDAPO,NUMPULE,SEQPALP,SEQCOL,IDCOL,INSECMOL,VLRPALP,
                VLRPALPF,VLRPALPD,DATAPO,IDLOT,IDHOR,IDTIPOAPO,PALP1,PALP2,PALP3,PALP4,PALP5,SITAPO
                FROM APOSTA_PALPITESCOL
                WHERE
                APOSTA_PALPITESCOL.NUMPULE = '$numpule' 
                AND APOSTA_PALPITESCOL.SEQPALP = '$seqpalp'
                ORDER BY APOSTA_PALPITESCOL.SEQCOL

        ");

//        dd($data);
        return $data;
    }

    public function returnApostaDescarregadas($numpule, $seqpalp, $seqdes){
        $data = DB::select(" /*+RULE*/
                SELECT APOSTA_DESCARGA.IDBASE,APOSTA_DESCARGA.IDVEN,APOSTA_DESCARGA.IDREVEN,
                APOSTA_DESCARGA.IDTER,APOSTA_DESCARGA.IDAPO,APOSTA_DESCARGA.NUMPULE,
                APOSTA_DESCARGA.SEQPALP, APOSTA_DESCARGA.SEQDES,APOSTA_DESCARGA.IDBASED,
                APOSTA_DESCARGA.IDVEND,APOSTA_DESCARGA.IDBASEO,APOSTA_DESCARGA.IDVENO,
                APOSTA_DESCARGA.VLRPALPO,APOSTA_DESCARGA.VLRPALP,APOSTA_DESCARGA.VLRPALPF,
                APOSTA_DESCARGA.VLRPALPD,APOSTA_DESCARGA.VLRPRESEC,APOSTA_DESCARGA.VLRPREMOL,
                APOSTA_DESCARGA.VLRPRESMJ,APOSTA_DESCARGA.VLRPRE,APOSTA_DESCARGA.VLRPREPAG,
                APOSTA_DESCARGA.SITDES,APOSTA_DESCARGA.COLMOTDES,APOSTA_DESCARGA.COLPRE,
                APOSTA_DESCARGA.PERDESC,APOSTA_DESCARGA.DATAPO,APOSTA_DESCARGA.HORAPO,
                APOSTA_DESCARGA.IDTIPOAPO,APOSTA_DESCARGA.IDCOL,APOSTA_DESCARGA.DATENV,
                APOSTA_DESCARGA.HORENV,APOSTA_DESCARGA.GRUPDES,APOSTA_DESCARGA.INCOMB,
                APOSTA_DESCARGA.VLRCOTACAO,APOSTA_DESCARGA.IDMENU,APOSTA_DESCARGA.INFODESC,
                APOSTA_DESCARGA.TIPODESC,APOSTA_DESCARGA.VLRPALPSECO,APOSTA_DESCARGA.VLRPALPMOLHADO,
                APOSTA_DESCARGA.INVISU,APOSTA_DESCARGA.IDCOLDESC,
                       VENDEDOR.NOMVEN
                FROM APOSTA_DESCARGA
                INNER JOIN VENDEDOR ON VENDEDOR.IDBASE = APOSTA_DESCARGA.IDBASEO AND
                                          VENDEDOR.IDVEN = APOSTA_DESCARGA.IDVENO
                WHERE
                       APOSTA_DESCARGA.NUMPULE = '$numpule'
                       AND APOSTA_DESCARGA.SEQPALP = '$seqpalp'
                       AND APOSTA_DESCARGA.SEQDES = '$seqdes'

        ");

//        dd($data);
        return $data;
    }
}
