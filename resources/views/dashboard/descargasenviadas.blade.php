@extends('dashboard.templates.app')

@section('content')
    {{--@forelse($ideven2 as $p)--}}
        {{--{{$p}}--}}
        {{--@empty--}}
        {{--@endforelse--}}

    <div class="section">
        <div class="row">
            <div class="col s12">
               
                   
                        <form class="form-group" id="form-cad-edit" method="post" action="/admin/descargasenviadas/{{$ideven}}" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            <div class="row">
                                <div class="input-field col s6 m2">
                                    <input id="datIni" name="datIni" type="date" class="datepicker"
                                           placeholder ="Data inicial">

                                </div>
                                <div class="input-field col s6 m2">
                                     <input id="datFim" name="datFim" type="date" class="datepicker"
                                             placeholder ="Data final">
                                </div>

                                <div class="input-field col s12 m4">
                                    <select multiple name="sel_lotodia[]">
                                        <option value="" disabled selected>Selecionar</option>
                                        @forelse($semana as $s)

                                            @if(!empty($idehor))
                                                <option value="{{$s->idehor}}" @forelse($idehor as $select) {{ $s->idehor == $select  ? 'selected' : '' }} @ @empty @endforelse >{{$s->idehor}}-{{$s->deshor}}</option>

                                                @else
                                                <option value="{{$s->idehor}}">{{$s->deshor}}</option>

                                            @endif

                                        @empty
                                            <option value="" disabled selected>Nenhum</option>
                                        @endforelse

                                    </select>
                                    <label>Loterias do Dia</label>
                                </div>
                                <div class="input-field col s12 m4">
                                    <select multiple name="sel_vendedor[]">
                                        <option value="" disabled selected>Selecionar Vendedores</option>
                                        @forelse($baseAll as $bases)
                                            @if( empty($ideven2) )
                                            <option value="{{$bases->ideven}}" {{ $bases->ideven == $ideven  ? 'selected' : '' }} >{{$bases->ideven}}-{{$bases->nomven}}</option>
                                            @elseif(isset($ideven2) && (is_array($ideven2)))
                                            <option value="{{$bases->ideven}}" @forelse($ideven2 as $select) {{ $bases->ideven == $select  ? 'selected' : '' }} @ @empty @endforelse >{{$bases->ideven}}-{{$bases->nomven}}</option>
                                             @else
                                                <option value="{{$bases->ideven}}">{{$bases->ideven}}-{{$bases->nomven}}</option>
                                            @endif
                                        @empty
                                            <option value="" disabled selected>Nenhuma base</option>
                                        @endforelse

                                    </select>
                                    <label>Bases selecionadas</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s12 m2">
                                    <select name="sel_vendedord">
                                        <option value="" >Nenhum</option>
                                        @if(!empty($descarga))
                                            @forelse($descarga as $d)
                                                <option value="{{$d->idbasedesc}}{{$d->idvendesc}}"@if( !empty($idvendd)) {{ $d->idbasedesc.$d->idvendesc == $idvendd  ? 'selected' : '' }} @endif>{{$d->nomven}}</option>
                                            @empty
                                                <option value="" disabled selected>Nenhum vendedor</option>
                                            @endforelse
                                        @endif
                                    </select>
                                    <label>Vendedor</label>
                                </div>
                                <div class="input-field col s12 m2">
                                    <select  name="sel_situacao">
                                        {{--<option value="3" disabled selected>Opções</option>--}}
                                        <option value="3" @if(!empty($idsit)) {{ $idsit == 3  ? 'selected' : '' }}  @endif>Todos</option>
                                        <option value="0" @if(!empty($idsit)) {{ $idsit == 0  ? 'selected' : '' }}  @endif>Pendente de liberação</option>
                                        <option value="1" @if(!empty($idsit)) {{ $idsit == 1  ? 'selected' : '' }}  @endif>Liberadas</option>
                                        <option value="2" @if(!empty($idsit)) {{ $idsit == 2  ? 'selected' : '' }}  @endif>Não liberadas</option>
                                    </select>
                                    <label>Situação</label>
                                </div>
                                <div class="input-field col s8 m2 l2">
                                    <input  id="numpule" name="numpule" type="text" class="validate" @if(!empty($palpite)) value="{{$palpite}}" @else value="" @endif>
                                    <label class="active" for="numpule">Nº Aposta</label>
                                </div>
                                <div class="input-field col s12 m2">
                                    <select  name="sel_loterias">
                                        <option value=""  selected>Todos</option>
                                        @forelse($loterias as $l)
                                        <option value="{{$l->idlot}}" @if(!empty($idlot)) {{ $l->idlot == $idlot  ? 'selected' : '' }}  @endif>{{$l->deslot}}</option>
                                        @empty
                                        <option value="">Nenhum</option>
                                        @endforelse
                                    </select>
                                    <label>Loterias</label>
                                </div>
                                <div class="input-field col s12 m2">
                                    <button class="btn waves-effect waves-light" type="submit" name="action">Mostrar
                                        <i class="material-icons right">send</i>
                                    </button>
                                </div>
                            </div>

                        </form>


                        @if(!empty($data))

                            <table class="mdl-data-table " id="example"  cellspacing="0" width="100%">

                                <thead><tr>
                                    <th>Info.</th>
                                    <th>Pule</th>
                                    <th>Vlr. Descarga</th>
                                    <th>Loteria</th>
                                    <th>Modalidade</th>
                                    <th>Palpite</th> 
                                    <th>Data Sorteio</th>                                                                     
                                    <th></th>
                                    <th>Colocação Descarga</th>
                                    <th>Situação</th>
                                    <th>Colocação Palpite</th>
                                    <th>Horário Limite</th>
                                    <th>Vendedor Destino</th>
                                    <th>Data Envio Aposta</th>
                                </tr></thead>
                                <tbody>

                                @forelse($data as $d)

                                    <tr>     
                                       <td><a class="waves-effect waves-light btn btn_grid blue" onclick='edit("/admin/descargasenviadas/view/{{$d->ideven}}/{{$d->idreven}}/{{$d->idter}}/{{$d->idapo}}/{{$d->numpule}}/{{$d->seqpalp}}/")'><i class="material-icons">info</i></a>
                                        <td>{{ $d->numpule }}</td>
                                        <td>{{ number_format($d->vlrpalpo, 2, ',', '.') }}</td>
                                        <td>{{ $d->deshor }}</td>
                                        <td>{{ $d->destipoapo }}</td>
                                        <td>  @if( isset($d->palp1) ){{$d->palp1}}@endif

                                            @if( isset($d->palp2) ){{'- '.$d->palp2}}@endif

                                            @if( isset($d->palp3) ) {{'- '.$d->palp3}} @endif

                                            @if( isset($d->palp4) ){{'- '.$d->palp4}}@endif

                                            @if( isset($d->palp5) ){{'- '.$d->palp5}}@endif

                                            @if( isset($d->palp6) ){{'- '.$d->palp6}}@endif

                                            @if( isset($d->palp7) ){{'- '.$d->palp7}}@endif

                                            @if( isset($d->palp8) ){{'- '.$d->palp8}}@endif

                                            @if( isset($d->palp9) ){{'- '.$d->palp9}}@endif

                                            @if( isset($d->palp10) ){{'- '.$d->palp10}}@endif

                                            @if( isset($d->palp11) ){{'- '.$d->palp11}}@endif

                                            @if( isset($d->palp12) ){{'- '.$d->palp12}}@endif

                                            @if( isset($d->palp13) ){{'- '.$d->palp13}}@endif

                                            @if( isset($d->palp13) ){{'- '.$d->palp13}}@endif

                                            @if( isset($d->palp14) ){{'- '.$d->palp14}}@endif

                                            @if( isset($d->palp15) ){{'- '.$d->palp15}}@endif

                                            @if( isset($d->palp16) ){{'- '.$d->palp16}}@endif

                                            @if( isset($d->palp17) ){{'- '.$d->palp17}}@endif

                                            @if( isset($d->palp18) ){{'- '.$d->palp18}}@endif

                                            @if( isset($d->palp19) ){{'- '.$d->palp19}}@endif

                                            @if( isset($d->palp20) ){{'- '.$d->palp20}}@endif

                                            @if( isset($d->palp21) ){{'- '.$d->palp21}}@endif

                                            @if( isset($d->palp22) ){{'- '.$d->palp22}}@endif

                                            @if( isset($d->palp23) ){{'- '.$d->palp23}}@endif

                                            @if( isset($d->palp24) ){{'- '.$d->palp24}}@endif

                                            @if( isset($d->palp25) ){{'- '.$d->palp25}}@endif
                                        </td>       
                                        <td>{{ Carbon\Carbon::parse($d->datapo)->format('d/m/Y') }}</td>
                                        <td>{{ $d->infodesc }}</td>
                                        <td>{{ $d->tipodesc }}</td>
                                        @if($d->sitdes = 'PRO')
                                            <td>LIBERADO</td>
                                        @elseif($d->sitdes = 'EL')
                                            @if( ( Carbon\Carbon::parse($d->horlim)->format('H:i:s') < Carbon\Carbon::now()->format('H:i:s') )
                                    && ( Carbon\Carbon::parse($d->datapo)->format('d/m/Y') == Carbon\Carbon::now()->format('Hd/m/Y') )
                                    || ( Carbon\Carbon::parse($d->datapo)->format('d/m/Y') < Carbon\Carbon::now()->format('Hd/m/Y') ) )
                                                <td>NÃO LIBERADO</td>
                                            @else
                                                <td>PENDENTE DE LIBERAÇÃO</td>
                                            @endif
                                        @elseif($d->sitdes = 'PRE')
                                            <td class="orange">PREMIADO</td>
                                        @endif
                                        <td>{{ $d->descol }}</td>

                                        <td>{{Carbon\Carbon::parse($d->horlim)->format('H:i:s')}}</td>
                                        <td>{{ $d->nomven }}</td>
                                        <td> {{ Carbon\Carbon::parse($d->datenv)->format('d/m/Y') }} - {{Carbon\Carbon::parse($d->horenv)->format('H:i:s')}} </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <p>nenhum registro encontrado!</p>
                                    </tr>
                                @endforelse

                                <tfoot>
                                <tr>
                                    <th>Vlr. Descarga</th>
                                    <th>Modalidade</th>
                                    <th>Palpite</th>
                                    <th>Loteria</th>
                                    <th>Data Sorteio</th>
                                    <th>Pule</th>
                                    <th></th>
                                    <th></th>
                                    <th>Colocação Descarga</th>
                                    <th>Situação</th>
                                    <th>Colocação Palpite</th>
                                    <th>Horário Limite</th>
                                    <th>Vendedor Destino</th>
                                    <th>Data Envio Aposta</th>

                                </tr>
                                </tfoot>


                            </table>

                            @else
                            <p>Nenhum registro encontrado!</p>

                        @endif


                    </div>

                </div>
            </div>
            @php
                $total = 0;
                foreach ($data as $d){
                    $total = $total + $d->vlrpalpo;
                }
            @endphp
            <div class="row">
                <div class="col s12 offset-m9 m3 offset-l9 l3">
                    <div class="col s12 z-depth-2 blue-grey lighten-5 hoverable">
                        <div class="row left-align">
                            <h5 class=" blue-grey-text">
                                Total:
                            </h5>
                        </div>
                        <div class="row right-align">
                            <h5 class=" blue-grey-text">@php echo number_format($total, 2, ',', '.');  @endphp</h5>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>


    <!-- Modal Structure -->
    <div id="modaldescarga" class="modal modal3">

        <div class="right-align ">
            <a href="#!" class=" btn modal-action modal-close waves-effect waves-light red "><i class=" Tiny material-icons">close</i></a>
        </div>
        <div class="modal-content">
            <h4>Informações de Descarga Enviada</h4>
            <div class="row">
                <form action="" id="myForm">
                    <div class="row">
                        <div class="input-field col s12 m6 l6">
                            <input placeholder="Vendedor Origem" id="nomvem_o" type="text" readonly class="validate">
                            <label for="nomvem_o">Vendedor Origem</label>
                        </div>
                        <div class="input-field col s12 m6 l6">
                            <input placeholder="Vendedor Destino" id="nomven" type="text" readonly class="validate">
                            <label for="nomven">Vendedor Destino</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s8 m4 l2">
                            <input placeholder="Pule" id="numpulec" type="text" readonly class="validate">
                            <label for="numpulec">Pule</label>
                        </div>
                        <div class="input-field col s8 m2 l2">
                            <input placeholder="Data Sorteio" id="datapo" type="text" readonly class="validate">
                            <label for="datapo">Data Sorteio</label>
                        </div>
                        <div class="input-field col s8 m2 l2">
                            <input placeholder="Loteria" id="deslot" type="text" readonly class="validate">
                            <label for="deslot">Loteria</label>
                        </div>
                        <div class="input-field col s8 m2 l3">
                            <input placeholder="Horário Encerramento" id="horlim" type="text" readonly class="validate">
                            <label for="horlim">Horário Encerramento</label>
                        </div>
                        <div class="input-field col s8 m2 l3">
                            <input placeholder="Horário Sorteio" id="horsor" type="text" readonly class="validate">
                            <label for="horsor">Horário Sorteio</label>
                        </div>
                    </div>
                    <div class="row">

                        <div class="input-field col s8 m2 l2">
                            <input placeholder="Modalidade Aposta" id="destipoapo" type="text" readonly class="validate">
                            <label for="destipoapo">Modalide Aposta</label>
                        </div>
                        <div class="input-field col s8 m2 l2">
                            <input placeholder="Colocação" id="descol" type="text" readonly class="validate">
                            <label for="descol">Colocação</label>
                        </div>
                        <div class="input-field col s12 m8 l8">
                            <input placeholder="Palpite" id="vaDesPalp" type="text" readonly class="validate">
                            <label for="vaDesPalp">Palpite</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s8 m2 l2">
                            <input placeholder="Valor Descarga" id="vlrpalp" type="text" readonly class="validate">
                            <label for="vlrpalp">Valor Descarga</label>
                        </div>
                        <div class="input-field col s8 m2 l2">
                            <input placeholder="Prêmio Seco" id="vlrpresec" type="text" readonly class="validate">
                            <label for="vlrpresec">Prêmio Seco</label>
                        </div>
                        <div class="input-field col s8 m2 l2">
                            <input placeholder="Prêmio Molhado" id="vlrpremol" type="text" readonly class="validate">
                            <label for="vlrpremol">Prêmio Molhado</label>
                        </div>
                        <div class="input-field col s8 m2 l2">
                            <input placeholder="Prêmio Seco + Molhado" id="vlrpresmj" type="text" readonly class="validate">
                            <label for="vlrpresmj">Prêmio Seco + Molhado</label>
                        </div>
                        <div class="input-field col s8 m2 l2">
                            <input placeholder="Situação" id="sitdes" type="text" readonly class="validate">
                            <label for="sitdes">Situação</label>
                        </div>
                        <div class="input-field col s8 m2 l2">
                            <input placeholder="Info" id="infodesc" type="text" readonly class="validate">
                            <label for="infodesc">Info</label>
                        </div>
                    </div>
                    </div>
                </form>
            </div>
            <div class="row">
                    <div class="col s12 m6 l12">
                        <ul class="tabs">
                            <li class="tab col s6 m6 l6"><a class="active" href="#test1">Informações sobre a aposta</a></li>
                            <li class="tab col s6 m6 l6"><a href="#test2">Descarga Recebida</a></li>
                        </ul>
                    </div>
            </div>
            <div class="row"></div>

                    <div id="test1" class="row">
                        <div class="col s12 m6 l6">
                            <table class="display mdl-data-table" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>Valor</th>
                                    <th>Bancou</th>
                                    <th>Descarregou</th>
                                </tr>
                                </thead>
                                <tbody id="tbody_infoaposta">
                                </tbody>
                            </table>
                        </div>
                       
                        <div class="col s12 m6 l6">
                            <form action=""id="formTabs">
                                <div class="input-field col s8 m4 l4">
                                    <input placeholder="Valor Aposta" id="ap_vlrpalp" type="text" readonly class="validate">
                                    <label for="ap_vlrpalp">Valor Aposta</label>
                                </div>
                                <div class="input-field col s8 m4 l4">
                                    <input placeholder="Valor Cotação" id="ap_vlrcotacao" type="text" readonly class="validate">
                                    <label for="ap_vlrcotacao">Valor Cotação</label>
                                </div>
                                <div class="input-field col s8 m4 l4">
                                    <input placeholder="Valor Prêmio" id="vnAP_VlrPreMotDesc" type="text" readonly class="validate">
                                    <label for="vnAP_VlrPreMotDesc">Valor Prêmio</label>
                                </div>
                                <div class="input-field col s8 m4 l4">
                                    <input placeholder="Limite" id="vlrlimdesc" type="text" readonly class="validate">
                                    <label for="vlrlimdesc">Limite</label>
                                </div>
                                <div class="input-field col s8 m4 l4">
                                    <input placeholder="Limite Excedido" id="vnVlrLimExedido" type="text" readonly class="validate">
                                    <label for="vnVlrLimExedido">Limite Excedido</label>
                                </div>
                                <div class="input-field col s8 m4 l4">
                                    <input placeholder="Valor Bancou" id="ap_vlrpalpf" type="text" readonly class="validate">
                                    <label for="ap_vlrpalpf">Valor Bancou</label>
                                </div>
                                <div class="input-field col s8 m4 l4">
                                    <input placeholder="Valor Descarregou" id="ap_vlrpalpd" type="text" readonly class="validate">
                                    <label for="ap_vlrpalpd">Valor Descarregou</label>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div id="test2" class="col s12">
                    <div id="test2" class="col s12">
                        <div class="row">
                            <div class="col s6">
                                <form action=""id="formTabsDesc">
                                    <div class="row">
                                        <div class="input-field col s8 m6 l6">
                                            <input placeholder="Vendedor Origem" id="1_nomven" type="text" readonly class="validate">
                                            <label for="1_nomven">Vendedor Origem</label>
                                        </div>
                                    </div>

                                    <div class="input-field col s8 m4 l4">
                                        <input placeholder="Valor" id="1_vlrpalp" type="text" readonly class="validate">
                                        <label for="1_vlrpalp">Valor</label>
                                    </div>
                                    <div class="input-field col s8 m4 l4">
                                        <input placeholder="Valor Bancou" id="1_vlrpalpf" type="text" readonly class="validate">
                                        <label for="1_vlrpalpf">Valor Bancou</label>
                                    </div>
                                    <div class="input-field col s8 m4 l4">
                                        <input placeholder="Valor Descarregou" id="1_vlrpalpd" type="text" readonly class="validate">
                                        <label for="1_vlrpalpd">Valor Descarregou</label>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection


@push('scripts')

<script>
    $(document).ready(function() {
        $('.modal').modal();


        $('ul.tabs').tabs();

        var table = $('#example').DataTable( {


            dom: 'Brtip',
            buttons: [
                {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL'
            },
                'excel',
                {
                    extend: 'print',
                    text: 'Imprimir',
                }
            ],


            scrollY: 480,
            scrollX:        true,
            scrollCollapse: true,
            paging:         false,
            Bfilter:        false,
            "aaSorting": [[2, "desc"],[4, "desc"]],


//            columnDefs: [
//                {
//                    targets: [ 0, 1, 2 ,3 ,4 ,5 ,6 ,7 ,8 ,9 ,10 ,11],
//                    className: 'mdl-data-table__cell--non-numeric'
//                }


//            ],

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

    //Editar
    function edit(urlEdit){
        document.getElementById("myForm").reset();
        document.getElementById("formTabs").reset();
//        document.getElementById("formTabsDesc").reset();
        $('#tbody_infoaposta').empty(); //Limpando a tabela

        jQuery.getJSON(urlEdit, function(data){

            if ( typeof data[0].palp1 !== "undefined" && data[0].palp1) {
                palpite = data[0].palp1
            }
            if ( typeof data[0].palp2 !== "undefined" && data[0].palp2) {
                palpite = palpite + ' - ' + data[0].palp2
            }
            if ( typeof data[0].palp3 !== "undefined" && data[0].palp3) {
                palpite = palpite + ' - ' + data[0].palp3
            }
            if ( typeof data[0].palp4 !== "undefined" && data[0].palp4) {
                palpite = palpite + ' - ' + data[0].palp4
            }
            if ( typeof data[0].palp5 !== "undefined" && data[0].palp5) {
                palpite = palpite + ' - ' + data[0].palp5
            }
            if ( typeof data[0].palp6 !== "undefined" && data[0].palp6) {
                palpite = palpite + ' - ' + data[0].palp6
            }
            if ( typeof data[0].palp7 !== "undefined" && data[0].palp7) {
                palpite = palpite + ' - ' + data[0].palp7
            }
            if ( typeof data[0].palp8 !== "undefined" && data[0].palp8) {
                palpite = palpite + ' - ' + data[0].palp8
            }
            if ( typeof data[0].palp9 !== "undefined" && data[0].palp9) {
                palpite = palpite + ' - ' + data[0].palp9
            }
            if ( typeof data[0].palp10 !== "undefined" && data[0].palp10) {
                palpite = palpite + ' - ' + data[0].palp10
            }
            if ( typeof data[0].palp11 !== "undefined" && data[0].palp11) {
                palpite = palpite + ' - ' + data[0].palp11
            }
            if ( typeof data[0].palp12 !== "undefined" && data[0].palp12) {
                palpite = palpite + ' - ' + data[0].palp12
            }
            if ( typeof data[0].palp13 !== "undefined" && data[0].palp13) {
                palpite = palpite + ' - ' + data[0].palp13
            }
            if ( typeof data[0].palp14 !== "undefined" && data[0].palp14) {
                palpite = palpite + ' - ' + data[0].palp14
            }
            if ( typeof data[0].palp15 !== "undefined" && data[0].palp15) {
                palpite = palpite + ' - ' + data[0].palp15
            }
            if ( typeof data[0].palp16 !== "undefined" && data[0].palp16) {
                palpite = palpite + ' - ' + data[0].palp16
            }
            if ( typeof data[0].palp17 !== "undefined" && data[0].palp17) {
                palpite = palpite + ' - ' + data[0].palp17
            }
            if ( typeof data[0].palp18 !== "undefined" && data[0].palp18) {
                palpite = palpite + ' - ' + data[0].palp18
            }
            if ( typeof data[0].palp19 !== "undefined" && data[0].palp19) {
                palpite = palpite + ' - ' + data[0].palp19
            }
            if ( typeof data[0].palp20 !== "undefined" && data[0].palp20) {
                palpite = palpite + ' - ' + data[0].palp20
            }
            if ( typeof data[0].palp21 !== "undefined" && data[0].palp21) {
                palpite = palpite + ' - ' + data[0].palp21
            }
            if ( typeof data[0].palp22 !== "undefined" && data[0].palp22) {
                palpite = palpite + ' - ' + data[0].palp22
            }
            if ( typeof data[0].palp23 !== "undefined" && data[0].palp23) {
                palpite = palpite + ' - ' + data[0].palp23
            }
            if ( typeof data[0].palp24 !== "undefined" && data[0].palp24) {
                palpite = palpite + ' - ' + data[0].palp24
            }
            if ( typeof data[0].palp25 !== "undefined" && data[0].palp25) {
                palpite = palpite + ' - ' + data[0].palp25
            }




            $('#nomvem_o').val(data[0].nomvem_o);
            $('#nomven').val(data[0].nomven);
            $('#numpulec').val(data[0].numpule);
            $('#datapo').val(DateChance(data[0].datapo));
            $('#deslot').val(data[0].deslot);
            $('#horlim').val(time_format(new Date(data[0].horlim)));
            $('#horsor').val(time_format(new Date(data[0].horsor)));
            $('#destipoapo').val(data[0].destipoapo);
            $('#descol').val(data[0].descol);
            $('#vaDesPalp').val(palpite);
            $('#vlrpalp').val((parseFloat(data[0].vlrpalpo)).formatMoney(2, ',', '.'));
            $('#vlrpresec').val((parseFloat(data[0].vlrpresec)).formatMoney(2, ',', '.'));
            $('#vlrpremol').val((parseFloat(data[0].vlrpremol)).formatMoney(2, ',', '.'));
            $('#vlrpresmj').val((parseFloat(data[0].vlrpresmj)).formatMoney(2, ',', '.'));
            $('#sitdes').val(data[0].sitdes);
            $('#infodesc').val(data[0].infodesc);

            infoAposta(data[0].numpule, data[0].seqpalp);


            var vnAP_VlrPreMotDesc = 0;
            var ap_vlrpresec = parseFloat(data[0].ap_vlrpresec);
            var ap_vlrpremol = parseFloat(data[0].ap_vlrpremol);
            var ap_vlrpresmj = parseFloat(data[0].ap_vlrpresmj);

            if ( ( ap_vlrpresec > ap_vlrpremol ) && ( ap_vlrpresec > ap_vlrpresmj ) ){
                vnAP_VlrPreMotDesc = ap_vlrpresec;
            } else if ( ( ap_vlrpremol > ap_vlrpresec ) && ( ap_vlrpremol > ap_vlrpresmj )) {
                vnAP_VlrPreMotDesc = ap_vlrpremol;
            } else if ( ( ap_vlrpresmj > ap_vlrpresec ) && ( ap_vlrpresmj > ap_vlrpremol )){
                vnAP_VlrPreMotDesc = ap_vlrpresmj;
            } else {
                vnAP_VlrPreMotDesc = 0;
            }

            var vnVlrLimExedido = vnAP_VlrPreMotDesc - parseFloat(data[0].vlrlimdesc);

            if ( vnVlrLimExedido < 0 ){
                vnVlrLimExedido = parseFloat(data[0].ap_vlrpalp) * parseFloat(data[0].ap_vlrcotacao) ;
            }


            $('#ap_vlrpalp').val((parseFloat(data[0].ap_vlrpalp)).formatMoney(2, ',', '.'));
            $('#ap_vlrcotacao').val((parseFloat(data[0].ap_vlrcotacao)).formatMoney(2, ',', '.'));
            $('#vlrlimdesc').val((parseFloat(data[0].vlrlimdesc)).formatMoney(2, ',', '.'));
            $('#ap_vlrpalpf').val((parseFloat(data[0].ap_vlrpalpf)).formatMoney(2, ',', '.'));
            $('#ap_vlrpalpd').val((parseFloat(data[0].ap_vlrpalpd)).formatMoney(2, ',', '.'));

            $('#vnAP_VlrPreMotDesc').val((vnAP_VlrPreMotDesc).formatMoney(2, ',', '.'));
            $('#vnVlrLimExedido').val((vnVlrLimExedido).formatMoney(2, ',', '.'));





            infoApostaDescarregada(data[0].numpule, data[0].seqpalp, data[0].seqdes);

        });

        $('#modaldescarga').modal('open');


        return false;
    }

    function infoAposta(numpule, seqpalp){
        var url = '/admin/descargasenviadas/infoaposta/'+numpule+'/'+seqpalp;
        jQuery.getJSON(url, function(data){
            for (var i = 0; i <data.length; i++){

                var newRow = $("<tr>");
                var cols = "";

                cols += '<td>'+data[i].seqcol+'</td>';
                cols += '<td>'+data[i].insecmol+'</td>';
                cols += '<td>'+parseFloat(data[i].vlrpalp).formatMoney(2, ',', '.')+'</td>';
                cols += '<td>'+parseFloat(data[i].vlrpalpf).formatMoney(2, ',', '.')+'</td>';
                cols += '<td>'+parseFloat(data[i].vlrpalpd).formatMoney(2, ',', '.')+'</td>';

                newRow.append(cols);
                $("#tbody_infoaposta").append(newRow);
            }
        });
        }


    function infoApostaDescarregada(numpule, seqpalp, seqdes){
        var url = '/admin/descargasenviadas/infoapostadescarregadas/'+numpule+'/'+seqpalp+'/'+seqdes;
        jQuery.getJSON(url, function(data){



            $('#1_nomven').val(data[0].nomven);
            $('#1_vlrpalp').val((parseFloat(data[0].vlrpalp)).formatMoney(2, ',', '.'));
            $('#1_vlrpalpf').val((parseFloat(data[0].vlrpalpf)).formatMoney(2, ',', '.'));
            $('#1_vlrpalpd').val((parseFloat(data[0].vlrpalpd)).formatMoney(2, ',', '.'));
        });
    }

    function DateChance(data) {
        var getDate = data.slice(0, 10).split('-'); //create an array
        var _date =getDate[2] +'/'+ getDate[1] +'/'+ getDate[0];
        return _date;

    }

    function time_format(date) {
        var hours = date.getHours();
        var minutes = date.getMinutes();
        var seg = date.getSeconds();

//        hours = hours % 12;
//        hours = hours ? hours : 12; // the hour '0' should be '12'
        minutes = minutes < 10 ? '0'+minutes : minutes;
//        var strTime = hours + ':' + minutes + ':' + seg;
        var strTime = hours + ':' + minutes;
//        return date.getMonth()+1 + "/" + date.getDate() + "/" + date.getFullYear() + " " + strTime;
        return strTime;
    }

    Number.prototype.formatMoney = function(c, d, t){
        var n = this, c = isNaN(c = Math.abs(c)) ? 2 : c, d = d == undefined ? "," : d, t = t == undefined ? "." : t, s = n < 0 ? "-" : "", i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", j = (j = i.length) > 3 ? j % 3 : 0;
        return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
    };
</script>
@endpush

