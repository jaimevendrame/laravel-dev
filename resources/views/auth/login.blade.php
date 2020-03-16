@extends('layouts.template')

@section('content')
<div class="container">
    <div class="row">
        <div class="col s12 m6 l6  offset-m3  offset-l3">
            <div id="cardLogin" class="card z-depth-4">
                <div class="card-content">
                    <div class="row center-align">
                         {{--   <span class="card-title center-align">Lotec Sistema</span>--}}
                            <img class="responsive-img"  alt="Logo" src="images/logo3.png"width="190" height="190" />
                    </div>
                    {{--<span class="card-title center-align">LOTECWeb</span>--}}
                    <div class="row">
                        <form class="col s12" role="form" method="POST" action="{{ env('URL_ADMIN_LOGIN') }}">
                            {{ csrf_field() }}
                            <div class="row">
                                {{--<div class="row">--}}
                                    {{--<div class="input-field col s12">--}}
                                        {{--<i class="material-icons prefix">perm_identity</i>--}}
                                        {{--<input id="email" type="email" name="email" class="validate {{ $errors->has('email') ? ' has-error' : '' }}">--}}
                                        {{--<label for="email">Email</label>--}}
                                        {{--@if ($errors->has('email'))--}}
                                            {{--<span class="help-block">--}}
                                        {{--<strong>{{ $errors->first('email') }}</strong>--}}
                                    {{--</span>--}}
                                        {{--@endif--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                <div class="row">
                                    <div  class="input-field col s12">
                                        <i class="material-icons prefix">perm_identity</i>
                                        <input  placeholder="Nome de Usuário" id="username" type="text" name="username" class="validate {{ $errors->has('username') ? ' has-error' : '' }}">
                                        {{--<label class="active" for="username">Username</label>--}}
                                        @if ($errors->has('username'))
                                            <span class="help-block">
                                                 {{--   <strong>Usuário não encontrado!</strong>--}}
                                        <strong>{{ $errors->first('username') }}xx</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <i class="material-icons prefix">lock</i>
                                        <input placeholder="Senha" id="password" type="password" name="password" class="validate {{ $errors->has('password') ? ' has-error' : '' }}">
                                        {{--<label for="password">Senha</label>--}}
                                        @if ($errors->has('password'))
                                            <span class="help-block">
                                           {{-- <strong>Informe uma senha correta.</strong>--}}
                                         <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s12 ">
                                        <input type="checkbox" id="remember" {{ old('remember') ? 'checked' : ''}}/>
                                        <label for="remember">Lembre-me?</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s12 center">
                                        <button id="botao" class="btn waves-effect waves-light col s12 z-depth-4" type="submit" name="action">
                                            Entrar
                                        </button>
                                    </div>
                                    </div>
                            </div>
                        </form>
                    </div>
                </div>
                {{--<div class="card-action right-align">--}}
                    {{--<a href="{{ url('/password/reset') }}">Esqueceu a senha?</a>--}}
                {{--</div>--}}
            </div>
        </div>
    </div>
</div>
@endsection
