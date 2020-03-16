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

class MovimentosCobradorController extends StandardController
{
    protected $model;
    protected $nameView = 'dashboard.movimentoscobrador';
    protected $data;
    protected $title = 'Movimentos de Caixa Pendentes';
    protected $redirectCad = '/admin/contatos/cadastrar';
    protected $redirectEdit = '/admin/contatos/editar';
    protected $route = '/admin/movimentoscobrador';
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

    public function index2($ideven){

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

        $title = $this->title;

        $baseAll = $this->retornaBasesAll($idusu);

        $p_ideven = $ideven;

        $teste = $this->returnIdevenQuery($ideven);

        $p = $this->retornaBasepeloIdeven($ideven);

        $ideven_default = $this->returnWebControlData($idusu);

        $data = $this->retornaMovimentosCobrador($ideven);
        $sel_cobrador = $this->request->get('sel_cobrador');
        $dataLiberados = $this->retornaMovimentosLiberadosGeral($ideven);

        return view("{$this->nameView}",compact('idusu',
            'user_base', 'user_bases', 'usuario_lotec', 'vendedores', 'menus', 'categorias', 'data','title',
            'baseAll', 'reven', 'cobrador', 'p_ideven','p', 'ideven_default', 'menuMaisUsu','dataLiberados'));
    }

    public function indexGo($ideven) {

        $reven = $this->retornaRevendedor($ideven);

        $cobrador = $this->retornaCobrador($ideven);

        $sel_revendedor = $this->request->get('sel_revendedor');

        $sel_cobrador = $this->request->get('sel_cobrador');

        $idusu = Auth::user()->idusu;

        $user_base = $this->retornaBase($idusu);

        $user_bases = $this->retornaBases($idusu);

        $usuario_lotec = $this->retornaUserLotec($idusu);

        $vendedores = $this->retornaBasesUser($idusu);

        $menus = $this->retornaMenu($idusu);
        $menuMaisUsu = $this->retornaMenuMaisUsu($idusu);

        $categorias = $this->retornaCategorias($menus);

        $data = $this->retornaMovimentosCobradorParameter($ideven, $sel_cobrador);

        $title = $this->title;

        $baseAll = $this->retornaBasesAll($idusu);

        $p_ideven = $ideven;

        $p = $this->retornaBasepeloIdeven($ideven);

        $ideven_default = $this->returnWebControlData($idusu);
        $dataLiberados = $this->retornaMovimentosLiberados($ideven, $sel_cobrador);


        return view("{$this->nameView}",compact('idusu',
            'user_base', 'user_bases', 'usuario_lotec', 'vendedores', 'menus', 'categorias', 'data','dataLiberados','title',
            'baseAll', 'reven', 'cobrador', 'p_ideven','sel_revendedor', 'sel_cobrador', 'p', 'ideven_default', 'menuMaisUsu'));
    }

    public function retornaMovimentosCobrador($ideven){

        $str_idbase = '';
        $str_idven = '';
        $idusu = Auth::user()->idusu;
        $admin = Usuario::where('idusu', '=', $idusu)->first();

        if ($admin->inadim != 'SIM'){
           $p = $this->retornaBasepeloIdeven($ideven);

            $str_idbase = "AND RECEBIMENTO_PEN.IDBASE = ".$p->idbase;
            $str_idven = "AND RECEBIMENTO_PEN.IDVEN = ".$p->idven ;

        }

        $statusMov ='NAO';

        $data = DB::select ("SELECT  RECEBIMENTO_PEN.*, REVENDEDOR.NOMREVEN,COBRADOR.NOMCOBRA
                             FROM RECEBIMENTO_PEN
                                INNER JOIN REVENDEDOR ON
                                    REVENDEDOR.IDBASE  = RECEBIMENTO_PEN.IDBASE
                                    AND REVENDEDOR.IDVEN   = RECEBIMENTO_PEN.IDVEN
                                    AND REVENDEDOR.IDREVEN = RECEBIMENTO_PEN.IDREVEN
                                INNER JOIN COBRADOR ON
                                    COBRADOR.IDBASE  = RECEBIMENTO_PEN.IDBASE
                                    AND COBRADOR.IDVEN   =  RECEBIMENTO_PEN.IDVEN
                                    AND COBRADOR.IDCOBRA = RECEBIMENTO_PEN.IDCOBRA
                                WHERE
                                    RECEBIMENTO_PEN.IDBASE <> 999999
                                    AND RECEBIMENTO_PEN.INLIB = '$statusMov'                             
                                    $str_idbase
                                    $str_idven   "

        );

   // dd($data);
        return $data;
    }

