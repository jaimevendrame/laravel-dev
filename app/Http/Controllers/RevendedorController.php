<?php

namespace lotecweb\Http\Controllers;

use DateInterval;
use DateTime;
use Illuminate\Http\Request;
use App\Employer;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use lotecweb\Http\Requests;
use lotecweb\Models\Usuario;
use lotecweb\Models\Usuario_ven;
use lotecweb\Models\Vendedor;
use lotecweb\Models\Terminal;


class RevendedorController extends StandardController
{
    protected $model;
    protected $nameView = 'dashboard.revendedor';
    protected $data;
    protected $title = 'Revendedores';
    protected $redirectCad = '/admin/contatos/cadastrar';
    protected $redirectEdit = '/admin/contatos/editar';
    protected $route = '/admin/contatos';
    public $data_inicial;
    public $data_fim;

    public $estadosBrasileiros = array(
        'AC'=>'Acre',
        'AL'=>'Alagoas',
        'AP'=>'Amapá',
        'AM'=>'Amazonas',
        'BA'=>'Bahia',
        'CE'=>'Ceará',
        'DF'=>'Distrito Federal',
        'ES'=>'Espírito Santo',
        'GO'=>'Goiás',
        'MA'=>'Maranhão',
        'MT'=>'Mato Grosso',
        'MS'=>'Mato Grosso do Sul',
        'MG'=>'Minas Gerais',
        'PA'=>'Pará',
        'PB'=>'Paraíba',
        'PR'=>'Paraná',
        'PE'=>'Pernambuco',
        'PI'=>'Piauí',
        'RJ'=>'Rio de Janeiro',
        'RN'=>'Rio Grande do Norte',
        'RS'=>'Rio Grande do Sul',
        'RO'=>'Rondônia',
        'RR'=>'Roraima',
        'SC'=>'Santa Catarina',
        'SP'=>'São Paulo',
        'SE'=>'Sergipe',
        'TO'=>'Tocantins'
    );



    public $localtrabalho = array(
        'AMBULANTE'=>'AMBULANTE',
        'BAR'=>'BAR',
        'CHALE'=>'CHALE',
        'PONTO FIXO'=>'PONTO FIXO',
    );

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
 

    public function dadosModalRev($ideven, $idereven){
        $dadosReven = $idereven;
      return $dadosReven;
    }
   


    public function index2($ideven)
    {
        $sel_revendedor = $this->request->get('idInput');
       // dd($sel_revendedor);
        
        

     //   dd($sel_revendedor);
        $idusu = Auth::user()->idusu;


        $vendedores = $this->retornaBasesUser($idusu);

        $menus = $this->retornaMenu($idusu);
        $menuMaisUsu = $this->retornaMenuMaisUsu($idusu);

        $categorias = $this->retornaCategorias($menus);

        $title = $this->title;

        $usuario_lotec = $this->retornaUserLotec($idusu);
       // dd($usuario_lotec);

        //RETORNA SQL REVENDEDOR -> INDEX
        $data = $this->returnIndex($ideven);

        $ideven_default = $this->returnWebControlData($idusu);

//        $loterias = $this->returnLotVen($ideven);

        //dd($data);

        // Deletar a sessao
       /* if (session()->has('sitReven'))
            session()->forget('sitReven');*/

     $baseAll = $this->retornaBasesAll($idusu);
     $valores = $baseAll;
     $cobrador = $this->retornaCobrador($ideven);

     foreach ($valores as $val){

        if ($val->ideven == $ideven_default) {
            $baseNome  = $val->nombas;
            $idbase = $val->idbase;
            $vendedorNome = $val->nomven;
            $idvendedor = $val->idven;
        }
    }

    
   // $idereven = $request->input('idmodal');
   // if($idereven!= null){
    //    dd($idereven);
  //  }
    //dd($revendedores);
        $dados = DB::table('REVENDEDOR')->where([
            ['idbase', '=', $idbase],
            ['idven', '=', $idvendedor],
            ['idereven', '=',$sel_revendedor]
        ])->first();

    

    //    $dados->datcad = date('d/m/Y', strtotime( $dados->datcad));
   //     $dados->datalt = date('d/m/Y', strtotime( $dados->datalt));


        $ufs = $this->estadosBrasileiros;
        $lc = $this->localtrabalho;



         session(['sitReven' => '2']);

        return view("{$this->nameView}",compact('idusu',
            'vendedores', 'menus', 'categorias', 'data','dados','title', 'ideven','baseNome','idbase','idvendedor','vendedorNome','ufs','cobrador','lc','ideven_default', 'menuMaisUsu', 'usuario_lotec'));
    }


    public function indexFiltro($ideven)
    {

        $idusu = Auth::user()->idusu;

        $vendedores = $this->retornaBasesUser($idusu);

        $menus = $this->retornaMenu($idusu);
        $menuMaisUsu = $this->retornaMenuMaisUsu($idusu);

        $categorias = $this->retornaCategorias($menus);

        $title = $this->title;

        //RETORNA SQL REVENDEDOR -> INDEX
       //  $data = $this->returnIndexFiltro($ideven);
       
        $sit = $this->request->input('sel_situacao');
        
        if($sit == null){
            $situacao = 'TODOS';    
        }      
        elseif ($sit == 0){
            $situacao = 'ATIVO';
        }
        elseif ($sit == 1){
                $situacao = 'INATIVO';
        }
        elseif ($sit == 2){
            $situacao = 'TODOS';
        }
        else{
            $situacao = 'TODOS';    
        }
        
        session(['sitReven' => $sit]);
        
         $data = $this->returnIndexFiltro($ideven, $situacao);


        $ideven_default = $this->returnWebControlData($idusu);
        

        //dd($data);

        $baseAll = $this->retornaBasesAll($idusu);
        $valores = $baseAll;
        $cobrador = $this->retornaCobrador($ideven);
   
        foreach ($valores as $val){
   
           if ($val->ideven == $ideven_default) {
               $baseNome  = $val->nombas;
               $idbase = $val->idbase;
               $vendedorNome = $val->nomven;
               $idvendedor = $val->idven;
           }
       }

       $ufs = $this->estadosBrasileiros;
        $lc = $this->localtrabalho;


        return view("{$this->nameView}",compact('idusu',
            'vendedores', 'menus', 'categorias', 'data','title', 'baseAll', 'ideven','ideven_default', 'menuMaisUsu' ,'baseNome','idbase','vendedorNome','idvendedor','ufs','cobrador','lc'));
    }



public function createRevendedor($ideven){

    $idusu = Auth::user()->idusu;

    $vendedores = $this->retornaBasesUser($idusu);

    $menus = $this->retornaMenu($idusu);
    $menuMaisUsu = $this->retornaMenuMaisUsu($idusu);

    $categorias = $this->retornaCategorias($menus);

    $title = $this->title;

    $ideven_default = $this->returnWebControlData($idusu);

    $this->nameView = 'dashboard.revendedor-create';

    $bases = $this->retornaBases($idusu);

    $cobrador = $this->retornaCobrador($ideven);

    $ideven = $ideven;

    $baseAll = $this->retornaBasesAll($idusu);


    $valores = $baseAll;


    $collection = collect([
        ['sigla' => 'AC', 'nome' => 'ACRE'],
        ['sigla' => 'AL', 'nome' => 'ALAGOAS']
    ]);


    foreach ($valores as $val){

        if ($val->ideven == $ideven_default) {
            $baseNome  = $val->nombas;
            $idbase = $val->idbase;
            $vendedorNome = $val->nomven;
            $idvendedor = $val->idven;
        }
    }

    $ufs = $this->estadosBrasileiros;

    $lc = $this->localtrabalho;

    return view("{$this->nameView}",compact('idusu',
         'vendedores', 'menus', 'categorias', 'title', 'baseAll', 'ideven', 'ideven_default', 'bases', 'cobrador','baseNome', 'idbase', 'vendedorNome', 'idvendedor', 'ufs', 'lc', 'menuMaisUsu'))->render();
}
    /**
     * @return mixed
     */

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



