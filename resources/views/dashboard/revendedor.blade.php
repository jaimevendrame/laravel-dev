@extends('dashboard.templates.app')

@section('content')

    <div class="section">
        <div class="row">
            <div class="col s12">
                
                    @if( Session::has('success'))
                        <div class="row hide-msg">
                            <div class="col s12 m12">
                                <div class="card-panel green hide-msg">
                                    <ul>
                                        <h4><i class="icon fa fa-warning"></i> {{Session::get('success')}}</h4>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                   
                            <div class="row ">
                                <div class="input-field col s12 m8">
                                    
                                    <a href="/admin/revendedor/create/{{$ideven}}/add" class="btn ">NOVO</a>
                                </div>

                                <form class="form-group" id="form-filtro-reven" method="post" action="/admin/revendedor/{{$ideven}}" enctype="multipart/form-data">
                                    {{ csrf_field() }}

                                   @php
                                      if(session()->has('sitReven')){ 
                                         $idsit = session('sitReven');
                                      }
                                   @endphp
        
                                    <div class="input-field col s12 m2 right-align">
                                        <select  name="sel_situacao">
                                            {{--<option value="4" disabled selected> </option>--}}
                                            <option value="0" @if(!empty($idsit)) {{ $idsit == 0  ? 'selected' : '' }}  @endif>Ativo</option>
                                            <option value="1" @if(!empty($idsit)) {{ $idsit == 1  ? 'selected' : '' }}  @endif>Inativo</option>
                                            <option value="2" @if(!empty($idsit)) {{ $idsit == 2  ? 'selected' : '' }}  @endif>Todos</option>
                                        </select>
                                        <label>Situação</label>
                                    </div>
  
                                    <div class="input-field col s12 m2 right-align">
                                        <button class="btn waves-effect waves-light" type="submit" name="action">Mostrar
                                            
                                        </button>
                                    </div>
                                
                                </form>  
                            </div>   
                        
                    @if(!empty($data))
                            <table class="mdl-data-table " id="example"  cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                       
                                        <th>ID</th>
                                        <th>NOME REVENDEDOR</th>
                                        <th>CIDADE</th>
                                        <th>UF</th>
                                        <th>SITUAÇÃO</th>
                                        <th>COBRADOR</th>
                                        <th>LIMITE CRED.</th>
                                        <th>VENDEDOR</th>
                                        <th>NOME VENDEDOR</th>
                                    </tr>
                            </thead>
                                <tbody>
                                @forelse($data as $d)
                        
                                    <tr>  
                                        <td><a class="botao-transmissoes btn modal-trigger btn_grid btn_grid_100px" href="/admin/revendedor/update/{{$ideven}}/{{$d->idereven}}">{{ $d->idereven}}</a></td>
                                        <td>{{ $d->nomreven }}</td>
                                        <td>{{ $d->cidreven }}</td>
                                        <td>{{ $d->sigufs }}</td>
                                        <td @if($d->sitreven == 'INATIVO') class='red-text text-darken-2' @endif>{{ $d->sitreven }}</span></td>                                                                        
                                        <td>{{ $d->nomcobra }}</td>
                                        <td><b>{{number_format($d->limcred, 2, ',' , '.')}}</b></td>
                                        <td>{{ $d->idven }}</td>
                                        <td>{{ $d->nomven }}</td>
                                    </tr>
                                    
                                    @empty
                                        <tr>
                                            <p>nenhum registro encontrado!</p>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                
                                </tfoot>
                            </table>
                            
                            @php
                           
                        @endphp
                            @else
                            <p>Nenhum registro encontrado!</p>

                         
                            
                    @endif
                      
                </div>
            </div>
        </div>
    </div>
    
   
    </form>  
</div>

