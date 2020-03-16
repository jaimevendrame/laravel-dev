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
                        
                        <div class="row">
                            <div class="card grey lighten-2">
                                <div class="card-content black-text">
                            @if(isset($dados))
                                <form method="post" action="/admin/terminal/update/{{$ideven}}/{{$dados->idter}}" class="col m12">
                                    {{csrf_field()}}
                                @else
                                <form method="post" action="/admin/terminal/create/{{$ideven}}/add" class="col m12">
                                    {{csrf_field()}}
                                @endif

                                <div class="row">
                                    <div class="col s12 m6 l12">
                                        <ul class="tabs">
                                            <li class="tab col s6 m6 l6"><a class="active" href="#cadastro">Cadastro</a></li>

                                            @if(isset($dados))
                                            <li class="tab col s6 m6 l6"><a href="#opcionais">Opcionais</a></li>
                                            @endif

                                        </ul>
                                    </div> 
                                </div>
                                <div class="row"></div>{{--1--}}
                                     <div id="cadastro" class="col s12">{{--2--}}

                                         <div class="row">
                                             <div class="input-field col s12 m8 l6">
                                                 <input id="nomebase" type="text" class="validate" disabled readonly value="{{$baseNome}}">
                                                 <label id="lnomebase" class="active" for="nomebase">Base</label>
                                                 @if ($errors->has('nomebase'))
                                                     <span class="alert-validation">
                                                         <strong>{{ $errors->first('nomebase') }}</strong>
                                                     </span>
                                                 @endif
                                                 <input id="idbase" type="hidden" name="idbase" class="validate" readonly value="{{$dados->idbase or $idbase}}">
                                             </div>
                                        
                                             <div class="input-field col s12 m8 l6">
                                                <input id="nomven" type="text" class="validate"  disabled readonly value="{{$vendedorNome}}">
                                                <label id="lnomven" class="active" for="nomven">Vendedor</label>
                                                @if ($errors->has('nomven'))
                                                    <span class="alert-validation">
                                                        <strong>{{ $errors->first('nomven') }}</strong>
                                                    </span>
                                                @endif
                                             </div>
                                                <input id="idven" type="hidden" name="idven" class="validate" readonly value="{{$dados->idven or $idvendedor}}">
										 </div>

                                         <div class="row">
                                       


                                             <div class="input-field col s12 m6 l4">
                                                 <select name="idreven">
                                                     <option value="" disabled selected>Selecione</option>

                                                     @forelse($revendedores as $rev)
                                                        <option value="{{ $rev->idreven }}"
                                                                @if(isset($dados->idreven) && $dados->idreven == $rev->idreven )
                                                                selected
                                                                @endif
                                                        >{{ $rev->nomreven }}</option>
                                                     @empty
                                                         <option value="" disabled selected>Nenhum revendedor</option>
                                                     @endforelse
                                                </select>
                                                <label>Revendedor</label>

                                                @if ($errors->has('idreven'))
                                                    <span class="alert-validation">
                                                        <strong>{{ $errors->first('idreven') }}</strong>
                                                    </span>
                                                @endif

                                             </div>
                                           
                                            <div class="input-field col s12 m6 l2">
                                                    <select name="modouso">
                                                        @if(isset($dados))
                                                            <option value="1" {{ $dados->modouso == '1'  ? 'selected' : '' }} >1</option>
                                                            <option value="2" {{ $dados->modouso == '2'  ? 'selected' : '' }} >2</option>
                                                            <option value="3" {{ $dados->modouso == '3'  ? 'selected' : '' }} >3</option>
                                                            <option value="4" {{ $dados->modouso == '4'  ? 'selected' : '' }} >4</option>
                                                                @else
                                                                     <option value="" disabled selected>Selecione</option>
                                                                     <option value="1">1</option>
                                                                     <option value="2">2</option>
                                                                     <option value="3">3</option>   
                                                                     <option value="4">4</option> 
                                                                @endif
                                                        </select>
                                                        <label>Modo de Uso</label>
        
                                                        @if ($errors->has('modouso'))
                                                            <span class="alert-validation">
                                                                <strong>{{ $errors->first('modouso') }}</strong>
                                                            </span>
                                                        @endif
                                            </div>

                                                    
                                            <div class="input-field col s12 m6 l2" >
                                                    <select name="modope">
                                                        @if(isset($dados))
                                                            <option value="ONOFF" {{ $dados->modope == 'ONOFF'  ? 'selected' : '' }} >ONOFF</option>
                                                            <option value="ONLINE" {{ $dados->modope == 'ONLINE'  ? 'selected' : '' }} >ONLINE</option>
                                                                @else
                                                                <option value="ONOFF" selected>ONOFF</option>
                                                                <option value="ONLINE">ONLINE</option>
                                                                @endif
                                                        </select>
                                                        <label>Modo de Operação</label>
                                                </div>

                                                    <div class="input-field col s12 m6 l4">
                                                            @if(isset($dados))
                                                                <input id="limpen" type="text" class="validate" name="limpen" value="{{$dados->limpen or old('limpen')}}">
                                                            @else
                                                                <input id="limpen" type="text" class="validate" name="limpen" value="10">
                                                            @endif
                                                                <label class="active" for="limpen">Limite de apostas pendentes</label>
            
                                                                @if ($errors->has('limpen'))
                                                                    <span class="alert-validation">
                                                                        <strong>{{ $errors->first('limpen') }}</strong>
                                                                    </span>
                                                                @endif
                                                        </div>

                                         
                                        </div>

                                    <div class="row">

                                            @if(isset($dados))
                                         
                                            <div class="input-field col s12 m6 l4">
                                                <input id="ideter" type="text" class="validate red-text text-darken-2" name="ideter" readonly value="{{$dados->ideter or old('ideter')}}">
                                                <label class="active" for="ideter">Identificação Única</label>
                                            </div>
                                  
                                            
                                        @endif

                                            <div class="input-field col s12 m6 l4">
                                                    <input id="senhaini" type="text" class="validate red-text text-darken-2" name="senhaini" readonly value="{{$dados->senhaini or old('senhaini')}}">
                                                    <label class="active" for="senhaini">Senha de Inicialização</label>
                                                </div>
                                       
                                        
                                            
                                     
                                        

                                        @if(isset($dados))
                                        
                                            <div class="input-field col s12 m6 l4">
                                            <select name="solsenace">
                                                @if(isset($dados))
                                                    <option value="NAO" {{ $dados->solsenace == 'NAO'  ? 'selected' : '' }} >NAO</option>
                                                    <option value="SIM" {{ $dados->solsenace == 'SIM'  ? 'selected' : '' }} >SIM</option>
                                                        @else
                                                        <option value="NAO" selected>NAO</option>
                                                        <option value="SIM">SIM</option>
                                                        @endif
                                                </select>
                                                <label>Solicitar Senha de Acesso</label>

                                                @if ($errors->has('solsenace'))
                                                    <span class="alert-validation">
                                                        <strong>{{ $errors->first('solsenace') }}</strong>
                                                    </span>
                                                @endif

                                            </div>
                                        </div>
                                    
                                        @endif 

                                        @if(isset($dados))
                                        <div class="row">
                                            <div class="input-field col s12 m6 l6">
                                                <input id="senha" type="text" class="validate" name="senha" readonly value="{{$dados->senha or old('senha')}}">
                                                <label class="active" for="senha">Senha de Acesso</label>
                                            </div>
                                       
                                        
                                            <div class="input-field col s12 m6 l6">
                                            <select name="sitter">
                                                @if(isset($dados))
                                                    <option value="ATIVO" {{ $dados->sitter == 'ATIVO'  ? 'selected' : '' }} >ATIVO</option>
                                                    <option value="INATIVO" {{ $dados->sitter == 'INATIVO'  ? 'selected' : '' }} >INATIVO</option>
                                                        @else
                                                        <option value="ATIVO">ATIVO</option>
                                                        <option value="INATIVO" selected>INATIVO</option>
                                                        @endif
                                                </select>
                                                <label>Situação</label>
                                            </div>
                                        </div>
                                        @endif


                                        @if(isset($dados))
                                             @if($usuAdmin == 'SIM')
                                                 <div class="row">
                                                     <div class="input-field col s12 m6 l6">
                                                         <input id="senhaconf" type="text" class="validate" name="senhaconf" readonly value="{{$dados->senhaconf or old('senhaconf')}}">
                                                         <label class="active" for="senhaconf">Senha de Configuração</label>
                                                     </div>
                                                 </div> 
                                             @endif
                                        @endif

                                     </div>{{--2--}}

                                    {{--OPCIONAIS--}}
                                     @if(isset($dados))
                                     <div id="opcionais" class="col s12 m12 l12">{{--4--}}

                                        <div class="row">
                                            <div class="input-field col s12 m6 l4">
                                                <input id="idter" type="text" class="validate" name="idter" readonly value="{{$dados->idter or old('idter')}}">
                                                <label class="active" for="ideter">ID Terminal</label>
                                            </div>
                                       
                                             <div class="input-field col s12 m6 l4">
                                                 <select name="modelo">
                                                     <option value="" disabled selected> </option>

                                                     @forelse($modelos as $mod)
                                                        <option value="{{ $mod->modelo }}"
                                                                @if(isset($dados->modelo) && $dados->modelo == $mod->modelo )
                                                                selected
                                                                @endif
                                                        >{{ $mod->modelo }}</option>
                                                     @empty
                                                         <option value="" disabled selected>Nenhum modelo</option>
                                                     @endforelse
                                                </select>
                                                <label>Modelo</label>
                                             </div>
                                       
                                            <div class="input-field col s12 m6 l4">
                                                <input id="versis" type="text" class="validate" name="versis" readonly value="{{$dados->versis or old('versis')}}">
                                                <label class="active" for="versis">Versão Sistema</label>
                                            </div>
                                         </div>  

                                         <div class="row">
                                            <div class="input-field col s12 m6 l4">
                                                <input id="serial" type="text" class="validate" name="serial" readonly value="{{$dados->serial or old('serial')}}">
                                                <label class="active" for="serial">Nº Série Terminal</label>
                                            </div>
                                        
                                            <div class="input-field col s12 m6 l4">
                                                <input id="serialchip" type="text" class="validate" name="serialchip" readonly value="{{$dados->serialchip or old('serialchip')}}">
                                                <label class="active" for="serialchip">Nº Série Chip</label>
                                            </div>
                                         
                                            <div class="input-field col s12 m6 l4">
                                                <input id="idapo" type="text" class="validate" name="idapo" readonly value="{{$dados->idapo or old('idapo')}}">
                                                <label class="active" for="idapo">Nº Aposta</label>
                                            </div>
                                         </div>  

                                         <div class="row">
                                            <div class="input-field col s12 m6 l4">
                                                <input id="datcad" type="text" class="validate" name="datcad" readonly value="{{$dados->datcad or old('datcad')}}">

                                                <label class="active" for="datcad">Data Cadastro</label>
                                            </div>
                                     
                                            <div class="input-field col s12 m6 l4">
                                                <input id="usercad" type="text" class="validate" name="idusucad" value="{{$dados->idusucad or Auth()->user()->idusu}}" readonly>
                                                <label class="active" for="usercad">Usuário Cadastrado</label>
                                            </div>
                                        
                                            <div class="input-field col s12 m6 l4">
                                                <input id="datalt" type="text" class="" name="datalt" value="{{$dados->datalt or old('datalt')}}" readonly>
                                                <label class="active" for="datalt">Data última Alteração</label>
                                            </div>
                                         </div>   

                                         <div class="row">
                                            <div class="input-field col s12 m6 l6">
                                                <input id="useralt" type="text" class="validate" name="idusualt" value="{{$dados->idusualt or old('idusualt')}}" readonly>
                                                <label class="active" for="useralt">Usuário Alteração</label>
                                            </div>
                                         </div>
                                                                                                                                                                                    

                                     </div>{{--4--}}
                                     @endif
                                </div>{{--1--}}

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

                s(urlVend);

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

