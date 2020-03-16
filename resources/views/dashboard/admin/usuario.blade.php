@extends('dashboard.templates.app')

@section('content')
    <script src="{{url('assets/js/jquery-3.2.0.min.js')}}"></script>
    <div class="section">
        <div class="row">
            <div class="col s12">
                <div class="card">
                    <div class="card-content">
                        <div class="row">


                            <table class="mdl-data-table" id="example">
                                <thead>
                                <tr>
                                    <th colspan="6">Dados dos usuários Desktop</th>
                                </tr>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Login</th>
                                    <th>Senha</th>
                                    <th>Email</th>
                                    <th>Ações</th>
                                    <th>Ações</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!empty($data))
                                @forelse($data as $u)
                                    <tr>
                                        <td>{{$u->idusu}}</td>
                                        <td>{{$u->nomusu}}</td>
                                        <td>{{$u->logusu}}</td>
                                        <td>{{$u->senusu}}</td>
                                        <td>{{$u->emausu}}</td>
                                        <td><a href="/admin/manager/web/create/{{$u->idusu}}" class="btn"><i class="material-icons">web</i></a></td>
                                        <td><a href="/admin/manager/web-go/{{$u->idusu}}" class="btn"><i class="material-icons">web</i></a></td>
                                    </tr>
                                    @empty
                                <p>Nenhum registro!</p>
                                    @endforelse
                                    @endif
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Login</th>
                                    <th>Senha</th>
                                    <th>Email</th>
                                    <th>Ações</th>
                                    <th>Ações</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>


                    </div>

                </div>
            </div>
            <div class="row">


            </div>
        </div>

    </div>

@endsection

@push('scripts')

<script>
    $(document).ready(function() {

        var table = $('#example').DataTable( {

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
                }
            ],


            scrollY: 480,
            scrollX:        true,
            scrollCollapse: true,
            paging:        false,
            Bfilter:        false,
            "aaSorting": [[0, "asc"]],


            columnDefs: [
                {
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