<div id="myModal" class="modal modal2 modal-fixed-footer" >
    <div class="right-align">
            <a href="#!" class=" btn modal-action modal-close waves-effect waves-light red "><i class=" Tiny material-icons">close</i></a>
        </div>
        <div class="row">
            <div class="col s12">             
                    @if( Session::has('success'))
                        <div class="row hide-msg">
                            <div class="col s12 m12">
                                <div class="card-panel green hide-msg">
                                    <ul>
                                        <h4><i class="icon fa fa-warning"></i> {{Session::get('success')}}</h4>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div id="aposta" class="modal">
                        <div class="col s12 m6 l10">
                            <h5>Cadastro de Revendedor:</h5>
                        </div>
                        
                        <div class="right-align">
                                <a href="#!" class=" btn modal-action modal-close waves-effect waves-light red "><i class=" Tiny material-icons">close</i></a>
                        </div>
                        </div>
                    
                <div class="row">
                     <div class="card grey lighten-2">
                        <div class="card-content black-text">
                            @if(isset($dados))
                                <form method="post" action="/admin/revendedor/update/{{$ideven}}/{{$dados->idereven}}" class="col m12">
                                    {{csrf_field()}}
                              {{--  @else
                                <form method="post" action="/admin/revendedor/create/{{$ideven}}/add" class="col m12"> 
                                    {{csrf_field()}} --}}
                            @endif

                                <div class="row">
                                    <div class="col s12 m6 l6">
                                        <ul class="tabs">
                                            <li class="tab col s6 m6 l6"><a class="active" href="#cadastro">Cadastro</a></li>
                                            <li class="tab col s6 m6 l6"><a href="#opcionais">Informações Adicionais</a></li>
                                        </ul>
                                    </div> 
                                </div>
                                <div class="row"></div>
                                    <div id="cadastro" class="col s12">
                                        <div class="row">
                                            <div class="input-field col s4 m8 l4">
                                                 <label id="lnomebase" class="active" for="nomebase">Base</label>
                                                    <input id="nomebase" type="text" class="validate"  readonly value="{{$dados->baseNome or $baseNome}}">
                                                    @if ($errors->has('nomebase'))
                                                        <span class="alert-validation">
                                                            <strong>{{ $errors->first('nomebase') }}</strong>
                                                        </span>
                                                    @endif
                                                    <input id="idbase" type="hidden" name="idbase" class="validate"  readonly value="{{$dados->idbase or $idbase}}">
                                            </div>
                                       
                                            <div class="input-field col s4 m8 l4">
                                                    <label id="lnomven" class="active" for="nomven">Vendedor</label>
                                                    <input id="nomven" type="text" class="validate" disabled readonly value="{{$dados->vendedorNome or $vendedorNome}}">
                                                
                                                    @if ($errors->has('nomven'))
                                                        <span class="alert-validation">
                                                            <strong>{{ $errors->first('nomven') }}</strong>
                                                        </span>
                                                    @endif
                                            </div>
                                                <input id="idven" type="hidden" name="idven" class="validate" readonly value="{{$dados->idven or $idvendedor}}">
                                        
                                            <div class="input-field col s4 m12 l4">
                                                    <input id="idereven" type="text" class="validate" name="idereven"  readonly value="{{$dados->idereven or old('idereven')}}">
                                                    <label class="active" for="idereven">Identificação única</label>
                                                    @if ($errors->has('idereven'))
                                                        <span class="alert-validation">
                                                            <strong>{{ $errors->first('idereven') }}</strong>
                                                        </span>
                                                    @endif
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-field col s12 m12 l7">
                                                <input id="nomreven" type="text" class="validate" name="nomreven" value="{{$dados->nomreven or old('nomreven')}}">
                                                <label class="active" for="nomreven">Nome</label>
                                                @if ($errors->has('nomreven'))
                                                    <span class="alert-validation">
                                                        <strong>{{ $errors->first('nomreven') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                    
                                            <div class="input-field col s12 m8 l3">
                                                <input id="cidreven" type="text" class="validate" name="cidreven" value="{{$dados->cidreven or old('cidreven')}}">
                                                <label class="active"for="cidreven">Cidade</label>
                                                @if ($errors->has('cidreven'))
                                                    <span class="alert-validation">
                                                        <strong>{{ $errors->first('cidreven') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="input-field col s12 m4 l2">
                                                <select name="sigufs">
                                                    <option value="" disabled selected>Selecione</option>

                                                    @foreach($ufs as $key => $value)
                                                        <option value="{{ $key }}"
                                                                @if(($key == old('sigufs')) )
                                                                selected
                                                                @elseif(isset($dados) && $dados->sigufs == $key)
                                                                    selected
                                                                @endif
                                                        >{{ $key }}</option>
                                                    @endforeach
                                                </select>
                                                <label>UF</label>
                                                @if ($errors->has('sigufs'))
                                                    <span class="alert-validation">
                                                        <strong>{{ $errors->first('sigufs') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-field col s6 m6 l3">
                                                @if(isset($dados))
                                                   <input id="limcred" type="text" class="validate" name="limcred" readonly value="{{isset($dados) ? number_format($dados->limcred, 2, ',', '.') : old('limcred')}}">  
                                                @else
                                                   <input id="limcred" type="text" class="validate" name="limcred" value="{{isset($dados) ? number_format($dados->limcred, 2, ',', '.') : old('limcred')}}">
                                                @endif
                                                <label class="active" for="limcred">Limite de Crédito</label>
                                                @if ($errors->has('limcred'))
                                                    <span class="alert-validation">
                                                        <strong>{{ $errors->first('limcred') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            @if(isset($dados))
                                               <div class="input-field col s6 m2 l2">
                                                    <a href="#modallimite" class="btn waves-effect waves-light btn-small" style="width: 190px;">Alterar Limite</a>                                              

                                               {{--   <a href="/admin/revendedor/limite/{{$dados->idbase}}/{{$dados->idven}}/{{$dados->idreven}}" class="btn waves-effect waves-light btn-small" style="width: 190px;">Alterar Limite</a>        --}}                                      
                                               </div> 
                                            @endif
                                            <div class="input-field col s12 m6 l2">
                                            </div>
                                        
                                            <div class="input-field col s6 m6 l3">
                                            @if(isset($dados))
                                                <input id="vlrcom" type="text" class="validate" name="vlrcom" readonly value="{{isset($dados) ? number_format($dados->vlrcom, 2, ',', '.') : old('vlrcom')}}">
                                            @else
                                                <input id="vlrcom" type="text" class="validate" name="vlrcom" value="{{isset($dados) ? number_format($dados->vlrcom, 2, ',', '.') : old('vlrcom')}}">
                                            @endif
                                                <label class="active" for="vlrcom">Comissão Padrão %</label>
                                                @if ($errors->has('vlrcom'))
                                                    <span class="alert-validation">
                                                        <strong>{{ $errors->first('vlrcom') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            @if(isset($dados))
                                               <div class="input-field col s6 m2 l2">
                                                  <a href="#modalComissao" class="btn waves-effect waves-light btn-small" style="width: 190px;">Alterar Comissão</a>                                              
                                               </div> 
                                            @endif
                                        </div>
                                        <div class="row">
                                            <div class="input-field col s12 m6 l4">
                                                <input id="vlrmaxpalp" type="text" class="validate" name="vlrmaxpalp" value="{{isset($dados) ? number_format($dados->vlrmaxpalp, 2, ',', '.') : old('vlrmaxpalp')}}">
                                                <label class="active" for="vlrmaxpalp">Vlr. Máximo p/ Palpite</label>
                                                @if ($errors->has('vlrmaxpalp'))
                                                    <span class="alert-validation">
                                                        <strong>{{ $errors->first('vlrmaxpalp') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        
                                            <div class="input-field col s12 m6 l4">
                                                <input id="vlrblopre" type="tel" class="validate" name="vlrblopre" value="{{isset($dados) ? number_format($dados->vlrblopre, 2, ',', '.') : old('vlrblopre')}}">
                                                <label class="active" for="vlrblopre">Bloquear prêmio maior que</label>
                                                @if ($errors->has('vlrblopre'))
                                                    <span class="alert-validation">
                                                        <strong>{{ $errors->first('vlrblopre') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        
                                            <div class="input-field col s12 m6 l4">
                                                <input id="limlibpre" type="text" class="validate" name="limlibpre" value="{{isset($dados) ? $dados->limlibpre : old('limlibpre')}}">
                                                <label class="active" for="limlibpre">Limite de dias para prêmio</label>
                                                @if ($errors->has('limlibpre'))
                                                    <span class="alert-validation">
                                                        <strong>{{ $errors->first('limlibpre') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-field col s12 m6 l4">
                                                <select name="sitreven">
                                                    @if(isset($dados))
                                                    <option value="ATIVO" {{ $dados->sitreven == 'ATIVO'  ? 'selected' : '' }} >ATIVO</option>
                                                    <option value="INATIVO" {{ $dados->sitreven == 'INATIVO'  ? 'selected' : '' }} >INATIVO</option>
                                                    <option value="BLOQUEADO" {{ $dados->sitreven == 'BLOQUEADO'  ? 'selected' : '' }} >BLOQUEADO</option>
                                                        @else
                                                        <option value="ATIVO" selected >ATIVO</option>
                                                        <option value="INATIVO" >INATIVO</option>
                                                        <option value="BLOQUEADO"  >BLOQUEADO</option>
                                                        @endif
                                                </select>
                                                <label>Situação</label>
                                                @if ($errors->has('sitreven'))
                                                    <span class="alert-validation">
                                                        <strong>{{ $errors->first('sitreven') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>


                                    
                                    <div id="opcionais" class="col s12">
                                        <div class="row">
                                            <div class="input-field col s12 m6 l2">
                                                <input id="idreven" type="text" class="validate" name="idreven" readonly value="{{$dados->idreven or old('idreven')}}">
                                                <label class="active" for="idreven">Id Revendedor</label>
                                            </div>
                                      
                                            <div class="input-field col s12 m6 l6">
                                                <input id="endreven" type="text" class="validate" name="endreven" value="{{$dados->endreven or old('endreven')}}">
                                                <label class="active" for="endreven">Endereço</label>
                                            </div>
                                        
                                            <div class="input-field col s12 m6 l4">
                                                <input id="baireven" type="text" class="validate" name="baireven" value="{{$dados->baireven or old('baireven')}}">
                                                <label class="active" for="baireven">Bairro</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-field col s12 m6 l2">
                                                <input id="celreven" type="text" class="validate" name="celreven" value="{{$dados->celreven or old('celreven')}}">
                                                <label class="active" for="celreven">Celular</label>
                                            </div>
                                       
                                            <div class="input-field col s12 m6 l6">
                                                <input id="obsreven" type="text" class="validate" name="obsreven" value="{{$dados->obsreven or old('obsreven')}}">
                                                <label class="active" for="obsreven">Observação</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-field col s12 m6 l4">
                                                <select name="idcobra">
                                                    <option value="" disabled selected>Selecione</option>

                                                    @forelse($cobrador as $c)
                                                        <option value="{{ $c->idcobra }}"
                                                                @if(isset($dados->idcobra) && $dados->idcobra == $c->idcobra )
                                                                selected
                                                                @endif
                                                        >{{ $c->nomcobra }}</option>
                                                    @empty
                                                        <option value="" disabled selected>Nenhum vendedor</option>
                                                    @endforelse
                                                </select>
                                                <label>Cobrador</label>
                                            </div>
                                     
                                            <div class="input-field col s12 m6 l4">
                                            <select name="insolaut">
                                                @if(isset($dados))
                                                    <option value="SIM" {{ $dados->insolaut == 'SIM'  ? 'selected' : '' }} >SIM</option>
                                                    <option value="NAO" {{ $dados->insolaut == 'NAO'  ? 'selected' : '' }} >NAO</option>
                                                        @else
                                                        <option value="SIM">SIM</option>
                                                        <option value="NAO" selected>NÃO</option>
                                                        @endif
                                                </select>
                                                <label>Solicita Autênciacação p/ Liberar Prêmio</label>
                                            </div>
                                        
                                            <div class="input-field col s12 m6 l4">
                                            <select name="in_impapo">
                                                @if(isset($dados))
                                                    <option value="SIM" {{ $dados->in_impapo == 'SIM'  ? 'selected' : '' }} >SIM</option>
                                                    <option value="NAO" {{ $dados->in_impapo == 'NAO'  ? 'selected' : '' }} >NAO</option>
                                                        @else
                                                        <option value="SIM">SIM</option>
                                                        <option value="NAO" selected>NÃO</option>
                                                        @endif
                                                </select>
                                                <label>Permissão Reimprimir Aposta</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-field col s12 m6 l3">
                                                <select name="in_canapo">
                                                @if(isset($dados))
                                                    <option value="SIM" {{ $dados->in_canapo == 'SIM'  ? 'selected' : '' }} >SIM</option>
                                                    <option value="NAO" {{ $dados->in_canapo == 'NAO'  ? 'selected' : '' }} >NAO</option>
                                                        @else
                                                        <option value="SIM">SIM</option>
                                                        <option value="NAO" selected>NÃO</option>
                                                        @endif
                                                </select>
                                                <label>Permissão Terminal Cancelar Aposta</label>
                                            </div>
                                        
                                            <div class="input-field col s12 m6 l3">
                                                <select name="in_impdireta">
                                                  @if(isset($dados))
                                                    <option value="SIM" {{ $dados->in_impdireta == 'SIM'  ? 'selected' : '' }} >SIM</option>
                                                    <option value="NAO" {{ $dados->in_impdireta == 'NAO'  ? 'selected' : '' }} >NAO</option>
                                                        @else
                                                        <option value="SIM">SIM</option>
                                                        <option value="NAO" selected>NÃO</option>
                                                        @endif
                                                </select>                           
                                                <label>Impressão direta do Bilhete</label>
                                            </div>
                                        
                                            <div class="input-field col s12 m6 l3">
                                                <select name="loctrab">
                                                    <option value="" disabled selected>Selecione</option>
                                                    @foreach($lc as $key => $value)
                                                        <option value="{{ $key }}"
                                                                @if(($key == old('loctrab')) )
                                                                selected
                                                                @elseif(isset($dados) && $dados->loctrab == $key)
                                                                selected
                                                                @endif
                                                        >{{ $key }}</option>
                                                    @endforeach
                                                </select>
                                                <label>Local de Trabalho</label>
                                            </div>
                                            <div class="input-field col s12 m6 l3">
                                                <select name="in_impdireta">
                                                  @if(isset($dados))
                                                    <option value="SIM" {{ $dados->in_impdireta == 'SIM'  ? 'selected' : '' }} >SIM</option>
                                                    <option value="NAO" {{ $dados->in_impdireta == 'NAO'  ? 'selected' : '' }} >NAO</option>
                                                        @else
                                                        <option value="SIM">SIM</option>
                                                        <option value="NAO" selected>NÃO</option>
                                                        @endif
                                                </select>                           
                                                <label>Realiza Recarga de Celular</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-field col s12 m6 l3">
                                                <input id="datcad" type="text" class="datepicker" name="datcad" value="{{ $dados->datcad  or \Carbon\Carbon::now()->format('d/m/Y')}}">

                                                <label class="active" for="datcad">Data Cadastro</label>
                                            </div>
                                    
                                            <div class="input-field col s12 m6 l3">
                                                <input id="usercad" type="text" class="validate" name="idusucad" value="{{$dados->idusucad or Auth()->user()->idusu}}" readonly>
                                                <label class="active" for="usercad">Usuário Cadastrado</label>
                                            </div>
                                       
                                            <div class="input-field col s12 m6 l3">
                                                <input id="datalt" type="text" class="" name="datalt" value="{{$dados->datalt or old('datalt')}}" readonly>
                                                <label class="active" for="datalt">Data última Alteração</label>
                                            </div>
                                       
                                            <div class="input-field col s12 m6 l3">
                                                <input id="useralt" type="text" class="validate" name="idusualt" value="{{$dados->idusualt or old('idusualt')}}" readonly>
                                                <label class="active" for="useralt">Usuário Alteração</label>
                                            </div>
                                        </div>
                                        <!-- o isset loterias -->
                                        @if(isset($loterias))

                                        <div class="row">
                                            <div class="input-field col s12 m6 l6">
                                                <table class="mdl-data-table " id="example2"  cellspacing="0" width="100%">
                                                    <thead><tr>
                                                            <th COLSPAN="2">LOTERIAS</th>
                                                            <th>AÇÃO</th>
                                                    </tr></thead>
                                                    <tbody>
                                                    @forelse($loterias as $lv)
                                                        <tr>
                                                            <td>{{ $lv->deslot }}</td>
                                                            <td>{{ $lv->sitlig }}</td>
                                                            <td>
                                                              @if($lv->sitlig == 'ATIVO')
                                                                 @php
                                                                    $ope = 'INATIVO';
                                                                 @endphp  
                                                                <a href="/admin/revendedor/l/{{$dados->idbase}}/{{$dados->idven}}/{{$dados->idreven}}/{{$lv->idlot}}/{{$ope}}" class="btn-floating btn-small waves-effect waves-light red "><i class="material-icons">remove</i>Inativar</a>  
                                                              @else
                                                                 @php
                                                                    $ope = 'ATIVO';
                                                                 @endphp            
                                                                <a href="/admin/revendedor/l/{{$dados->idbase}}/{{$dados->idven}}/{{$dados->idreven}}/{{$lv->idlot}}/{{$ope}}" class="btn-floating btn-small waves-effect waves-light"><i class="material-icons">add</i>Ativar</a>  
                                                              @endif
                                                                                                          
                                                            </td>  
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <p>nenhum registro encontrado!</p>
                                                        </tr>
                                                    @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                         @endif


                                    </div>
                                </div>

                                <div class="row">
                                        <div class="col s8  m10 l10">
                                        </div>
                                    <div class="col s4 m2 l2">
                                        <button class="btn waves-effect waves-light" type="submit" >Confirmar
                                                <i class="material-icons right">send</i>
                                        </button>
                                            <br>
                                    </div>
                                </div>

                                <div class="row">
                                </div>
                              
                            </form>{{-- end form !--}}
                        </div>
                        
            </div>
            </div>
        </div>
    </div>

    {{-- Modal altera Limite--}}
    <div id="modallimite" class="modal" >

            <div class="right-align">
                    <a href="#!" class=" btn modal-action modal-close waves-effect waves-light red "><i class=" Tiny material-icons">close</i></a>
            </div>
            <div class="row">
                <div class="input-field col s10 m10 l10">
                    <div class="center-align">
                        <h4>Alterar Limite Crédito Revendedor2</h4>
                    </div>
                </div>
            </div>
            @if(isset($dados))
                <form class="form-group" id="form-limite" method="post" action="/admin/revendedor/limite/{{$dados->idbase}}/{{$dados->idven}}/{{$dados->idreven}}" enctype="multipart/form-data">
                    {{ csrf_field() }}               
                    <div class="row">
                            <div class="input-field col s10 m10 l10">
                                    <input id="nomreven" type="text" class="validate" name="nomreven"  value="{{$dados->nomreven}}">
                                    <label class="active" for="nomreven">Revendedor: </label>                                        
                            </div>
                    </div>
                    <div class="row">                            
                            <div class="input-field col s10 m10 l5">
                                    <input id="nomreven" type="text" class="validate" name="nomreven"  value="{{number_format($dados->limcred, 2, ',' , '.')}}">
                              {{--  <h6 id="limiteAtu" class="left blue-grey-text text-darken-3"><b>{{number_format($nomLimRevendedor->limcred, 2, ',' , '.')}}</b></h6>--}}
                                <label class="active" for="limiteAtu">Limite Atual: </label>
                            </div>
                            <div class="input-field col s10 m10 l5">
                                    <input id="limiteNovo" name="limiteNovo" placeholder="0,00" type="text" class="validate">
                                    <label class="active" for="limiteNovo">Alterar Para</label>
                            </div>
                            
                    </div>
                    <div class="row">
                            <div class="input-field col s12 m12 l12">
                                <div class="right-align">
                                    <button class="btn waves-effect waves-light" type="submit" name="action">Alterar
                                        <i class="material-icons right">send</i>
                                    </button>
                                </div>
                            </div> 
                    </div>  
                   
                        
                </form>
            @endif

            
    </div>


    {{-- Modal altera comissão--}}
    <div id="modalComissao" class="modal" >

            <div class="right-align">
                    <a href="#!" class=" btn modal-action modal-close waves-effect waves-light red "><i class=" Tiny material-icons">close</i></a>
            </div>
            <div class="row">
                <div class="input-field col s10 m10 l10">
                    <div class="center-align">
                        <h4>Alterar % comissão Revendedor</h4>
                    </div>
                </div>
            </div>
                @if(isset($dados))
                <form class="form-group" id="form-comissao" method="post" action="/admin/revendedor/comissao/{{$dados->idbase}}/{{$dados->idven}}/{{$dados->idreven}}" enctype="multipart/form-data">
                    {{ csrf_field() }}               
                    <div class="row">

                            <div class="input-field col s10 m10 l10">
                                    <input id="nomreven" type="text" class="validate" name="nomreven"  value="{{$dados->nomreven}}">
                                    <label class="active" for="nomreven">Revendedor: </label>                                        
                            </div>
                    </div>   
                    <div class="row">
                            <div class="input-field col s10 m10 l5">
                                    <input id="nomreven" type="text" class="validate" name="nomreven"  value="{{number_format($dados->vlrcom, 2, ',' , '.')}}">
                                    <label class="active" for="nomreven">Comissão Atual: </label>                                        
                            </div>
                            <div class="input-field col s10 m10 l5">
                                <input id="comissaoNovo" name="comissaoNovo" placeholder="0,00" type="text" class="validate">
                                <label class="active" for="comissaoNovo">Alterar Para: </label>
                            </div>
                    </div>   
                    <div class="row">
                        <div class="input-field col s12 m12 l12">
                            <div class="right-align">
                                <button class="btn waves-effect waves-light" type="submit" name="action">Alterar
                                    <i class="material-icons right">send</i>
                                </button>
                            </div>
                        </div> 
                    </div>    
                       
                 </div>
               
                </form>
                @endif
    </div>
</div>



@endsection

@push('scripts')



<script>
    

    $(document).ready(function() {
    
        $('.modal').modal();
        $('#open-modal').trigger('click');

//modal revendedor
        $('.btn').click(function(){
        var a =$(this).attr('id');     
        document.getElementById("idInput").innerHTML = a;    
      //   alert(a);
      //   $(a).modal({
       ///     show: true
       // });
          //  document.getElementById("idIframe").src = "/admin/revendedor/update/"+{{$ideven}}+"/"+a;
            
        });

        

        $('ul.tabs').tabs();

        var table = $('#example').DataTable( {


            dom: 'fBrtip',
            buttons: [
                
                'pdf',
                'excel',
                {
                    extend: 'print',
                    text: 'Imprimir',
                }
            ],


            scrollY: 310,
            scrollX:        true,
            scrollCollapse: true,
            paging:         false,
            Bfilter:        true,
            "aaSorting": [[1, "asc"],[6, "desc"]],


            language: {
                "searchPlaceholder": "Digite aqui para pesquisar",
                "decimal":        ",",
                "thousands":      ".",
                "sEmptyTable": "Nenhum registro encontrado",
                "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                "sInfoPostFix": "",
                "sInfoThousands": ".",
                "sLengthMenu": "_MENU_ resultados por página",
                "sLoadingRecords": "Carregando...",
                "sProcessing": "Processando...",
                "sZeroRecords": "Nenhum registro encontrado",
                "sSearch": " ",
                "oPaginate": {
                    "sNext": "Próximo",
                    "sPrevious": "Anterior",
                    "sFirst": "Primeiro",
                    "sLast": "Último"
                },
                "oAria": {
                    "sSortAscending": ": Ordenar colunas de forma ascendente",
                    "sSortDescending": ": Ordenar colunas de forma descendente"
                }
            }

        } );

        // #myInput is a <input type="text"> element
        $('#myInput').on( 'keyup', function () {
            table.search( this.value ).draw();
        } );

    });




</script>
<script>
    function openModal1(url) {


    $('#myModal').modal('open');
    };

</script>
<script>
    $(function () {
        setTimeout("$('.hide-msg').fadeOut();", 5000)
    })
</script>



@endpush

