@extends('dashboard.templates.app')

@section('content')
<div class="section">
        <div class="row">
            <div class="col s12">
                
                    @if( Session::has('success'))
                        <div class="row hide-msg">
                            <div class="col s12 m12">
                                <div class="card-panel green hide-msg">
                                    <ul>
                                        <h4><i class="icon fa fa-warning"></i> {{Session::get('success')}}</h4>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                        <div class="row ">
                                <div class="input-field col s12 m8">
                                    <a href="/admin/terminal/create/{{$ideven}}/add" class="btn">NOVO</a>
                                </div>

                                <form class="form-group" id="form-filtro-terminal" method="post" action="/admin/terminal/{{$ideven}}" enctype="multipart/form-data">
                                    {{ csrf_field() }}

                                   @php
                                      if(session()->has('sitTer')){ 
                                         $idsit = session('sitTer');
                                      }
                                   @endphp
        
                                    <div class="input-field col s12 m2 right-align">
                                        <select  name="sel_situacao">
                                            {{--<option value="4" disabled selected> </option>--}}
                                            <option value="0" @if(!empty($idsit)) {{ $idsit == 0  ? 'selected' : '' }}  @endif>Ativo</option>
                                            <option value="1" @if(!empty($idsit)) {{ $idsit == 1  ? 'selected' : '' }}  @endif>Inativo</option>
                                            <option value="2" @if(!empty($idsit)) {{ $idsit == 2  ? 'selected' : '' }}  @endif>Todos</option>
                                        </select>
                                        <label>Situação</label>
                                    </div>
  
                                    <div class="input-field col s12 m2 right-align">
                                        <button class="btn waves-effect waves-light" type="submit" name="action">Mostrar
                                            <i class="material-icons right">send</i>
                                        </button>
                                    </div>
                                
                                </form>  
                        </div>  
                    
                        @if(!empty($data))
                            <table class="mdl-data-table " id="example"  cellspacing="0" width="100%">
                                <thead><tr>
                                    <th style="padding-right: 1px; width:20px;">ID TERMINAL</th>
                                    <th>REVENDEDOR</th>
                                    <th>MODELO</th>
                                    <th>VERSÃO</th>
                                    <th>MODO OPE</th>
                                    <th>ÚLTIMO ATU</th>
                                    <th>SITUAÇÃO</th>
                                    <th>CIDADE</th>
                                    <th>UF</th> 
                                    <th>SERIAL CHIP</th>
                                </tr></thead>
                                <tbody>
                                @forelse($data as $d)
                                    <tr>  
                                        @php
                                             $dataAtu = date('d/m/Y', strtotime($d->datatuter));
                                             $horaAtu = date('H:i:s', strtotime($d->horatuter));
                                             $datHor = $dataAtu.' - '.$horaAtu;

                                             if($dataAtu == '31/12/1969'){
                                               $datHor = '';  
                                             }
                                        @endphp                               
                                        <td><a class="botao-transmissoes btn modal-trigger btn_grid btn_grid_130px" href="/admin/terminal/update/{{$ideven}}/{{$d->idter}}">{{ $d->ideter}}</a></td>
                                        <td>{{ $d->nomreven }}</td>
                                        <td>{{ $d->modelo }}</td>
                                        <td>{{ $d->versis }}</td>
                                        <td>{{ $d->modope }}</td>
                                        <td>{{ $datHor }}</td>
                                        <td  @if($d->sitter == 'INATIVO') class='red-text text-darken-2' @endif>{{ $d->sitter }}</span></td> 
                                        <td>{{ $d->cidreven }}</td>
                                        <td>{{ $d->sigufs }}</td>     
                                        <td>{{ $d->serialchip }}</td>                                                                                                            
                                    </tr>
                                @empty
                                    <tr>
                                        <p>nenhum registro encontrado!</p>
                                    </tr>
                                @endforelse
                                <tfoot>
                               
                                </tfoot>
                            </table>
                            @else
                            <p>Nenhum registro encontrado!</p>
                        @endif
                    
                </div>
            </div>
        </div>
</div>
   
@endsection


@push('scripts')

<script>
    $(document).ready(function() {
        $('.modal').modal();


        $('ul.tabs').tabs();

        var table = $('#example').DataTable( {


            dom: 'Bfrtip',
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


            scrollY: 310,
            scrollX:        true,
            scrollCollapse: true,
            paging:         false,
            Bfilter:        true,
            "aaSorting": [[1, "asc"]],


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
    $(function () {
        setTimeout("$('.hide-msg').fadeOut();", 5000)
    })
</script>
@endpush