    /**
     * @return mixed
     */
    public function retornaMovimentosCobradorParameter($ideven, $sel_cobrador){

        $str_idbase = '';
        $str_idven = '';

        if (Auth::user()->idusu <> 1000){
            $p = $this->retornaBasepeloIdeven($ideven);

            $str_idbase = "AND RECEBIMENTO_PEN.IDBASE = ".$p->idbase;
            $str_idven = "AND RECEBIMENTO_PEN.IDVEN = ".$p->idven ;

        }

        $str_idcobra = "";

        if ($sel_cobrador != NULL){

            $str_idcobra = " AND RECEBIMENTO_PEN.IDCOBRA = ".$sel_cobrador;
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

            $p = $valor;
        } else {
            $p = implode(",", $valor);
        }

        $sel_movi = $this->request->get('sel_movimento');
   //     dd($sel_movi);
        $statusMovi = implode(",", $sel_movi);


     if ($statusMovi == "RECEBIMENTO,PAGAMENTO") {
        $movimento = "'RECEBIMENTO','PAGAMENTO'";
       
    } elseif($statusMovi == "RECEBIMENTO") {
        $movimento = "'RECEBIMENTO'";
       
    }else{
        $movimento = "'PAGAMENTO'";
    }

        $data = DB::select ("SELECT  RECEBIMENTO_PEN.*, REVENDEDOR.NOMREVEN,COBRADOR.NOMCOBRA
                                    FROM RECEBIMENTO_PEN
                                    INNER JOIN REVENDEDOR ON
                                        REVENDEDOR.IDBASE  = RECEBIMENTO_PEN.IDBASE
                                        AND REVENDEDOR.IDVEN   = RECEBIMENTO_PEN.IDVEN
                                        AND REVENDEDOR.IDREVEN = RECEBIMENTO_PEN.IDREVEN
                                    INNER JOIN COBRADOR ON
                                        COBRADOR.IDBASE  = RECEBIMENTO_PEN.IDBASE
                                        AND COBRADOR.IDVEN   =  RECEBIMENTO_PEN.IDVEN
                                        AND COBRADOR.IDCOBRA = RECEBIMENTO_PEN.IDCOBRA
                                    WHERE
                                    RECEBIMENTO_PEN.IDBASE <> 999999
                                    AND RECEBIMENTO_PEN.INLIB = 'NAO'
                                    AND RECEBIMENTO_PEN.TIPOMOV  IN ($movimento)                           
                                    $str_idbase
                                    $str_idven 
                                    $str_idcobra "
        );
        return $data;
    }

