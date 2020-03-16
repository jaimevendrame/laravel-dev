<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    {{--<!-- Styles -->--}}
    <link href="{{ asset('css/estilo.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css" rel="stylesheet">
    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
<div id="app">
    <ul id="dropdown1" class="dropdown-content">
        <li><a href="#!">Editar Perfil</a></li>
        <li><a href="#!">Configurações</a></li>
        <li class="divider"></li>
        <li><a href="{{ env('URL_ADMIN_LOGOUT') }}"
               onclick="event.preventDefault();
               document.getElementById('logout-form').submit();">
               Logout
            </a>
            <form id="logout-form" action="{{ env('URL_ADMIN_LOGOUT') }}" method="POST"
                  style="display: none;">
                {{ csrf_field() }}
            </form>
        </li>
    </ul>
    <nav>
        <div class="nav-wrapper">
            <a href="{{ url('/') }}" class="brand-logo"><i class="material-icons">cloud</i>{{ config('app.name', 'Laravel') }}</a>
            <ul class="right hide-on-med-and-down">
                <!-- Authentication Links -->
                @if (Auth::guest())
                    <li><a href="{{ env('URL_ADMIN_LOGIN') }}">Login</a></li>
                {{--<li><a href="{{ url('/register') }}">Cadastra-se</a></li>--}}
                @else
                <li><a>Usuário: {{ $usuario_lotec->idusu .' - '. $usuario_lotec->nomusu}}</a></li>
                <li><a href="#!" data-activates="slide-out" class="botao"><i class="material-icons">menu</i></a></li>
                <!-- Dropdown Trigger -->
                <li><a class="dropdown-button" href="#!" data-activates="dropdown1">{{ Auth::user()->name }}<i class="material-icons right">arrow_drop_down</i></a></li>
                @endif
            </ul>
        </div>
    </nav>

    <ul id="slide-out" class="side-nav">
        <li><div class="userView">
                <div class="background">
                    <img src="{{ asset ('assets/img/office.jpg') }}">
                </div>
                <a href="#!user"><img class="circle" src="{{ asset ('assets/img/user_default.jpg') }}"></a>
                @if (Auth::guest())
                    <a href="#!name"><span class="white-text name">NONONONO</span></a>
                    <a href="#!email"><span class="white-text email">NONONO}</span></a>
                @else
                    <a href="#!name"><span class="white-text name">{{ Auth::user()->name }}</span></a>
                    <a href="#!email"><span class="white-text email">{{ Auth::user()->email }}</span></a>
                @endif

            </div></li>
        <li><a href="#!"><i class="material-icons">cloud</i>Menu</a></li>
        <li><a class="dropdown-button waves-effect" href="#!" data-activates="mais-usados">Mais usados</a></li>
        <li><div class="divider"></div></li>
        <li><a class="dropdown-button waves-effect" href="#!" data-activates="revendedor">Revendedor</a></li>
        <li><div class="divider"></div></li>
        <li><a class="dropdown-button waves-effect" href="#!" data-activates="apostas">Apostas</a></li>
        <li><div class="divider"></div></li>
        <li><a class="dropdown-button waves-effect" href="#!" data-activates="descargas">Descargas</a></li>
        <li><div class="divider"></div></li>
        <li><a class="dropdown-button waves-effect" href="#!" data-activates="cobrador">Cobrador</a></li>
        <li><div class="divider"></div></li>
        <li><a class="dropdown-button waves-effect" href="#!" data-activates="caixa">Caixa</a></li>
        <li><div class="divider"></div></li>
        <li><a class="dropdown-button waves-effect" href="#!" data-activates="mensagens">Mensagens</a></li>
        <li><div class="divider"></div></li>
        <li><a class="dropdown-button waves-effect" href="#!" data-activates="consultas">Consultas</a></li>
        <li><div class="divider"></div></li>
        <li><a class="dropdown-button waves-effect" href="#!" data-activates="suporte">Suporte Técnico</a></li>
        <li><div class="divider"></div></li>



    </ul>
    <!--//SideBar - Menus
        dropdown: mais usados -->

    <ul id="mais-usados" class="dropdown-content">
        <li><a href="#!">Resumo Geral por Revendedor</a></li>
        <li><a href="#!">Histórico de Venda</a></li>
        <li><a href="#!">Transmissões de Apostas</a></li>
        <li><a href="#!">Apostas Premiadas</a></li>
        <li><a href="#!">Caixa</a></li>
        <li><a href="#!">Senha para Movimentar Caixa</a></li>
        <li><a href="#!">Revendedor - Cadastro</a></li>
        <li><a href="#!">Terminal</a></li>
        <li><a href="#!">Descargas Enviadas</a></li>
        <li class="divider"></li>

    </ul>
    <!--//SideBar - Menus
    dropdown: Revendedor -->

    <ul id="revendedor" class="dropdown-content">
        <li><a href="#!">Revendedor - Cadastro</a></li>
        <li><a href="#!">Terminal</a></li>
        <li><a href="#!">Terminal - Gerar Senha de Incialização</a></li>
        <li><a href="#!">Revendedor - Limite de Crédito</a></li>
        <li><a href="#!">Revendedor X Modalidade de Aposta</a></li>
        <li><a href="#!">Revendedor X Horários de Aposta</a></li>
        <li><a href="#!">Revendedor X (Comissão) e (Cotação) Modalidade de Aposta</a></li>
        <li class="divider"></li>

    </ul>

    <!--//SideBar - Menus
