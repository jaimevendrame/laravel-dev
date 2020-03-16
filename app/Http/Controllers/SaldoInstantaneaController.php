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

class SaldoInstantaneaController extends ApostasController
{
    protected $model;
    protected $nameView = 'dashboard.saldoinstantanea';
    protected $data;
    protected $title = 'Consulta Saldo Instantânea';
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
  //      $data = $this->retornaApostasInstantane($ideven);
        $title = $this->title;
        $baseAll = $this->retornaBasesAll($idusu);
        $ideven_default = $this->returnWebControlData($idusu);
        $saldo = $this->saldoInstantanea($ideven);


        return view("{$this->nameView}",compact('idusu',
            'user_base', 'user_bases', 'usuario_lotec', 'vendedores', 'menus', 'categorias', 'title', 'baseAll', 'ideven', 'ideven_default', 'menuMaisUsu','saldo'));
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
        $title = $this->title;
        $saldo = $this->saldoInstantanea($ideven);
        $baseAll = $this->retornaBasesAll($idusu);

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

        $ideven_default = $this->returnWebControlData($idusu);
        $alterarSaldo = $this->alteraSaldoInstantanea($ideven);

                return view("{$this->nameView}",compact('idusu',
            'user_base', 'user_bases', 'usuario_lotec', 'vendedores', 'menus', 'categorias', 'saldo','title',
                    'baseAll','ideven2', 'in_ativos', 'ideven_default', 'menuMaisUsu','alterarSaldo'));
    }

    
   
public function saldoInstantanea($ideven){

    $p_query = '';

    $idusu = Auth::user()->idusu;
    $admin = Usuario::where('idusu', '=', $idusu)->first();

    if ($admin->inadim != 'SIM'){
        $p = $this->retornaBasepeloIdeven($ideven);
        $p_query = "SALDO_INSTANTANEA.IDBASE = '$p->idbase'
            AND SALDO_INSTANTANEA.IDVEN = '$p->idven'";
    }




    $data = DB::select (
        "SELECT SALDO_VENDA, SALDO_PREMIO, SALDO_LIQUIDO, PERLIB
            FROM SALDO_INSTANTANEA
            WHERE
            $p_query
        "



    );
//dd($data);
    return $data;
}

public function alteraSaldoInstantanea($ideven){
 
    $idusu = Auth::user()->idusu;
    $admin = Usuario::where('idusu', '=', $idusu)->first();

    if ($admin->inadim != 'SIM'){
       $p = $this->retornaBasepeloIdeven($ideven);
        $idbase = $p->idbase;
        $idven = $p->idven;
    }
    //Dados retorno request view
    $saldoVenda = $this->request->input('saldo_venda');
    $saldoPremio = $this->request->input('saldo_premio');
    $liquido = $this->request->input('liquido');
    $perLiberar = $this->request->input('per_liberar');
    //$id_ven = $this->request->input('id_ven');

    $saldoVenda = str_replace('.', '', $saldoVenda);
    $saldoPremio = str_replace('.', '', $saldoPremio);
    $liquido = str_replace('.', '', $liquido);

        $dados_array = [    
            "saldo_venda" =>  floatval(str_replace(',', '.', $saldoVenda)), 
            "saldo_premio" => floatval(str_replace(',', '.', $saldoPremio)), 
            "saldo_liquido" =>floatval(str_replace(',', '.', $liquido)),
            "perlib" => $perLiberar,
            
        ];
    //  dd($dados_array, $idven, $idbase);
        $update = DB::table('SALDO_INSTANTANEA')->where([
            ['idbase', '=', $idbase],
            ['idven', '=', $idven],
        
        ])->update($dados_array);

        

    if($update)
    return redirect("/admin/saldoinstantanea/$p->idven")
         ->with(['success'=>' Confirmado com sucesso!']);
else
    return redirect("/admin/saldoinstantanea/$p->idven")
       ->withErrors(['errors' => 'Falha ao atualizar'])
       ->withInput();


}

}