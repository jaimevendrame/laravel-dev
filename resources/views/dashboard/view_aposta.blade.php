@extends('dashboard.templates.app')

@section('content')
    <div class="section">
        <div class="row">
            <div class="col s12">
              
                    <div class="card-content">
                        @forelse ($baseAll as $bases)
                        
                        {{ csrf_field() }}
                                                                                                     
                            <form class="form-group" id="form-cad-edit" method="post" action="/admin/apostas/cancelar/{{$bases->ideven}}" enctype="multipart/form-data">
                                
                                @empty
                            
                        @endforelse
                                <div class="row">
                                  
    
                                    <div class="input-field col s12 m4 l2">
                                        <input id="n_pule" name="n_pule" type="tel" class="validate">
                                        <label class="active" for="n_pule">Nº Aposta</label>
                                    </div>
    
                                    <div class="input-field col s6 m4 l2">
                                        <input id="datIni" name="datIni" type="date" class="datepicker"
                                               placeholder ="Data inicial">
                                    </div>
                                    <div class="input-field col s6 m4 l2">
                                         <input id="datFim" name="datFim" type="date" class="datepicker"
                                                 placeholder ="Data final">
                                    </div>
    
    
                                    <div class="input-field col s12 m6 l2">
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
    
                                    <div class="input-field col s12 m6 l2">
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
    
                                    <div class="input-field col s12 m6 l2">
                                        <button class="btn waves-effect waves-red" type="submit" name="action">Mostrar
                                            
                                        </button>
                                    </div>
    
                                </div>
                            </form>
                            @if(!empty($dataCancelar))
                            <table class="mdl-data-table" id="example" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                   
                                    <th>Pule</th>
                                    <th>Valor</th>
                                    <th>Revendedor</th>
                                    <th>Geração</th>
                                    <th>Envio</th>
                                    <th>Situação</th>
                                    <th>Vendedor</th>
                                    <th>Cidade</th>


                                </tr>
                                </thead>
                                <tbody>
                                {{--//#fffde7--}}

                                @forelse($dataCancelar as $apostas)
                                    <tr>
                                        <td>
                                            <a id="link-modal" class="botao-transmissoes btn modal-trigger btn_grid btn_grid_130px" href="#" onclick='openModal1("/admin/apostas/view/{{$apostas->numpule}}/{{$apostas->ideven}}")'>
                                            {{$apostas->numpule}}</a>
                                        </td>
                                       
                                        <td><b>{{ number_format($apostas->vlrpalp, 2, ',', '.') }}</b></td>
                                        <td>{{$apostas->nomreven}}</td>
                                        <td>{{Carbon\Carbon::parse($apostas->horger)->format('H:i:s')}} {{Carbon\Carbon::parse($apostas->datger)->format('d/m/Y')}}</td>
                                        <td>{{Carbon\Carbon::parse($apostas->horenv)->format('H:i:s')}} {{Carbon\Carbon::parse($apostas->datenv)->format('d/m/Y')}}</td>
                                        <td {{ $apostas->sitapo == 'CAN'  ? "bgcolor = #f44336" : '' }}> @if($apostas->sitapo == 'CAN') CANCELADO @elseif($apostas->sitapo == 'V')VALIDO
                                             @elseif($apostas->sitapo == 'I')
                                             <a id="link-modal2" class=" btn modal-trigger btn_grid btn_grid_130px" href="#" onclick='openModalPremio("/admin/apostas/instantanea/{{$apostas->numpule}}")'>
                                                INSTANTANEA</a>

                                              @else PREMIADO @endif</td>
                                        <td>{{$apostas->nomven}}</td>
                                        <td>{{$apostas->cidreven}}</td>
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
        </div>
       {{-- <div class="row">
            <div class="col s12">
                <div class="card">
                    <div class="card-content">

                        @php
                         if (!empty($data)){

                         $totalPules= 0;
                            foreach ($data as $key){

                                    $totalPules += $key->vlrpalp;

                            }
                         }


                        @endphp


                        @if($title == 'Cancelar Aposta')
                            <form class="form-group" id="form-cad-edit" method="post" action="/admin/apostas/cancel/{{$ideven}}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                @else
                            <form class="form-group" id="form-cad-edit" method="post" action="/admin/apostas/view/{{$ideven}}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                        @endif
                            <div class="row">
                                <div class="input-field col s8 m3">
                                    <input  id="numpule2" name="numpule2" type="text" class="validate" value="@if(!empty($data)) {{$data[0]->numpule}} @endif">
                                    <label class="active" for="numpule">Nº Aposta</label>
                                </div>
                                <div class="input-field col s4 m1">
                                    <button class="tiny btn waves-effect waves-light" type="submit" name="action">
                                        <i class="tiny material-icons">send</i>
                                    </button>
                                </div>

                                <div class="input-field col s12 m2">
                                    <input  readonly id="vlr_aposta" type="text" class="validate" value="@if(!empty($data)) {{number_format($totalPules, 2, ',', '.')}} @endif">
                                    <label class="active" for="vlr_aposta">Valor</label>
                                </div>
                                <div class="input-field col s12 m3">
                                    <input  readonly id="revendedor" type="text" class="validate" value="@if(!empty($data)) {{$data[0]->idbase}}-{{$data[0]->nomreven}} @endif">
                                    <label class="active" for="revendedor">Revendedor</label>
                                </div>
                                <div class="input-field col s12 m3">
                                    <input  readonly id="vendedor" type="text" class="validate" value="@if(!empty($data)) {{$data[0]->idven}}-{{$data[0]->nomven}} @endif">
                                    <label class="active" for="vendedor">Vendedor</label>
                                </div>
                            </div>

                        </form>
                                @if($title == 'Cancelar Aposta')
                                <form class="form-group" id="form-cancel" method="post" action="/admin/apostas/cancel/pule/{{$ideven}}" send="cancelar" enctype="multipart/form-data">
                                    {{ csrf_field() }}

                                    @if(!empty($data))
                                        @php
                                            $nr_pule = str_replace(" ","",$data[0]->numpule);
                                        @endphp
                                    @endif
                                    <input type="text" name="numpule2" id="numpule2" value="@if(!empty($data)) {{$nr_pule}} @endif">
                                    <input type="text" name="idlot2" id="idlot2" value="@if(!empty($data)) {{$data[0]->idlot}} @endif">
                                    <input type="text" name="idhor2" id="idhor2" value="@if(!empty($data)) {{$data[0]->idhor}} @endif">
                                    <input type="text" name="retorno2" id="retorno2" value="PENDENTE">
                                    <input type="text" name="dataaposta2" id="dataaposta2" value="@if(!empty($data)) {{Carbon\Carbon::parse($data[0]->datapo)->format('Y-m-d')}} @endif">
                                </form>
                                @endif
                        @if(!empty($data))
                            <div class="row">
                                @if($data[0]->sitapo == 'CAN')
                                    <div class="col s12 z-depth-2 red">
                                        <div class="row">
                                            <h6>Aposta: <b class="white-text">{{$data[0]->numpule}} </b>já foi Cancelada!</h6>
                                        </div>
                                    </div>
                                @else
                                    <table class="mdl-data-table " id="apostas"  cellspacing="0" width="100%">
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
                                        <tbody>

                                        @forelse($data as $aposta)
                                            <tr>
                                                <td>{{$aposta->destipoapo}}</td>
                                                <td>
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
                                                <td>{{$aposta->descol}}</td>
                                                <td>{{number_format($aposta->vlrpalp, 2, ',', '.')}}</td>
                                                <td>{{Carbon\Carbon::parse($aposta->datapo)->format('d/m/Y')}}</td>
                                                <td>{{$aposta->deshor}}</td>
                                                <td>@if($aposta->sitapo == 'CAN') CANCELADO @elseif($aposta->sitapo == 'V')VALIDO @else PREMIADO @endif</td>
                                                <td>{{Carbon\Carbon::parse($aposta->datenv)->format('d/m/Y')}}</td>
                                                <td>{{Carbon\Carbon::parse($aposta->horenv)->format('H:i:s')}}</td>
                                                <td>@if(!empty($aposta->datcan)){{Carbon\Carbon::parse($aposta->datcan)->format('d/m/Y')}}@endif</td>
                                                <td>@if(!empty($aposta->horcan)){{Carbon\Carbon::parse($aposta->horcan)->format('H:i:s')}}@endif</td>
                                                <td>{{number_format($aposta->vlrcotacao, 2, ',', '.')}}</td>
                                                <td>{{number_format($aposta->vlrpre, 2, ',', '.')}}</td>
                                                <td>{{number_format($aposta->vlrpalpf, 2, ',', '.')}}</td>
                                                <td>{{number_format($aposta->vlrpalpd, 2, ',', '.')}}</td>
                                                <td>{{number_format($aposta->vlrpresec, 2, ',', '.')}}</td>
                                                <td>{{number_format($aposta->vlrpremol, 2, ',', '.')}}</td>
                                                <td>{{number_format($aposta->vlrpresmj, 2, ',', '.')}}</td>
                                                <td>{{number_format($aposta->vlrprepag, 2, ',', '.')}}</td>
                                            </tr>

                                        @empty
                                            <tr>
                                                nenhum registro encontrado!
                                            </tr>
                                        @endforelse
                                        </tbody>
                                        <tfoot>
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
                                        </tfoot>
                                    </table>
                                @endif
                            </div>
                        @else
                        <p>Nenhum registro encontrado!</p>
                        @endif
                    </div>

                </div>
            </div>
        </div> --}}

        <div id="aposta" class="modal">
            <div class="right-align">
                <a href="#!" class=" btn modal-action modal-close waves-effect waves-light red "><i class=" Tiny material-icons">close</i></a>
            </div>
            <div class="modal-content">
                <h4>Visualizar Aposta</h4>
                <div id="modal_content" class="row">
                    <div class="row">
                        <form class="form-group" id="form-cancelar" method="post" action="/admin/apostas/cancel/pule/{{$ideven}}" send="cancelar" enctype="multipart/form-data">
                            {{ csrf_field() }}

                         <div class="input-field col s6 m2 l2">
                                <label class="active" for="numpule2">Nº Aposta</label>
                            <input  readonly  type="text" name="numpule" id="numpule" class="validate" value="0000">
                            
                        </div>
                            
                            <input type="hidden" name="idlot" id="idlot" >
                            <input type="hidden" name="idhor" id="idhor" >
                            <input type="hidden" name="retorno" id="retorno" value="PENDENTE">
                            <input type="hidden" name="dataaposta" id="dataaposta" >
                        </form>
                        
                        <div class="input-field col s6 m2 l2">
                            <input  readonly id="vlr_aposta2" type="text" class="validate" value="0,00">
                            <label class="active" for="vlr_aposta2">Valor</label>
                        </div>
                        <div class="input-field col s6 m3 l3">
                                <label class="active" for="revendedor2">Revendedor</label>
                            <input  readonly id="revendedor2" type="text" class="validate" value="Revendedor">
                            
                        </div>
                        <div class="input-field col s6 m3 l3">
                            <input  readonly id="vendedor2" type="text" class="validate" value="Vendedor">
                            <label class="active" for="vendedor2">Vendedor</label>
                        </div>
                        <div class="input-field col s6 m3 l2">
                            <button type="reset" id="btncancel" class="reset btn waves-effect green waves-light " onclick="cancelarAposta()">Cancelar Aposta</button>
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

        <div id="sorteio" class="modal">
            <div class="right-align">
                <a href="#!" class=" btn modal-action modal-close waves-effect waves-light red "><i class=" Tiny material-icons">close</i></a>
            </div>
            <div class="modal-content">
                <h4>Sorteio Instantânea </h4>
                <div id="modal_content" class="row">
                    <div class="row">

                        <div class="input-field col s6 m2">
                                <label class="active" for="n_apostainst">Nº Aposta</label>
                            <input  readonly id="n_apostainst" type="text" class="validate" value="0000">
                            
                        </div>
                        <div class="input-field col s6 m2">
                            <input  readonly id="vlr_apostainst" type="text" class="validate" value="0,00">
                            <label class="active" for="vlr_apostainst">Valor</label>
                        </div>
                        <div class="input-field col s6 m4">
                                <label class="active" for="n_revendedor_inst">Revendedor</label>
                            <input  readonly id="n_revendedor_inst" type="text" class="validate" value="Revendedor">
                            
                        </div>
                        <div class="input-field col s6 m4">
                            <input  readonly id="vendedorinst" type="text" class="validate" value="Vendedor">
                            <label class="active" for="vendedorinst">Vendedor</label>
                        </div>

                    </div>
                    <div class="scroll">
                        <div class="row">
                            <table id="tb-sorteio" class="display mdl-data-table" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>PULE</th>
                                    <th>SORTEIO</th>
                                    <th>VALOR PRÊMIO</th>
                                    <th>REVENDEDOR</th>
                                    <th>DATA</th>
                                    <th>HORA</th>
                               
                                </tr>
                                </thead>
                                <tbody id="tbody_sorteio">
                                </tbody>
                                
                            </table>
                        </div>
                    </div>   

                </div>
    </div>
</div>

    </div>

@endsection

@if($title == 'Cancelar Aposta')

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



    function cancelarAposta(){

        var cancelar = $('#form-cancelar').attr('send');

        $('#numpule').mask('####################'), {reverse: true};

          var nr_pule = $('#numpule').val();

         

            if ( nr_pule == ''){
                Materialize.toast('Digite uma Aposta', 3000)
                return false;
            }

            var dadosForm = jQuery('#form-cancelar').serialize();
         

            var action= $('#form-cancelar').attr('action');
          

           decisao = confirm("Cancelar a aposta: "+ nr_pule);
          

            if (decisao){

                jQuery.ajax({
                   url: action,
                   data: dadosForm,
                   method: 'POST'


                }).done(function (data) {


                    if (data == '1') {

                        alert('Aposta '+ nr_pule + ' cancelada com sucesso');

                        location.reload();

                    } else {
                        alert('Falha ao cancelar a Aposta: '+ nr_pule + '\n' + data);

                    }
                }).fail(function () {
                    alert('Falha ao enviar dados!!');


                });

               return false;



            } else {
                return false;
            }

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
                var idhor = data[i].idhor;
                var idlot = data[i].idlot;
                var idreven = data[i].idbase +' '+ data[i].nomreven;
                var nomven = data[i].idven +' '+ data[i].nomven;


               

                var datApo = DateChance(data[i].datapo);
                var datEnv = DateChance(data[i].datenv);
                var dataAtual = new Date();
                var dataAtualFormatada =  dataAtual.getFullYear() + "-" 
                + ("0" + (dataAtual.getMonth() + 1)).substr(-2) + "-" + ("0" + dataAtual.getDate()).substr(-2);
                  
                if(data[i].sitapo == 'V'){
                    var vaSituapo = 'VALIDO';
                } else if(data[i].sitapo == 'CAN'){
                    document.getElementById("btncancel").disabled = true;
                    var vaSituapo = 'CANCELADO';
                } else if(data[i].sitapo == 'I'){
                    document.getElementById("btncancel").disabled = true;
                    var vaSituapo = 'INSTANTANEA';
                } 
                 else {
                    document.getElementById("btncancel").disabled = true;
                    var vaSituapo = 'PREMIADO';
                }

                if((vaSituapo = 'VALIDO') && (datApo < dataAtualFormatada)){
                   // alert(datApo);
                    document.getElementById("btncancel").disabled = true;
                }

               
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

            $('#vlr_aposta2').val(vlrPalp.toFixed(2).replace(".", ","));
            $('#numpule').val(numpule);
            $('#revendedor2').val(idreven);
            $('#vendedor2').val(nomven);
            $('#idlot').val(idlot);
            $('#idhor').val(idhor);
            $('#dataaposta').val(datApo);
        });

    };

    function DateChance(data) {
        var getDate = data.slice(0, 10).split('-'); //create an array
        var _date =getDate[0] +'-'+ getDate[1] +'-'+ getDate[2];
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

});herc
         
    $(document).ready(function() {

        var cancelar = $('#form-cancel').attr('send');


        $('#numpule').mask('####################'), {reverse: true};


        var table = $('#apostas').DataTable( {
//            fixedColumns: {
//                leftColumns: 2
//
//            },


            dom: 'Brtip',


            buttons: [


                {
                    text: 'Cancelar Aposta',
                    action: function () {

                        var nr_pule = $('#numpule2').val();

                        if ( nr_pule == ''){
                            Materialize.toast('Digite uma Aposta', 3000)
                            return false;
                        }

                        var dadosForm = jQuery('#form-cancel').serialize();
                        alert(dadosForm);
                        console.info(dadosForm);
                        var action= $('#form-cancel').attr('action');
                        alert(action);
                        console.info(action);

                        decisao = confirm("Cancelar a aposta: "+ nr_pule);

                        if (decisao){

                            jQuery.ajax({
                                url: action,
                                data: dadosForm,
                                method: 'POST'


                            }).done(function (data) {


                                if (data == '1') {

                                    alert('Aposta '+ nr_pule + ' cancelada com sucesso');

                                    location.reload();

                                } else {
                                    alert('Falha ao cancelar a Aposta: '+ nr_pule + '\n' + data);

                                }
                            }).fail(function () {
                                alert('Falha ao enviar dados!!');


                            });

                            return false;



                        } else {
                            return false;
                        }

                    }
                },


            ],


            scrollY: 480,
            scrollX:        true,
            scrollCollapse: true,
            paging:         false,
            Bfilter:        false,
            "aaSorting": [[0, "desc"]],


            columnDefs: [
                {
                    targets: [ 0, 1, 2 ,3 ,4 ,5 ,6 ,7 ,8 ],
                    className: 'mdl-data-table__cell--non-numeric'
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



    });



</script>
@endpush
    @else
    @push('scripts')
    <script type="text/javascript" src="{{url('js/jquery.mask.js')}}"></script>

    <script>
        $(document).ready(function() {

            var cancelar = $('#form-cancel').attr('send');


            $('#numpule').mask('####################'), {reverse: true};


            var table = $('#apostas').DataTable( {
//            fixedColumns: {
//                leftColumns: 2
//
//            },


                dom: 'Brtip',


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
                    },


                ],


                scrollY: 480,
                scrollX:        true,
                scrollCollapse: true,
                paging:         false,
                Bfilter:        false,
                "aaSorting": [[0, "desc"]],


                columnDefs: [
                    {
                        targets: [ 0, 1, 2 ,3 ,4 ,5 ,6 ,7 ,8 ],
                        className: 'mdl-data-table__cell--non-numeric'
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



        });



    </script>
    @endpush
@endif