    public function retornaMovimentosLiberados($ideven, $sel_cobrador){

        $str_idbase = '';
        $str_idven = '';
        $idusu = Auth::user()->idusu;
        $admin = Usuario::where('idusu', '=', $idusu)->first();

        if ($admin->inadim != 'SIM'){
           $p = $this->retornaBasepeloIdeven($ideven);

            $str_idbase = "AND RECEBIMENTO_PEN.IDBASE = ".$p->idbase;
            $str_idven = "AND RECEBIMENTO_PEN.IDVEN = ".$p->idven ;

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

        $sel_movi = $this->request->get('sel_movimento');
      
        $statusMovi = implode(",", $sel_movi);
     
     
          if ($statusMovi == "RECEBIMENTO,PAGAMENTO") {
             $movimento = "'RECEBIMENTO','PAGAMENTO'";
            
         } elseif($statusMovi == "RECEBIMENTO") {
             $movimento = "'RECEBIMENTO'";
            
         }else{
             $movimento = "'PAGAMENTO'";
         }

        $statusLib ='SIM';

        $str_idcobra = "";

        if ($sel_cobrador != NULL){

            $str_idcobra = " AND RECEBIMENTO_PEN.IDCOBRA = ".$sel_cobrador;
        }

        $dataLiberados = DB::select ("SELECT  RECEBIMENTO_PEN.*, REVENDEDOR.NOMREVEN,COBRADOR.NOMCOBRA
                             FROM RECEBIMENTO_PEN
                                INNER JOIN REVENDEDOR ON
                                    REVENDEDOR.IDBASE  = RECEBIMENTO_PEN.IDBASE
                                    AND REVENDEDOR.IDVEN   = RECEBIMENTO_PEN.IDVEN
                                    AND REVENDEDOR.IDREVEN = RECEBIMENTO_PEN.IDREVEN
                                INNER JOIN COBRADOR ON
                                    COBRADOR.IDBASE  = RECEBIMENTO_PEN.IDBASE
                                    AND COBRADOR.IDVEN   =  RECEBIMENTO_PEN.IDVEN
                                    AND COBRADOR.IDCOBRA = RECEBIMENTO_PEN.IDCOBRA
                                WHERE
                                    RECEBIMENTO_PEN.IDBASE <> 999999
                                    AND RECEBIMENTO_PEN.DATLIB  BETWEEN '$datIni' AND '$datFim'
                                    AND RECEBIMENTO_PEN.INLIB = '$statusLib'   
                                    AND RECEBIMENTO_PEN.TIPOMOV  IN ($movimento)                          
                                    $str_idbase
                                    $str_idven
                                    $str_idcobra    "
        );
//dd( $dataLiberados);
        return $dataLiberados;
    }

    public function retornaMovimentosLiberadosGeral($ideven){

        $str_idbase = '';
        $str_idven = '';
        $idusu = Auth::user()->idusu;
        $admin = Usuario::where('idusu', '=', $idusu)->first();

        if ($admin->inadim != 'SIM'){
           $p = $this->retornaBasepeloIdeven($ideven);

            $str_idbase = "AND RECEBIMENTO_PEN.IDBASE = ".$p->idbase;
            $str_idven = "AND RECEBIMENTO_PEN.IDVEN = ".$p->idven ;

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

        $statusMov ='SIM';

        $dataLiberados = DB::select ("SELECT  RECEBIMENTO_PEN.*, REVENDEDOR.NOMREVEN,COBRADOR.NOMCOBRA
                             FROM RECEBIMENTO_PEN
                                INNER JOIN REVENDEDOR ON
                                    REVENDEDOR.IDBASE  = RECEBIMENTO_PEN.IDBASE
                                    AND REVENDEDOR.IDVEN   = RECEBIMENTO_PEN.IDVEN
                                    AND REVENDEDOR.IDREVEN = RECEBIMENTO_PEN.IDREVEN
                                INNER JOIN COBRADOR ON
                                    COBRADOR.IDBASE  = RECEBIMENTO_PEN.IDBASE
                                    AND COBRADOR.IDVEN   =  RECEBIMENTO_PEN.IDVEN
                                    AND COBRADOR.IDCOBRA = RECEBIMENTO_PEN.IDCOBRA
                                WHERE
                                    RECEBIMENTO_PEN.IDBASE <> 999999
                                    AND RECEBIMENTO_PEN.INLIB = '$statusMov' 
                                    AND RECEBIMENTO_PEN.DATLIB  BETWEEN '$datIni' AND '$datFim'                            
                                    $str_idbase
                                    $str_idven   "
        );
//dd( $dataLiberados);
        return $dataLiberados;
    }


    public function alterarMovimentos($seqmov){

        $dataAlterar = DB::select ("SELECT  RECEBIMENTO_PEN.VLRMOV, RECEBIMENTO_PEN.SEQMOV
                             FROM RECEBIMENTO_PEN
                                INNER JOIN REVENDEDOR ON
                                    REVENDEDOR.IDBASE  = RECEBIMENTO_PEN.IDBASE
                                    AND REVENDEDOR.IDVEN   = RECEBIMENTO_PEN.IDVEN
                                    AND REVENDEDOR.IDREVEN = RECEBIMENTO_PEN.IDREVEN
                                INNER JOIN COBRADOR ON
                                    COBRADOR.IDBASE  = RECEBIMENTO_PEN.IDBASE
                                    AND COBRADOR.IDVEN   =  RECEBIMENTO_PEN.IDVEN
                                    AND COBRADOR.IDCOBRA = RECEBIMENTO_PEN.IDCOBRA
                                WHERE
                                    RECEBIMENTO_PEN.SEQMOV = '$seqmov' "
        );

      return json_encode($dataAlterar);
    }


    public function confirmaMovimentos($seqmov){

        $dataConfirmar = DB::select ("SELECT  RECEBIMENTO_PEN.SEQMOV
                             FROM RECEBIMENTO_PEN
                                INNER JOIN REVENDEDOR ON
                                    REVENDEDOR.IDBASE  = RECEBIMENTO_PEN.IDBASE
                                    AND REVENDEDOR.IDVEN   = RECEBIMENTO_PEN.IDVEN
                                    AND REVENDEDOR.IDREVEN = RECEBIMENTO_PEN.IDREVEN
                                INNER JOIN COBRADOR ON
                                    COBRADOR.IDBASE  = RECEBIMENTO_PEN.IDBASE
                                    AND COBRADOR.IDVEN   =  RECEBIMENTO_PEN.IDVEN
                                    AND COBRADOR.IDCOBRA = RECEBIMENTO_PEN.IDCOBRA
                            WHERE
                                RECEBIMENTO_PEN.SEQMOV = '$seqmov'    "
        );
     
      return json_encode($dataConfirmar);
    }


    public function excluiMovimentos($seqmov){

        $dataExcluir = DB::select ("SELECT  RECEBIMENTO_PEN.SEQMOV
                             FROM RECEBIMENTO_PEN
                                INNER JOIN REVENDEDOR ON
                                    REVENDEDOR.IDBASE  = RECEBIMENTO_PEN.IDBASE
                                    AND REVENDEDOR.IDVEN   = RECEBIMENTO_PEN.IDVEN
                                    AND REVENDEDOR.IDREVEN = RECEBIMENTO_PEN.IDREVEN
                                INNER JOIN COBRADOR ON
                                    COBRADOR.IDBASE  = RECEBIMENTO_PEN.IDBASE
                                    AND COBRADOR.IDVEN   =  RECEBIMENTO_PEN.IDVEN
                                    AND COBRADOR.IDCOBRA = RECEBIMENTO_PEN.IDCOBRA
                                WHERE
                                    RECEBIMENTO_PEN.SEQMOV = '$seqmov'   "
        );
      return json_encode($dataExcluir);
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

   
    public function confirmaCaixa()
    {
        $dadosForm = 'OK';

        return $dadosForm;

    }
    public function confirmaCaixaGo(){
 
        //Dados retorno request view
        $seqmov = $this->request->input('seq_movimento');
        $acaoMov = $this->request->input('confirma');
        $id_ven = $this->request->input('id_ven');

            $dados_array = [    
                "acao" =>  $acaoMov,
            ];
          //  dd($dados_array);
            $update = DB::table('RECEBIMENTO_PEN')->where([
                ['seqmov', '=', $seqmov],
               
            ])->update($dados_array);

                

            if($update)
            return redirect("/admin/movimentoscobrador/$id_ven")
                 ->with(['success'=>' Confirmado com sucesso!']);
        else
            return redirect("/admin/movimentoscobrador/$id_ven")
               ->withErrors(['errors' => 'Falha ao atualizar'])
               ->withInput();


    }


    public function confirmaCaixaVarios() {
   

        $data = $this->request->get('seq_movimento_varios');
        $id_ven = $this->request->input('id_ven');
        $x = 0;

        $dados = array();



        for ($i=0; $i<count($data); $i++){

            $explode = explode(",", $data[$i]);    

        }

        $dados_array = [    
            "acao" => "CONFIRMAR",          
          ];

        for ($i=0; $i<count($explode); $i++){
            $data = DB::table('RECEBIMENTO_PEN')->where([
                ['seqmov', '=', $explode[$i]]
            ])->update($dados_array);
            }
        
   //   dd("Ok deu certo");  //return $x;

        if($data)
            return redirect("/admin/movimentoscobrador/$id_ven")
                ->with(['success'=>' Confirmado com sucesso!']);
                    else
            return redirect("/admin/movimentoscobrador/$id_ven")
                ->withErrors(['errors' => 'Falha ao atualizar'])
                ->withInput();

            }  
            
            
    
            public function alteraCaixaGo(){
 
                //Dados retorno request view
                $seqmov = $this->request->input('seq_movimento_alterar');
                $acaoMov = $this->request->input('alterar');
                $id_ven = $this->request->input('id_ven');
                $vlr_ori = $this->request->input('vlr_movimento');
                $novo_vlr = $this->request->input('vlr_novo');
        
             //  dd( $seqmov, $acaoMov, $id_ven, $novo_vlr, $vlr_ori);
                    $dados_array = [    
                        "acao" =>  $acaoMov,
                        "vlrori" =>  $vlr_ori,
                        "vlrmov" =>  $novo_vlr,
                    ];
                  //  dd($dados_array);
                    $update = DB::table('RECEBIMENTO_PEN')->where([
                        ['seqmov', '=', $seqmov],
                       
                    ])->update($dados_array);
        
                        
        
                    if($update)
                    return redirect("/admin/movimentoscobrador/$id_ven")
                         ->with(['success'=>' Confirmado com sucesso!']);
                else
                    return redirect("/admin/movimentoscobrador/$id_ven")
                       ->withErrors(['errors' => 'Falha ao atualizar'])
                       ->withInput();
        
        
            }
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

    


