@extends('dashboard.templates.app')


@section('content')
    {{--@forelse($ideven2 as $p)--}}
        {{--{{$p}}--}}
        {{--@empty--}}
        {{--@endforelse--}}

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
                    <h4>Cadastro de Revendedor:</h4>
                <div class="row">
                     <div class="card grey lighten-2">
                        <div class="card-content black-text">
                            @if(isset($dados))
                                <form method="post" action="/admin/revendedor/update/{{$ideven}}/{{$dados->idereven}}" class="col m12">
                                    {{csrf_field()}}
                                @else
                                <form method="post" action="/admin/revendedor/create/{{$ideven}}/add" class="col m12">
                                    {{csrf_field()}}
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
                                                    <input id="nomven" type="text" class="validate"  readonly value="{{$dados->vendedorNome or $vendedorNome}}">
                                                
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
                                                  <a href="/admin/revendedor/limite/{{$dados->idbase}}/{{$dados->idven}}/{{$dados->idreven}}" class="btn waves-effect waves-light btn-small" style="width: 190px;">Alterar Limite</a>                                              
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
                                                  <a href="/admin/revendedor/comissao/{{$dados->idbase}}/{{$dados->idven}}/{{$dados->idreven}}" class="btn waves-effect waves-light btn-small" style="width: 190px;">Alterar Comissão</a>                                              
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
                                                <select name="recarga_cel">
                                                  @if(isset($dados))
                                                    <option value="SIM" {{ $dados->in_recarga == 'SIM'  ? 'selected' : '' }} >SIM</option>
                                                    <option value="NAO" {{ $dados->in_recarga == 'NAO'  ? 'selected' : '' }} >NAO</option>
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
                              <!--  @if(isset($dados))
                                <div class="row">
                                    <div class="col s12 m6 l6">
                                        <button class="btn waves-effect waves-light red" onClick="history.go(-1)" >Voltar
                                           <i class="material-icons left">keyboard_arrow_left</i>
                                        </button>
                                    </div>
                                </div>
                                @endif -->
                              
                            </form>{{-- end form !--}}
                        </div>
                        </div>
            </div>
            </div>
        </div>
    </div>
    
@endsection


@push('scripts')
        <script type="text/javascript" src="{{url('js/jquery.mask.js')}}"></script>


<script>

    $(document).ready(function() {

        $('#limcred').mask('000.000.000.000.000,00', {reverse: true});
        $('#vlrcom').mask('000.000.000.000.000,00', {reverse: true});
        $('#vlrmaxpalp').mask('000.000.000.000.000,00', {reverse: true});
        $('#vlrblopre').mask('000.000.000.000.000,00', {reverse: true});
//        $('#limlibpre').mask('000.000.000.000.000,00', {reverse: true});
        $('#celreven').mask('(00)00000-0000');



        $('.modal').modal();


        $('ul.tabs').tabs();

        var table = $('#example').DataTable( {


            dom: 'fBrtip',
            buttons: [
                {
                    extend: 'copy',
                    text: 'Copiar',
                },
                'pdf',
                'excel',
                {
                    extend: 'print',
                    text: 'Imprimir',
                }
            ],


            scrollY: 480,
            scrollX:        true,
            scrollCollapse: true,
            paging:         true,
            Bfilter:        true,
            "aaSorting": [[1, "asc"],[6, "desc"]],


            language: {
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
                "sSearch": "Pesquisar",
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


            $( ".base" ).change(function() {
                urlBase = '/admin/revendedor/base/'+ this.value;
                base(urlBase);
            });

            $( ".vendedor" ).change(function() {


                var base = 0;

                base = $('#idbase').val();


                if (base == 0) {
                    alert('Selecione uma base antes');
                    return false;
                }



                urlVend = '/admin/revendedor/vendedor/'+ base +'/'+ this.value;

//                alert( urlVend);

//                return false;

                vendedor(urlVend);

            });
            function base(url) {
                jQuery.getJSON(url, function (data) {
//                    alert( data[0].nompro );
                    $('#idbase').val(data[0].idbase);
                    $('#lidbase').addClass("active");

                    $('#nompro').val(data[0].nompro);
                    $('#lnompro').addClass("active");

                    $('#cidbas').val(data[0].cidbas);
                    $('#lcidbas').addClass("active");

                    $('#uf').val(data[0].sigufs);
                    $('#luf').addClass("active");

                });
            }

            function vendedor(url) {
                jQuery.getJSON(url, function (data) {
//                    alert( data[0].nompro );
                    $('#idven').val(data[0].idven);
                    $('#lidven').addClass("active");

                    $('#nomven').val(data[0].nomven);
                    $('#lnomven').addClass("active");

                    $('#cidven').val(data[0].cidven);
                    $('#lcidven').addClass("active");

                    $('#ufven').val(data[0].sigufs);
                    $('#lufven').addClass("active");

                });
            }

        </script>

        <script>
            $(function () {
                setTimeout("$('.hide-msg').fadeOut();", 5000)
            })
        </script>
@endpush

