@extends('dashboard.templates.app')

@section('content')
    <div class="section">
        <div class="row">
            <div class="col m12 s12 l6">
                <div class="card">
                    <div class="card-content">
                        @forelse ($baseAll as $bases)
                            <form class="form-group" id="form-cad-edit" method="post" action="/admin/saldoinstantanea/{{$bases->ideven}}" enctype="multipart/form-data">
                                {{ csrf_field() }}


                                @empty
                           
                        @endforelse

                        <div class="row">
                            @forelse ($saldo as $saldoIns)
                       

                            <div class="input-field col s12 m12 l12">
                                <input id="saldo_venda" onfocus="calcular()" name="saldo_venda" type="text" class="saldo_venda validate" value="{{number_format($saldoIns->saldo_venda, 2, ',', '.')}}">
                                <label class="active" for="saldo_venda">Saldo de Venda</label>
                            </div>

                            <div  class="input-field col s12 m12 l12">
                                <input id="saldo_premio" onblur="calcular()" min="0" name="saldo_premio" type="text" class="saldo_premio validate" value="{{number_format($saldoIns->saldo_premio, 2, ',', '.')}}">
                                <label class="active" for="saldo_premio">Saldo em Prêmio</label>
                            </div>

                            <div class="input-field col s12 m12 l12">
                                <input id="liquido" name="liquido" type="text" class="validate" readonly=“true” value="{{number_format($saldoIns->saldo_liquido, 2, ',', '.')}}">
                                <label class="active" for="liquido">Líquido</label>
                            </div>

                            <div class="input-field col s12 m12 l12">
                                <input id="per_liberar" name="per_liberar" type="text" class="validate" id="input" value="{{$saldoIns->perlib}}">
                                <label class="active" for="per_liberar">% Liberação</label>
                            </div>

                           

                            <div class="input-field col s12 m6 l2">
                                <button class="btn waves-effect waves-red" type="submit" id="botao" onclick="calcular()" name="action">Salvar
                                    <i class="material-icons right">send</i>
                                </button>
                            </div>
                            @empty
                            @endforelse
                        </div>
                    </form>
                    </div>
                </div>
            </div>

        </div>
   
    </div>
                

@endsection
@push('modal')
<script type="application/javascript">

    // liberar botão alterar
    $(document).ready(function(){

        //desabilita o botão no início
        document.getElementById("botao").disabled = true;
        //busca conteúdo do input

     //   $('form').on('change paste', 'input, select, textarea', function(){
     //       $mudou=true;

      //      if ($mudou==true){
     //       document.getElementById("botao").disabled = false;
     //   }
     //   });

    });
    
    function calcular(){
      
     $('.saldo_venda').mask('#.##0,00', {reverse: true});
     $('.saldo_premio').mask('#.##0,00', {reverse: true});
     $('.liquido').mask('#.##0,00', {reverse: true});
    
    var valor1 = document.getElementById('saldo_venda').value;
    var valor2 = document.getElementById('saldo_premio').value;
    var liqui = document.getElementById('liquido').value
    valor1 = parseFloat(valor1).toFixed(2);
    valor2 = parseFloat(valor2).toFixed(2);
    liqui = parseFloat(liqui).toFixed(2);
  //  alert(valor2);
    
  //  var valor2 = parseInt(document.getElementById('saldo_premio').value, 10);
   
  

  //  alert(valor1,valor2);
  
    if(valor2 > valor1){
              //alert('Para o seu consumo iremos tratar o seu orçamento e projeto de forma exclusiva. ');
             alert('Valor do prêmio não pode ser maior que o vendido');
             // event.preventDefault();
             document.getElementById("botao").disabled = true;
             document.getElementById("saldo_venda").disabled = true;
             liqui  = (valor1 - valor2)*1000;
           // alert(liqui);
            $('#liquido').val(liqui.toFixed(2).replace(".", ","));
             $("#saldo_premio").css("background","#FF4222");
                    
         } else{
            document.getElementById("botao").disabled = false;
            document.getElementById("saldo_venda").disabled = false;
            liqui  = (valor1 - valor2)*1000;
           // alert(liqui);
            $('#liquido').val(liqui.toFixed(2).replace(".", ","));
           //  document.getElementById('liquido').value =  parseInt(liqui*100);
            $("#saldo_premio").css("background","#0000");
         }

      
}

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



function openModalPremio(url) {

$('#tbody_sorteio').empty(); //Limpando a tabela

$('#sorteio').modal('open');


jQuery.getJSON(url, function (data) {
    
    var vlrPalp = 0

    for (var i = 0; i <data.length; i++){
       
        var newRow = $("<tr>");
        var cols = "";
        var horsorinst = time_format(new Date(data[i].horsor));
         var datasor = DateChance(data[i].datsor);
         
        cols += '<td>'+data[i].numpule+'</td>';
        cols += '<td>'+data[i].sorteio+'</td>';
        cols += '<td>'+parseFloat(data[i].vlrpremio).toFixed(2).replace(".", ",")+'</td>';
        cols += '<td>'+data[i].nomreven+'</td>';
        cols += '<td>'+datasor+'</td>';
        cols += '<td>'+horsorinst+'</td>';
      
        var vlrpreioinst = data[i].vlrpalp;
                var numpuleinst = data[i].numpule;
                var nomreveninst = data[i].nomreven;
                var nomveninst =  data[i].nomven;
                vlrPalp += parseFloat(vlrpreioinst);

        newRow.append(cols);
        $("#tbody_sorteio").append(newRow);
    }

    $('#vlr_apostainst').val(vlrPalp.toFixed(2).replace(".", ","));
    $('#n_apostainst').val(numpuleinst);
    $('#n_revendedor_inst').val(nomreveninst);
    $('#vendedorinst').val(nomveninst);
});

};


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
                }
                 else {
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

