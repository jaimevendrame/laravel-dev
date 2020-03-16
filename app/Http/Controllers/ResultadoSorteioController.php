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

class ResultadoSorteioController extends StandardController
{
    protected $model;
    protected $nameView = 'dashboard.resultadosorteio';
    protected $data;
    protected $title = 'Resultado de Sorteio';
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

        if (Auth::user()->idusu == 1000){


            $linhas = 9;
            $col = $linhas;

            $ideven = 1000;



        } else{
            $linhas = $this->returnColMax($ideven);

            $linhas = $linhas->colmax;
            $col = $linhas;
        }

        $ideven_default = $this->returnWebControlData($idusu);

        return view("{$this->nameView}",compact('idusu',
            'user_base', 'user_bases', 'usuario_lotec', 'vendedores', 'menus', 'categorias', 'title',
            'baseAll', 'ideven',   'linhas',  'col', 'ideven_default', 'menuMaisUsu'));
    }

    public function indexGo($ideven)
    {
        if (Auth::user()->idusu == 1000){


            $linhas = 9;
            $col = $linhas;

            $ideven = 1000;



        } else{
            $linhas = $this->returnColMax($ideven);

            $linhas = $linhas->colmax;
            $col = $linhas;
        }

     //   dd($col);

        $idusu = Auth::user()->idusu;

        $user_base = $this->retornaBase($idusu);

        $user_bases = $this->retornaBases($idusu);

        $usuario_lotec = $this->retornaUserLotec($idusu);

        $vendedores = $this->retornaBasesUser($idusu);

        $menus = $this->retornaMenu($idusu);
        $menuMaisUsu = $this->retornaMenuMaisUsu($idusu);

        $categorias = $this->retornaCategorias($menus);


        $ideven = $ideven;






        $valor = $this->tudoAqui($ideven);


        $title = $this->title;

        $baseAll = $this->retornaBasesAll($idusu);


        $col6 = $this->request->get('col6');
        $col7 = $this->request->get('col7');
        $col8 = $this->request->get('col8');

        $linhas = 5;
        if ($col6 != null ){
            $linhas = $col6;
        }
        if ($col7 != null ){
            $linhas = $col7;
        }
        if ($col8 != null ){
            $linhas = $col8;
        }

        $in_ativos = '';

        $datainicial = $this->request->get('datIni');


        $ideven_default = $this->returnWebControlData($idusu);



                return view("{$this->nameView}",compact('idusu',
            'user_base', 'user_bases', 'usuario_lotec', 'vendedores', 'menus', 'categorias', 'valor','title',
                    'baseAll','ideven', 'in_ativos', 'linhas','col', 'datainicial', 'ideven_default', 'menuMaisUsu'));
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





        //Resultado de Sorteio

        public function returnLoter($ideven){
            $p = $this->retornaBasepeloIdeven($ideven);

            if (Auth::user()->idusu == 1000){

                $p->idbase = 1000;
                $p->idven = 1000;

            }

//            dd($p->idbase);

            $data = DB::select (" 
            SELECT VEN_LOTERIA.IDBASE, VEN_LOTERIA.IDVEN, VEN_LOTERIA.IDLOT,
            VEN_LOTERIA.SITLIG, VEN_LOTERIA.INAUTO,
            LOTERIAS.DESLOT
            FROM VEN_LOTERIA
            INNER JOIN LOTERIAS ON LOTERIAS.IDLOT = VEN_LOTERIA.IDLOT
            WHERE
            VEN_LOTERIA.sitlig = 'ATIVO' AND
            VEN_LOTERIA.IDBASE = '$p->idbase' AND
            VEN_LOTERIA.IDVEN = '$p->idven'
            ORDER BY LOTERIAS.IDLOT
            
            ");

          //  dd($data);
            return $data;
        }




    public function returnColMax($ideven){

        $p = $this->retornaBasepeloIdeven($ideven);

        $data = DB::table('VENDEDOR')
            ->select('COLMAX')
            ->where([
                ['IDBASE', '=', $p->idbase],
                ['IDVEN', '=', $p->idven]
            ])
            ->first();

        //    dd($data);

        return $data;
    }

    public function sorteioA($idlot, $inauto, $idbase, $idven){
        $datIni = $this->request->get('datIni');
        if ($datIni == ''){
            $datIni = date ("Y/m/d");
        } else {

            //Converte data inicial de string para Date(y/m/d)
            $datetimeinicial = new DateTime();
            $newDateInicial = $datetimeinicial->createFromFormat('d/m/Y', $datIni);

            $datIni = $newDateInicial->format('Y/m/d');
        }

        if ($inauto == 'SIM'){
            $data = DB::select ("
            SELECT SORTEIOS.IDSOR, SORTEIOS.IDLOT, SORTEIOS.IDHOR, SORTEIOS.DESSOR,'$datIni' AS DATAINI,
            SORTEIOS.DEZ1, SORTEIOS.DEZ2, SORTEIOS.DEZ3,SORTEIOS.DEZ4,
            SORTEIOS.DEZ5, SORTEIOS.DEZ6, SORTEIOS.DEZ7,SORTEIOS.DEZ8,SORTEIOS.DATSOR
            FROM SORTEIOS
            WHERE
            SORTEIOS.DATSOR = '$datIni' 
            AND SORTEIOS.IDBASE = 0
            AND SORTEIOS.IDLOT = '$idlot'
            ORDER BY SORTEIOS.IDSOR
            ");
        } else {
            $data = DB::select ("
            SELECT SORTEIOS.IDSOR, SORTEIOS.IDLOT, SORTEIOS.IDHOR, SORTEIOS.DESSOR,'$datIni' AS DATAINI,
            SORTEIOS.DEZ1, SORTEIOS.DEZ2, SORTEIOS.DEZ3,SORTEIOS.DEZ4,
            SORTEIOS.DEZ5, SORTEIOS.DEZ6, SORTEIOS.DEZ7,SORTEIOS.DEZ8,SORTEIOS.DATSOR
            FROM SORTEIOS
            WHERE
            SORTEIOS.DATSOR = '$datIni' 
            AND SORTEIOS.IDBASE = '$idbase'
            AND SORTEIOS.IDVEN = '$idven'
            AND SORTEIOS.IDLOT = '$idlot'
            ORDER BY SORTEIOS.IDSOR
            ");
        }

      
        return $data;
    }
    public function returnSorteioIteA($idsor){
        $data = DB::select ("
            SELECT SORTEIOS_ITE.*
            FROM SORTEIOS_ITE
            WHERE
            SORTEIOS_ITE.IDSOR = '$idsor'
            ORDER BY SORTEIOS_ITE.SEQSOR ASC     
            ");

        return $data;
    }
    public function tudoAqui($ideven){

        $loteria = $this->returnLoter($ideven);

        $data = array();

//        dd($loteria);

        foreach ($loteria as $key){
//            echo $key->idlot. '-'. $key->deslot. ': ';
            $sorteios = $this->sorteioA($key->idlot, $key->inauto, $key->idbase, $key->idven);
            foreach ($sorteios as $sort){
//                echo $sort->idsor.' ';
                $sorteioite = $this->returnSorteioIteA($sort->idsor);
                foreach ($sorteioite as $ite){
//                    echo $ite->desseq.' ';
                    $linha = [
                        "idlot" => $key->idlot,
                        "deslot" => $key->deslot,
                        "idsor" => $sort->idsor,
                        "dessor" => $sort->dessor,
                        "datsor" => $sort->datsor,
                        "dez1" => $sort->dez1,
                        "dez2" => $sort->dez2,
                        "dez3" => $sort->dez3,
                        "dez4" => $sort->dez4,
                        "dez5" => $sort->dez5,
                        "dez6" => $sort->dez6,
                        "dez7" => $sort->dez7,
                        "dez8" => $sort->dez8,
                        "seqsor" => $ite->seqsor,
                        "desseq" => $ite->desseq,
                        "milsor" => $ite->milsor,
                        "gru" => $ite->gru,
                        "desgru" => $ite->desgru,
                        "super5" => $ite->super5,
                    ];
                    array_push($data, $linha);
                }

            }

        }

      //    dd($data);
       return $data;
    }

    public function edit($id){

        return view ();
    }

}