dropdown: Apostas -->

    <ul id="apostas" class="dropdown-content">
        <li><a href="#!">Lançar Aposta</a></li>
        <li><a href="#!">Cancelar Aposta</a></li>
        <li><a href="#!">Repetir Aposta</a></li>
        <li><a href="#!">Visualizar Aposta</a></li>
        <li class="divider"></li>

    </ul>

    <!--//SideBar - Menus
dropdown: Descargas -->

    <ul id="descargas" class="dropdown-content">
        <li><a href="#!">Descargas Enviadas</a></li>
        <li><a href="#!">Descargas Recebidas</a></li>
        <li><a href="#!">Descargas Premiadas</a></li>
        <li><a href="#!">Vendedor <> Descargas</a></li>
        <li><a href="#!">Modalidade x Limites de Descarga</a></li>
        <li><a href="#!">Fechamento Descargas Recebidas</a></li>
        <li class="divider"></li>

    </ul>
    <!--//SideBar - Menus
dropdown: Cobrador -->

    <ul id="cobrador" class="dropdown-content">
        <li><a href="#!">Cadastro</a></li>
        <li><a href="#!">Linha</a></li>
        <li><a href="#!">Senha para Movimentar Caixa</a></li>
        <li><a href="#!">Comissão Por Linha</a></li>
        <li><a href="#!">Comissão Por Coleta</a></li>
        <li class="divider"></li>
    </ul>

    <!--//SideBar - Menus
dropdown: Caixa -->

    <ul id="caixa" class="dropdown-content">
        <li><a href="#!">Caixa</a></li>
        <li><a href="#!">Movimentos por Cobradores</a></li>
        <li><a href="#!">Despesas - Lançar/Consultar</a></li>
        <li class="divider"></li>
    </ul>

    <!--//SideBar - Menus
dropdown: consultas -->

    <ul id="consultas" class="dropdown-content">
        <li><a href="#!">Resumo Geral por Revendedor</a></li>
        <li><a href="#!">Historico de Venda</a></li>
        <li><a href="#!">Aposta Premiadas</a></li>
        <li><a href="#!">Transmissões de Apostas com Atraso</a></li>
        <li><a href="#!">Resultado de Sorteio</a></li>
        <li><a href="#!">Log de Dados</a></li>
        <li><a href="#!">Terminais com Atraso no Horário</a></li>
        <li class="divider"></li>
    </ul>

    <div class="col s-12">
    @yield('content')
</div>

</div>


<!-- Scripts -->
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/js/materialize.min.js"></script>--}}

<! -  Material JavaScripts  ->
<script src = " {{asset ('js/bin/materialize.js')}} " > </script >
{{--<script type="text/javascript" src="{{url('assets/js/bootstrap.min.js')}}"></script>--}}


<script>
    $(document).ready(function(){
        $('.botao').sideNav();
        $('.dropdown-button').dropdown();
    });

</script>

</body>
</html>
