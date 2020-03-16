<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
//    \Illuminate\Support\Facades\Auth::LoginUsingId(2);
    return redirect()->route('admin.home');
});

//Route::get('/aluno', 'AlunoController@aluno');
//
//Route::get('/add-aluno', 'AlunoController@addAluno');
//Route::post('/add-aluno', 'AlunoController@addAlunoGo');
//
//
//Route::get('/tst/{cell}', 'AlunoController@pesquisar');
//Route::get('/editar-aluno/', 'AlunoController@editar');
//Route::post('/editar-aluno/{id}', 'AlunoController@editarGo');
//
//
//Route::get('/pesquisar/{cell}', 'AlunoController@pesquisar');
//
//Route::get('/sms/{idAluno}', 'AlunoController@sms');


Route::get('/home', function (){
    return redirect()->route('admin.home');
})->middleware('check.gmail');

Route::get('/teste', function () {
    return view('testes.feito');
});
Route::get('/expired', function () {
    return view('errors.expired');
});


Route::get('/suporte', function () {
    return view('layouts.suporte');
});

//Route::get('/data', 'TestController@index');
//Route :: get ( ' /teste ' , function() {
//    phpinfo();
//});

Route::group([
    'prefix' => 'admin',
    'as' => 'admin.'
], function (){

    Auth::routes();

    Route::group(['middleware' => ['can:access-admin', 'check.gmail']], function (){



        Route::get('/home', 'Home2Controller@index')->name('home');
        Route::post('/home/data', 'Home2Controller@index3');

        Route::get('/delete/{idAluno}', 'HomeController@delete')->name('delete');
//        Route::get('/show/{idAluno}', 'AlunoController@show')->name('show');
//        Route::get('/sms/{idAluno}', 'AlunoController@sms')->name('sms');
        Route::get('/gerarusers/', 'StandardController@gerarUser')->name('gerarusers');

        //resumo caixa
        Route::get('/resumocaixa/{idven}', 'ResumoCaixaController@index2')->name('retornaresumo');
        Route::post('/resumocaixa/{idven}', 'ResumoCaixaController@indexGo')->name('retornaresumo');
        Route::get('/resumocaixa/aposta_premiada/{idven}/{idbase}/{idreven}/{datini}/{datfim}',
            'ResumoCaixaController@retornaApostaPremios')->name('apostapremiada');

        Route::get('/resumocaixa/transmissao_resumo/{idven}/{idbase}/{idreven}/{datini}/{datfim}',
            'ResumoCaixaController@retornaTransmissoes')->name('transmissaoresumo');


        //movimentos de caixa
        Route::get('/movimentoscaixa/{idven}', 'MovimentosCaixaController@index2')->name('movimentocaixa');
        Route::post('/movimentoscaixa/{idven}', 'MovimentosCaixaController@indexGo')->name('movimentocaixa');
        Route::get('/movimentoscaixa2', 'MovimentosCaixaController@addCaixa')->name('addcaixa');
        Route::post('/movimentoscaixa2', 'MovimentosCaixaController@addCaixaGo')->name('addcaixa');


        //Movimentos Cobrador

         //movimentos de caixa
         Route::get('/movimentoscobrador/{idven}', 'MovimentosCobradorController@index2')->name('movimentocaixa');
         Route::post('/movimentoscobrador/{idven}', 'MovimentosCobradorController@indexGo')->name('movimentocaixa');
         Route::get('/movimentoscobrador/alteramov/{seqmov}', 'MovimentosCobradorController@alterarMovimentos')->name('alterarmovi');

         Route::get('/movimentoscobrador/confirma/{seqmov}', 'MovimentosCobradorController@confirmaMovimentos')->name('alterarmovi');
         Route::get('/movimentoscobrador/exclui/{seqmov}', 'MovimentosCobradorController@excluiMovimentos')->name('alterarmovi');

         Route::get('/movimentoscobrador/confirmaVarios', 'MovimentosCobradorController@confirmaCaixaVarios')->name('alterarmovi');


         Route::get('/movimentosCobrador2', 'MovimentosCobradorController@confirmaCaixa')->name('confirmacaixa');
         Route::post('/movimentosCobrador2', 'MovimentosCobradorController@confirmaCaixaGo')->name('confirmacaixa');
 
         Route::post('/movimentosCobradorVarios', 'MovimentosCobradorController@confirmaCaixaVarios')->name('confirmacaixavarios');
         Route::post('/movimentosCobradorAlterar', 'MovimentosCobradorController@alteraCaixaGo')->name('alteraCaixaGo');

         
//
//        Route::post('/movimentoscaixa2/', function()
//        {
//            return 'Success! ajax in laravel 5';
//        });

        //rotas de testes da aplicação
        Route::get('/resumocaixa2/', 'ResumoCaixaController@retornaResumoCaixa')->name('caixa');
        Route::post('/resumocaixa2/', 'ResumoCaixaController@retornaResumoCaixa')->name('caixa');

        Route::get('/resumorevendedor/', 'ResumoCaixaController@retornaRevendedor')->name('retornarevendedor');
        Route::get('/test/', 'TestController@test')->name('test');
        Route::get('/adduserweb/', 'StandardController@addUserWeb')->name('adduser');


        //transmissoes de apostas
        Route::get('/apostas/{idven}', 'ApostasController@index2')->name('retornaresumo');
        Route::post('/apostas/{idven}', 'ApostasController@indexGo')->name('retornaresumo');
        Route::get('/apostas/view/{idven}', 'ApostasController@viewPule')->name('view_pule');
        Route::post('/apostas/view/{idven}', 'ApostasController@viewPuleGo')->name('view_pule');
        Route::get('/apostas/cancel/{idven}', 'ApostasController@cancelPule')->name('cancel_pule');
        Route::post('/apostas/cancel/{idven}', 'ApostasController@cancelPuleGo')->name('cancel_pule');
        Route::post('/apostas/cancel/pule/{idven}', 'ApostasController@cancelAposta')->name('cancel_pule');
        Route::post('/apostas/cancelar/pule/{idven}', 'ApostasController@cancelarAposta')->name('cancelar_pule');

        Route::get('/apostas/cancelar/{idven}', 'ApostasController@cancelPule')->name('cancel_pule');
        Route::post('/apostas/cancelar/{idven}', 'ApostasController@cancelarPule')->name('cancelar_pule');

        Route::post('/apostas/view/transmissao/{idven}', 'ApostasController@retornaTransmissoesReven')->name('view_pule'); 
        Route::get('/apostas/view/{pule}/{idven}', 'ApostasController@retornaPule')->name('apostapremiada');    
        Route::get('/apostas/instantanea/{pule}', 'ApostasController@retornaPuleInstantanea')->name('apostapremiada');

        //Apostas Premiadas
        Route::get('/apostaspremiadas/{idven}', 'ApostasPremiadaController@index2')->name('apostapremiada');
        Route::post('/apostaspremiadas', 'ApostasPremiadaController@indexGo')->name('apostapremiadaGo');
        Route::post('/apostaspremiadas/paybet', 'ApostasPremiadaController@payBet')->name('apostaspremiadas');

        //RESULTADO DE SORTEIO

        Route::get('/resultadosorteio/{ideven}', 'ResultadoSorteioController@index2')->name('resultadosorteio');
        Route::post('/resultadosorteio/{ideven}', 'ResultadoSorteioController@indexGo')->name('resultadosorteioGo');


        //Manager Usuários
        Route::get('/manager/desktop/', 'StandardController@returnUsuarioDesktop')->name('manager-user-desktop');
        Route::get('/manager/web/', 'StandardController@returnUsuarioWeb')->name('manager-user-web');
        Route::get('/manager/web/create/{id}', 'StandardController@createUsuarioWeb')->name('create-user-web');
        Route::post('/manager/web/create/{id}', 'StandardController@createUsuarioWeb')->name('create-user-web');
        Route::post('/manager/web/update/{id}', 'StandardController@updateUsuarioWeb')->name('update-user-web');
        Route::get('/manager/web/insert/', 'StandardController@insertUsuarioWeb')->name('insert-user-web');
        Route::post('/manager/web/insert/', 'StandardController@insertUsuarioWeb')->name('insert-user-web');
//        Route::post('/manager/web/insert2/', 'StandardController@WebGo');

        Route::get('/manager/web-go/', 'StandardController@webinsert');
        Route::post('/manager/web-go/', 'StandardController@webinsertGo');



        //Senha do Dia
        Route::get('/senhadodia/{idven}', 'SenhaDiaController@index2')->name('senhadia');

        //Descarga Envidas
        Route::get('/descargasenviadas/{idven}', 'DescargasEnviadasController@index2')->name('descargaenviadas');
        Route::post('/descargasenviadas/{idven}', 'DescargasEnviadasController@indexGo')->name('descargaenviadas');
        Route::get('/descargasenviadas/view/{idven}/{idreven}/{idter}/{idapo}/{numpule}/{seqpalp}', 'DescargasEnviadasController@returnInfoDescEnv')->name('descargaenviadas');
        Route::get('/descargasenviadas/infoaposta/{numpule}/{seqpalp}', 'DescargasEnviadasController@returnInfoAposta')->name('infoaposta');
        Route::get('/descargasenviadas/infoapostadescarregadas/{numpule}/{seqpalp}/{seqdes}', 'DescargasEnviadasController@returnApostaDescarregadas')->name('infoapostadescarregadas');


        //Cadastro de usuário web

        Route::get('/acesso/desktop/{id}', 'AcessoWebController@indexDesktop')->name('index.desktop');
        Route::get('/acesso/web/', 'AcessoWebController@indexWeb')->name('index.web');
        Route::get('/acesso/web/create/{id}', 'AcessoWebController@create')->name('create.get');
        Route::post('/acesso/web/create/data/{id}', 'AcessoWebController@createGo')->name('create.post');
        Route::post('/acesso/web/update/{id}', 'AcessoWebController@update')->name('update.post');


        //REVENDEDOR
        
        Route::get('/revendedor/create/{idven}', 'RevendedorController@index2')->name('revendedor');
        Route::post('/revendedor/{idven}', 'RevendedorController@indexFiltro')->name('revendedorFiltro');

        Route::post('/revendedor/{idven}/{idreven}', 'RevendedorController@indexFiltro')->name('revendedorFiltro');
        Route::get('/revendedor/create/{idven}/add', 'RevendedorController@createRevendedor')->name('revendedor-create');
        Route::get('/revendedor/update/{ideven}/{idereven}', 'RevendedorController@edit')->name('revendedor.edit');
        Route::post('/revendedor/update/{ideven}/{idereven}', 'RevendedorController@update')->name('revendedor.update');
        Route::post('/revendedor/create/{idven}/add', 'RevendedorController@createRevendedorGo')->name('revendedor.create');
        Route::get('/revendedor/base/{idbase}', 'RevendedorController@retornaBase')->name('retorna-base');
        Route::get('/revendedor/vendedor/{idbase}/{idven}', 'RevendedorController@retornaVend')->name('retorna-vend');
        Route::get('/revendedor/limite/{idbase}/{idven}/{idreven}', 'RevendedorController@alterarLimite')->name('limite-credito');
        Route::post('/revendedor/limite/{idbase}/{idven}/{idreven}', 'RevendedorController@alterarLimiteGo')->name('limite-credito-go');
        Route::get('/revendedor/comissao/{idbase}/{idven}/{idreven}', 'RevendedorController@alterarComissao')->name('comissao');
        Route::post('/revendedor/comissao/{idbase}/{idven}/{idreven}', 'RevendedorController@alterarComissaoGo')->name('comissao-go');
        Route::get('/revendedor/l/{idbase}/{idven}/{idreven}/{idlot}/{operacao}', 'RevendedorController@ativarDesativarLoteriasReven')->name('loterias-reven');
        
        Route::get('/revendedor/{ideven}/{idereven}', 'RevendedorController@dadosModalRev')->name('retornaCadastro');

        
        //TERMINAL
        Route::get('/terminal/create/{ideven}', 'TerminalController@index2')->name('terminal');
        Route::post('/terminal/{ideven}', 'TerminalController@indexFiltro')->name('terminalFiltro');
        Route::get('/terminal/create/{ideven}/add', 'TerminalController@createTerminal')->name('terminal-create');
        Route::post('/terminal/create/{ideven}/add', 'TerminalController@createTerminalGo')->name('terminal-create-go');
        Route::get('/terminal/update/{ideven}/{idter}', 'TerminalController@edit')->name('terminal-edit');
        Route::post('/terminal/update/{ideven}/{idter}', 'TerminalController@update')->name('terminal-update');

    
    //HISTORICO DE VENDAS revendedor
    Route::get('/historicovendas/{ideven}/{idreven}', 'HistoricoVendasController@indexhistorico')->name('retornaresumo');
   
    Route::get('/historicovendas/{idven}', 'HistoricoVendasController@index2')->name('retornaresumo');
    Route::post('/historicovendas/{idven}', 'HistoricoVendasController@indexGo')->name('retornaresumo');
   
    Route::get('/historicovendas/aposta_premiada/{idven}/{idbase}/{idreven}/{datmov}',
        'HistoricoVendasController@retornaApostaPremios')->name('apostapremiada');

    Route::get('/historicovendas/transmissao_resumo/{idven}/{idbase}/{idreven}/{datini}/{datfim}',
        'HistoricoVendasController@retornaTransmissoes')->name('transmissaoresumo');
    Route::post('/historicovendas/teste/{idven}', 'HistoricoVendasController@indexTestes')->name('retornaresumo');


    //HISTORIO VENDAS MENSAL
    Route::get('/historicovendasmensal/{ideven}/{idreven}', 'HistoricoVendasMensalController@indexhistorico')->name('retornaresumo');
   
    Route::get('/historicovendasmensal/{idven}', 'HistoricoVendasMensalController@index2')->name('retornaresumo');
    Route::post('/historicovendasmensal/{idven}', 'HistoricoVendasMensalController@indexGo')->name('retornaresumo');
   
    Route::get('/historicovendasmensal/aposta_premiada/{idven}/{idbase}/{idreven}/{datmov}/{datmovano}',
        'HistoricoVendasMensalController@retornaApostaPremios')->name('apostapremiada');

    Route::get('/historicovendasmensal/transmissao_resumo/{idven}/{idbase}/{idreven}/{datini}/{datfim}',
        'HistoricoVendasMensalController@retornaTransmissoes')->name('transmissaoresumo');
    

    Route::post('/historicovendasmensal/{idven}', 'HistoricoVendasMensalController@indexTestes')->name('retornaresumo');

    //Consulta Resultado instantanea
    Route::get('/consultaresultadoinst/{idven}', 'ConsultaResultadoInstantaneaController@index2')->name('retornaresultado');
    Route::post('/consultaresultadoinst/{idven}', 'ConsultaResultadoInstantaneaController@indexGo')->name('retorresultado');
    Route::get('/consultaresultadoinst/retornaSorteioIntantanea/{numpule}','ConsultaResultadoInstantaneaController@retornaSorteioIntantanea')->name('sorteioInstantanea');

    // Saldo Intantanea
    Route::get('/saldoinstantanea/{idven}', 'SaldoInstantaneaController@index2')->name('saldoinst');
    Route::post('/saldoinstantanea/{idven}', 'SaldoInstantaneaController@indexGo')->name('saldoinst');

    //Mensagens Terminal
    Route::get('/mensagemrecebida/{idven}', 'mensagemrecebidaController@index2')->name('msgenv');
     //Mensagens Terminal
     Route::post('/mensagemrecebida/{idven}', 'mensagemrecebidaController@indexGo')->name('msgenv');
    
    
    });



    
    //LIMPAR CACHE LARAVEL
    Route::get('/clear-cache', function() {
        Artisan::call('cache:clear');
        return "Cache is cleared";
    });

});

