@extends('dashboard.templates.app')

@section('content')
    <div class="section">
        <div class="row">
            <div class="col s12 m8 l8">
                    <div class="card grey lighten-2">
                            <div class="card-content black-text">
                        <span class="card-title center blue-grey-text text-darken-3"><b>Alterar Comissão %</b></span>
                        <div class="card-content">
                        <form class="form-group" id="form-comissao" method="post" action="/admin/revendedor/comissao/{{$comRevendedor->idbase}}/{{$comRevendedor->idven}}/{{$comRevendedor->idreven}}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="row">

                                    <div class="input-field col s12 m12 l7">
                                            <input id="nomreven" type="text" class="validate" name="nomreven"  value="{{$comRevendedor->nomreven}}">
                                            <label class="active" for="nomreven">Revendedor: </label>                                        
                                    </div>
                            </div>   

                            <div class="row">

                                    <div class="input-field col s12 m12 l7">
                                            <input id="nomreven" type="text" class="validate" name="nomreven"  value="{{number_format($comRevendedor->vlrcom, 2, ',' , '.')}}">
                                            <label class="active" for="nomreven">Comissão Atual: </label>                                        
                                    </div>
                            </div>   

                            <div class="row">
                                <div class="input-field col s6 m4">
                                    <input id="comissaoNovo" name="comissaoNovo" placeholder="0,00" type="text" class="validate">
                                    <label class="active" for="comissaoNovo">Alterar Para: </label>
                                </div>
                            </div>    
                            <div class="row">
                                <div class="input-field col s12 m2">
                                    <button class="btn waves-effect waves-light" type="submit" name="action">Alterar
                                        <i class="material-icons right">send</i>
                                    </button>
                                </div> 
                            </div>       
                            </div>
                        </form>
                          

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
 
    $('#comissaoAtu').mask('000.000.000.000.000,00', {reverse: true});
    $('#comissaoNovo').mask('000.000.000.000.000,00', {reverse: true});
    
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

@endpush





