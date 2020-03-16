@extends('dashboard.templates.app')

@section('content')
   

    <div class="section">
        
        <div class="row">
            <div class="col s12">
                
                    <div class="card-content">
                        <form class="form-group" id="form-cad-edit" method="post" action="/admin/movimentoscaixa/{{$p_ideven}}" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            <div class="row">
                                <div class="input-field col s6 m2">
                                    <input id="datIni" name="datIni" type="date" class="datepicker"
                                           placeholder ="Data inicial">

                                </div>
                                <div class="input-field col s6 m2">
                                        
                                     <input id="datFim" name="datFim" type="date" class="datepicker"
                                             placeholder ="Data final" >
                                             
                                </div>

                                <div class="input-field col s12 m3">
                                    <select name="sel_revendedor">
                                        <option value="" >Nenhum</option>
                                        @if(!empty($data))
                                            @forelse($reven as $r)
                                                <option value="{{$r->idreven}}" @if( isset($sel_revendedor)) {{ $r->idreven == $sel_revendedor  ? 'selected' : '' }} @endif>{{$r->nomreven}}</option>
                                            @empty
                                                <option value="" disabled selected>Nenhuma Revendedor</option>
                                            @endforelse
                                        @endif

                                    </select>
                                    <label>Revendedor</label>
                                </div>

                             <!--   <div class="input-field col s12 m6 l4">
                                    <input type="text" name="sel_revendedor2" id="default" list="combobox" placeholder="Nenhum">
                                    <datalist id="combobox" name="default">
                                        @if(!empty($data))
                                            @forelse($reven as $r)
                                                <option data-value="{{$r->idreven}}" value="{{$r->nomreven}}" ></option>
                                            @empty
                                                <option value="" disabled selected>Nenhuma Revendedor</option>
                                            @endforelse
                                        @endif
                                    </datalist>
                                    <label>Revendedor</label>
                                </div> -->



                                <div class="input-field col s12 m2">
                                    <select name="sel_cobrador">
                                        <option value="" >Nenhum</option>
                                        @if(!empty($cobrador))
                                            @forelse($cobrador as $cob)
                                                <option value="{{$cob->idcobra}}"  @if(isset($sel_cobrador)) {{ $cob->idcobra == $sel_cobrador  ? 'selected' : '' }} @endif>{{$cob->nomcobra}}</option>
                                            @empty
                                                <option value="" disabled selected>Nenhuma Cobrador</option>
                                            @endforelse
                                        @endif
                                    </select>
                                    <label>Cobradores</label>
                                </div>
                                <div class="input-field col s6 m2">
                                    <button class="btn waves-effect waves-light" type="submit" name="action">Mostrar
                                        <i class="material-icons right">send</i>
                                    </button>
                                </div>
                                <div class="input-field col s6 m1">
                                    <!-- Modal Trigger -->
                                    <a class="waves-effect waves-light  btn-floating red modal-trigger" href="#modal_movcaixa"><i class="material-icons">add</i></a>
                                </div>

                            </div>


                        </form>
                    </div>
            </div>
        </div>
            <div class="row">
                <div class="col s12">
                        @if(!empty($data))
                        <table class="mdl-data-table " id="movcaixa"  cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>Revendedor</th>
                                <th>seqordem</th>
                                <th>Valor</th>
                                <th>Tipo Mov.</th>
                                <th>Saldo Anterior</th>
                                <th>Saldo Após Mov.</th>
                                <th>Data Mov.</th>
                                <th>Horário</th>
                                <th>Cobrador</th>
                                <th>Usuário Mov.</th>

                            </tr>
                            </thead>
                            <tbody>

                            @forelse($data as $movi)

                                <tr>
{{--                                    <td>{{ $movi->nomreven }}</td>--}}
                                    <td>@php if (strlen($movi->nomreven) <=15) {
                                    echo $movi->nomreven;
                                    } else {
                                    echo substr($movi->nomreven, 0, 15) . '...';
                                    }@endphp</td>
                                    <td>{{ $movi->seqordem }}</td>
                                    <td @if ($movi->tipomov == 'RECEBIMENTO') class='white-text' bgcolor='#4caf50'
                                        @elseif($movi->tipomov == 'DESPESA') class='white-text' bgcolor='#ff9800'
                                        @elseif($movi->tipomov == 'ESTORNO PAG.') class='white-text' bgcolor='#dd2c00'
                                        @elseif($movi->tipomov == 'ESTORNO REC.') class='white-text' bgcolor='#0277bd'
                                        @else class='white-text' bgcolor='#e53935'@endif style="text-align: left;">
                                        <b>{{ number_format($movi->vlrmov, 2, ',', '.') }}</b>
                                    </td>
                                    <td style="text-align: left;">
                                        <b>{{ $movi->tipomov }}</b>
                                    </td>
                                    <td><b>{{ number_format($movi->saldoant, 2, ',', '.') }}</b></td>
                                    <td>{{ number_format($movi->saldoatu, 2, ',', '.') }}</td>
                                    <td> {{ Carbon\Carbon::parse($movi->datmov)->format('d/m/Y') }}</td>
                                    @php
                                      $hormov = date('H:i:s', strtotime($movi->hormov));
                                    @endphp
                                    {{--<td>{{ Carbon\Carbon::parse($movi->hormov)->format('H:m:s') }}</td>--}}
                                    <td>{{ $hormov }}</td>

                                    <td>{{ $movi->nomcobra }}</td>
                                    <td>{{ $movi->nomeusumov }}</td>
                                    
                                </tr>
                            @empty
                                <tr>
                                    nenhum registro encontrado!
                                </tr>
                            @endforelse

                            <tfoot>
                            @php
                                $recebimento = 0;

                                $pagamento = 0;

                                $despesas = 0;
                                $estornoRecebimento = 0;
                                $estornoPagamento = 0;
                                $saldoCaixa = 0;
                                $debitoPag = 0;
                                $saldoPagamento =0;

                                foreach($data as $key) {



                               //     if ( ($key->tipomov == 'RECEBIMENTO') || ($key->tipomov == 'ESTORNO REC.') || ($key->tipomov == 'DEBITO REC.') || ($key->tipomov == 'CREDITO REC.')){

                                //        $recebimento += $key->vlrmov;
                                //    }

                                if ( ($key->tipomov == 'RECEBIMENTO') ){

                                $recebimento += $key->vlrmov;
                                }
                                 //   if ( ($key->tipomov == 'PAGAMENTO') || ($key->tipomov == 'ESTORNO PAG.') || ($key->tipomov == 'DEBITO PAG.') || ($key->tipomov == 'CREDITO PAG.')){

                                  //      $pagamento += $key->vlrmov;
                                 //   }
                                 if ( ($key->tipomov == 'DEBITO PAG.')){

                                    $debitoPag += $key->vlrmov;
                                    }

                                    if ( ($key->tipomov == 'PAGAMENTO')){

                                    $pagamento += $key->vlrmov;
                                    
                                    }

                                   

                                    if ( ($key->tipomov == 'DESPESA') ){

                                        $despesas += $key->vlrmov;
                                    }

                                    if ( ($key->tipomov == 'ESTORNO REC.') ){

                                    $estornoRecebimento += $key->vlrmov;
                                    }

                                    if ( ($key->tipomov == 'ESTORNO PAG.') ){

                                    $estornoPagamento += $key->vlrmov;
                                    }
                                    $saldoPagamento = $pagamento - $debitoPag;
                                     $saldoCaixa = $recebimento - $saldoPagamento -$estornoRecebimento + $estornoPagamento;

                                     

                                            }
                            @endphp
                            
                            </tfoot>


                        </table>

                        <div class="row"></div>

                        <div class="row">

                            <div class="col s12 m12 l3">
                                <div class="col s12 z-depth-2 green hoverable">                                  
                                    <h5 class="white-text left-align">Total Recebimento:</h5>
                                                       
                                    <h5 class="white-text right-align">@php echo number_format($recebimento, 2, ',', '.'); @endphp</h5>                                   
                                </div>
                            </div>

                            <div class="col s12 m12 l3">
                                <div class="col s12 z-depth-2 red darken-1 hoverable">       
                                        <h5 class="white-text left-align">Total Pagamento:</h5>         
                                    
                                        <h5 class="white-text right-align">@php echo number_format($saldoPagamento, 2, ',', '.'); @endphp</h5>                                 
                                </div>
                            </div>

                            <div class="col s12 m12 l3">
                                <div class="col s12 z-depth-2 orange hoverable">
                                        <h5 class="white-text left-align"> Despesas:</h5>
                                 
                                        <h5 class="white-text right-align">@php echo number_format($despesas, 2, ',', '.'); @endphp</h5>
                                </div>

                            </div>
          
                            <div class="col s12 m12 l3">

                                    <?php if ($saldoCaixa >= 0) { ?>
                            
                                        <div class="col s12 z-depth-2 green hoverable">
                                                <h5 class="white-text left-align">Saldo Caixa:</h5>
        
                                                <h5 class="white-text right-align">@php echo number_format($saldoCaixa, 2, ',', '.');  @endphp</h5>
                                        </div>
                                      
                                    <?php } ?>
                                    <?php if ($saldoCaixa < 0) { ?>
                            
                                        <div class="col s12 z-depth-2 red accent-4 hoverable">
                                                <h5 class="white-text left-align">Saldo Caixa:</h5>
        
                                                <h5 class="white-text right-align">@php echo number_format($saldoCaixa, 2, ',', '.');  @endphp</h5>
                                        </div>
                                      
                                    <?php } ?>


                               
                            </div>
                            <div class="row"></div>
                            <div class="row">
                                <?php if ($estornoRecebimento > 0) { ?>
                            
                                    <div class="col s12 m12 l3">
                                        <div class="col s12 z-depth-2  light-blue darken-3  hoverable">
                                                <h5 class="white-text left-align">Estorno de Recebimento:</h5>
        
                                                <h5 class="white-text right-align">@php echo number_format($estornoRecebimento , 2, ',', '.');  @endphp</h5>
                                        </div>
                                    </div>
                                
                                <?php } ?>
    
                                <?php if ($estornoPagamento > 0) { ?>
                                    <div class="col s12 m12 l3">
                                        <div class="col s12 z-depth-2  deep-orange accent-4 hoverable">
                                                <h5 class="white-text left-align">Estorno de Pagamento:</h5>
        
                                                <h5 class="white-text right-align">@php echo number_format($estornoPagamento , 2, ',', '.');  @endphp</h5>
                                        </div>
                                    </div>
                                <?php } ?>

                                <?php if ($debitoPag > 0) { ?>
                                    <div class="col s12 m12 l3">
                                        <div class="col s12 z-depth-2  yellow accent-4 hoverable">
                                                <h5 class="white-text left-align">Débito de Pagamento:</h5>
        
                                                <h5 class="white-text right-align">@php echo number_format($debitoPag , 2, ',', '.');  @endphp</h5>
                                        </div>
                                    </div>
                                <?php } ?>

                            </div>
                            
                 
                        </div>
                        @else
                            <p>Nenhum registro encontrado!</p>
                        @endif
                    </div>

                </div>
    </div>
    
           
        </div>

    </div>


    <!-- Modal Structure -->
    <div id="modal_movcaixa" class="modal modal2 modal-fixed-footer">
        <div class="right-align">
            <a href="#!" class=" btn modal-action modal-close waves-effect waves-light red "><i class=" Tiny material-icons">close</i></a>
        </div>
        <div class="modal-content">

            <h4>Movimentar Caixa de Revendedor</h4>
            <form id="myform">
                <div class="row">
                    {{--<div class="input-field col s12 m12 l2">--}}
                        {{--<select name="movcaixa_sel_revendedor" id="movcaixa_sel_revendedor">--}}
                            {{--<option value="0" selected>Nenhum</option>--}}
                            {{--@if(!empty($data_movcax))--}}
                                {{--@forelse($data_movcax as $r)--}}
                                    {{--<option value="{{$r->idreven}}" data-saldo="{{number_format($r->vlrdevatu, 2, ',', '.') }}" >{{$r->nomreven}}</option>--}}
                                {{--@empty--}}
                                    {{--<option value="" disabled selected>Nenhuma Revendedor</option>--}}
                                {{--@endforelse--}}
                            {{--@endif--}}

                        {{--</select>--}}
                        {{--<label>Revendedor</label>--}}
                    {{--</div>--}}
                    <div class="input-field col s12 m12 l2">
                        <input type="text" id="default" list="combobox" placeholder="Nenhum">
                        <datalist id="combobox">
                            @if(!empty($data_movcax))
                                @forelse($data_movcax as $r)
                                    <option data-value="{{$r->idreven}}" value="{{$r->nomreven}}" data-saldo="{{number_format($r->vlrdevatu, 2, ',', '.') }}" ></option>
                                @empty
                                    <option value="" disabled selected>Nenhuma Revendedor</option>
                                @endforelse
                            @endif
                        </datalist>
                        <label>Revendedor</label>
                    </div>
                    <div class="input-field col s12 m12 l2">
                        <input readonly id="saldoatu" placeholder="0,00" type="text" class="validate">
                        <label class="active" for="saldoatu">Saldo Atual</label>
                    </div>
                    <div class="input-field col s12 m12 l2">
                        <select name="movcaixa_sel_cobrador" id="movcaixa_sel_cobrador">
                            <option value="" >Nenhum</option>
                            @if(!empty($cobrador))
                                @forelse($cobrador as $cob)
                                    <option value="{{$cob->idcobra}}"  @if(isset($sel_cobrador)) {{ $cob->idcobra == $sel_cobrador  ? 'selected' : '' }} @endif>{{$cob->nomcobra}}</option>
                                @empty
                                    <option value="" disabled selected>Nenhuma Cobrador</option>
                                @endforelse
                            @endif
                        </select>
                        <label>Cobradores</label>
                    </div>
                    <div class="input-field col s12 m12 l2">
                        <input placeholder="Valor" id="vlrmov" type="tel" class="validate">
                        <label for="first_name">Valor</label>
                    </div>
                    <div class="input-field col s12 m12 l2">
                        <button type="reset" class="reset btn waves-effect green waves-light " onclick="addMov('R')">R</button>
                        <button type="reset"  class="reset btn waves-effect red waves-light " onclick="addMov('P')">P</button>
                        <button type="reset" class="reset btn waves-effect orange waves-light " onclick="addMov('D')">D</button>
                    </div>

                   
                </div>
      

            </form>
            <form class="form-group" id="form-add-mov" method="post" action="/admin/movimentoscaixa2" enctype="multipart/form-data">
                {{ csrf_field() }}

            <div id="scroll">
                    <table id="products-table">
                        <thead >
                        <tr>
                            <th>Revendedor</th>
                            <th>Tipo Movimento</th>
                            <th>Valor Movimento</th>
                            <th>Saldo Atual</th>
                            <th>Saldo Resultado</th>
                            <th>Cobrador</th>
                            <th>Ações</th>
                        </tr>
                        </thead>

                        <tbody>
                        </tbody>
                    </table>

            </div>

            <div class="row">
                    <div class="input-field col s12 m12 l2">
                    </div>
                    <div class="input-field col s12 m12 l2">
                    </div>
                    <div class="input-field col s12 m12 l2">
                    </div>
                    <div class="input-field col s12 m12 l2">
                    </div>
                    <div class="input-field col s12 m12 l2">
                    </div>
                    <div class="input-field col s12 m12 l2">
                            <button class="btn waves-effect waves-light" type="submit" >Salvar Movimento
                                    <i class="material-icons right">send</i>
                                </button>
                    </div>
            </div>

            <div class="modal-footer">
                    {{--<a href="#!" class=" btn modal-action  waves-effect waves-green" onclick="enviarDados()">Salvar Movimento</a>
                    <button class="btn waves-effect waves-light" type="submit" >Salvar Movimento
                        <i class="material-icons right">send</i>
                    </button>--}}
                </div>
        </div>
       
    </form>
    </div>

