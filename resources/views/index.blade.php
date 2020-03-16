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
    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
<div id="app">


    <div class="container">
        <h1>{{ config('app.name', 'Laravel') }}</h1>
    </div>
    echo “<?php phpinfo(); ?>”

</div>


<!-- Scripts -->
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/js/materialize.min.js"></script>--}}

<! -  Material JavaScripts  ->
<script src = " {{asset ('js/bin/materialize.js')}} " > </script >
{{--<script type="text/javascript" src="{{url('assets/js/bootstrap.min.js')}}"></script>--}}


</body>
</html>
