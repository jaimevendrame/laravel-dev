@extends('dashboard.templates.app')

@section('content')
   

    <div class="section">
        <div class="row">
            <div class="col s12 l12">
                    
                <div class="row">
                        <div class="card-content">
                                <form class="form-group" id="form-cad-edit" method="post" action="/admin/movimentoscobrador/{{$p_ideven}}" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                
                                    <div class="row">
                                               
                                                <div class="input-field col s12 m2 l3">
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
                
                                                <div class="input-field col s12 m6 l3 ">
                                                    <select multiple name="sel_movimento[]">
                                                        <option value="RECEBIMENTO" selected>RECEBIMENTO</option>
                                                        <option value="PAGAMENTO" selected>PAGAMENTO</option>
                                                        
                                                    </select>
                                                    <label>Tipo de Movimento</label>
                                                </div>

                                                <div class="input-field col s6 m2 l2">
                                                        <input id="datIni" name="datIni" type="date" class="datepicker"
                                                            placeholder ="Data inicial">
                            
                                                    </div>
                                                    <div class="input-field col s6 m2 l2">    
                                                        <input id="datFim" name="datFim" type="date" class="datepicker"
                                                                         placeholder ="Data final" >
                                                                         
                                                    </div>
                
                                                <div class="input-field col s6 m2">
                                                    <button class="btn waves-effect waves-light" type="submit" name="action">Mostrar
                                                        <i class="material-icons right">send</i>
                                                    </button>
                                                </div>
                                               
                                        </div>
                                        
                                    </form>
                        </div>
                    <div class="col s12 m12 l12">
                        <ul class="tabs">
                            <li class="tab col s6 m6 l6"><a class="active" href="#pendentes" onfocus="esconderData()">Movimentos Pendentes</a></li>
                            <li class="tab col s6 m6 l6"><a href="#liberados" onfocus="mostrarData()">Movimentos Liberados</a></li>
                        </ul>
                    </div> 
                                    
                       <div class="row"></div>
                       <div id="pendentes" class="col s12 l12">

                            <div id="liberar_mov" >
                                    <form id="frm-paybet" name="frm-paybet" method="post"enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        <input id="input-paybet" name="dados" type="hidden" value="0"/>

                                    </form>
                                    <button class="btn waves-effect waves-light" onclick='openModalConfirmarVarios("/admin/movimentoscobrador/confirmaVarios")' name="action">Liberar Movimentos
                                    </button>
                            <div class="row"></div>
                            </div>
                            
                            @if(!empty($data))
                                <table class="mdl-data-table " id="movcobrador"  cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th> <input type="checkbox" class="filled-in checkbox-pink teste" id="checkTodos"  name="teste2[]" />
                                                <label for="checkTodos"></label></th>
                                            <th>REC/PGT</th>
                                            <th>REVENDEDOR</th>
                                            <th>COBRADOR</th>
                                            <th>VALOR</th>
                                            <th>DATA ENV</th>
                                            <th>HORA ENV</th>
                                            <th>C</th>
                                            <th>D</th>
                                            <th>E</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    @forelse($data as $movi)

                                    <tr>
                                        
                                          <td>  
                                                <input type="checkbox" class="teste" id="{{$movi->seqmov}}" value="{{$movi->seqmov}}" name="teste[]" class="filled-in checkbox-pink"  />
                                                <label for="{{$movi->seqmov}}"></label>
                                          </td>

                                    <td {{ $movi->tipomov == 'PAGAMENTO'  ? "bgcolor = #f44336" : 'bgcolor = #00e676'  }}> @if($movi->tipomov == 'PAGAMENTO') PAGAMENTO @elseif($movi->tipomov == 'RECEBIMENTO' ? "bgcolor = #00e676" : '')RECEBIMENTO @endif
                                    <td>{{ $movi->nomreven }}</td>
                                    <td>{{ $movi->nomcobra }}</td>
                                    <td>{{ number_format($movi->vlrmov, 2, ',', '.') }}</td>
                                    <td>{{ Carbon\Carbon::parse($movi->datenv)->format('d/m/Y')}}</td>
                                        <td>
                                                @php
                                                $horenv = date('H:i:s', strtotime($movi->horenv));
                                                @endphp
                                            {{ Carbon\Carbon::parse($movi->horenv)->format('H:m:s') }}</td>
                                        <td><a class="waves-effect waves-light btn-small " href="#" onclick='openModalConfirmar("/admin/movimentoscobrador/confirma/{{$movi->seqmov}}")'><i class="Small material-icons green-text">check_circle</i></a></td>
                                        <td><a class="waves-effect waves-light btn-small" href="#" onclick='openModalExcluir("/admin/movimentoscobrador/exclui/{{$movi->seqmov}}")'><i class="Small material-icons red-text">delete_outline</i></a></td>
                        

                                    <td>
                                            <a id="link-modal2" class="waves-effect waves-light btn-small" href="#" onclick='openModalAlterar("/admin/movimentoscobrador/alteramov/{{$movi->seqmov}}")'>
                                                <i class="Small material-icons blue-text">edit</i></a>
                                            </a>
                                    </td>
                                            
                            @empty
                                
                            @endforelse
                            </tbody>
                                    <tfoot>
                                    @php
                                        $recebimento = 0;

                                        $pagamento = 0;
                         
                                        foreach($data as $key) {

                                            if ( ($key->tipomov == 'RECEBIMENTO') || ($key->tipomov == 'ESTORNO REC.') || ($key->tipomov == 'DEBITO REC.') || ($key->tipomov == 'CREDITO REC.')){

                                                $recebimento += $key->vlrmov;
                                            }

                                            if ( ($key->tipomov == 'PAGAMENTO') || ($key->tipomov == 'ESTORNO PAG.') || ($key->tipomov == 'DEBITO PAG.') || ($key->tipomov == 'CREDITO PAG.')){

                                                $pagamento += $key->vlrmov;
                                            }
                                        
                                                    }
                                    @endphp
                                    
                                    </tfoot>

                                </table>    
                               
                                <div class="row">
        
                                    <div class="col s12 m12 l4">
                                        <div class="col s12 z-depth-2 green hoverable">                                  
                                            <h5 class="white-text left-align">Total Recebimento:</h5>
                                                               
                                            <h5 class="white-text right-align">@php echo number_format($recebimento, 2, ',', '.'); @endphp</h5>                                   
                                        </div>
                                    </div>
        
                                    <div class="col s12 m12 l4">
                                        <div class="col s12 z-depth-2 red darken-1 hoverable">       
                                                <h5 class="white-text left-align">Total Pagamento:</h5>         
                                            
                                                <h5 class="white-text right-align">@php echo number_format($pagamento, 2, ',', '.'); @endphp</h5>                                 
                                        </div>
                                    </div>
 
                                    <div class="col s12 m12 l4">
                                        <div class="col s12 z-depth-2 teal hoverable">
                                                <h5 class="white-text left-align">Saldo Movimentos:</h5>
        
                                                <h5 class="white-text right-align">@php echo number_format($recebimento - $pagamento, 2, ',', '.');  @endphp</h5>
                                        </div>
                                    </div>
        
                                </div>
                                @else
                                    <p>Nenhum registro encontrado!</p>
                                @endif               
                    </div><!-- Fecha a div Pendentes--> 
                    <div id="liberados" class="col s12 l12">
    
                        @if (!empty($dataLiberados))
 
                            <table class="mdl-data-table " id="movcobradorLib"  cellspacing="0" width="100%">
                                    <thead>
                                        <tr>   
                                            <th>REC/PGT</th>
                                            <th>REVENDEDOR</th>
                                            <th>COBRADOR</th>
                                            <th>VALOR</th>
                                            <th>DATA ENVIO</th>
                                            <th>DATA LIBERAÇÂO</th>
                                            <th>HORA ENVIO</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                     @forelse ($dataLiberados as $lib)
                                        <tr>
                                            <td {{ $lib->tipomov == 'PAGAMENTO'  ? "bgcolor = #f44336" : 'bgcolor = #00e676'  }}> @if($lib->tipomov == 'PAGAMENTO') PAGAMENTO @elseif($lib->tipomov == 'RECEBIMENTO' ? "bgcolor = #00e676" : '')RECEBIMENTO @endif
                                            <td>{{$lib->nomreven}}</td>
                                            <td>{{$lib->nomcobra}}</td>
                                            <td> {{ number_format($lib->vlrmov, 2, ',', '.') }} </td>
                                            <td>{{ Carbon\Carbon::parse($lib->datenv)->format('d/m/Y')}}</td>     
                                            <td>{{ Carbon\Carbon::parse($lib->datlib)->format('d/m/Y')}}</td>
                                            <td>
                                                    @php
                                                    $horenv = date('H:i:s', strtotime($lib->horlib));
                                                    @endphp
                                                {{ Carbon\Carbon::parse($lib->horlib)->format('H:m:s') }}</td>
                                            
                                        </tr>
                                @empty
                                
                            @endforelse
                                    
                                    </tbody>
                                    <tfoot>
                                            @php
                                            $recebimento = 0;
    
                                            $pagamento = 0;
                             
                                            foreach($dataLiberados as $key) {
    
                                                if ( ($key->tipomov == 'RECEBIMENTO') || ($key->tipomov == 'ESTORNO REC.') || ($key->tipomov == 'DEBITO REC.') || ($key->tipomov == 'CREDITO REC.')){
    
                                                    $recebimento += $key->vlrmov;
                                                }
    
                                                if ( ($key->tipomov == 'PAGAMENTO') || ($key->tipomov == 'ESTORNO PAG.') || ($key->tipomov == 'DEBITO PAG.') || ($key->tipomov == 'CREDITO PAG.')){
    
                                                    $pagamento += $key->vlrmov;
                                                }
                                            
                                                        }
                                        @endphp

                                    </tfoot>
                            </table>
                            <div class="row">
        
                                    <div class="col s12 m12 l4">
                                        <div class="col s12 z-depth-2 green hoverable">                                  
                                            <h5 class="white-text left-align">Total Recebimento:</h5>
                                                               
                                            <h5 class="white-text right-align">@php echo number_format($recebimento, 2, ',', '.'); @endphp</h5>                                   
                                        </div>
                                    </div>
        
                                    <div class="col s12 m12 l4">
                                        <div class="col s12 z-depth-2 red darken-1 hoverable">       
                                                <h5 class="white-text left-align">Total Pagamento:</h5>         
                                            
                                                <h5 class="white-text right-align">@php echo number_format($pagamento, 2, ',', '.'); @endphp</h5>                                 
                                        </div>
                                    </div>

                                    <div class="col s12 m12 l4">
                                        <div class="col s12 z-depth-2 teal hoverable">
                                                <h5 class="white-text left-align">Saldo Movimentos:</h5>
        
                                                <h5 class="white-text right-align">@php echo number_format($recebimento - $pagamento, 2, ',', '.');  @endphp</h5>
                                        </div>
                                    </div>
        
                                </div>
                                @else
                                    <p>Nenhum registro encontrado!</p>
                               
                        @endif
                    </div>              
                </div>
            <!-- Modal Structure -->
            <div id="modal_confirma_movimento" class="modal modal-pequeno ">
                <div class="right-align">
                    <a href="#!" class=" btn modal-action modal-close waves-effect waves-light red "><i class=" Tiny material-icons">close</i></a>
                </div>
                <div class="modal-content center-align">
    
                            <form class="form-group" id="form-confirma-mov" method="post" action="/admin/movimentosCobrador2" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <h4>Deseja Confirmar o Movimento : <input  readonly id="seq_movimento" type="text" name="seq_movimento" class="text" value="0"></h4>
                                    <input type="hidden" value="CONFIRMAR" name="confirma">
                                    <input type="hidden" value="{{$p_ideven}}" name="id_ven">

                                    <div class="row">
                                            <div class="col s12 m12 l3">
                                            </div>
                                            <div class="col s12 m12 l3">     
                                                <a href="#!" class="btn-large modal-action modal-close waves-effect waves-light red ">NÃO</a>
                                            </div>

                                            <div class="col s12 m12 l3">   
                                                <button class="btn-large  waves-effect waves-light green " type="submit" >SIM
                                                    
                                                </button>
                                            </div>
                                    </div>
                            </form>
                        </div>
                    </div>

            <!-- modal DELETE-->
            <div id="modal_deleta_movimento" class="modal modal-pequeno ">
                    <div class="right-align">
                        <a href="#!" class=" btn modal-action modal-close waves-effect waves-light red "><i class=" Tiny material-icons">close</i></a>
                    </div>
                    <div class="modal-content center-align">
        
                        <form class="form-group" id="form-Exclui-mov" method="post" action="/admin/movimentosCobrador2" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <h4>Deseja Excluir o Movimento : <input  readonly id="seq_movimento_delete" type="text" name="seq_movimento" class="text" value="0"></h4>
                                <input type="hidden" value="EXCLUIR" name="confirma">
                                <input type="hidden" value="{{$p_ideven}}" name="id_ven">
                                <div class="row">
                                        <div class="col s12 m12 l3">
                                        </div>
                                        <div class="col s12 m12 l3">
                                                
                                                <a href="#!" class="btn-large modal-action modal-close waves-effect waves-light red ">NÃO</a>
    
                                        </div>
                                        <div class="col s12 m12 l3">
                                                <button class="btn-large  waves-effect waves-light green " type="submit" >SIM
                                                   
                                                 </button>
                                        </div>
  
                                        <div class="col s12 m12 l3">
                                        </div>
                                </div>
                        </form>
                    </div>
            </div>

                                    <!-- modal EDIT -->
            <div id="modal_alterar_movimento" class="modal modal-pequeno ">
                    <div class="right-align">
                            <a href="#!" class=" btn modal-action modal-close waves-effect waves-light red "><i class=" Tiny material-icons">close</i></a>
                        </div>
                        <div class="modal-content center-align">


                            <form class="form-group" id="form-confirma-mov" method="post" action="/admin/movimentosCobradorAlterar" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <h4> Alterar o Movimento do Cobrador </h4>
                                    <input id="seq_movimento_alterar" type="hidden" name="seq_movimento_alterar" class="text" value="0">
                                    <input type="hidden" value="ALTERAR" name="alterar">
                                    <input type="hidden" value="{{$p_ideven}}" name="id_ven">

                                    
                                    <div class="row">
                                            <div class=" input-field col s6 m6 l6">
                                                    <input  readonly id="vlr_movimento" name="vlr_movimento" type="text" class="validate" value="0,00">
                                                    <label class="active" for="vlr_movimento">Valor Atual</label>
                                       
                                            </div>
                                            <div class="input-field col s6 m6 l6">
                                                    <input id="vlr_novo" name="vlr_novo" placeholder="0,00" type="text" class="validate">
                                                    <label class="active" for="vlr_novo">Alterar Para</label>
                                            </div>
                                    </div> 
                                    <div class="row">   
                                        
                                           
                                      <div class="col s12 m12 l12">
                                                <button class="btn-large  waves-effect waves-light green " type="submit" >
                                                    Confirmar Alteração
                                                </button>
                                            </div>
                                            
                                    </div>
                                    </div>
                            </form>   

                           
                  </div>

            <!--Modal Liberar todos-->

             <div id="modal_confirma_varios_movimento" class="modal modal-pequeno ">
                    <div class="right-align">
                        <a href="#!" class=" btn modal-action modal-close waves-effect waves-light red "><i class=" Tiny material-icons">close</i></a>
                    </div>
                    <div class="modal-content center-align">
            
                                <form class="form-group" id="form-confirma-mov" method="post" action="/admin/movimentosCobradorVarios" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <h4>Deseja Confimar o(s) Movimento(s) : <input  readonly id="seq_movimento_varios" type="text" name="seq_movimento_varios[]" class="text" value="0"></h4>
                                        <input type="hidden" value="libera" name="confirma">
                                        <input type="hidden" value="{{$p_ideven}}" name="id_ven">
                                        
            
                                        <div class="row">
                                                <div class="col s12 m12 l3">
                                                </div>
                                                <div class="col s12 m12 l3">
                                                        
                                                        <a href="#!" class="btn-large modal-action modal-close waves-effect waves-light red ">NÃO</a>
            
                                                </div>
            
                                                <div class="col s12 m12 l3">
                                                        
                                                    <button class="btn-large  waves-effect waves-light green " type="submit" >SIM
                                                        
                                                    </button>
                                            </div>
            
                                        </div>
                                </form>
                            </div>
                        </div>
    
            </div>
            
        </div>

    </div>

