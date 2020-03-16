@extends('dashboard.templates.app')

@section('content')
    <div class="section">

        <div class="row">
            <div class="card grey lighten-2">
               <div class="card-content black-text">
                  
                       <div class="row">
                           <div class="col s12 m6 l12">
                               <ul class="tabs">
                                   <li class="tab col s6 m6 l6"><a class="active" href="#naovisualizada">Não Visualizadas</a></li>
                                   <li class="tab col s6 m6 l6"><a href="#visualizada">Visualizadas</a></li>
                               </ul>
                           </div> 
                       </div>
                       <div class="row"></div>
                           <div id="naovisualizada" class="col s12">
                            
                               <div class="row">
                                    <div class="col s12 m6 l12">
                                        
                                        <table class="mdl-data-table" id="example" cellspacing="0" width="100%">
                                            <thead>
                                            <tr > 
                                                <th class="black-text"><b>Revendedor</b></th>
                                                <th class="black-text"><b>Mensagem</b></th>
                                                <th class="black-text">Data Envio</th>
                                                <th class="black-text"></th>
                                            </tr>
                
                                            </thead>
                                            <tbody>

                                                @foreach ($msgEnv as $msg)
                                                 
                                                <tr>
                                                    <td >{{ $msg->nomreven }}</td>

                                                    <td ondblclick="showNomeReven('{{$msg->msg}}')">@php if (strlen($msg->msg) <=30) {
                                                        echo $msg->msg;
                                                        } else {
                                                        echo substr($msg->msg, 0, 30) . '...';
                                                        }@endphp</td>
                    
                                                    <td>{{ Carbon\Carbon::parse($msg->datenv)->format('d/m/Y') }} {{Carbon\Carbon::parse($msg->horenv)->format('H:i:s')}}</td>
  
                                                    <td>  
                                                        
                                                            <form class="form-group" id="form-cad-edit" method="post" action="/admin/mensagemrecebida/{{$ideven}}" enctype="multipart/form-data">
                                                                {{csrf_field()}}
                                                                
                                                                    <button class="botao-transmissoes btn modal-trigger btn_grid btn_grid_140px" type="submit" name="action">Conf. Visualização</button>
                                                                    
                                                                    <input type="hidden" value="{{$msg->seqmsg}}" name="seq_msg">
                                                            </form>
                                                        
                                                        
                                                    </td>
                                                </tr>
                                                @endforeach
                                               
                                                </tbody>
                                        </table>
                                    </div>
                               </div>
                           </div>

                           
                           <div id="visualizada" class="col s12">
                               
                            <div class="row">
                                <div class="col s12 m12 l12">
                                    
                                    <table class="mdl-data-table" id="mensagemVisualizada" cellspacing="0" width="100%">
                                        <thead>
                                        <tr > 
                                            <th class="black-text"><b>Revendedor</b></th>
                                            <th class="black-text"><b>Mensagem</b></th>
                                            <th class="black-text">Data Envio</th>
                                        </tr>
            
                                        </thead>
                                        <tbody>
                                            @foreach ($msgVisualizada as $msg)
                                            <tr>
                                                <td >{{ $msg->nomreven }}</td>
                                                <td ondblclick="showNomeReven('{{$msg->msg}}')">@php if (strlen($msg->msg) <=30) {
                                                    echo $msg->msg;
                                                    } else {
                                                    echo substr($msg->msg, 0, 30) . '...';
                                                    }@endphp</td>
                
                                                <td>{{ Carbon\Carbon::parse($msg->datenv)->format('d/m/Y') }} {{Carbon\Carbon::parse($msg->horenv)->format('H:i:s')}}</td>

                                            </tr>
                                            @endforeach

                                            </tbody>
                                      
                                    </table>

                                </div>
                           </div>

                           </div>
                       </div>

                       <div class="row">
                       </div>
               </div>
               
            </div>
    </div>


    @push('scripts')
    <script type="text/javascript" src="{{url('js/jquery.mask.js')}}"></script>

    <script>
    $(document).ready(function() {
        $('.modal').modal();

        var table = $('#example').DataTable( {

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
            "aaSorting": [0, "desc"],


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
                    "sSortDescending": ": Ordenar colunas de forma descendente",
                    "sSortAscending": ": Ordenar colunas de forma ascendente"
                }
            }

        } );

        var table = $('#mensagemVisualizada').DataTable( {

        
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
            "aaSorting": [0, "desc"],


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
                    "sSortDescending": ": Ordenar colunas de forma descendente",
                    "sSortAscending": ": Ordenar colunas de forma ascendente"
                }
            }

            } );

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

@endsection