    //principal

    public function returnIndex($ideven){



        $valor = $this->retornaAdmin();

//        dd($valor);

        if ($valor != 'SIM'){
            $p = $this->retornaBasepeloIdeven($ideven);

            $data = DB::select(" 
                   SELECT REVENDEDOR.IDBASE, REVENDEDOR.IDVEN, REVENDEDOR.IDREVEN, REVENDEDOR.NOMREVEN,
                    REVENDEDOR.CIDREVEN, REVENDEDOR.SIGUFS, REVENDEDOR.SITREVEN, REVENDEDOR.IDEREVEN, REVENDEDOR.LIMCRED,
                    VENDEDOR.NOMVEN, REVENDEDOR.IDCOBRA, COBRADOR.NOMCOBRA
                    FROM REVENDEDOR
                    INNER JOIN VENDEDOR ON VENDEDOR.IDBASE = REVENDEDOR.IDBASE AND
                    VENDEDOR.IDVEN = REVENDEDOR.IDVEN
                    LEFT JOIN COBRADOR ON COBRADOR.IDBASE = REVENDEDOR.IDBASE AND 
                    COBRADOR.IDVEN = REVENDEDOR.IDVEN AND 
                    COBRADOR.IDCOBRA = REVENDEDOR.IDCOBRA
                    INNER JOIN BASE ON BASE.IDBASE = REVENDEDOR.IDBASE
                    WHERE
                    REVENDEDOR.IDREVEN <> 99999999
                    AND REVENDEDOR.IDBASE = '$p->idbase'
                    AND REVENDEDOR.IDVEN = '$p->idven'
        ");
        } else {
            $data = DB::select(" 
                   SELECT REVENDEDOR.IDBASE, REVENDEDOR.IDVEN, REVENDEDOR.IDREVEN, REVENDEDOR.NOMREVEN,
                    REVENDEDOR.CIDREVEN, REVENDEDOR.SIGUFS, REVENDEDOR.SITREVEN, REVENDEDOR.IDEREVEN, REVENDEDOR.LIMCRED,
                    VENDEDOR.NOMVEN, REVENDEDOR.IDCOBRA, COBRADOR.NOMCOBRA
                    FROM REVENDEDOR
                    INNER JOIN VENDEDOR ON VENDEDOR.IDBASE = REVENDEDOR.IDBASE AND
                    VENDEDOR.IDVEN = REVENDEDOR.IDVEN
                    LEFT JOIN COBRADOR ON COBRADOR.IDBASE = REVENDEDOR.IDBASE AND 
                    COBRADOR.IDVEN = REVENDEDOR.IDVEN AND 
                    COBRADOR.IDCOBRA = REVENDEDOR.IDCOBRA
                    INNER JOIN BASE ON BASE.IDBASE = REVENDEDOR.IDBASE
                    WHERE
                    REVENDEDOR.IDREVEN <> 99999999
        ");

        }



        return $data;
    }


    public function returnIndexFiltro($ideven, $situacao){



        $valor = $this->retornaAdmin();

       // dd($situacao);
    if($situacao == 'TODOS'){
        
        if ($valor != 'SIM'){
            $p = $this->retornaBasepeloIdeven($ideven);

            $data = DB::select(" 
                   SELECT REVENDEDOR.IDBASE, REVENDEDOR.IDVEN, REVENDEDOR.IDREVEN, REVENDEDOR.NOMREVEN,
                    REVENDEDOR.CIDREVEN, REVENDEDOR.SIGUFS, REVENDEDOR.SITREVEN, REVENDEDOR.IDEREVEN, REVENDEDOR.LIMCRED,
                    VENDEDOR.NOMVEN, REVENDEDOR.IDCOBRA, COBRADOR.NOMCOBRA
                    FROM REVENDEDOR
                    INNER JOIN VENDEDOR ON VENDEDOR.IDBASE = REVENDEDOR.IDBASE AND
                    VENDEDOR.IDVEN = REVENDEDOR.IDVEN
                    LEFT JOIN COBRADOR ON COBRADOR.IDBASE = REVENDEDOR.IDBASE AND 
                    COBRADOR.IDVEN = REVENDEDOR.IDVEN AND 
                    COBRADOR.IDCOBRA = REVENDEDOR.IDCOBRA
                    INNER JOIN BASE ON BASE.IDBASE = REVENDEDOR.IDBASE
                    WHERE
                    REVENDEDOR.IDREVEN <> 99999999
                    AND REVENDEDOR.IDBASE = '$p->idbase'
                    AND REVENDEDOR.IDVEN = '$p->idven'
        ");
        } else {
            $data = DB::select(" 
                   SELECT REVENDEDOR.IDBASE, REVENDEDOR.IDVEN, REVENDEDOR.IDREVEN, REVENDEDOR.NOMREVEN,
                    REVENDEDOR.CIDREVEN, REVENDEDOR.SIGUFS, REVENDEDOR.SITREVEN, REVENDEDOR.IDEREVEN, REVENDEDOR.LIMCRED,
                    VENDEDOR.NOMVEN, REVENDEDOR.IDCOBRA, COBRADOR.NOMCOBRA
                    FROM REVENDEDOR
                    INNER JOIN VENDEDOR ON VENDEDOR.IDBASE = REVENDEDOR.IDBASE AND
                    VENDEDOR.IDVEN = REVENDEDOR.IDVEN
                    LEFT JOIN COBRADOR ON COBRADOR.IDBASE = REVENDEDOR.IDBASE AND 
                    COBRADOR.IDVEN = REVENDEDOR.IDVEN AND 
                    COBRADOR.IDCOBRA = REVENDEDOR.IDCOBRA
                    INNER JOIN BASE ON BASE.IDBASE = REVENDEDOR.IDBASE
                    WHERE
                    REVENDEDOR.IDREVEN <> 99999999
        ");

        }
    }else{ 
        
        if ($valor != 'SIM'){
            $p = $this->retornaBasepeloIdeven($ideven);

            $data = DB::select(" 
                   SELECT REVENDEDOR.IDBASE, REVENDEDOR.IDVEN, REVENDEDOR.IDREVEN, REVENDEDOR.NOMREVEN,
                    REVENDEDOR.CIDREVEN, REVENDEDOR.SIGUFS, REVENDEDOR.SITREVEN, REVENDEDOR.IDEREVEN, REVENDEDOR.LIMCRED,
                    VENDEDOR.NOMVEN, REVENDEDOR.IDCOBRA, COBRADOR.NOMCOBRA
                    FROM REVENDEDOR
                    INNER JOIN VENDEDOR ON VENDEDOR.IDBASE = REVENDEDOR.IDBASE AND
                    VENDEDOR.IDVEN = REVENDEDOR.IDVEN
                    LEFT JOIN COBRADOR ON COBRADOR.IDBASE = REVENDEDOR.IDBASE AND 
                    COBRADOR.IDVEN = REVENDEDOR.IDVEN AND 
                    COBRADOR.IDCOBRA = REVENDEDOR.IDCOBRA
                    INNER JOIN BASE ON BASE.IDBASE = REVENDEDOR.IDBASE
                    WHERE
                    REVENDEDOR.IDREVEN <> 99999999
                    AND REVENDEDOR.IDBASE = '$p->idbase'
                    AND REVENDEDOR.IDVEN = '$p->idven'
                    AND REVENDEDOR.SITREVEN = '$situacao'
        ");
        } else {
            $data = DB::select(" 
                   SELECT REVENDEDOR.IDBASE, REVENDEDOR.IDVEN, REVENDEDOR.IDREVEN, REVENDEDOR.NOMREVEN,
                    REVENDEDOR.CIDREVEN, REVENDEDOR.SIGUFS, REVENDEDOR.SITREVEN, REVENDEDOR.IDEREVEN, REVENDEDOR.LIMCRED,
                    VENDEDOR.NOMVEN, REVENDEDOR.IDCOBRA, COBRADOR.NOMCOBRA
                    FROM REVENDEDOR
                    INNER JOIN VENDEDOR ON VENDEDOR.IDBASE = REVENDEDOR.IDBASE AND
                    VENDEDOR.IDVEN = REVENDEDOR.IDVEN
                    LEFT JOIN COBRADOR ON COBRADOR.IDBASE = REVENDEDOR.IDBASE AND 
                    COBRADOR.IDVEN = REVENDEDOR.IDVEN AND 
                    COBRADOR.IDCOBRA = REVENDEDOR.IDCOBRA
                    INNER JOIN BASE ON BASE.IDBASE = REVENDEDOR.IDBASE
                    WHERE
                    REVENDEDOR.IDREVEN <> 99999999
                    AND REVENDEDOR.SITREVEN = '$situacao'
        ");

        } 
    }



        return $data;
    }

    public function retornaCobrador($ideven){

        $valor = $this->retornaAdmin();

        if ($valor != 'SIM') {
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

    public function retornaBase($idbase){
        $data = DB::select(" 
                   SELECT IDBASE,NOMPRO,NOMBAS,CIDBAS,SIGUFS
                   FROM BASE
                    WHERE SITBAS = 'ATIVO'
                    AND IDBASE = '$idbase'
            ");
        return $data;

    }

    public function retornaVend($idbase, $idven){
        $data = DB::select(" 
                   SELECT IDBASE,IDVEN,NOMVEN,APEVEN,CIDVEN,SIGUFS,PORTA_COM,IN_IMPAPO,IN_CANAPO,IN_IMPDIRETA
                    FROM VENDEDOR
                    WHERE SITVEN = 'ATIVO' AND
                    IDBASE = '$idbase'
                    AND VENDEDOR.IDVEN = '$idven'
            ");
        return $data;

    }

    public function retornaVlrPagPre($idbase, $idven, $idtipoapo){
        $data = DB::select(" 
                    SELECT IDBASE, IDVEN, IDTIPOAPO, VLRPAGPRE
                     FROM VEN_TIPO_APO
                    WHERE
                      IDBASE = '$idbase' AND
                      IDVEN = '$idven' AND
                      IDTIPOAPO = '$idtipoapo'   
            ");
        return $data;
    }

    public function retornaPorta_Com($idbase, $idven){
        $data = DB::select(" 
                     SELECT PORTA_COM
                      FROM VENDEDOR
                     WHERE
                     IDBASE = '$idbase' AND
                     IDVEN = '$idven'
            ");
        return $data;
    }

    public function retornaVen_HorApo_Exce($idbase, $idven, $idlot, $idhor){
        $data = DB::select(" 
                     SELECT HORLIM
                       FROM VEN_HORAPO_EXCE
                      WHERE
                       IDBASE = '$idbase' AND
                       IDVEN =  '$idven' AND
                       IDLOT = '$idlot' AND 
                       IDHOR = '$idhor'
            ");
        return $data;
    }

    public function createRevendedorGo($ideven)
    {
        $dataForm = $this->request->all();


        /** @var $rules */
        $rules = [
            'idbase'    => 'required',
            'idven'     => 'required',
            'nomreven'  => 'required|min:3|max:255',
            'cidreven'  => 'required|min:3|max:255',
            'sigufs'    => 'required',
            'limcred'   => 'required',
            'vlrcom'    => 'required',
            'vlrmaxpalp'=> 'required',
            'vlrblopre' => 'required',
            'limlibpre' => 'required',
            'limlibpre' => 'required',
            'sitreven'  => 'required',

        ];

        $required = 'é uma campo obrigatório';
        $min = 'deve ter no mínimo 3 caracteres';
        $max = 'deve ter no máximo 255 caracteres';
        $numeric = 'é um campo númerico';

        /** @var $mensagens */
        $mensagens = [
            'nomreven.required'     => "NOME {$required}",
            'nomreven.min'          => "NOME {$min}",
            'nomreven.max'          => "NOme {$max}",
            'cidreven.required'     => "CIDADE {$required}",
            'cidreven.min'          => "CIDADE {$min}",
            'cidreven.max'          => "CIDADE {$max}",
            'sigufs.required'       => "UF {$required}",
            'limcred.required'      => "LIMITE DE CRÉDITO {$required}",
            'limcred.NUMERIC'       => "LIMITE DE CRÉDITO {$numeric}",
            'vlrcom.required'       => "COMISSÃO PADRÃO {$required}",
            'vlrcom.numeric'        => "COMISSÃO PADRÃO {$numeric}",
            'vlrmaxpalp.required'   => "VLR. MÁXIMO P/ PALPITE {$required}",
            'vlrmaxpalp.numeric'    => "VLR. MÁXIMO P/ PALPITE {$numeric}",
            'vlrblopre.required'    => "BLOQUEAR PRÊMIO MAIOR QUE {$required}",
            'vlrblopre.numeric'     => "BLOQUEAR PRÊMIO MAIOR QUE {$numeric}",
            'limlibpre.required'    => "LIMITE DE DIAS PARA PRÊMIO {$required}",
            'limlibpre.numeric'     => "LIMITE DE DIAS PARA PRÊMIO {$numeric}",
            'endreven.required'     => "ENDEREÇO {$required}",
            'endreven.min'          => "ENDEREÇO {$min}",
            'endreven.max'          => "ENDEREÇO {$max}",
            'baireven.required'     => "BAIRRO {$required}",
            'baireven.min'          => "BAIRRO {$min}",
            'baireven.max'          => "BAIRRO {$max}",
            'celreven.required'     => "CELULAR {$required}",
            'idcobra.required'      => "COBRADOR {$required}",           
            'datcad.required'       => "DATA DE CADASTRO {$required}",
            'datalt.required'       => "DATA DE ALTERAÇÃO {$required}",
            'loctrab.required'       => "LOCAL DO TRABALHO {$required}",
        ];
        /*'porta_com.required'    => "PORTA COMUNICAÇÃO {$required}", estava ali em cima*/

        /** validação do request */
        $this->validate($this->request, $rules, $mensagens);

        $idbase = $this->request->input('idbase');
        $idven = $this->request->input('idven');
        $idereven = $this->request->input('idereven');
        $nomreven = $this->request->input('nomreven');
        $apereven = $this->request->input('nomreven');
        $cidreven = $this->request->input('cidreven');
        $sigufs = $this->request->input('sigufs');
        $limcred = $this->request->input('limcred');
        $vlrcom = $this->request->input('vlrcom');
        $vlrmaxpalp = $this->request->input('vlrmaxpalp');
        $vlrblopre = $this->request->input('vlrblopre');
        $limlibpre = $this->request->input('limlibpre');
        $sitreven = $this->request->input('sitreven');
        $idreven = $this->request->input('idreven');
        $endreven = $this->request->input('endreven');
        $baireven = $this->request->input('baireven');
        $celreven = $this->request->input('celreven');
        $obsreven = $this->request->input('obsreven');
        $insolaut = $this->request->input('insolaut');
        $idcobra = $this->request->input('idcobra');
        /*$porta_com = $this->request->input('porta_com');*/
        $datcad = $this->request->input('datcad');
        $in_impapo = $this->request->input('in_impapo');
        $idusucad = $this->request->input('idusucad');
        $in_canapo = $this->request->input('in_canapo');
        $datalt = $this->request->input('datalt');
        $in_impdireta = $this->request->input('in_impdireta');
        $idusualt = $this->request->input('idusualt');
        $loctrab = $this->request->input('loctrab');
        $recarga_cel = $this->request->input('recarga_cel');


        if ($datcad != ''){
            $dataCadastro = new DateTime();
            $newDateInicial = $dataCadastro->createFromFormat('d/m/Y', $datcad);
            $datcad = $newDateInicial->format('Y/m/d');
        }



        if ($datalt != ''){
            $dataAlteracao = new DateTime();
            $newDateInicial = $dataAlteracao->createFromFormat('d/m/Y', $datalt);
            $datalt = $newDateInicial->format('Y/m/d');
        }



        $idreven = $this->returnRevendedor($idbase, $idven);
        $idereven = $this->returnIdReven();
        $idusualt = 1000;

        $porta = $this->retornaPorta_Com($idbase, $idven);
        $porta_com = $porta[0]->porta_com;

        $limcred = str_replace('.', '', $limcred);
        $vlrcom = str_replace('.', '', $vlrcom);
        $vlrmaxpalp = str_replace('.', '', $vlrmaxpalp);
        $vlrblopre = str_replace('.', '', $vlrblopre);
        $limlibpre = str_replace('.', '', $limlibpre);
        
        $dados_array = [

            "idbase" => $idbase,
            "idven" => $idven,
            "idereven" => $idereven,
            "nomreven" => mb_strtoupper($nomreven,'UTF-8'),
            "apereven" => mb_strtoupper($apereven,'UTF-8'),
            "cidreven" => mb_strtoupper($cidreven, 'UTF-8'),
            "sigufs" => $sigufs,
            "limcred" => floatval(str_replace(',', '.', $limcred)),
            "vlrcom" => floatval(str_replace(',', '.', $vlrcom)),
            "vlrmaxpalp" => floatval(str_replace(',', '.', $vlrmaxpalp)),
            "vlrblopre" => floatval(str_replace(',', '.', $vlrblopre)),
            "limlibpre" => floatval(str_replace(',', '.', $limlibpre)),
            "sitreven" => $sitreven,
            "idreven" => $idreven,
            "endreven" => strtoupper($endreven),
            "baireven" => strtoupper($baireven),
            "celreven" => $celreven,
            "obsreven" => strtoupper($obsreven),
            "insolaut" => $insolaut,
            "idcobra" => $idcobra,
            "porta_com" => $porta_com,
            "datcad" => $datcad,
            "in_impapo" => $in_impapo,
            "idusucad" => $idusucad,
            "in_canapo" => $in_canapo,
            "datalt" => $datalt,
            "in_impdireta" => $in_impdireta,
            "idusualt" => $idusualt,
            "loctrab" => $loctrab,
            "in_recarga" => $recarga_cel,

        ];

     //  dd($dados_array);

        $insert = DB::table('REVENDEDOR')->insert($dados_array);
//        dd($insert);


        //INSERIR LOTERIAS REVENDEDOR


        if ($insert){
            $p = $this->retornaBasepeloIdeven($ideven);

            $dados = [];

            $ven_loterias = DB::select(" 
                SELECT VEN_LOTERIA.IDBASE, VEN_LOTERIA.IDVEN, VEN_LOTERIA.IDLOT, VEN_LOTERIA.SITLIG, '$idreven' as IDREVEN
                FROM VEN_LOTERIA
                INNER JOIN LOTERIAS ON LOTERIAS.IDLOT = VEN_LOTERIA.IDLOT
                WHERE
                      VEN_LOTERIA.IDBASE = '$p->idbase' AND
                      VEN_LOTERIA.IDVEN = '$p->idven'
                ORDER BY LOTERIAS.DESLOT               
            ");
            foreach ($ven_loterias as $vl) {
                $v = [
                    "idbase" => $vl->idbase,
                    "idven" => $vl->idven,
                    "idreven" => $vl->idreven,
                    "idlot" => $vl->idlot,
                    "sitlig" => $vl->sitlig,
                ];

                array_push($dados, $v);

    //            dd($v);
                $insert = DB::table('REVEN_LOTERIA')->insert($v);

                //douglas
                if ($insert){
                   if ($vl->sitlig = 'ATIVO'){
                       $ven_hor_apo = DB::select("
                       SELECT VEN_HOR_APO.IDBASE,VEN_HOR_APO.IDVEN,VEN_HOR_APO.IDLOT,VEN_HOR_APO.IDHOR, '$idreven' as IDREVEN,
                              VEN_HOR_APO.SITLIG, HOR_APOSTA.HORLIM
                          FROM VEN_HOR_APO
                           INNER JOIN HOR_APOSTA ON HOR_APOSTA.IDLOT = VEN_HOR_APO.IDLOT AND
                                                    HOR_APOSTA.IDHOR = VEN_HOR_APO.IDHOR
                          WHERE
                            VEN_HOR_APO.IDBASE = '$p->idbase' AND
                            VEN_HOR_APO.IDVEN = '$p->idven' AND
                            VEN_HOR_APO.SITLIG = 'ATIVO' AND
                            HOR_APOSTA.SITHOR = 'ATIVO' AND
                            VEN_HOR_APO.IDLOT = '$vl->idlot'
                            ORDER BY HOR_APOSTA.HORSOR
                       ");
                       foreach ($ven_hor_apo as $vha) {
                          $h = [
                            "idbase" => $vha->idbase,
                            "idven" => $vha->idven,
                            "idreven" => $vha->idreven,
                            "idlot" => $vha->idlot,
                            "idhor" => $vha->idhor,
                            "sitlig" => $vha->sitlig,  
                            "horlim" => $vha->horlim,
                            "datcad" => $datcad, 
                            "idusucad" => $idusucad,
                            "datalt" => $datalt, 
                            "idusualt" => $idusualt,
                          ];

                          $exce = $this->retornaVen_HorApo_Exce($vha->idbase, $vha->idven, $vha->idlot, $vha->idhor);                                               
                          if (!empty($exce)){
                            $h["horlim"] = $exce[0]->horlim; 
                          }
                          
                          $insert = DB::table('REVEN_HOR_APO')->insert($h);
                       }
                   }
                }
            }
           //agora as modalidades 
           $ven_tipo_apo = DB::select("
           SELECT VEN_TIPO_APO.IDBASE, VEN_TIPO_APO.IDVEN, VEN_TIPO_APO.IDTIPOAPO, VEN_TIPO_APO.SITLIG, '$idreven' as IDREVEN
               FROM VEN_TIPO_APO
            INNER JOIN TIPO_APOSTA ON TIPO_APOSTA.IDTIPOAPO = VEN_TIPO_APO.IDTIPOAPO
              WHERE
                TIPO_APOSTA.SITTIPOAPO = 'ATIVO' AND
                VEN_TIPO_APO.SITLIG = 'ATIVO' AND
                VEN_TIPO_APO.IDBASE = '$p->idbase' AND
                VEN_TIPO_APO.IDVEN = '$p->idven'
                ORDER BY VEN_TIPO_APO.IDTIPOAPO           
           ");
           foreach ($ven_tipo_apo as $vta) {
          
            $vv = $this->retornaVlrPagPre($vta->idbase, $vta->idven, $vta->idtipoapo);

            //dd($v);
            $tp = [
              "idbase" => $vta->idbase,
              "idven" => $vta->idven,
              "idreven" => $vta->idreven,
              "idtipoapo" => $vta->idtipoapo,
              "sitlig" => $vta->sitlig,  
              "datcad" => $datcad, 
              "idusucad" => $idusucad,
              "datalt" => $datalt, 
              "idusualt" => $idusualt,
              "vlrcom" => floatval(str_replace(',', '.', $vlrcom)),
              "vlrpagpre" => $vv[0]->vlrpagpre,
            ];
            
            $insert = DB::table('REVEN_TIPO_APO')->insert($tp);
          }

          //menu de modalidades
          $ven_menuapoter = DB::select("
          SELECT VEN_MENUAPOTER.IDBASE, VEN_MENUAPOTER.IDVEN, VEN_MENUAPOTER.IDMENU, VEN_MENUAPOTER.VLRLIMPALPOFFF, 
                 VEN_MENUAPOTER.IDTIPOAPOMAIOR, MENUAPO_TER.IDMENUCOMB, VEN_MENUAPOTER.SITLIG , '$idreven' as IDREVEN
            FROM VEN_MENUAPOTER
             INNER JOIN MENUAPO_TER ON MENUAPO_TER.IDMENU = VEN_MENUAPOTER.IDMENU
            WHERE
               MENUAPO_TER.SITMENU = 'ATIVO' AND
               VEN_MENUAPOTER.SITLIG = 'ATIVO' AND
               VEN_MENUAPOTER.IDBASE = '$p->idbase' AND
               VEN_MENUAPOTER.IDVEN = '$p->idven'
               ORDER BY MENUAPO_TER.ORDAPR
          ");

          foreach ($ven_menuapoter as $vma) {
            $ma = [
                "idbase" => $vma->idbase,
                "idven" => $vma->idven,
                "idreven" => $vma->idreven,
                "idmenu" => $vma->idmenu,
                "vlrmaxpalp" => floatval(str_replace(',', '.', $vlrmaxpalp)),
                "vlrlimpalpofff" => $vma->vlrlimpalpofff,
                "idtipoapomaior" => $vma->idtipoapomaior,
                "sitlig" => $vma->sitlig,
                "datcad" => $datcad, 
                "idusucad" => $idusucad,
            ];

            $insert = DB::table('REVEN_MENUAPOTER')->insert($ma);

        }
      }

        if($insert)
            return redirect("/admin/revendedor/create/{$ideven}")
                ->with(['success'=>'Cadastro realizado com sucesso!']);
        else
            return redirect()
                ->route("revendedor-create")
                ->withErrors(['errors' => 'Falha ao cadastrar'])
                ->withInput(); 
    }


    public function  returnRevendedor($idbase, $idven){

        $data = DB::select (" 
        SELECT MAX(REVENDEDOR.IDREVEN) AS IDREVEN
                  FROM REVENDEDOR
                  WHERE
                  REVENDEDOR.IDBASE = '$idbase' AND
                  REVENDEDOR.IDVEN = '$idven' 

        ");

        $data = $data[0]->idreven;

      //  dd($data);
        $data = $data + 1;

        return $data;
    }

    public function returnIdReven(){
        $data = DB::select (" 
        SELECT MAX(REVENDEDOR.IDEREVEN) AS IDEREVEN
                  FROM REVENDEDOR 

        ");
        $data = $data[0]->idereven;

        $data = $data + 1;

        return $data;
    }

    public function retornaLimCredReven($idbase, $idven, $idreven){
        $data = DB::select (" 
          SELECT IDBASE, IDVEN, IDREVEN, NOMREVEN, LIMCRED, IDEREVEN
            FROM REVENDEDOR
           WHERE
            IDBASE = '$idbase' AND
            IDVEN = '$idven' AND
            IDREVEN = '$idreven'
        ");

        return $data;
    }

    public function retornaComissaoReven($idbase, $idven, $idreven){
        $data = DB::select (" 
          SELECT IDBASE, IDVEN, IDREVEN, NOMREVEN, VLRCOM, IDEREVEN
            FROM REVENDEDOR
           WHERE
            IDBASE = '$idbase' AND
            IDVEN = '$idven' AND
            IDREVEN = '$idreven'
        ");

        return $data;
    }

    public function retornaIdeReven($idbase, $idven, $idreven){
        $data = DB::select (" 
          SELECT IDBASE, IDVEN, IDREVEN, NOMREVEN, IDEREVEN
            FROM REVENDEDOR
           WHERE
            IDBASE = '$idbase' AND
            IDVEN = '$idven' AND
            IDREVEN = '$idreven'
        ");

        return $data;
    }

    public function gerarComandosTerminal($idbase, $idven, $idreven, $cmd){
        $terminais = DB::select (" 
          SELECT IDBASE, IDVEN, IDTER, IDETER, IDREVEN
            FROM TERMINAL
           WHERE
             IDBASE = '$idbase' AND
             IDVEN = '$idven' AND
             IDREVEN = '$idreven' AND
             TIPOTER <> 'SIMULADOR' 
        ");

        foreach ($terminais as $terminal){
          $comandos = DB::select ("
            SELECT IDETER, COMANDOS
             FROM 
               TERMINAL_COMANATU
             WHERE
               IDETER = '$terminal->ideter'
          ");

          if (empty($comandos)){
             
              $comando = $cmd.'+';

              $horaAtual = new DateTime();
              $dataAtual = date ("Y-m-d");

              $dados_Terminal = [
                "ideter" => $terminal->ideter,
                "comandos" => $comando,
                "horultcom" => $horaAtual,
                "datultcom" => $dataAtual,
              ];
  
            $insert = DB::table('TERMINAL_COMANATU')->insert($dados_Terminal); 

          }
          else{
               $str = $comandos[0]->comandos;
               $pos = strpos( $str, $cmd);
               if ($pos === false) {
                  $str = $str.$cmd.'+';
                  
                  $horaAtual = new DateTime();
                  $dataAtual = date ("Y-m-d");
                  
                  $dados_array = [    
                    "comandos" => $str,
                    "horultcom" => $horaAtual,
                    "datultcom" => $dataAtual,
                  ];
 
                  $update = DB::table('TERMINAL_COMANATU')->where([
                    ['IDETER', '=', $terminal->ideter]
                  ])->update($dados_array);
               }
            }
        }
    }

    public function alterarLimiteGo($idbase, $idven, $idreven){

        $dataForm = $this->request->all();

       /* dd($dataForm);*/

        /** @var $rules */
        $rules = [
            'limcred'   => 'required',
        ];

        $required = 'é uma campo obrigatório';
        $min = 'deve ter no mínimo 3 caracteres';
        $max = 'deve ter no máximo 255 caracteres';
        $numeric = 'é um campo númerico';

        /** @var $mensagens */
        $mensagens = [
            
            'limcred.required'      => "LIMITE DE CRÉDITO {$required}",
            'limcred.NUMERIC'       => "LIMITE DE CRÉDITO {$numeric}",
        ];
        
        /** validação do request */
      /*  $this->validate($this->request, $rules, $mensagens);*/

        $limcred = $this->request->input('limiteNovo');
        $limcred = str_replace('.', '', $limcred);
     
        $dados_array = [    
            "limcred" => floatval(str_replace(',', '.', $limcred)),
        ];

        $update = DB::table('REVENDEDOR')->where([
            ['IDBASE', '=', $idbase],
            ['IDVEN', '=', $idven],
            ['IDREVEN', '=', $idreven]
        ])->update($dados_array);

        $nn = $this->retornaLimCredReven($idbase, $idven, $idreven);

        $ideven_ = DB::select (" 
          SELECT IDEVEN
            FROM VENDEDOR
           WHERE
            IDBASE = '$idbase' AND
            IDVEN = '$idven'
        ");
    
        
            $coman =  $this->gerarComandosTerminal($idbase, $idven, $idreven, 'lim:');

        if($update)
            return redirect("/admin/revendedor/update/{$ideven_[0]->ideven}/{$nn[0]->idereven}")
                   ->with(['success'=>'Limite atualizado com sucesso!']);
        else
            return redirect("/admin/revendedor/update/{$ideven_[0]->ideven}/{$nn[0]->idereven}")
                ->withErrors(['errors' => 'Falha ao atualizar'])
                ->withInput();

    }

    public function alterarLimite($idbase, $idven, $idreven){
        
        $idusu = Auth::user()->idusu;

        $user_base = $this->retornaBase($idusu);

        $user_bases = $this->retornaBases($idusu);

        $usuario_lotec = $this->retornaUserLotec($idusu);

        $vendedores = $this->retornaBasesUser($idusu);

        $menus = $this->retornaMenu($idusu);
        $menuMaisUsu = $this->retornaMenuMaisUsu($idusu);

        $categorias = $this->retornaCategorias($menus);

        $title = 'Limite de Crédito';

        $baseAll = $this->retornaBasesAll($idusu);

        $admin = Usuario::where('idusu', '=', $idusu)->first();

        $this->nameView = 'dashboard.revendedor-limite';

        $ideven_default = $this->returnWebControlData($idusu);

        $nn = $this->retornaLimCredReven($idbase, $idven, $idreven);
        $nomLimRevendedor = $nn[0];
        
     //  Retirei o ('ideven') return view("{$this->nameView}",compact('idusu',
     //       'user_base', 'user_bases', 'usuario_lotec', 'vendedores', 'menus', 'categorias', 'title',
     //       'baseAll', 'ideven', 'ideven_default', 'nomLimRevendedor', 'menuMaisUsu'));        
   // } 

    return view("{$this->nameView}",compact('idusu',
    'user_base', 'user_bases', 'usuario_lotec', 'vendedores', 'menus', 'categorias', 'title',
    'baseAll', 'ideven_default', 'nomLimRevendedor', 'menuMaisUsu'))->render();        
} 

    public function alterarComissaoGo($idbase, $idven, $idreven){

        $dataForm = $this->request->all();

       /* dd($dataForm);*/

        /** @var $rules */
        $rules = [
            'vlrcom'   => 'required',
        ];

        $required = 'é uma campo obrigatório';
        $min = 'deve ter no mínimo 3 caracteres';
        $max = 'deve ter no máximo 255 caracteres';
        $numeric = 'é um campo númerico';

        /** @var $mensagens */
        $mensagens = [
            
            'vlrcom.required'      => "LIMITE DE CRÉDITO {$required}",
            'vlrcom.NUMERIC'       => "LIMITE DE CRÉDITO {$numeric}",
        ];
        
        /** validação do request */
      /*  $this->validate($this->request, $rules, $mensagens);*/

        $comissao = $this->request->input('comissaoNovo');

      //  dd($comissao);
        $dados_array = [    
            "vlrcom" => floatval(str_replace(',', '.', $comissao)),
        ];

      //  dd($dados_array, $idbase, $idven,$idreven );
    
        

        $update = DB::table('REVENDEDOR')->where([
            ['IDBASE', '=', $idbase],
            ['IDVEN', '=', $idven],
            ['IDREVEN', '=', $idreven]
        ])->update($dados_array);

       // dd($update);

        $nn = $this->retornaComissaoReven($idbase, $idven, $idreven);

     //   dd($nn);

        $ideven_ = DB::select (" 
          SELECT IDEVEN
            FROM VENDEDOR
           WHERE
            IDBASE = '$idbase' AND
            IDVEN = '$idven'
        ");
    
        if($update){
    
            $update = DB::table('REVEN_TIPO_APO')->where([
                ['IDBASE', '=', $idbase],
                ['IDVEN', '=', $idven],
                ['IDREVEN', '=', $idreven]
            ])->update($dados_array);

            return redirect("/admin/revendedor/update/{$ideven_[0]->ideven}/{$nn[0]->idereven}")
                   ->with(['success'=>'% Comissão atualizado com sucesso!']);
        }
        else
            return redirect("/admin/revendedor/update/{$ideven_[0]->ideven}/{$nn[0]->idereven}")
                ->withErrors(['errors' => 'Falha ao atualizar'])
                ->withInput();

    }

    public function alterarComissao($idbase, $idven, $idreven){
        
        $idusu = Auth::user()->idusu;

        $user_base = $this->retornaBase($idusu);

        $user_bases = $this->retornaBases($idusu);

        $usuario_lotec = $this->retornaUserLotec($idusu);

        $vendedores = $this->retornaBasesUser($idusu);

        $menus = $this->retornaMenu($idusu);
        $menuMaisUsu = $this->retornaMenuMaisUsu($idusu);

        $categorias = $this->retornaCategorias($menus);

        $title = '% Comissão';

        $baseAll = $this->retornaBasesAll($idusu);

        $admin = Usuario::where('idusu', '=', $idusu)->first();

        $this->nameView = 'dashboard.revendedor-comissao-modal';

        $ideven_default = $this->returnWebControlData($idusu);

        $nn = $this->retornaComissaoReven($idbase, $idven, $idreven);
        $comRevendedor = $nn[0];
        
  // retirei o  'ideven',    return view("{$this->nameView}",compact('idusu',
  //          'user_base', 'user_bases', 'usuario_lotec', 'vendedores', 'menus', 'categorias', 'title',
  //          'baseAll', 'ideven', 'ideven_default', 'comRevendedor', 'menuMaisUsu'));        
   // }
    
    return view("{$this->nameView}",compact('idusu',
    'user_base', 'user_bases', 'usuario_lotec', 'vendedores', 'menus', 'categorias', 'title',
    'baseAll',  'ideven_default', 'comRevendedor', 'menuMaisUsu'))->render();        
}

    public function ativarDesativarLoteriasReven($idbase, $idven, $idreven, $idlot, $operacao){

       $exe = 'SIM';

       $data = DB::select (" 
                     SELECT VEN_LOTERIA.SITLIG
                        FROM VEN_LOTERIA
                        WHERE
                          IDBASE = '$idbase' AND
                          IDVEN = '$idven' AND
                          IDLOT = '$idlot' AND
                          SITLIG = 'ATIVO'
        ");

       if(empty($data)){
          $exe = 'NAO';  
       }

       if($exe == 'SIM'){

          $dados_array = [    
             "sitlig" => $operacao,
          ];

          $update = DB::table('REVEN_LOTERIA')->where([
              ['IDBASE', '=', $idbase],
              ['IDVEN', '=', $idven],
              ['IDREVEN', '=', $idreven],
              ['IDLOT', '=', $idlot]
          ])->update($dados_array);

          $update = DB::table('REVEN_HOR_APO')->where([
            ['IDBASE', '=', $idbase],
            ['IDVEN', '=', $idven],
            ['IDREVEN', '=', $idreven],
            ['IDLOT', '=', $idlot]
        ])->update($dados_array);

       }else{
              $update = false;
            }

        $coman =  $this->gerarComandosTerminal($idbase, $idven, $idreven, 'hra:');

       $idereven_ = $this->retornaIdeReven($idbase, $idven, $idreven);

        $ideven_ = DB::select (" 
          SELECT IDEVEN
            FROM VENDEDOR
           WHERE
            IDBASE = '$idbase' AND
            IDVEN = '$idven'
        ");

        if($update){
              return redirect("/admin/revendedor/update/{$ideven_[0]->ideven}/{$idereven_[0]->idereven}")
              ->with(['success'=>'Loteria atualizada com sucesso!']);
        }else{
              return redirect("/admin/revendedor/update/{$ideven_[0]->ideven}/{$idereven_[0]->idereven}")
              ->withErrors(['errors' => 'Falha ao atualizar'])
              ->withInput();
             }    

    }
    
   

    public function edit($ideven,$idereven){

        $idusu = Auth::user()->idusu;

        $vendedores = $this->retornaBasesUser($idusu);

        $menus = $this->retornaMenu($idusu);
        $menuMaisUsu = $this->retornaMenuMaisUsu($idusu);

        $categorias = $this->retornaCategorias($menus);

        $title = $this->title;

        $ideven_default = $this->returnWebControlData($idusu);

        $bases = $this->retornaBases($idusu);

        $cobrador = $this->retornaCobrador($ideven);

        $ideven = $ideven;

        $baseAll = $this->retornaBasesAll($idusu);

        $valores = $baseAll;

        $this->nameView = 'dashboard.revendedor-modal';

        foreach ($valores as $val){

            if ($val->ideven == $ideven_default) {
                $baseNome  = $val->nombas;
                $idbase = $val->idbase;
                $vendedorNome = $val->nomven;
                $idvendedor = $val->idven;
            }
        }
      //  dd($baseNome,$idbase,$idvendedor,$vendedorNome);
      $revendedores = $this->retornaRevendedores($idbase, $idvendedor);
        foreach($revendedores as $rev){
            $identificacaoRev = $rev->idereven;
        }

   //   dd($revendedores);
        $dados = DB::table('REVENDEDOR')->where([
            ['idbase', '=', $idbase],
            ['idven', '=', $idvendedor],
            ['idereven', '=', $idereven]
        ])->first();
//dd($dados);
        $dados->datcad = date('d/m/Y', strtotime( $dados->datcad));
        $dados->datalt = date('d/m/Y', strtotime( $dados->datalt));


        $ufs = $this->estadosBrasileiros;
        $lc = $this->localtrabalho;

        $loterias = $this->returnLotVen($ideven, $dados->idreven);
        
        return view("{$this->nameView}",compact('idusu',
            'vendedores', 'menus', 'categorias', 'title', 'baseAll', 'ideven', 'ideven_default', 'bases', 'cobrador','baseNome',
            'idbase', 'vendedorNome', 'idvendedor','dados', 'ufs', 'lc', 'loterias', 'menuMaisUsu','revendedores'))->render();

    }
    public function update($ideven)
    {
        $dataForm = $this->request->all();

    //    dd($dataForm);
        /** @var $rules */
        $rules = [
            'idbase'    => 'required',
            'idven'     => 'required',
            'nomreven'  => 'required|min:3|max:255',
            'cidreven'  => 'required|min:3|max:255',
            'sigufs'    => 'required',
            'limcred'   => 'required',
            'vlrcom'    => 'required',
            'vlrmaxpalp'=> 'required',
            'vlrblopre' => 'required',
            'limlibpre' => 'required',
            'limlibpre' => 'required',
            'sitreven'  => 'required',
        ];
        
        $required = 'é uma campo obrigatório';
        $min = 'deve ter no mínimo 3 caracteres';
        $max = 'deve ter no máximo 255 caracteres';
        $numeric = 'é um campo númerico';

        
         /** @var $mensagens */
        $mensagens = [
            'nomreven.required'     => "NOME {$required}",
            'nomreven.min'          => "NOME {$min}",
            'nomreven.max'          => "NOme {$max}",
            'cidreven.required'     => "CIDADE {$required}",
            'cidreven.min'          => "CIDADE {$min}",
            'cidreven.max'          => "CIDADE {$max}",
            'sigufs.required'       => "UF {$required}",
            'limcred.required'      => "LIMITE DE CRÉDITO {$required}",
            'limcred.NUMERIC'       => "LIMITE DE CRÉDITO {$numeric}",
            'vlrcom.required'       => "COMISSÃO PADRÃO {$required}",
            'vlrcom.numeric'        => "COMISSÃO PADRÃO {$numeric}",
            'vlrmaxpalp.required'   => "VLR. MÁXIMO P/ PALPITE {$required}",
            'vlrmaxpalp.numeric'    => "VLR. MÁXIMO P/ PALPITE {$numeric}",
            'vlrblopre.required'    => "BLOQUEAR PRÊMIO MAIOR QUE {$required}",
            'vlrblopre.numeric'     => "BLOQUEAR PRÊMIO MAIOR QUE {$numeric}",
            'limlibpre.required'    => "LIMITE DE DIAS PARA PRÊMIO {$required}",
            'limlibpre.numeric'     => "LIMITE DE DIAS PARA PRÊMIO {$numeric}",
            'endreven.required'     => "ENDEREÇO {$required}",
            'endreven.min'          => "ENDEREÇO {$min}",
            'endreven.max'          => "ENDEREÇO {$max}",
            'baireven.required'     => "BAIRRO {$required}",
            'baireven.min'          => "BAIRRO {$min}",
            'baireven.max'          => "BAIRRO {$max}",
            'celreven.required'     => "CELULAR {$required}",
            'idcobra.required'      => "COBRADOR {$required}",
            'porta_com.required'    => "PORTA COMUNICAÇÃO {$required}",
            'datcad.required'       => "DATA DE CADASTRO {$required}",
            'datalt.required'       => "DATA DE ALTERAÇÃO {$required}",
            'loctrab.required'       => "LOCAL DO TRABALHO {$required}",

        ];
        
        
        /** validação do request */
        $this->validate($this->request, $rules, $mensagens);

        

        $idbase = $this->request->input('idbase');
        $idven = $this->request->input('idven');
        $idereven = $this->request->input('idereven');
        $nomreven = $this->request->input('nomreven');
        $apereven = $nomreven;
        $cidreven = $this->request->input('cidreven');
        $sigufs = $this->request->input('sigufs');
        $limcred = $this->request->input('limcred');
        $vlrcom = $this->request->input('vlrcom');
        $vlrmaxpalp = $this->request->input('vlrmaxpalp');
        $vlrblopre = $this->request->input('vlrblopre');
        $limlibpre = $this->request->input('limlibpre');
        $sitreven = $this->request->input('sitreven');
        $idreven = $this->request->input('idreven');
        $endreven = $this->request->input('endreven');
        $baireven = $this->request->input('baireven');
        $celreven = $this->request->input('celreven');
        $obsreven = $this->request->input('obsreven');
        $insolaut = $this->request->input('insolaut');
        $idcobra = $this->request->input('idcobra');
        $in_impapo = $this->request->input('in_impapo');
        $in_canapo = $this->request->input('in_canapo');
        $datalt = $this->request->input('datalt');
        $in_impdireta = $this->request->input('in_impdireta');
        $loctrab = $this->request->input('loctrab');
        $recarga_cel = $this->request->input('recarga_cel');
   //     dd($recarga_cel);

        $datalt = date('Y/m/d');
        $idusualt = Auth()->user()->idusu;
        $limcred = str_replace('.', '', $limcred);
        
        $dados_array = [
            "idbase" => $idbase,
            "idven" => $idven,
            "idereven" => $idereven,
            "nomreven" => mb_strtoupper($nomreven,'UTF-8'),
            "apereven" => mb_strtoupper($apereven,'UTF-8'),
            "cidreven" => mb_strtoupper($cidreven, 'UTF-8'),
            "sigufs" => $sigufs,
            "limcred" => floatval(str_replace(',', '.', $limcred)),
            "vlrcom" => floatval(str_replace(',', '.', $vlrcom)),
            "vlrmaxpalp" => floatval(str_replace(',', '.', $vlrmaxpalp)),
            "vlrblopre" => floatval(str_replace('.', '', $vlrblopre)),
            "limlibpre" => floatval(str_replace(',', '.', $limlibpre)),
            "sitreven" => $sitreven,
            "idreven" => $idreven,
            "endreven" => strtoupper($endreven),
            "baireven" => strtoupper($baireven),
            "celreven" => $celreven,
            "obsreven" => strtoupper($obsreven),
            "insolaut" => $insolaut,
            "idcobra" => $idcobra,
            "in_impapo" => $in_impapo,
            "in_canapo" => $in_canapo,
            "datalt" => $datalt,
            "in_impdireta" => $in_impdireta,
            "idusualt" => $idusualt,
            "loctrab" => $loctrab, 
            "in_recarga" => $recarga_cel,          
        ];

   //     dd($dados_array);
        $inativo = 'INATIVO';
        $p_format = "'yyyy/mm/dd'";
        $data_h = "'$datalt'";
        
        $update = DB::table('REVENDEDOR')->where('idereven', $idereven)->update($dados_array);
//dd($update);
            if($update){

                if ($sitreven == 'INATIVO'){
                    $update_terminal = Terminal::where(
                        ['TERMINAL.IDBASE' => $idbase, 'TERMINAL.IDVEN' => $idven, 'TERMINAL.IDREVEN'=> $idreven])
                        ->update([
                            'SITTER' => 'INATIVO',
                            'DATALT' => $datalt,
                            'IDUSUALT' => $idusualt
                            ]);
                }


                $valor = Terminal::where( ['TERMINAL.IDBASE' => $idbase, 'TERMINAL.IDVEN' => $idven, 'TERMINAL.IDREVEN'=> $idreven])->get();
//
//
             //  dd($valor);
            }

        if($update){
            $coman =  $this->gerarComandosTerminal($idbase, $idven, $idreven, 'rev:');
            //dd($coman);
            return redirect("/admin/revendedor/create/{$ideven}")
                ->with(['success'=>'Cadastro atualizado com sucesso!']);
        }
        else
            return redirect("/admin/revendedor/update/{$ideven}/{$idereven}")
                ->withErrors(['errors' => 'Falha ao atualizar'])
                ->withInput();


    }

    public function returnLotVen($ideven, $idreven){

        $p = $this->retornaBasepeloIdeven($ideven);

        $dados = [];

        $ven_loterias = DB::select(" 
                SELECT REVEN_LOTERIA.IDBASE, REVEN_LOTERIA.IDVEN, REVEN_LOTERIA.IDLOT, REVEN_LOTERIA.SITLIG, '$idreven' as IDREVEN,
                LOTERIAS.DESLOT, LOTERIAS.ABRLOT
                FROM REVEN_LOTERIA
                INNER JOIN LOTERIAS ON LOTERIAS.IDLOT = REVEN_LOTERIA.IDLOT
                WHERE
                      REVEN_LOTERIA.IDBASE = '$p->idbase' AND
                      REVEN_LOTERIA.IDVEN = '$p->idven' AND 
                      REVEN_LOTERIA.IDREVEN = '$idreven'
                ORDER BY LOTERIAS.DESLOT               
        ");

        $data = $ven_loterias;
//        dd($data);


        return $data;
    }

    public function retornaRevendedores($idbase, $idven){
        $data = DB::select(" 
             SELECT IDBASE, IDVEN, IDREVEN, IDEREVEN, NOMREVEN, IN_RECARGA
                 FROM REVENDEDOR
                     WHERE
                         IDBASE = '$idbase' AND
                         IDVEN = '$idven' AND
                         SITREVEN = 'ATIVO'
                     ORDER BY NOMREVEN 
        ");

        return $data;
    }
}