@endsection

@push('scripts')



<script>
    $(function () {
        jQuery("#form-add-mov").submit(function () {

            var dadosForm = jQuery(this).serialize();


            decisao = confirm("Salvar a movimentação");

            if (decisao){

                jQuery.ajax({
                    url: '/admin/movimentoscaixa2',
                    data: dadosForm,
                    method: 'POST'


                }).done(function (data) {


                    if (data > '0') {

                        alert('Movimentação salva com sucesso');

                        location.reload();

//                    setTimeout("location.reload();", 3000);

                    } else {
                        alert('Falha ao cadastrar movimentação!!');

                    }
                }).fail(function () {
                    alert('Falha ao enviar dados!!');


                });

                return false;



            } else {
                return false;
            }




        });
    });
</script>
<script type="text/javascript" src="{{url('js/jquery.mask.js')}}"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/numbro//min/languages.min.js"></script>



<script>

        $(document).ready(function() {

            $('.modal').modal();



            $('#saldoatu').mask('000.000,00');
            $('#vlrmov').mask('000.000.000.000.000,00', {reverse: true});


            $('#movcaixa_sel_revendedor').change(function(){
                $('#saldoatu').val($(this).find(':selected').data('saldo'));
                var valor = $("#saldoatu").val().replace(/\./g, "").replace(",", ".");

                if (parseFloat(valor) < 0){
                $('#saldoatu').css('color', '#FF0000')}
                else if (parseFloat(valor) > 0){
                    $('#saldoatu').css('color', 'green')
                } else {
                    $('#saldoatu').css('color', 'black')
                }

            });


            $('#default').on('input', function() {
                var userText = $(this).val();

                $("#combobox").find("option").each(function() {
                    if ($(this).val() == userText) {
//                        alert("Make Ajax call here.");
                        var revendedor = $("#default").val();
                        var idreven = document.querySelector("#combobox option[value='"+revendedor+"']").dataset.saldo;
                        $('#saldoatu').val(idreven);
                        var valor = $("#saldoatu").val().replace(/\./g, "").replace(",", ".");

                        if (parseFloat(valor) < 0){
                            $('#saldoatu').css('color', '#FF0000')}
                        else if (parseFloat(valor) > 0){
                            $('#saldoatu').css('color', 'green')
                        } else {
                            $('#saldoatu').css('color', 'black')
                        }

                    }
                })
            });



            var table = $('#movcaixa').DataTable(
                {
//                    fixedColumns: {
//                        leftColumns: 1
//
//                    },

                    dom: 'Brtip',
                    buttons: [
                        'pdf',
                        'excelHtml5',
                        {
                            extend: 'print',
                            text: 'Imprimir',
                        }
                    ],


                    scrollY: 200,
                    scrollX:        true,
                    scrollCollapse: true,
                    paging:         false,
                    Bfilter:        false,
                    //ordenar pela coluna seqordem
                    "aaSorting": [[1, "desc"]],


                    columnDefs: [
                        {
                            className: 'mdl-data-table__cell--non-numeric'
                        },
                        //ocultar coluna seqordem
                        {
                            "targets": [ 1],
                            "visible": false,
                            "searchable": false
                        }

                    ],

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

                }

            );


            // #myInput is a <input type="text"> element
            $('#myInput').on( 'keyup', function () {
                table.search( this.value ).draw();
            } );

        } );



        // remover linha
        (function($) {
            remove = function(item) {
                var tr = $(item).closest('tr');

                tr.fadeOut(400, function() {
                    tr.remove();
                });

                return false;
            }
        })(jQuery);

        //adicionar linha

        function addMov(el) {

            if ($("#default").val() == 0){
                Materialize.toast('Selecione um Revendedor!', 3000)
                return false;
            }

            if ($("#vlrmov").val() == ''){
                Materialize.toast('Digite um valor de movimento!', 3000)
                return false;
            }

            var newRow = $("<tr>");
            var cols = "";

//            var idreven = $("#movcaixa_sel_revendedor :selected").val();
            var revendedor = $("#default").val();
//            var revendedor = $("#movcaixa_sel_revendedor :selected").text();
            var idreven = document.querySelector("#combobox option[value='"+revendedor+"']").dataset.value;
            var saldo = $("#saldoatu").val().replace(/\./g, "").replace(",", ".");
            var vlrmov = $("#vlrmov").val().replace(/\./g, "").replace(",", ".");
            var cobrador = $("#movcaixa_sel_cobrador :selected").text();
            var idbase = "<?php if (!empty($p)) echo $p->idbase; ?>";
            var idven = "<?php if (!empty($p)) echo $p->idven; ?>";
            var idusu = "<?php echo $idusu; ?>";
            var idcobra = $("#movcaixa_sel_cobrador :selected").val();

            if (el == 'P'){
                var saldoresul = parseFloat(saldo) + parseFloat(vlrmov);
                var tipomov = '<input readonly type="hidden" name="obsdes[]" value="null"/> <input readonly type="text" class="red white-text center-align" name="tipomov[]" value="PAGAMENTO"/> ';
            }
            if(el == 'R'){
                var saldoresul = saldo - vlrmov;
                var tipomov = '<input readonly type="hidden" name="obsdes[]" value="null"/> <input readonly type="text" class="green white-text center-align" name="tipomov[]" value="RECEBIMENTO"/> ';

            }
            if(el == 'D'){
                var despinfo;
                do {
                    despinfo = prompt ("Informe a Despesa");
                } while (despinfo == null || despinfo == "");
//                alert ("Despesa: "+despinfo);
                var saldoresul = parseFloat(saldo) - parseFloat(vlrmov);
                var tipomov = '<input readonly type="hidden" name="obsdes[]" value="'+despinfo+'"/> <input readonly type="text" class="orange white-text center-align" name="tipomov[]" value="DESPESA"/> ';

            }



//            cols += '<td data-idreven="'+$("#movcaixa_sel_revendedor :selected").val()+'">'+ $("#movcaixa_sel_revendedor :selected").text() +'</td>';
//            cols += "<td>"+ $("#saldoatu").val()  +"</td>"
//            cols += "<td>"+ $("#vlrmov").val() + "</td>";
//            cols += '<td data-idcobra="'+ $("#movcaixa_sel_cobrador :selected").val() +'">'+ $("#movcaixa_sel_cobrador :selected").text() +'</td>';

            cols += '<td><input type="hidden" name="idbase[]" value="'+ idbase +'">' +
                '<input type="hidden" name="idven[]" value="'+idven+'">' +
                '<input type="hidden" name="idreven[]" value="'+idreven+'">' +
                '<input readonly type="text" name="revendedor[]" value="'+ revendedor +'"/></td>';
            cols += '<td>'+tipomov+'</td>';
            cols += '<td><input readonly type="text" name="vlrmov[]" value="'+ parseFloat(vlrmov).toFixed(2).replace(".", ",")+'"/></td>';
            cols += '<td><input readonly type="text" id="saldoatu2" name="saldoatu[]" value="'+saldo.replace(".", ",")+'"/></td>';
            cols += '<td><input readonly type="text" name="saldoresul[]" value="'+ saldoresul.toFixed(2).replace(".", ",")+'"/></td>';
            cols += '<td><input type="hidden" name="idusu[]" value="'+ idusu +'">' +
                '<input type="hidden" name="idcobra[]" value="'+ idcobra +'">' +
                '<input readonly type="text" name="cobrador[]" value="'+cobrador+'"/></td>';
            cols += '<td>';
            cols += '<button class="btn waves-effect waves-light grey" onclick="remove(this)" type="button"><i class="material-icons">delete</i></button>';
            cols += '</td>';

            newRow.append(cols);
            $("#products-table").append(newRow);

//            $('#saldoatu2').mask('000,00');



            return false;
        }


</script>

@endpush



