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
use lotecweb\Models\Terminal;

class TerminalController extends StandardController
{
    protected $model;
    protected $nameView = 'dashboard.terminal';
    protected $data;
    protected $title = 'Cadastro de Terminal';
    protected $redirectCad = '/admin/contatos/cadastrar';
    protected $redirectEdit = '/admin/contatos/editar';
    protected $route = '/admin/contatos';
    

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

        $vendedores = $this->retornaBasesUser($idusu);

        $menus = $this->retornaMenu($idusu);
        $menuMaisUsu = $this->retornaMenuMaisUsu($idusu);

        $categorias = $this->retornaCategorias($menus);

        $title = $this->title;

        $usuario_lotec = $this->retornaUserLotec($idusu);

        //RETORNA SQL TERMINAIS -> INDEX
        $data = $this->retornaTerminais($ideven);

      //  dd($data);
        $ideven_default = $this->returnWebControlData($idusu);
        
        session(['sitTer' => '0']);
        
        return view("{$this->nameView}",compact('idusu',
            'vendedores', 'menus', 'categorias', 'data','title', 'ideven','ideven_default', 'menuMaisUsu', 'usuario_lotec'));
    }


    public function indexFiltro($ideven)
    {

        $idusu = Auth::user()->idusu;

        $vendedores = $this->retornaBasesUser($idusu);

        $menus = $this->retornaMenu($idusu);
        $menuMaisUsu = $this->retornaMenuMaisUsu($idusu);

        $categorias = $this->retornaCategorias($menus);

        $title = $this->title;
       
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
        
        session(['sitTer' => $sit]);
        
         $data = $this->retornaIndexFiltro($ideven, $situacao);



        $ideven_default = $this->returnWebControlData($idusu);

        $usuario_lotec = $this->retornaUserLotec($idusu);
        
      //  dd($data);

        return view("{$this->nameView}",compact('idusu',
        'vendedores', 'menus', 'categorias', 'data','title', 'ideven','ideven_default', 'menuMaisUsu', 'usuario_lotec'));


    //  (tirei o campo loterias)  return view("{$this->nameView}",compact('idusu',
    //    'vendedores', 'menus', 'categorias', 'data','title', 'ideven','ideven_default', 'loterias', 'menuMaisUsu'));


  //      return view("{$this->nameView}",compact('idusu',
    //       'vendedores', 'menus', 'categorias', 'data','title', 'baseAll', 'ideven','ideven_default', 'loterias', 'menuMaisUsu'));
    }



