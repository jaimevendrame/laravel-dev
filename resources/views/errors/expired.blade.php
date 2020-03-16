@extends('errors.template')

@section('content')

<div class="container">
    <div class="content">
        <div class="title">Acesso Bloqueado.</div>
        {{--<h1>error 403</h1>--}}
        <a class="btn" href="../">tentar novamente.</a>
        <div>
            <a href="{{ env('URL_ADMIN_LOGOUT') }}"
               onclick="event.preventDefault();
               document.getElementById('logout-form').submit();">
                Logout
            </a>
            <form id="logout-form" action="{{ env('URL_ADMIN_LOGOUT') }}" method="POST"
                  style="display: none;">
                {{ csrf_field() }}
            </form>
        </div>

    </div>
</div>

@endsection
