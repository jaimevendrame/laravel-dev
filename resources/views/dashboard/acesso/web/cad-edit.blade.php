@extends('dashboard.templates.app')

@section('content')

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

                      {{--{{$usuarioWeb}}--}}

                    @if($usuarioWeb != Null)
                        <form id="form-cad-edit" method="post" action="/admin/acesso/web/update/{{$usuarioWeb->id}}" enctype="multipart/form-data" autocomplete="off">
                            <input type="hidden" value="{{$usuarioWeb}}" name="id">
                            @else
                                <form id="form-cad-edit" method="post" action="/admin/acesso/web/create/data/{{$data->idusu}}" enctype="multipart/form-data" autocomplete="off">

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
                                            <input placeholder="Username" id="login" type="text" class="validate"
                                                   @if( !empty($usuarioWeb))  value="{{$usuarioWeb->username}}"
                                                   @else
                                                   value="{{$data->logusu}}"   @endif name="username">
                                            <label for="login">USERNAME</label>
                                        </div>
                                        <div class="input-field col s6">
                                            <input id="password" type="text" readonly class="validate" value="{{$data->senusu }}" name="password">
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
