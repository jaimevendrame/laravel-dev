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
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>

    <style>
        #botao{
            background: 	#B22222;

    </style>
   
    <style>
        #cardLogin{
           // -webkit-box-shadow: -4px 0px 17px 3px #000000; 
         //   box-shadow: -4px 0px 17px 3px #000000;
           // background-color: rgba(0,0,0,.5);
           background-color: rgba(0,0,0,.05);
            
        }
        ::placeholder {
            color: black;
            
            }
    </style>

</head>
<body>
<div id="app">

    @yield('content')

</div>


{{--<!-- Scripts -->--}}
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/js/materialize.min.js"></script>--}}

{{--<! -  Material JavaScripts  ->--}}
<script src = " {{asset ('js/bin/materialize.js')}} " > </script >
{{--<script type="text/javascript" src="{{url('assets/js/bootstrap.min.js')}}"></script>--}}
<script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#example').DataTable( {
            "scrollY": 200,
            "scrollX": true
        } );
    } );

</script>

@stack('jivosite')


</body>
</html>
