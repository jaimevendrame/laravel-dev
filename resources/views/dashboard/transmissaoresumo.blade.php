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
    <link type="text/css" rel="stylesheet" href="{{ asset('materialize/css/material.css') }}"  media="screen,projection"/>

    <link type="text/css" rel="stylesheet" href="{{ asset('admin/css/admin.css') }}"  media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="{{ asset('modallink/jquery.modalLink.css') }}"  media="screen,projection"/>
    <!--<link href="https://cdnjs.cloudflare.com/ajax/libs/material-design-lite/1.1.0/material.min.css" rel="stylesheet">-->
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
<div class="card-content">
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
            <th>PULE</th>
            <th>VALOR</th>
            <th>REVENDEDOR</th>
            <th>GERAÇÃO</th>
            <th>ENVIO</th>
            <th>SITUAÇÃO</th>
            <th>VENDEDOR</th>
           

        </tr>
        </thead>
        <tbody>
        @forelse($data as $aposta)
            <tr>
                <td>
                    <a id="link-modal" class="botao-transmissoes btn modal-trigger btn_grid btn_grid_130px" href="#" onclick='openModal1("/admin/apostas/view/{{$aposta->numpule}}/{{$aposta->ideven}}")'>
                    {{$aposta->numpule}}</a>
                    </td>
               
                <td>{{ number_format($aposta->vlrpalp, 2, ',', '.') }}</td>
                <td>{{ $aposta->nomreven }}</td>
                <td>{{Carbon\Carbon::parse($aposta->datger)->format('d/m/Y')}} {{Carbon\Carbon::parse($aposta->horger)->format('H:i:s')}}</td>
                <td> {{Carbon\Carbon::parse($aposta->datenv)->format('d/m/Y')}} {{Carbon\Carbon::parse($aposta->horenv)->format('H:i:s')}}</td>
                <td {{ $aposta->sitapo == 'CAN'  ? "bgcolor = #f44336" : '' }}> @if($aposta->sitapo == 'CAN') CANCELADO @elseif($aposta->sitapo == 'V')VALIDO @else PREMIADO @endif</td>
                <td>{{ $aposta->nomven }}</td>
                

            </tr>
        @empty
            <tr>
                nenhum registro encontrado!
            </tr>
        @endforelse
        
    </table>

    <div id="apostaTransmissao" class="modal">
        <div class="right-align">
            <a href="#!" class=" btn modal-action modal-close waves-effect waves-light red "><i class=" Tiny material-icons">close</i></a>
        </div>
        <div class="modal-content">
            <h4>Visualizar Aposta</h4>
            <div id="modal-content" class="row">
                <div class="row">
                    <div class="input-field col s6 m2">
                            <label class="active" for="n_aposta">Nº Aposta</label>
                        <input  readonly id="n_aposta" type="text" class="validate" value="0000">
                        
                    </div>
                    <div class="input-field col s6 m2">
                        <input  readonly id="vlr_aposta" type="text" class="validate" value="0,00">
                        <label class="active" for="vlr_aposta">Valor</label>
                    </div>
                    <div class="input-field col s6 m4">
                            <label class="active" for="revendedor">Revendedor</label>
                        <input  readonly id="revendedor" type="text" class="validate" value="Revendedor">
                        
                    </div>
                    <div class="input-field col s6 m4">
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
</body>
<!-- Scripts -->
<!--Import jQuery before materialize.js-->
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
                    { data: "PULE" },
                    { data: "Valor" },
                    { data: "Revendedor" },
                    { data: "Geração" },
                    { data: "Envio"},
                    { data: "Situação" },
                    { data: "Vendedor" }
                

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
//                        alert(sum);
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


<script type="application/javascript">


    $(document).ready(function(){



        //init the modal
        $('.modal').modal();

        $('#tb-apostas').DataTable({

            dom: 'rt',
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

        $('#apostaTransmissao').modal('open');


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
                cols += '<td>'+vaSituapo+'</td>';
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






</html>