@endsection

@push('scripts')

<script>
window.onload = function()
{
        document.getElementById("datIni").disabled = true;
        document.getElementById("datFim").disabled = true;
};
</script>

<script>

    function mostrarData(){
        document.getElementById("datIni").disabled = false;
        document.getElementById("datFim").disabled = false;
    };

    function esconderData(){
        document.getElementById("datIni").disabled = true;
        document.getElementById("datFim").disabled = true;
    };
   
    
</script>

<script>
    
        $(document).ready(function() {
        $(".teste").click(function(e) {
            
            var checados = [];  
            $.each($("input[name='teste[]']:checked"), function(){            
                checados.push($(this).val());
            });
           // alert(checados);
           // console.log(checados.join(", "));

            if (checados == 0){
                $("#liberar_mov").hide();
            } else {
                $("#liberar_mov").show();
            }
            $('#seq_movimento_varios').val(checados);

        });
    });

</script>
<script>
    //Função para alterar movimento
    function openModalAlterar(url) {

        $('#movcobrador').empty(); //Limpando a tabela

        $('#modal_alterar_movimento').modal('open');

        jQuery.getJSON(url, function (dataAlterar) {
            
            var vlrPalp = 0        

            var vlrmovimento = dataAlterar[0].vlrmov;
                    vlrPalp += parseFloat(vlrmovimento);
                //  alert(vlrPalp);
            var seq_mov_alterar = dataAlterar[0].seqmov;
            $('#vlr_movimento').val(vlrPalp);
            $('#seq_movimento_alterar').val(seq_mov_alterar);

        });
    };

  //Função para Confirmar movimento
  function openModalConfirmar(url) {

        $('#movcobrador').empty(); //Limpando a tabela

        $('#modal_confirma_movimento').modal('open');

        jQuery.getJSON(url, function (dataConfirmar) {
            
            var seq_mov = dataConfirmar[0].seqmov;
                
                //  alert(seq_mov);
        
            $('#seq_movimento').val(seq_mov);

});
};

        //Função para Confirmar Varios movimento
    function openModalConfirmarVarios(url) {

        $('#movcobrador').empty(); //Limpando a tabela

        $('#modal_confirma_varios_movimento').modal('open');

        };

                //Função para Excluir movimento
    function openModalExcluir(url) {

                $('#movcobrador').empty(); //Limpando a tabela

                $('#modal_deleta_movimento').modal('open');


                jQuery.getJSON(url, function (dataExclui) {
                    
                    var seq_mov = dataExclui[0].seqmov;
                        
                    $('#seq_movimento_delete').val(seq_mov);

                });
            };