public function createTerminal($ideven){

    $idusu = Auth::user()->idusu;

    $vendedores = $this->retornaBasesUser($idusu);

    $menus = $this->retornaMenu($idusu);
    $menuMaisUsu = $this->retornaMenuMaisUsu($idusu);

    $categorias = $this->retornaCategorias($menus);

    $title = $this->title;

    $ideven_default = $this->returnWebControlData($idusu);

    $this->nameView = 'dashboard.terminal-create';

    $bases = $this->retornaBases($idusu);

    $ideven = $ideven;


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

    $revendedores = $this->retornaRevendedores($idbase, $idvendedor);

    $modelos = $this->retornaModelos();

    return view("{$this->nameView}",compact('idusu',
    'vendedores', 'menus', 'categorias', 'title', 'baseAll', 'ideven', 'ideven_default', 'bases', 'baseNome', 'idbase', 'vendedorNome', 'idvendedor', 'revendedores', 'modelos', 'menuMaisUsu'));


  // (Retirei o campo 'data')return view("{$this->nameView}",compact('idusu',
  //       'vendedores', 'menus', 'categorias', 'data','title', 'baseAll', 'ideven', 'ideven_default', 'bases', 'baseNome', 'idbase', 'vendedorNome', 'idvendedor', 'revendedores', 'modelos', 'menuMaisUsu'));
}
    /**
     * @return mixed
     */

    public function retornaModelos(){
        $data = DB::select(" 
           SELECT MODELO
                FROM MODELO_TER
                WHERE
                      MODELO <> 'SIMULADOR' AND
                      SITMOD = 'ATIVO'
           ORDER BY MODELO
        ");

        return $data;
    }

    public function retornaRevendedores($idbase, $idven){
        $data = DB::select(" 
             SELECT IDBASE, IDVEN, IDREVEN, IDEREVEN, NOMREVEN
                 FROM REVENDEDOR
                     WHERE
                         IDBASE = '$idbase' AND
                         IDVEN = '$idven' AND
                         SITREVEN = 'ATIVO'
                     ORDER BY NOMREVEN 
        ");

        return $data;
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



    //principal

    public function retornaTerminais($ideven){

        $valor = $this->retornaAdmin();

//        dd($valor);

        if ($valor != 'SIM'){
            $p = $this->retornaBasepeloIdeven($ideven);

            $data = DB::select(" 
                   SELECT TERMINAL.IDBASE, TERMINAL.IDVEN, TERMINAL.IDTER, TERMINAL.IDETER,
                          TERMINAL.SITTER, TERMINAL.MODELO, TERMINAL.VERSIS, TERMINAL.MODOPE, TERMINAL.MODOUSO, TERMINAL.DATATUTER, TERMINAL.HORATUTER, TERMINAL.SERIALCHIP,
                          REVENDEDOR.NOMREVEN, REVENDEDOR.CIDREVEN, REVENDEDOR.SIGUFS, REVENDEDOR.IDEREVEN,
                          VENDEDOR.NOMVEN
                    FROM TERMINAL
                        INNER JOIN VENDEDOR ON VENDEDOR.IDBASE = TERMINAL.IDBASE AND
                                               VENDEDOR.IDVEN = TERMINAL.IDVEN     
                        INNER JOIN BASE ON BASE.IDBASE = TERMINAL.IDBASE
                        LEFT JOIN REVENDEDOR ON REVENDEDOR.IDBASE = TERMINAL.IDBASE AND
                                                REVENDEDOR.IDVEN = TERMINAL.IDVEN AND
                                                REVENDEDOR.IDREVEN = TERMINAL.IDREVEN AND
                                                REVENDEDOR.SITREVEN = 'ATIVO'
                    WHERE
                          TERMINAL.MODELO <> 'SIMULADOR'
                      AND    TERMINAL.IDBASE = '$p->idbase'
                      AND TERMINAL.IDVEN = '$p->idven' 
                      AND TERMINAL.SITTER = 'ATIVO'
                    ORDER BY TERMINAL.DATATUTER DESC, TERMINAL.HORATUTER DESC
        ");
        } else {
            $data = DB::select(" 
                    SELECT TERMINAL.IDBASE, TERMINAL.IDVEN, TERMINAL.IDTER, TERMINAL.IDETER,
                           TERMINAL.SITTER, TERMINAL.MODELO, TERMINAL.VERSIS, TERMINAL.MODOPE, TERMINAL.MODOUSO, TERMINAL.DATATUTER, TERMINAL.HORATUTER, TERMINAL.SERIALCHIP,
                           REVENDEDOR.NOMREVEN, REVENDEDOR.CIDREVEN, REVENDEDOR.SIGUFS, REVENDEDOR.IDEREVEN,
                           VENDEDOR.NOMVEN
                    FROM TERMINAL
                    INNER JOIN VENDEDOR ON VENDEDOR.IDBASE = TERMINAL.IDBASE AND
                                           VENDEDOR.IDVEN = TERMINAL.IDVEN     
                    INNER JOIN BASE ON BASE.IDBASE = TERMINAL.IDBASE
                    LEFT JOIN REVENDEDOR ON REVENDEDOR.IDBASE = TERMINAL.IDBASE AND
                                            REVENDEDOR.IDVEN = TERMINAL.IDVEN AND
                                            REVENDEDOR.IDREVEN = TERMINAL.IDREVEN
                    WHERE
                      TERMINAL.MODELO <> 'SIMULADOR'
                    ORDER BY TERMINAL.DATATUTER DESC, TERMINAL.HORATUTER DESC
        ");

        }



        return $data;
    }


    public function retornaIndexFiltro($ideven, $situacao){

        $valor = $this->retornaAdmin();

        $corpo_select = "SELECT TERMINAL.IDBASE, TERMINAL.IDVEN, TERMINAL.IDTER, TERMINAL.IDETER,
                                TERMINAL.SITTER, TERMINAL.MODELO, TERMINAL.VERSIS, TERMINAL.MODOPE, TERMINAL.MODOUSO, TERMINAL.DATATUTER, TERMINAL.HORATUTER, TERMINAL.SERIALCHIP,
                                REVENDEDOR.NOMREVEN, REVENDEDOR.CIDREVEN, REVENDEDOR.SIGUFS, REVENDEDOR.IDEREVEN,
                                VENDEDOR.NOMVEN
                         FROM TERMINAL
                            INNER JOIN VENDEDOR ON VENDEDOR.IDBASE = TERMINAL.IDBASE AND
                                      VENDEDOR.IDVEN = TERMINAL.IDVEN     
                            INNER JOIN BASE ON BASE.IDBASE = TERMINAL.IDBASE
                            LEFT JOIN REVENDEDOR ON REVENDEDOR.IDBASE = TERMINAL.IDBASE AND
                                      REVENDEDOR.IDVEN = TERMINAL.IDVEN AND
                                      REVENDEDOR.IDREVEN = TERMINAL.IDREVEN AND
                                      REVENDEDOR.SITREVEN = 'ATIVO'
                            WHERE
                              TERMINAL.MODELO <> 'SIMULADOR' AND";

if($situacao == 'TODOS'){

     if ($valor != 'SIM'){
         $p = $this->retornaBasepeloIdeven($ideven);

         $c_where = " TERMINAL.IDBASE = '$p->idbase' AND
                      TERMINAL.IDVEN = '$p->idven'";

     }else {

            $c_where = " TERMINAL.IDETER <> 99999999";

     }

}else{
     if ($valor != 'SIM'){
        $p = $this->retornaBasepeloIdeven($ideven);

        $c_where = " TERMINAL.IDBASE = '$p->idbase' AND
                     TERMINAL.IDVEN = '$p->idven' AND
                     TERMINAL.SITTER = '$situacao'";

     }else{
         $c_where = " TERMINAL.SITTER = '$situacao'";
     }
}

    $sel = $corpo_select.$c_where;

    $data = DB::select($sel);

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

    public function retorna_idTer($idbase, $idven){
        $data = DB::select(" 
                SELECT MAX(TERMINAL.IDTER) AS IDTER
                     FROM TERMINAL
                 WHERE
                     TERMINAL.IDBASE = '$idbase' AND
                     TERMINAL.IDVEN  = '$idven'     
            ");
        return $data;
    }


    public function retorna_ideTer(){
        $data = DB::select(" 
                SELECT MAX(TERMINAL.IDETER) AS IDETER
                     FROM TERMINAL   
            ");
        return $data;
    }


    function gerar_senha($qtyCaraceters)
    {
     
        //Números aleatórios
        $numbers = (((date('Ymd') / 12) * 24) + mt_rand(800, 9999));
        $numbers .= 1234567890;
     
        //Junta tudo
        $characters = $numbers;
     
        //Embaralha e pega apenas a quantidade de caracteres informada no parâmetro
        $password = substr(str_shuffle($characters), 0, $qtyCaraceters);
     
        //Retorna a senha
        return $password;
    }


    public function createTerminalGo($ideven)
    {
        $dataForm = $this->request->all();


        /** @var $rules */
        $rules = [
            'idbase'    => 'required',
            'idven'     => 'required',
            'idreven'   => 'required',
            'limpen'    => 'required|numeric',
            'modouso' => 'required',
        ];

        $required = 'é um campo obrigatório';
        $min = 'deve ter no mínimo 3 caracteres';
        $max = 'deve ter no máximo 255 caracteres';
        $numeric = 'é um campo númerico';

        /** @var $mensagens */
        $mensagens = [
            'idreven.required'     => "REVENDEDOR {$required}",
            'idreven.numeric'      => "REVENDEDOR {$numeric}",
            'limpen.required'      => "Limite de Apostas Pendentes {$required}",
            'limpen.numeric'      => "Limite de Apostas Pendentes {$numeric}",
            'modouso.required'      => "Modo de uso {$required}",   
        ];

        /** validação do request */
        $this->validate($this->request, $rules, $mensagens);


        $idbase = $this->request->input('idbase');
        $idven = $this->request->input('idven');
        $idreven = $this->request->input('idreven');
        $modope = $this->request->input('modope');
        $limpen = $this->request->input('limpen');
        $modouso = $this->request->input('modouso');

        $modelo = ' ';
        $versis = '';
        $serial = '';
        $sitter = 'INATIVO';
        $solsenace = 'NAO';
        $tipoter = 'TERMINAL';
        $ideterori = 0;
        $idapo = 0;
        $serialchip = '';
        $datcad = date('Y/m/d');
        $idusucad = Auth()->user()->idusu;

        $idterminal = $this->retorna_idTer($idbase, $idven);
        $idter = ($idterminal[0]->idter) + 1; 

        $ideterminal = $this->retorna_ideTer();
        $ideter = ($ideterminal[0]->ideter) + 1; 

        $senha = '000'.$this->gerar_senha(3);
        $senhaini = $this->gerar_senha(8);
        $senhaconf = $this->gerar_senha(4);

        if($limpen == 0){
            $limpen = 10;
        }elseif($limpen > 80){
            $limpen = 30;
        }
        
        
        $dados_array = [

            "idbase" => $idbase,
            "idven" => $idven,
            "idter" => $idter,
            "ideter" => $ideter,
            "idreven" => $idreven,
            "modope" => $modope,
            "limpen" => $limpen, 
            "modouso" => $modouso,
            "modelo" => $modelo,
            "versis" => $versis,
            "serial" => $serial,
            "sitter" => $sitter,
            "solsenace" => $solsenace,
            "tipoter" => $tipoter,
            "ideterori" => $ideterori,
            "idapo" => $idapo,
            "serialchip" => $serialchip,
            "datcad" => $datcad,
            "idusucad" => $idusucad,
            "senha" => $senha,
            "senhaini" => $senhaini,
            "senhaconf" => $senhaconf,
        ];
       // dd($dados_array);

        $insert = DB::table('TERMINAL')->insert($dados_array);

        if($insert){
            $dados_array["ideterori"] = $dados_array["ideter"];
            $dados_array["idter"] = $dados_array["idter"] + 1;
            $dados_array["ideter"] = $dados_array["ideter"] + 1;
            $dados_array["modelo"] = 'SIMULADOR';
            $dados_array["versis"] = 'SIMULADOR';
            $dados_array["tipoter"] = 'SIMULADOR';
            $dados_array["sitter"] = 'ATIVO';  
        }

        $insert = DB::table('TERMINAL')->insert($dados_array);

        if($insert)
            return redirect("/admin/terminal/update/{$ideven}/{$idter}")
                ->with(['success'=>'Cadastro realizado com sucesso!']);
        else
            return redirect()
                ->route("terminal-create")
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

    

    public function edit($ideven, $idter){

        $idusu = Auth::user()->idusu;

        $vendedores = $this->retornaBasesUser($idusu);

        $menus = $this->retornaMenu($idusu);
        $menuMaisUsu = $this->retornaMenuMaisUsu($idusu);

        $categorias = $this->retornaCategorias($menus);

        $title = $this->title;

        $ideven_default = $this->returnWebControlData($idusu);

        $bases = $this->retornaBases($idusu);

        $ideven = $ideven;

        $baseAll = $this->retornaBasesAll($idusu);

        $this->nameView = 'dashboard.terminal-create';

        $valores = $baseAll;

        foreach ($valores as $val){

            if ($val->ideven == $ideven_default) {
                $baseNome  = $val->nombas;
                $idbase = $val->idbase;
                $vendedorNome = $val->nomven;
                $idvendedor = $val->idven;
            }
        }


        $dados = DB::table('TERMINAL')->where([
            ['idbase', '=', $idbase],
            ['idven', '=', $idvendedor],
            ['idter', '=', $idter]
        ])->first();

       // dd($dados);

        $dados->datcad = date('d/m/Y', strtotime( $dados->datcad));
        $dados->datalt = date('d/m/Y', strtotime( $dados->datalt));

        $revendedores = $this->retornaRevendedores($idbase, $idvendedor);

        $modelos = $this->retornaModelos();

        $usuAdmin = $this->retornaAdmin();

      // (Retirei o campo 'data' ) return view("{$this->nameView}",compact('idusu',
      //      'vendedores', 'menus', 'categorias', 'data','title', 'baseAll', 'ideven', 'ideven_default', 'bases', 'baseNome',
       //     'idbase', 'vendedorNome', 'idvendedor','dados', 'revendedores', 'modelos', 'usuAdmin', 'menuMaisUsu'));

            return view("{$this->nameView}",compact('idusu',
            'vendedores', 'menus', 'categorias', 'title', 'baseAll', 'ideven', 'ideven_default', 'bases', 'baseNome',
            'idbase', 'vendedorNome', 'idvendedor','dados', 'revendedores', 'modelos', 'usuAdmin', 'menuMaisUsu'));


    }



    public function update($ideven){

        $dataForm = $this->request->all();


      //  dd($dataForm);

        /** @var $rules */
        $rules = [
            'idbase'    => 'required',
            'idven'     => 'required',
            'idreven'   => 'required',
            'limpen'    => 'required|numeric',
            'modouso' => 'required',
        ];

        $required = 'é um campo obrigatório';
        $min = 'deve ter no mínimo 3 caracteres';
        $max = 'deve ter no máximo 255 caracteres';
        $numeric = 'é um campo númerico';

        /** @var $mensagens */
        $mensagens = [
            'idreven.required'     => "REVENDEDOR {$required}",
            'idreven.numeric'      => "REVENDEDOR {$numeric}",
            'limpen.required'      => "Limite de Apostas Pendentes {$required}",
            'limpen.numeric'      => "Limite de Apostas Pendentes {$numeric}",
            'modouso.required'      => "Modo de uso {$required}",   
        ];

        /** validação do request */
        $this->validate($this->request, $rules, $mensagens);


        $idbase = $this->request->input('idbase');
        $idven = $this->request->input('idven');
        $idter = $this->request->input('idter');
        $ideter = $this->request->input('ideter');
        $idreven = $this->request->input('idreven');
        $modope = $this->request->input('modope');
        $limpen = $this->request->input('limpen');
        $modouso = $this->request->input('modouso');
        $modelo = $this->request->input('modelo');
        $versis = $this->request->input('versis');
        $serial = $this->request->input('serial');
        $sitter = $this->request->input('sitter');
        $solsenace = $this->request->input('solsenace');
        $serialchip = $this->request->input('serialchip');
        $senha = $this->request->input('senha');
        $senhaini = $this->request->input('senhaini');
        $limpen = $this->request->input('limpen');

        $nomeReven = $this->request->input('teste');

     //   dd($nomeReven);

        $datalt = date('Y/m/d');
        $idusualt = Auth()->user()->idusu;

        if($limpen == 0){
            $limpen = 10;
        }elseif($limpen > 80){
            $limpen = 30;
        }
        
        
        $dados_array = [
            "idreven" => $idreven,
            "modope" => $modope,
            "limpen" => $limpen, 
            "modouso" => $modouso,
            "modelo" => $modelo,
            "versis" => $versis,
            "serial" => $serial,
            "sitter" => $sitter,
            "solsenace" => $solsenace,
            "serialchip" => $serialchip,
            "datalt" => $datalt,
            "idusualt" => $idusualt,
            "senha" => $senha,
            "senhaini" => $senhaini,
        ];

      //  dd($dados_array);

        $update = DB::table('TERMINAL')->where('ideter', $ideter)->update($dados_array);

            if($update){

                $update_terminal = Terminal::where(
                    ['TERMINAL.IDETERORI' => $ideter])
                    ->update([
                        'SITTER' => $sitter,
                        'IDREVEN' => $idreven,
                        'DATALT' => $datalt,
                        'IDUSUALT' => $idusualt
                        ]);        

            }

        if($update){

            $coman =  $this->gerarComandosTerminal($idbase, $idven, $idreven, 'rev:');

            return redirect("/admin/terminal/create/{$ideven}")
                ->with(['success'=>'Cadastro atualizado com sucesso!']);
        }
        else
            return redirect("/admin/terminal/update/{$ideven}/{$idter}")
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

}
