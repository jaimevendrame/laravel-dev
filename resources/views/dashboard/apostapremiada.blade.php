<!DOCTYPE html>
<html lang="pt-br" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!--Import Google Icon Font-->
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="{{ asset('materialize/css/materialize.min.css') }}"  media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="{{ asset('admin/css/admin.css') }}"  media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="{{ asset('modallink/jquery.modalLink.css') }}"  media="screen,projection"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/material-design-lite/1.1.0/material.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.15/css/dataTables.material.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/1.3.1/css/buttons.dataTables.min.css" rel="stylesheet">



    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
    <style>
        header, main, footer {
            padding-left: 300px;
        }
        @media only screen and (max-width : 992px) {
            header, main, footer {
                padding-left: 0;
            }
        }
        main {
            margin: 0px 10px 0px 10px;
        }
    </style>
</head>
<body>
<form>
    <div class="input-field">
        <input id="myInput" name="myInput" type="search" required>
        <label class="label-icon" for="search"><i class="material-icons">search</i></label>
        <i class="material-icons">close</i>
    </div>
</form>
    <table id="aposta" class="mdl-data-table" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>Aposta n°</th>
            <th>Sorteio</th>
            <th>Horário</th>
            <th>Valor Palpite</th>
            <th>Valor Prêmio</th>
            <th>Revendedor</th>
            <th>Modalidade</th>
            <th width="200px">Palpites</th>
            <th>Colocação</th>
            <th>Data Liberação</th>
            <th>Atrasado</th>

        </tr>
        </thead>
        <tbody>
        @forelse($data as $aposta)
            <tr>
                <td>{{ $aposta->numpule }}</td>
                <td>{{ Carbon\Carbon::parse($aposta->datapo)->format('d/m/Y')  }}</td>
                <td>{{ $aposta->deshor }}</td>
                <td>{{ number_format($aposta->vlrpalp, 2, ',', '.') }}</td>
                <td>{{ number_format($aposta->vlrpre, 2, ',', '.') }}</td>
                <td>{{ $aposta->nomreven }}</td>
                <td>{{ $aposta->destipoapo }}</td>
                <td width="200px">
                    @if( isset($aposta->palp1) ){{$aposta->palp1}}@endif @if( isset($aposta->palp2) ){{'- '.$aposta->palp2}}@endif @if( isset($aposta->palp3) ) {{'- '.$aposta->palp3}} @endif @if( isset($aposta->palp4) ){{'- '.$aposta->palp4}}@endif

                    @if( isset($aposta->palp5) ){{'- '.$aposta->palp5}}@endif

                    @if( isset($aposta->palp6) ){{'- '.$aposta->palp6}}@endif

                    @if( isset($aposta->palp7) ){{'- '.$aposta->palp7}}@endif

                    @if( isset($aposta->palp8) ){{'- '.$aposta->palp8}}@endif

                    @if( isset($aposta->palp9) ){{'- '.$aposta->palp9}}@endif

                    @if( isset($aposta->palp10) ){{'- '.$aposta->palp10}}@endif

                    @if( isset($aposta->palp11) ){{'- '.$aposta->palp11}}@endif

                    @if( isset($aposta->palp12) ){{'- '.$aposta->palp12}}@endif

                    @if( isset($aposta->palp13) ){{'- '.$aposta->palp13}}@endif

                    @if( isset($aposta->palp13) ){{'- '.$aposta->palp13}}@endif

                    @if( isset($aposta->palp14) ){{'- '.$aposta->palp14}}@endif

                    @if( isset($aposta->palp15) ){{'- '.$aposta->palp15}}@endif

                    @if( isset($aposta->palp16) ){{'- '.$aposta->palp16}}@endif

                    @if( isset($aposta->palp17) ){{'- '.$aposta->palp17}}@endif

                    @if( isset($aposta->palp18) ){{'- '.$aposta->palp18}}@endif

                    @if( isset($aposta->palp19) ){{'- '.$aposta->palp19}}@endif

                    @if( isset($aposta->palp20) ){{'- '.$aposta->palp20}}@endif

                    @if( isset($aposta->palp21) ){{'- '.$aposta->palp21}}@endif

                    @if( isset($aposta->palp22) ){{'- '.$aposta->palp22}}@endif

                    @if( isset($aposta->palp23) ){{'- '.$aposta->palp23}}@endif

                    @if( isset($aposta->palp24) ){{'- '.$aposta->palp24}}@endif

                    @if( isset($aposta->palp25) ){{'- '.$aposta->palp25}}@endif

                </td>
                <td>{{ $aposta->descol }}</td>
                <td>{{ Carbon\Carbon::parse($aposta->datlibpre)->format('d/m/Y')  }}</td>
                <td>{{ $aposta->inatrasado }}</td>

            </tr>
        @empty
            <tr>
                nenhum registro encontrado!
            </tr>
        @endforelse
      
    </table>

</body>

<script type="text/javascript" src="{{ asset('materialize/js/jquery-2.1.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('materialize/js/materialize.min.js') }}"></script>
<script src="{{ asset('materialize/lib/pt_BR.js') }}"></script>

<script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.2.2/js/dataTables.fixedColumns.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.15/api/sum().js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.print.min.js"></script>



<script>

    $(document).ready(function() {



        var table2 = $('#aposta').DataTable( {

            dom: 'rtip',

                columns: [
                    { data: "Aposta n°" },
                    { data: "Sorteio" },
                    { data: "Horário" },
                    { data: "Valor Palpite" },
                    { data: "Valor Prêmio", className: "sum" },
                    { data: "Revendedor" },
                    { data: "Modalidade" },
                    { data: "Palpites" },
                    { data: "Colocação" },
                    { data: "Data Liberação" },
                    { data: "Atradados" }

                ],
                "footerCallback": function(row, data, start, end, display) {
                    var api = this.api();

                    var intVal = function ( i ) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,.]/g, '')*1 :
                            typeof i === 'number' ?
                                i : 0;
                    };

                    var numFormat = $.fn.dataTable.render.number( '.', ',', 2).display;


                    api.columns('.sum', { page: 'current' }).every(function () {
                        var sum = api
                            .cells( null, this.index(), { page: 'current'} )
                            .render('display')
                            .reduce(function (a, b) {
                                var x = intVal(a) || 0;
                                var y = intVal(b) || 0;
                                return x + y;
                            }, 0);
                        console.log(this.index() +' '+ sum);
                     //   alert(sum);
                        $(this.footer()).html((numFormat(parseInt(sum)/100)));
//                        $( api.columns( 11 ).footer() ).html( (numFormat(parseInt(sum)/100)));
                    });
                },

            "paging":   false,
            "scrollY": 400,
            "scrollX":        true,
            "scrollCollapse": true,
            "search": true,

            "language": {
                 "searchPlaceholder": "Digite aqui para pesquisar",
                "decimal": ",",
                "thousands": ".",
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
                columnDefs: [
                    {
                        targets: [ 0, 1, 2 ,3 ,4 ,5 ,6 ,7 ,8 ,9 ,10 ,11],
                        className: 'mdl-data-table__cell--non-numeric'
                    }]


            }});

        // #myInput is a <input type="text"> element
        $('#myInput').on( 'keyup', function () {
            table2.search( this.value ).draw();
        } );


    } );




</script>
</html>