</script>

<script>
     $("#checkTodos").click(function(){
   	 $('input:checkbox').prop('checked', $(this).prop('checked'));
       // $('input:checkbox').attr('checked', $(this).attr('checked'));
   });
</script>
<script>
        $(document).ready(function(){
            $('.modalPequeno').modal();
          })
</script>

<script>
//checkbox
jQuery(function($) {
  $("td input[type=checkbox]").on('change', function (e) {
    console.log('change');
    row = $(this).closest('tr');
    console.log(row);
    console.log($(this).is(':checked'));
    if ($(this).is(':checked')) {
        row.addClass('selected');
    } else {
        row.removeClass('selected');
    }
  });
});


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

<script>

        $(document).ready(function() {

            $("#liberar_mov").hide();
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


            var table = $('#movcobrador').DataTable( {

                dom: 'fBrtip',
                buttons: [
                    
                    'pdf',
                    'excel',
                    {
                        extend: 'print',
                        text: 'Imprimir',
                    }
                ],


                scrollY: 315,
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


                var table = $('#movcobradorLib').DataTable( {

                dom: 'fBrtip',
                buttons: [
                    
                    'pdf',
                    'excel',
                    {
                        extend: 'print',
                        text: 'Imprimir',
                    }
                ],


                scrollY: 415,
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


            var table = $('#').DataTable(
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


                    scrollY: 500,
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

                } );


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

      cols += '<td data-idcobra="'+ $("#movcaixa_sel_cobrador :selected").val() +'">'+ $("#movcaixa_sel_cobrador :selected").text() +'</td>';

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


<script type="text/javascript" src="{{url('js/jquery.mask.js')}}"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/numbro//min/languages.min.js"></script>

@endpush



