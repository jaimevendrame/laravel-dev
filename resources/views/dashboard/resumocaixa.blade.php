@extends('dashboard.templates.app')

@section('content')
    {{--@forelse($ideven2 as $p)--}}
        {{--{{$p}}--}}
        {{--@empty--}}
        {{--@endforelse--}}

    <div class="section">
        <div class="row">
            <div class="col s12">
                <div class="card-content">     
                    @forelse ($baseAll as $bases)
                        
                    {{ csrf_field() }}
                        <form class="form-group" id="form-cad-edit" method="post" action="/admin/resumocaixa/{{$bases->ideven}}/" enctype="multipart/form-data">
                            
                            @empty
                        
                            @endforelse
                            <div class="row">
                                <div class="col s12 m12 l5">
                                    <div class="row">
                                        <div class="input-field col s6 m6 l3">
                                            <input id="datIni" name="datIni" type="date" class="datepicker" id="id_nomReven"
                                                   placeholder ="Data inicial">
        
                                        </div>
                                        <div class="input-field col s6 m6 l3">
                                             <input id="datFim" name="datFim" type="date" class="datepicker"
                                                     placeholder ="Data final">
                                        </div>
        
                                        
                                        <div class="input-field col s12 m6 l6">
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
        
        
        
                                        
                                    </div>

                                </div>
                                <div class="col s12 m12 l7">
                                    <div class="row">
                                            @php
                                                if(session()->has('idCobra')){ 
                                                $idCobra = session('idCobra');
                                                }else{
                                                    $idCobra = 0;
                                                }
                                             @endphp
    
                                     
  
                                  <div class="input-field col s6 m2 l4">
                                      <select name="idcobra">
                                          <option value="" selected>Selecione</option>
                                              @if($idCobra <> 0){
                              
                                                  @forelse($cobradores as $c)
                                                  <option value="{{ $c->idcobra }}"
                                                  @if($c->idcobra == $idCobra )
                                                      selected
                                                  @endif
                                                       >{{ $c->nomcobra }}</option>
                                                   @empty
                                                      <option value="" disabled selected>Nenhum Cobrador</option>
                                                  @endforelse
  
                                              }
                                              @else{
  
                                                  @forelse($cobradores as $c)
                                                  <option value="{{ $c->idcobra }}"
                                                  @if(isset($dados->idcobra) && $dados->idcobra == $c->idcobra )
                                                      selected
                                                  @endif
                                                       >{{ $c->nomcobra }}</option>
                                                   @empty
                                                      <option value="" disabled selected>Nenhum Cobrador</option>
                                                  @endforelse
  
                                              }
                                              @endif
  
                                              
                                      </select>
                                      <label>Cobrador</label>
                                  </div>
  
                                 
                                  <div class="input-field col s6 m2 l3">
                                      <select multiple name="sel_options[]">
                                          <option value="" disabled selected>Opções</option>
                                          <option value="1" @if(isset($var_despesas)){{ $var_despesas == 'SIM'  ? 'selected' : '' }} @endif>Com Despesas</option>
                                          <option value="2" @if(isset($in_ativos)){{ $in_ativos == 'SIM'  ? 'selected' : '' }} @endif>Mostrar Inativos</option>
                                      </select>
                                      <label>Opções</label>
                                  </div>
  
                                  <div class="input-field col s6 m3 l3">
                                      <select multiple name="sel_vendedor[]">
                                          <option value="" disabled selected>Região</option>
                                          @forelse($baseAll as $bases)
                                              @if( isset($ideven) && !empty($ideven))
                                                   <option value="{{$bases->ideven}}" {{ $bases->ideven == $ideven  ? 'selected' : '' }} >{{$bases->nomven}}</option>
                                              @elseif(isset($ideven2) && (is_array($ideven2)))                                                  
                                                   <option value="{{$bases->ideven}}" @forelse($ideven2 as $select) {{ $bases->ideven == $select  ? 'selected' : '' }}  @empty @endforelse >{{$bases->nomven}}</option>
                                              @else
                                                   <option value="{{$bases->ideven}}">{{$bases->nomven}}</option>
                                              @endif
                                          @empty
                                              <option value="" disabled selected>Nenhuma base</option>
                                          @endforelse
  
                                      </select>
                                      <label>Região</label>
                                  </div>
                                             
                                    <div class="input-field col s6 m2 l2 right-align">
                                        <button class="btn waves-effect waves-light" type="submit" name="action">Mostrar
                                            <i class="material-icons right">send</i>
                                        </button>
                                    </div>
  
                                    </div>
                                </div>
                                

                            </div>
                           
                       
                          
                        </form>
           

                        <table class="mdl-data-table" id="example" cellspacing="0" width="100%">
                            <thead>
                            @php
                                $saldoanterior = 0;

                                $venda = 0;

                                $comissao = 0;

                                $liquido = 0;

                                $premio = 0;

                                $despesas = 0;


                                $pagto = 0;

                                $recb = 0;

                                $saldoatual = 0;
                                $semvendas = 0;

                                foreach($data as $key) {

                                        if ($key->vlrven <= 0){
                                        $semvendas += 1;
                                        }

                                         $saldoanterior += $key->vlrdevant;

                                         $venda += $key->vlrven;

                                         $comissao += $key->vlrcom;

                                         $liquido += $key->vlrliqbru;

                                         $premio+= $key->vlrpremio;

                                         $despesas += $key->despesas;


                                         $pagto+= $key->vlrpagou;

                                         $recb+= $key->vlrreceb;

                                         $saldoatual+= $key->vlrdevatu;

                                            }
                            @endphp

                            <tr > 
                                <th class="black-text"><b>Revendedor</b></th>
                                <th class="black-text"> <b>Vendido</b></br>@php echo number_format($venda, 2, ',', '.'); @endphp </th>                                
                                <th class="black-text"><b>Comissão</b></br>@php echo number_format($comissao, 2, ',', '.'); @endphp</th>
                                <th class="black-text"><b>Liquido</b></br>@php echo number_format($liquido, 2, ',', '.'); @endphp</th>
                                <th class="black-text"><b>Prêmio</b></br>@php echo number_format($premio, 2, ',', '.'); @endphp</th>
                                <th class="black-text"><b>Despesas</b></br>@php echo number_format($despesas, 2, ',', '.'); @endphp</th>
                                <th class="black-text"><b>Lucro</b></br>@php echo number_format($liquido - $premio - $despesas, 2, ',', '.'); @endphp</th>
                                <th class="black-text"><b>Pagamento</b></br>@php echo number_format($pagto, 2, ',', '.'); @endphp</th>
                                <th class="black-text"><b>Recebimento</b></br>@php echo number_format($recb, 2, ',', '.'); @endphp</th>
                                <th class="black-text"><b>Saldo Atual</b></br>@php echo number_format($saldoatual, 2, ',', '.'); @endphp</th>
                                <th class="black-text">Limite Créd.</th>
                                <th class="black-text">Última Venda</th>
                                <th class="black-text"><b>Saldo Anterior</b></br>@php echo number_format($saldoanterior, 2, ',', '.'); @endphp</th>     
                            </tr>

                            </thead>
                            <tbody>

                            @forelse($data as $resumo)

                                <tr>
                                    <td ondblclick="showNomeReven('{{$resumo->nomreven}}')">@php if (strlen($resumo->nomreven) <=15) {
                                    echo $resumo->nomreven;
                                    } else {
                                    echo substr($resumo->nomreven, 0, 15) . '...';
                                    }@endphp</td>

                                    <td>{{ number_format($resumo->vlrven, 2, ',', '.') }}</td>
                                        
                                  
                                

                                    <td>{{ number_format($resumo->vlrcom, 2, ',', '.') }}</td>
                                    <td>{{ number_format($resumo->vlrliqbru, 2, ',', '.') }}</td>
                                    <td>@if($resumo->vlrpremio > 0)
                                             <a href="#{{$resumo->vlrdevant}}"
                                             class="waves-effect waves-light btn-small">
                                        @endif{{ number_format($resumo->vlrpremio, 2, ',', '.') }}</a></td>
                                    @if($resumo->vlrpremio > 0)
                                    <!-- Modal Structure -->
                                        <div id="{{$resumo->vlrdevant}}" class="modal modal2">
                                            <div class="modal-content">
                                                <div class="modal-footer">
                                                    <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat"><i class="material-icons">close</i></a>
                                                </div>
                                                <h4>Aposta Premiada</h4>
                                                <p>
                                                <div class="video-container">
                                                    <iframe width="700" height="315" src="/admin/resumocaixa/aposta_premiada/{{$resumo->idven}}/{{$resumo->idbase}}/{{$resumo->idreven}}/{{Carbon\Carbon::parse($resumo->dataini)->format('Y-m-d')}}/{{Carbon\Carbon::parse($resumo->datafim)->format('Y-m-d')}}" frameborder="0" allowfullscreen></iframe>
                                                </div>
                                                </p>

                                            </div>
                                        </div>
                                    @endif
                                    @if(isset($var_despesas))
                                        @if($var_despesas == 'SIM')
                                        <td>{{ number_format($resumo->despesas, 2, ',', '.') }}</td>
                                        @else
                                        <td>{{ number_format(0, 2, ',', '.') }}
                                        </td>
                                        @endif
                                    @else
                                        <td>{{ number_format(0, 2, ',', '.') }}</td>

                                    @endif
                                    <td>{{ number_format(($resumo->vlrliqbru - $resumo->vlrpremio - $resumo->despesas), 2, ',', '.') }}</td>
                                    <td>{{ number_format($resumo->vlrpagou, 2, ',', '.') }}</td>
                                    <td>{{ number_format($resumo->vlrreceb, 2, ',', '.') }}</td>
                                    <td @if ($resumo->vlrdevatu < 0) class='white-text' bgcolor='#e53935'
                                        @elseif ($resumo->vlrdevant > 0) class='white-text' bgcolor='#4caf50'
                                    @else @endif><b>{{ number_format($resumo->vlrdevatu, 2, ',', '.') }}</b></td>
                                    <td>{{ number_format($resumo->limcred, 2, ',', '.') }}</td>
                                    <td> {{ Carbon\Carbon::parse($resumo->dataultven)->format('d/m/Y') }}</td>
                                    {{--                                    <td> {{$resumo->dataultve n}}</td>--}}

                                    <td
                                            @if ($resumo->vlrdevant < 0)class='white-text' bgcolor='#e53935'
                                            @elseif ($resumo->vlrdevant > 0) class='white-text' bgcolor='#4caf50'
                                    @else @endif >
                                        <b>{{ number_format($resumo->vlrdevant, 2, ',', '.') }}</b></td>

    
                                </tr>
                                
                            @empty
                                <tr>
                                    nenhum registro encontrado!
                                </tr>
                            @endforelse
                                </tbody>
                          
                        </table>
                            {{--    </div>  --}}
                       

                        <div class="row">
                            <div class="col s12 m2 l3">
                                <div class="col s12 z-depth-2 blue-grey lighten-5 hoverable">
                                    <div class="col s12 m5 l12 left-align"> 
                                        <h5 class=" blue-grey-text left-align"> Revendedor: @php echo count($data)@endphp </h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col s12 m2 l3">
                                <div class="col s12 z-depth-2 blue-grey lighten-5 hoverable">
                                    <div class="col s12 m5 l12 left-align"> 
                                        <h5 class="blue-grey-text left-align">Sem vendas: @php echo $semvendas @endphp</h5>
                                        
                                    </div>                                  
                                </div>
                            </div>
                            <div class="col s12 m2 l3">
                                <div class="col s12 z-depth-2 blue-grey lighten-5 hoverable">
                                    <div class="col s12 m5 l12 left-align"> 
                                        <h5 class="blue-grey-text left-align">Com vendas: @php echo count($data) - $semvendas @endphp</h5>
                                </div>
                                </div>
                            </div>
                            <div class="col s12 m2 l3">
                                @php
                                if ( ($recb - $pagto) < 0)
                                 $cor = 'vermelho';
                                else
                                $cor = 'verde';
                                @endphp
                                <div class="col s12 z-depth-2 @php echo $cor @endphp">
                                    <div class="col s12 m5 l12 left-align"> 
                                        <h5 class="blue-grey-text white-text left-align"> Caixa: @php echo number_format($recb - $pagto, 2, ',', '.');@endphp</h5>
                                    </div>    
                                </div>
                            </div>
                        </div>

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
        $('.modal').modal();


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

<script>
    function showNomeReven(NomeReven) {
        Materialize.toast(NomeReven, 2000);
    }
</script>
@endpush

