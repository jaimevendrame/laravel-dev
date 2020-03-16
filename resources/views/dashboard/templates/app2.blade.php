<!DOCTYPE html>
<html lang="pt-br" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!--Import Google Icon Font-->
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="{{ asset('materialize/css/materialize.css') }}" media="screen,projection" />


    <link type="text/css" rel="stylesheet" href="{{ asset('admin/css/admin.css') }}" media="screen,projection" />
    <link type="text/css" rel="stylesheet" href="{{ asset('admin/css/estilo.css') }}" media="screen,projection" />

    <link type="text/css" rel="stylesheet" href="{{ asset('admin/css/material.css') }}" media="screen,projection" />
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/material-design-lite/1.1.0/material.min.css" rel="stylesheet">  foi substituido pela de cima-->

    <link href="https://cdn.datatables.net/1.10.15/css/dataTables.material.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/1.3.1/css/buttons.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/select/1.2.2/css/select.dataTables.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

    <!--<link rel='stylesheet'  href='https://cdn.datatables.net/v/dt/dt-1.10.12/se-1.2.0/datatables.min.css' type='text/css' media='all' />-->
    <link rel='stylesheet' href='https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.9/css/dataTables.checkboxes.css' type='text/css' media='all' />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <script type='text/javascript' src='//ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js'></script>



    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>


    <script>
            window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
            ]); ?>
    
    
            document.addEventListener("DOMContentLoaded", function() {
                $('.preloader-background').delay(1700).fadeOut('slow');
    
                $('.preloader-wrapper')
                    .delay(1700)
                    .fadeOut();
            });
        </script>

    <style>
            #conteudo{
	display:none;
}
.center{ 
	position:absolute;
	top: 50%;
	left: 50%;
	margin-left: -75px; /*centralizando a imagem*/
	margin-top: -35px; /*centralizando a imagem*/
}
    </style>

</head>

<body>

    <div id="app">

    <main>

        <body>
            <div>
               
                @yield('content')
            </div>
        </body>


    </main>

    {{--<!-- Scripts -->
    <!--Import jQuery before materialize.js-->--}}
    <script type="text/javascript" src="{{ asset('materialize/js/jquery-2.1.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('materialize/js/materialize.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('materialize/js/date.format.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>

    <script src="{{ asset('materialize/lib/pt_BR.js') }}"></script>
    <script type="application/javascript">
        $(document).ready(function() {



            // Show sideNav


            $('.dropdown-button').dropdown({
                inDuration: 300,
                outDuration: 225,
                constrainWidth: false, // Does not change width of dropdown to that of the activator
                hover: true, // Activate on hover
                gutter: 0, // Spacing from edge
                belowOrigin: false, // Displays dropdown below the button
                alignment: 'left', // Displays dropdown with edge aligned to the left of button
                stopPropagation: false, // Stops event propagation
            });


            $('select').material_select();


        });

        $('.datepicker').pickadate({
            labelMonthSelect: 'Selecione um mês',
            labelYearSelect: 'Selecione um ano',
            selectMonths: true, // Creates a dropdown to control month
            selectYears: 15, // Creates a dropdown of 15 years to control year

            onSet: function( arg ){
                if ( 'select' in arg ){ //prevent closing on selecting month/year
                    this.close();
                }
            }

        });

        @if(empty($data[0] -> dataini))
        $("#datIni").val('{{date("d/m/Y")}}');
        $("#datFim").val('{{date("d/m/Y")}}');


        @else
        $("#datIni").val('{{Carbon\Carbon::parse($data[0]->dataini)->format('d/m/Y')}}');
        $("#datFim").val('{{Carbon\Carbon::parse($data[0]->datafim)->format('d/m/Y')}}');
        @endif
    </script>

    @stack('modal')

   {{-- <script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>--}}
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.2.2/js/dataTables.fixedColumns.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.15/api/sum().js"></script>

    

    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.flash.min.js"></script>
    <script type="text/javascript" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.print.min.js"></script>

{{--<!-- Scripts -->--}}
    @stack('scripts')

    {{-- Corrige Bug datepiker --}}
    <script language="javascript">
        $('.datepicker').on('mousedown',function(event){
         event.preventDefault();
        })

    </script>

   <script language="javascript">
        $(document).ready(function() {
            $(".button-collapse").sideNav();
           $('.collapsible').collapsible('open', 0);

        });
    </script>
  {{--  @stack('jivosite')--}}
</body>
</html>
