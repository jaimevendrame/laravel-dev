@extends('dashboard.templates.app')

@section('content')
    <script src="{{url('assets/js/jquery-3.2.0.min.js')}}"></script>

    <div class="section">
        <div class="row">
            <div class="col s12">
                <div class="card">
                    <div class="card-content">
                            @forelse ($baseAll as $bases)
                        <form class="form-group" id="form-cad-edit2" method="post" action="/admin/apostaspremiadas" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            @empty
                                
                            @endforelse
                            <div class="row" style="margin-bottom:0px;">

                                <div class="input-field col s12 m4 l2">
                                    <input id="nr_pule" name="nr_pule" type="tel" class="validate">
                                    <label class="active" for="nr_pule">Nº Aposta</label>
                                </div>

                                

                                <div class="input-field col s6 m4 l2">
                                    @if(isset($p_situacao))
                                        <input  id="datIni" name="datIni" type="date" class="datepicker"
                                                placeholder ="Data inicial" @if($p_situacao == '0') disabled @else @endif>
                                        @else
                                        <input  id="datIni" name="datIni" type="date" class="datepicker"
                                                placeholder ="Data inicial"  disabled >
                                        @endif

                                </div>
                                <div class="input-field col s6 m4 l2">
                                    @if(isset($p_situacao))
                                     <input id="datFim" name="datFim" type="date" class="datepicker"
                                             placeholder ="Data final"  @if($p_situacao == '0') disabled @else @endif>
                                        @else
                                        <input id="datFim" name="datFim" type="date" class="datepicker"
                                               placeholder ="Data final" disabled>
                                    @endif

                                </div>

                                <div class="input-field col s12 m6 l2">
                                    <select multiple name="sel_vendedor[]">
                                        <option value="" disabled selected>Selecionar Vendedores</option>
                                        @forelse($baseAll as $bases)
                                            @if( isset($ideven) && !empty($ideven))
                                            <option value="{{$bases->ideven}}" {{ $bases->ideven == $ideven  ? 'selected' : '' }} >{{$bases->ideven}}-{{$bases->nomven}}</option>
                                                @elseif(isset($ideven2) && (is_array($ideven2))) <option value="{{$bases->ideven}}" @forelse($ideven2 as $select) {{ $bases->ideven == $select  ? 'selected' : '' }} @ @empty @endforelse >{{$bases->ideven}}-{{$bases->nomven}}</option>
                                             @else
                                                <option value="{{$bases->ideven}}">{{$bases->ideven}}-{{$bases->nomven}}</option>
                                            @endif
                                        @empty
                                            <option value="" disabled selected>Nenhuma base</option>
                                        @endforelse

                                    </select>
                                    <label>Bases selecionadas</label>
                                </div>
                                <div class="input-field col s12 m6 l2">
                                    <button class="btn waves-effect waves-light" type="submit" name="action">Mostrar
                                        <i class="material-icons right">send</i>
                                    </button>
                                </div>
                            </div>

                                
                                <div class="row" style="margin-bottom:0px;">
                                    <div class="input-field col s12 m6 l4">
                                        <input type="text" name="teste" id="default" list="combobox" placeholder="Nenhum">
                                        <datalist id="combobox" name="default">
                                            @if(!empty($revendedores))
                                                @forelse($revendedores as $r)
                                                    <option data-value="{{$r->idreven}}" value="{{$r->nomreven}}" ></option>
                                                @empty
                                                    <option value="" disabled selected>Nenhuma Revendedor</option>
                                                @endforelse
                                            @endif
                                        </datalist>
                                        <label>Revendedor</label>
                                    </div>

                                    <div class="input-field col s12 m6 l4">

                                            <input id="modalidades" name="modalidades" type="text" style="text-transform:uppercase" class="validate">
                                            <label class="active" for="modalidades">Modalidade</label>
                
                                    </div> 


                                    <div class="input-field col s12 m6 l4">
                                        <select multiple name="sel_lotodia[]">
                                            <option value="" disabled selected>Selecionar</option>
                                            @forelse($loterias as $s)
    
                                                @if(!empty($idehor))
                                                    <option value="{{$s->deshor}}" @forelse($idehor as $select) {{ $s->idehor == $select  ? 'selected' : '' }} @ @empty @endforelse >{{$s->idehor}}-{{$s->deshor}}</option>
    
                                                    @else
                                                    <option value="{{$s->deshor}}">{{$s->deshor}}</option>
    
                                                @endif
    
                                            @empty
                                                <option value="" disabled selected>Nenhum</option>
                                            @endforelse
    
                                        </select>
                                        <label>Loterias</label>
                                    </div>


                                </div>

                     

                            <div class="row left-align padding-materialize  z-depth-2 ">
                                <div class="input-field col s12 m3 l3 ">
                                    @if(isset($p_situacao))
                                        @if($p_situacao == '0')
                                            <input class="with-gap" data-valor="ok" name="group1" type="radio" id="test1"  checked="checked"  onclick="handleClick(this);" value="0"/>
                                            @else
                                            <input class="with-gap" name="group1" type="radio" id="test1"  onclick="handleClick(this);" value="0"/>
                                            @endif
                                        @else
                                        <input class="with-gap" name="group1" type="radio" id="test1"  checked="checked"  onclick="handleClick(this);" value="0"/>
                                        @endif

                                    <label for="test1">Aguardando Liberação</label>
                                </div>
                                <div class="input-field col s12 m3 l3 ">
                                    @if(isset($p_situacao))
                                        @if($p_situacao == '1')
                                        <input class="with-gap" name="group1" type="radio" id="test2" checked="checked" onclick="handleClick(this);" value="1"/>
                                        @else
                                        <input class="with-gap" name="group1" type="radio" id="test2" onclick="handleClick(this);" value="1"/>
                                        @endif
                                    @else
                                    <input class="with-gap" name="group1" type="radio" id="test2" onclick="handleClick(this);" value="1"/>
                                    @endif
                                    <label for="test2">Não Liberados</label>
                                </div>
                                <div class="input-field col s12 m3 l3">
                                    @if(isset($p_situacao))
                                        @if($p_situacao == '2')
                                            <input class="with-gap" name="group1" type="radio" id="test3"  checked="checked" onclick="handleClick(this);" value="2"/>
                                        @else
                                            <input class="with-gap" name="group1" type="radio" id="test3"  onclick="handleClick(this);" value="2"/>
                                        @endif
                                    @else
                                        <input class="with-gap" name="group1" type="radio" id="test3"  onclick="handleClick(this);" value="2"/>
                                    @endif
                                        <label for="test3">Liberados</label>
                                </div>
                                
                                @php
                                    $totalPulesValido = 0;
                                    foreach ($data as $key){
                                        $totalPulesValido += $key->vlrpre;
                                    }

                                @endphp
                                
                                <div class="col s12 m3 l3 z-depth-2 red hoverable">
                                    <div class="row right-align padding-materialize">
                                        <p class="white-text">Total de Prêmios:</p>
                                        <h5 class="white-text">@php echo number_format($totalPulesValido, 2, ',', '.'); @endphp</h5>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="row left-align padding-materialize  z-depth-2" id="pagar_premio">
                            
                                <div class="col s4 m3 l2 ">
                                    <form id="frm-paybet" name="frm-paybet" method="post"enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        <input id="input-paybet" name="dados" type="hidden" value="0"/>

                                    </form>
                                    <br>
                                    <br>
                                    <button class="btn waves-effect waves-light" onclick="teste();" name="action">Liberar
                                    </button>
                                    
                                    
                                </div>
                                <div class="col s4 m3 l3 ">
                                    <h4>Total: R$</h4>
                                    <br>
                                </div>

                                <div class="col s4 m3 l3 ">
                                    <h4 class="resultado">0,00</h4>
                                    <br>
                                </div>
                            
                          
                        </div>



                        @if(!empty($data))
                        <form id="frm-example">
                            <table class="mdl-data-table display" id="apostas_premiada"  cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Aposta Nº</th>
                                    <th>Data p/ Sorteio</th>
                                    <th>Horário</th>
                                    <th>Valor Palpite</th>
                                    <th>Valor Prêmio</th>
                                    <th>Limite p/ Pagamento</th>
                                    <th>Dias restante</th>
                                    <th>Revendedor</th>
                                    <th>Modalidade Apostas</th>
                                    <th>Palpite</th>
                                    <th>Colocação</th>
                                    <th>Data Liberação</th>
                                    <th>Manual</th>
                                </tr>
                                </thead>
                                <tbody>
                                {{--//#fffde7--}}
                                @php $l = 0 @endphp
                                @forelse($data as $apostas)
                                    @php $l += 1 @endphp

                                    <tr>
                                        <td>
                                            <div class="check-on" @if(isset($p_situacao) && ($p_situacao == '2')) style="display: none;"@endif>
                                                <input  data-dados="{{$apostas->idbase}} {{$apostas->idven}} {{$apostas->idreven}} {{$apostas->idter}} {{$apostas->idapo}} {{$apostas->numpule}} {{$apostas->seqpalp}} NAO "
                                                        type="checkbox" id="{{$l}}" class="filled-in"  onclick="handleClick2();"  value="{{$apostas->vlrpre}}"/><label  for="{{$l}}">&nbsp;</label>

                                            </div>
                                        </td>
                                        <td><a id="link-modal" class="classe1 modal-trigger" href="#" onclick='openModal1("/admin/apostas/view/{{$apostas->numpule}}/{{$apostas->ideven}}")'>
                                                {{$apostas->numpule}} </a></td>
                                        <td>{{Carbon\Carbon::parse($apostas->datapo)->format('d/m/Y')}}</td>
                                        <td>{{$apostas->deshor}}</td>
                                        <td class="valor">{{ number_format($apostas->vlrpalp, 2, ',', '.') }}</td>
                                        <td>{{ number_format($apostas->vlrpre, 2, ',', '.') }}</td>
                                        <td>{{Carbon\Carbon::parse($apostas->datlimpre)->format('d/m/Y')}}</td>
                                        <td>
                                        @php
                                        $dataAtual = date('Y-m-d');
                                        $dataLim = Carbon\Carbon::parse($apostas->datlimpre)->format('Y/m/d');
                                        $date1=date_create($dataAtual);
                                        $date2=date_create($dataLim);
                                        if($dataLim > $dataAtual){
                                            $diff=date_diff($date1,$date2);
                                            echo $diff->format("%a");
                                        }

                                        @endphp
                                        </td>
                                        <td>{{$apostas->nomreven}}</td>
                                        <td>{{$apostas->destipoapo}}</td>
                                        <td>
                                            @if( isset($apostas->palp1) ){{$apostas->palp1}}@endif

                                            @if( isset($apostas->palp2) ){{'- '.$apostas->palp2}}@endif

                                            @if( isset($apostas->palp3) ) {{'- '.$apostas->palp3}} @endif

                                            @if( isset($apostas->palp4) ){{'- '.$apostas->palp4}}@endif

                                            @if( isset($apostas->palp5) ){{'- '.$apostas->palp5}}@endif

                                            @if( isset($apostas->palp6) ){{'- '.$apostas->palp6}}@endif

                                            @if( isset($apostas->palp7) ){{'- '.$apostas->palp7}}@endif

                                            @if( isset($apostas->palp8) ){{'- '.$apostas->palp8}}@endif

                                            @if( isset($apostas->palp9) ){{'- '.$apostas->palp9}}@endif

                                            @if( isset($apostas->palp10) ){{'- '.$apostas->palp10}}@endif

                                            @if( isset($apostas->palp11) ){{'- '.$apostas->palp11}}@endif

                                            @if( isset($apostas->palp12) ){{'- '.$apostas->palp12}}@endif

                                            @if( isset($apostas->palp13) ){{'- '.$apostas->palp13}}@endif

                                            @if( isset($apostas->palp13) ){{'- '.$apostas->palp13}}@endif

                                            @if( isset($apostas->palp14) ){{'- '.$apostas->palp14}}@endif

                                            @if( isset($apostas->palp15) ){{'- '.$apostas->palp15}}@endif

                                            @if( isset($apostas->palp16) ){{'- '.$apostas->palp16}}@endif

                                            @if( isset($apostas->palp17) ){{'- '.$apostas->palp17}}@endif

                                            @if( isset($apostas->palp18) ){{'- '.$apostas->palp18}}@endif

                                            @if( isset($apostas->palp19) ){{'- '.$apostas->palp19}}@endif

                                            @if( isset($apostas->palp20) ){{'- '.$apostas->palp20}}@endif

                                            @if( isset($apostas->palp21) ){{'- '.$apostas->palp21}}@endif

                                            @if( isset($apostas->palp22) ){{'- '.$apostas->palp22}}@endif

                                            @if( isset($apostas->palp23) ){{'- '.$apostas->palp23}}@endif

                                            @if( isset($apostas->palp24) ){{'- '.$apostas->palp24}}@endif

                                            @if( isset($apostas->palp25) ){{'- '.$apostas->palp25}}@endif
                                        </td>
                                        <td>{{$apostas->descol}}</td>
                                        <td>{{Carbon\Carbon::parse($apostas->datlimpre)->format('d/m/Y')}} - {{Carbon\Carbon::parse($apostas->horlibpre)->format('H:i:s')}} </td>
                                        <td>{{$apostas->prelibmanual}}</td>
                                    </tr>

                                @empty
                                    <tr>
                                        nenhum registro encontrado!
                                    </tr>
                                @endforelse
                                </tbody>
                             
                            </table>
                        </form>
                        @else
                        <p>Nenhum registro encontrado!</p>
                        @endif

                   
            </div>
            <div class="row">

                <div class="row">

                </div>
              <!--  <div class="row">
                    <div class="col s12 m12 l3 z-depth-2 gray hoverable">
                        <div class="row">
                            <div class="col s6 m6 l6 valign-wrapper"  style="padding-top: 12%">
                                <div id="pagar_premio">
                                    <form id="frm-paybet" name="frm-paybet" method="post"enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        <input id="input-paybet" name="dados" type="hidden" value="0"/>

                                    </form>
                                    <button class="btn waves-effect waves-light" onclick="teste();" name="action">Liberar
                                    </button>
                                </div>
                            </div>
                            <div class="col s6 m6 l6 right-align">
                                    <h4 class="right-align">Total: R$</h4>
                                <h4 class="resultado">0,00</h4>
                            </div>
                        </div>
                    </div>

                </div>-->
            </div>
        </div>
       
    </div>
    <div id="aposta" class="modal modal2">
        <div class="right-align">
            <a href="#!" class=" btn modal-action modal-close waves-effect waves-light red "><i class=" Tiny material-icons">close</i></a>
        </div>
        <div class="modal-content">
            <h4>Visualizar Aposta</h4>
            <div id="modal_content" class="row">
                <div class="row">
                    <div class="input-field col s12 m2">
                        <input  readonly id="n_aposta" type="text" class="validate" value="0000">
                        <label class="active" for="n_aposta">Nº Aposta</label>
                    </div>
                    <div class="input-field col s12 m2">
                        <input  readonly id="vlr_aposta" type="text" class="validate" value="0,00">
                        <label class="active" for="vlr_aposta">Valor</label>
                    </div>
                    <div class="input-field col s12 m4">
                        <input  readonly id="revendedor" type="text" class="validate" value="Revendedor">
                        <label class="active" for="revendedor">Revendedor</label>
                    </div>
                    <div class="input-field col s12 m4">
                        <input  readonly id="vendedor" type="text" class="validate" value="Vendedor">
                        <label class="active" for="vendedor">Vendedor</label>
                    </div>
                </div>
                <div class="scroll">
                    <div class="row">
                        <table id="tb-apostas" class="display mdl-data-table" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>Modalidade</th>
                                <th>Palpites</th>
                                <th>Colocação</th>
                                <th>Valor</th>
                                <th>P/Dia</th>
                                <th>Horário</th>
                                <th>Situação</th>
                                <th>Data Envio</th>
                                <th>Hora Envio</th>
                                <th>Data Canc</th>
                                <th>Hora Canc</th>
                                <th>Cotação</th>
                                <th>Vlr Prêmio</th>
                                <th>Vlr Palp Bancou</th>
                                <th>Vlr Palp Desc</th>
                                <th>Prêmio Seco</th>
                                <th>Prêmio Molhado</th>
                                <th>Prêmio SecMol</th>
                                <th>Prêmio Bancou</th>
                            </tr>
                            </thead>
                            <tbody id="tbody_aposta">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')

<script type="text/javascript" src="{{url('js/jquery.mask.js')}}"></script>
<script>

            function teste() {

                $.confirm({
                    theme: 'supervan',
                    title: 'Pagar prêmios?',
                    content: 'Pagar prêmio das apostas selecionadas',
                    type: 'green',
                    buttons: {
                        ok: {
                            text: "SIM",
//                            btnClass: 'btn-primary',
                            keys: ['enter'],
                            action: function(){
                                payBet();
                                var dadosForm = jQuery('#frm-paybet').serialize();
                                console.log(dadosForm);

                                jQuery.ajax({
                                url: '/admin/apostaspremiadas/paybet',
                                data: dadosForm,
                                method: 'POST'

                            }).done(function (data) {

                                if (data > '0') {
                                    $.confirm({
                                        theme: 'supervan',
                                        title: 'Pagamento!',
                                        content: 'Pagamento realizado com sucesso!',
                                        buttons: {
                                            confirm: function () {
                                                document.getElementById("form-cad-edit2").submit();
                                            }

                                        }
                                    });



                                } else {
                                    $.alert({
                                        title: 'Pagamento!',
                                        content: 'Falha ao pagar!',
                                    });

                                }
                            }).fail(function () {
                                    $.confirm({
                                        theme: 'supervan',
                                        title: 'Enviar Dados!',
                                        content: 'Falha ao enviar dados!!',
                                        buttons: {
                                            confirm: function () {
                                                document.getElementById("form-cad-edit2").submit();
                                            }

                                        }
                                    });

                            });
                            return false;
                            }
                        },
                        NO:{
                            text: "NÃO",
                            cancel: function(){
                                console.log('the user clicked cancel');
                            }
                        }

                    }
                });
            };


</script>
<script>
    var currentValue = 0;
    function handleClick(myRadio) {
//        alert('Old value: ' + currentValue);
//        alert('New value: ' + myRadio.value);
        currentValue = myRadio.value;


        if (currentValue > 0){
            document.getElementById("datIni").disabled = false;
            document.getElementById("datFim").disabled = false;
            document.getElementById("form-cad-edit2").submit();

        } else {
            document.getElementById("datIni").disabled = true;
            document.getElementById("datFim").disabled = true;
            document.getElementById("form-cad-edit2").submit();


        }
        if (currentValue == 2){
            $(".check-on").hide();
            document.getElementById("form-cad-edit2").submit();

        } else {
            $(".check-on").show();
            document.getElementById("form-cad-edit2").submit();

        }

    }
</script>


<script>
    function id( el ){
        return document.getElementById( el );
    }

    function handleClick2() {


        var inputs = id('apostas_premiada').getElementsByTagName('input');
        var  valor = 0;
        var idel =';'
        for( var i=0; i<inputs.length; i++ )
        {
            if( inputs[i].type=='checkbox' )
            {
                 inputs[i].value;
                if (inputs[i].checked){
                    valor = parseFloat(valor) + parseFloat(inputs[i].value);
                }
            }

            if (valor > 0){
                $("#pagar_premio").show();
            } else {
                $("#pagar_premio").hide();
            }

            $(".resultado").html(valor.toFixed(2).replace('.',','));
        }
    }
    function payBet() {

        var inputs = id('apostas_premiada').getElementsByTagName('input');

        var  valor ='';
        var idel =''
        for( var i=0; i<inputs.length; i++ )
        {
            if( inputs[i].type=='checkbox' )
            {
                inputs[i].value;
                if (inputs[i].checked){
                    idel = inputs[i].id;
                    valor += $("#"+idel ).data('dados');
                }
            }
        }
        console.log(valor);
 //       alert(valor);

        $("#input-paybet").val(valor);

        if (valor != ''){
            return true;
        } else {
            return false;
        }
    }
</script>
<script>
    $(document).ready(function() {


        $("#pagar_premio").hide();

        $('#nr_pule').mask('####################'), {reverse: true};

        var table = $('#apostas_premiada').DataTable( {

            dom: 'fBrtip',
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


            scrollY: 215,
            scrollX:        true,
            scrollCollapse: true,
            paging:         false,
            Bfilter:        true,
            "aaSorting": [3, "desc"],


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
        // Handle form submission event
        $('#frm-example').on('submit', function(e){
            var form = this;

            var rows_selected = table.column(0).checkboxes.selected();

            // Iterate over all selected checkboxes
            $.each(rows_selected, function(index, rowId){
                // Create a hidden element
                $(form).append(
                    $('<input>')
                        .attr('type', 'hidden')
                        .attr('name', 'id[]')
                        .val(rowId)
                );
            });
        });

        // #myInput is a <input type="text"> element
        $('#myInput').on( 'keyup', function () {
            table.search( this.value ).draw();
        } );



    });
</script>
@endpush


@push('modal')
<script type="application/javascript">


    $(document).ready(function(){



        //init the modal
        $('.modal').modal();

        $('#tb-apostas').DataTable({

            dom: 'rt',
//            scrollY: 900,
//            scrollX:        true,
//            scrollCollapse: false,
            paging:         false,
            Bfilter:        false,
            "searching": false,
            "pagination": false,

            "columns": [
                { "width": "20%" },
                null,
                null,
                null,
                null
            ],


            columnDefs: [
                {
                    targets: [ 0, 1, 2 ,3 ,4 ,5 ,6 ,7 ,8 ,9 ,10 ,11,12,13,14,15,16,17,18],
                    className: 'mdl-data-table__cell--non-numeric'
                }
            ]
        });


    });


    function openModal1(url) {

        $('#tbody_aposta').empty(); //Limpando a tabela

        $('#aposta').modal('open');


        jQuery.getJSON(url, function (data) {

            var vlrPalp = 0

            for (var i = 0; i <data.length; i++){

                var newRow = $("<tr>");
                var cols = "";
                var palp = [data[i].palp1];


                if (data[i].palp2){
                    palp.push(data[i].palp2);
                }
                if (data[i].palp3){
                    palp.push(data[i].palp3);
                }
                if (data[i].palp4){
                    palp.push(data[i].palp4);
                }
                if (data[i].palp5){
                    palp.push(data[i].palp5);
                }
                if (data[i].palp6){
                    palp.push(data[i].palp6);
                }
                if (data[i].palp7){
                    palp.push(data[i].palp7);
                }
                if (data[i].palp8){
                    palp.push(data[i].palp8);
                }
                if (data[i].palp9){
                    palp.push(data[i].palp9);
                }
                if (data[i].palp10){
                    palp.push(data[i].palp10);
                }
                if (data[i].palp11){
                    palp.push(data[i].palp11);
                }
                if (data[i].palp12){
                    palp.push(data[i].palp12);
                }
                if (data[i].palp13){
                    palp.push(data[i].palp13);
                }
                if (data[i].palp14){
                    palp.push(data[i].palp14);
                }
                if (data[i].palp15){
                    palp.push(data[i].palp15);
                }
                if (data[i].palp16){
                    palp.push(data[i].palp16);
                }
                if (data[i].palp17){
                    palp.push(data[i].palp17);
                }
                if (data[i].palp18){
                    palp.push(data[i].palp18);
                }
                if (data[i].palp19){
                    palp.push(data[i].palp19);
                }
                if (data[i].palp20){
                    palp.push(data[i].palp20);
                }
                if (data[i].palp21){
                    palp.push(data[i].palp21);
                }
                if (data[i].palp22){
                    palp.push(data[i].palp22);
                }
                if (data[i].palp23){
                    palp.push(data[i].palp23);
                }
                if (data[i].palp24){
                    palp.push(data[i].palp24);
                }
                if (data[i].palp25){
                    palp.push(data[i].palp25);
                }

                var todosPalp = palp.join('-');

                var vlrpalp = data[i].vlrpalp;
                var numpule = data[i].numpule;
                var idreven = data[i].idbase +' '+ data[i].nomreven;
                var nomven = data[i].idven +' '+ data[i].nomven;


                if(data[i].sitapo == 'V'){

                    var vaSituapo = 'VALIDO';
                } else if(data[i].sitapo == 'CAN'){
                    var vaSituapo = 'CANCELADO';
                } else {
                    var vaSituapo = 'PREMIADO';
                
                }



                var datApo = DateChance(data[i].datapo);
                var datEnv = DateChance(data[i].datenv);

                if ((data[i].datcan != null)){
                    var datCan = DateChance(data[i].datcan);
                } else {
                    var datCan = '';
                }

                if ((data[i].horcan != null)){
                    var horCan = time_format(new Date(data[i].horcan));
                } else {
                    var horCan = '';
                }

                var horEnv = time_format(new Date(data[i].horenv));

                vlrPalp += parseFloat(vlrpalp);


                cols += '<td>'+data[i].destipoapo+'</td>';
                cols += '<td>'+todosPalp+'</td>';
                cols += '<td>'+data[i].descol+'</td>';
                cols += '<td>'+parseFloat(vlrpalp).toFixed(2).replace(".", ",")+'</td>';
                cols += '<td>'+datApo+'</td>';
                cols += '<td>'+data[i].deshor+'</td>';
                cols += '<td >'+vaSituapo+'</td>';
                cols += '<td>'+datEnv+'</td>';
                cols += '<td>'+horEnv+'</td>';
                cols += '<td>'+datCan+'</td>';
                cols += '<td>'+horCan+'</td>';
                cols += '<td>'+parseFloat(data[i].vlrcotacao).toFixed(2).replace(".", ",")+'</td>';
                cols += '<td>'+parseFloat(data[i].vlrpre).toFixed(2).replace(".", ",")+'</td>';
                cols += '<td>'+parseFloat(data[i].vlrpalpf).toFixed(2).replace(".", ",")+'</td>';
                cols += '<td>'+parseFloat(data[i].vlrpalpd).toFixed(2).replace(".", ",")+'</td>';
                cols += '<td>'+parseFloat(data[i].vlrpresec).toFixed(2).replace(".", ",")+'</td>';
                cols += '<td>'+parseFloat(data[i].vlrpremol).toFixed(2).replace(".", ",")+'</td>';
                cols += '<td>'+parseFloat(data[i].vlrpresmj).toFixed(2).replace(".", ",")+'</td>';
                cols += '<td>'+parseFloat(data[i].vlrpre).toFixed(2).replace(".", ",")+'</td>';


                newRow.append(cols);
                $("#tbody_aposta").append(newRow);
            }

            $('#vlr_aposta').val(vlrPalp.toFixed(2).replace(".", ","));
            $('#n_aposta').val(numpule);
            $('#revendedor').val(idreven);
            $('#vendedor').val(nomven);
        });

    };

    function DateChance(data) {
        var getDate = data.slice(0, 10).split('-'); //create an array
        var _date =getDate[2] +'/'+ getDate[1] +'/'+ getDate[0];
        return _date;

    }

    function time_format(date) {
        var hours = date.getHours();
        var minutes = date.getMinutes();
        var seg = date.getSeconds();
        hours = hours % 12;
        hours = hours ? hours : 12; // the hour '0' should be '12'
        minutes = minutes < 10 ? '0'+minutes : minutes;
        var strTime = hours + ':' + minutes + ':' + seg;
//        return date.getMonth()+1 + "/" + date.getDate() + "/" + date.getFullYear() + " " + strTime;
        return strTime;
    }

</script>

@endpush
