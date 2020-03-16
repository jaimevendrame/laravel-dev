@extends('dashboard.templates.app')

@section('content')
    <div class="section">
        <div class="row">
            <div class="col s12">
                
                    <div class="card-content">
                    @forelse ($baseAll as $bases)
                        
                   
                        <form class="form-group" id="form-cad-edit" method="post" action="/admin/consultaresultadoinst/{{$bases->ideven}}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            @empty
                        
                    @endforelse
                            <div class="row">

                                

                                       <div class="input-field col s6 m4 l2">
                                            <input id="datIni" name="datIni" type="date" class="datepicker"
                                                   placeholder ="Data inicial">
                                        </div>
                                        <div class="input-field col s6 m4 l2">
                                             <input id="datFim" name="datFim" type="date" class="datepicker"
                                                     placeholder ="Data final">
                                        </div>

                                        <div class="input-field col s12 m6 l4">
                                                <select multiple name="sel_status[]">
                                                    <option value="SIM" selected>PREMIADOS</option>
                                                    <option value="NAO" selected>NÃO PREMIADOS</option>
                                                    
                                                </select>
                                                <label>Selecionar transmissões</label>
                                        </div>

                                        <div class="input-field col s12 m6 l4">
                                                <select multiple name="sel_vendedor[]">
                                                    <option value="" disabled selected>Selecionar Regiões</option>
                                                    @forelse($baseAll as $bases)
                                                        @if( isset($ideven) && !empty($ideven))
                                                        <option value="{{$bases->ideven}}" {{ $bases->ideven == $ideven  ? 'selected' : '' }} >{{$bases->nomven}}</option>
                                                            @elseif(isset($ideven2) && (is_array($ideven2))) <option value="{{$bases->ideven}}" @forelse($ideven2 as $select) {{ $bases->ideven == $select  ? 'selected' : '' }} @ @empty @endforelse >{{$bases->nomven}}</option>
                                                         @else
                                                            <option value="{{$bases->ideven}}">{{$bases->ideven}}-{{$bases->nomven}}</option>
                                                        @endif
                                                    @empty
                                                        <option value="" disabled selected>Nenhuma região</option>
                                                    @endforelse
            
                                                </select>
                                                <label>Regiões selecionadas</label>
                                            </div>
                 
                            </div>
                            <div class="row">

                                    <div class="input-field col s12 m6 l4">
                                        <input type="text" name="teste" id="default" list="combobox" placeholder="Nenhum">
                                        <datalist id="combobox" name="default">
                                            @if(!empty($revendedoresAposta))
                                                @forelse($revendedoresAposta as $r)
                                                    <option data-value="{{$r->idreven}}" value="{{$r->nomreven}}" ></option>
                                                @empty
                                                    <option value="" disabled selected>Nenhuma Revendedor</option>
                                                @endforelse
                                            @endif
                                        </datalist>
                                        <label>Revendedor</label>
                                    </div>


                                <div class="input-field col s12 m4 l5">
                                    <input id="n_pule" name="n_pule" type="tel" class="validate">
                                    <label class="active" for="n_pule">Nº Aposta</label>
                                </div>

                               

                                <div class="input-field col s12 m6 l2">
                                    <button class="btn waves-effect waves-red" type="submit" name="action">Mostrar
                                        <i class="material-icons right">send</i>
                                    </button>
                                </div>

                            </div>
                        </form>
                        @if(!empty($data))
                            <table class="mdl-data-table" id="example" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                        <th>Pule</th>
                                        <th>Revendedor</th>
                                        <th>Sorteio</th>
                                        <th>Valor Pule</th>
                                        <th>Prêmio</th>
                                        <th>Data</th>
                                        <th>Hora</th>
                                        
  
                                </tr>
                                </thead>
                                <tbody>
                                {{--//#fffde7--}}

                                @forelse($data as $apostas)
                                    <tr>
                                        <td>
                                            <a id="link-modal" class="botao-transmissoes btn modal-trigger btn_grid btn_grid_130px" href="#" onclick='openModal1("/admin/apostas/view/{{$apostas->numpule}}/{{$apostas->ideven}}")'>
                                            {{$apostas->numpule}}</a>
                                        </td>
                                        <td>{{$apostas->nomreven}}</td>
                                        <td><b>{{$apostas->sorteio}}</b></td> 
                                        <td>R$ {{ number_format($apostas->vlrpalp, 2, ',', '.') }}</td>
                                        <td>R$ {{ number_format($apostas->vlrpremio, 2, ',', '.') }}</td>
                                        <td>{{Carbon\Carbon::parse($apostas->datsor)->format('d/m/Y')}}</td>
                                        <td>{{Carbon\Carbon::parse($apostas->horsor)->format('H:i:s')}}</td>
                                        

                                    </tr>

                                    
                                 
                                @empty
                                    <tr>
                                        Nenhum registro encontrado!
                                    </tr>
                                @endforelse
                                </tbody>
                                
                            </table>
                        @else
                        <p>Nenhum registro encontrado!</p>
                        @endif
                    </div>

                
            </div>
            <div class="row">
                @php
                    $totalPulesValido = 0;
                    $totalPremios =0;
                    $totalLiquido = 0;
                    foreach ($data as $key){
                        if ($key->sitapo == 'I'){
                            $totalPulesValido += $key->vlrpalp;
                            $totalPremios += $key->vlrpremio;
                            $totalLiquido += $key->vlrpalp - $key->vlrpremio;
                        }
                    }

                @endphp
                <div class="row">
                    <div class="col s12 m2 l4 blue-grey darken-3 center-align">  
                        <h5 class="white-text">Total Pules:&nbsp;&nbsp;&nbsp; @php echo number_format($totalPulesValido, 2, ',', '.'); @endphp</h5>    
                    </div>  
                    

                    <div class="col s12 m2 l4 blue-grey darken-3 center-align">  
                        <h5 class="white-text">Total Prêmios:&nbsp;&nbsp;&nbsp; @php echo number_format($totalPremios, 2, ',', '.'); @endphp</h5>    
                    </div>  
                   
                    <div class="col s12 m2 l4 blue-grey darken-3 center-align">  
                        <h5 class="white-text">Total Líquido:&nbsp;&nbsp;&nbsp; @php echo number_format($totalLiquido, 2, ',', '.'); @endphp</h5>    
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div id="aposta" class="modal">
        <div class="right-align">
            <a href="#!" class=" btn modal-action modal-close waves-effect waves-light red "><i class=" Tiny material-icons">close</i></a>
        </div>
        <div class="modal-content">
            <h4>Visualizar Aposta</h4>
            <div id="modal_content" class="row">
                <div class="row">
                    <div class="input-field col s6 m2">
                            <label class="active" for="n_aposta">Nº Aposta</label>
                        <input  readonly id="n_aposta" type="text" class="validate" value="0000">
                        
                    </div>
                    <div class="input-field col s6 m2">
                        <input  readonly id="vlr_aposta" type="text" class="validate" value="0,00">
                        <label class="active" for="vlr_aposta">Valor</label>
                    </div>
                    <div class="input-field col s6 m3">
                            <label class="active" for="revendedor">Revendedor</label>
                        <input  readonly id="revendedor" type="text" class="validate" value="Revendedor">
                        
                    </div>
                    <div class="input-field col s6 m3">
                        <input  readonly id="vendedor" type="text" class="validate" value="Vendedor">
                        <label class="active" for="vendedor">Vendedor</label>
                    </div>
                 <!--   <div class="input-field col s6 m2">
                            <a href="#sorteio"
                            class="btn waves-effect waves-light">Visualizar Sorteio</a>
                    </div>-->


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
        {{--<div class="modal-footer">--}}
            {{--<a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Ok</a>--}}
        {{--</div>--}}
    </div>

@endsection
@push('modal')
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
                } else if(data[i].sitapo == 'I'){
                    var vaSituapo = 'INSTANTANEA';
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
@endpush

@push('scripts')
        <script type="text/javascript" src="{{url('js/jquery.mask.js')}}"></script>

        <script>
    $(document).ready(function() {

        $('#numpule').mask('####################'), {reverse: true};

        var table = $('#example').DataTable( {

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

        // #myInput is a <input type="text"> element
        $('#myInput').on( 'keyup', function () {
            table.search( this.value ).draw();
        } );

    });
</script>
@endpush

