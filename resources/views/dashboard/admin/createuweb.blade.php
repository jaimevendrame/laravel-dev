@extends('dashboard.templates.app')

@section('content')
    <script src="{{url('assets/js/jquery-3.2.0.min.js')}}"></script>
    <div class="section">
        <div class="row">
            <div class="col s12">
                <div class="card">
                    <div class="card-content">
                        @if( isset($errors) && count ($errors) > 0)
                            <div class="alert alert-danger">
                                @foreach( $errors->all() as $errors )
                                    {{$errors}} <br>
                                @endforeach
                            </div>
                        @endif
                        @if(!empty($usuarioWeb))
                        <form id="form-cad-edit" method="post" action="/admin/manager/web/update/{{$usuarioWeb->id}}" enctype="multipart/form-data" autocomplete="off">
                            <input type="hidden" value="{{$usuarioWeb->id}}" name="id">
                        @else
                                <form id="form-cad-edit" method="post" action="/admin/manager/web-go/" enctype="multipart/form-data" autocomplete="off">

                                @endif
                                    {{ csrf_field() }}
                            <div class="row">
                                <div class="input-field col s6">
                                    <input  id="nomusu" type="text" class="validate" value="{{$data->nomusu}}" name="name" readonly>
                                    <label for="nomusu">Nome</label>
                                </div>
                                <div class="input-field col s6">
                                    <input placeholder="Email" id="email" type="email" class="validate"
                                           @if( !empty($usuarioWeb))  value="{{$usuarioWeb->email}}"
                                           @else
                                           value="{{$data->emausu}}"   @endif name="email">

                                    <label for="email">Email</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s6">
                                    <input id="login" type="text" readonly class="validate" value="{{$data->logusu }}" name="logusu">
                                    <label for="login">Login</label>
                                </div>
                                <div class="input-field col s6">
                                    <input id="password" type="password" readonly class="validate" value="{{$data->senusu }}" name="password">
                                    <label for="password">Senha</label>
                                </div>

                            </div>
                            <div class="row">
                                <div class="input-field col s6">
                                    <select name="role">
                                        <option value="client" @if( !empty($usuarioWeb)) {{$usuarioWeb->role == 'client'? 'selected':''}} @else @endif >NÃO</option>
                                        <option value="admin" @if( !empty($usuarioWeb)) {{$usuarioWeb->role == 'admin'? 'selected':''}} @else @endif>SIM</option>
                                    </select>
                                    <label>Autorizar acesso Web</label>
                                </div>
                                <input type="hidden" value="{{$data->idusu}}" name="idusu">

                            </div>
                            <div class="row">
                            </div>
                            <div class="row">
                                <div class="input-field col s12">
                                    <button class="btn waves-effect waves-light" type="submit" name="action">Cadastrar
                                        <i class="material-icons right">send</i>
                                    </button>
                                </div>
                            </div>
                        </form>
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



