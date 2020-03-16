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

    <!-- Scripts -->
    <script>
        function test(el) {
            var $lnk = document.getElementById("lnk-ideven");
            $lnk.href = $lnk.href.replace(/(.*)/, '/admin/resumocaixa/') + el.value;

            var $lnkcaixa = document.getElementById("lnk");
            $lnkcaixa.href = $lnk.href.replace(/(.*)/, '/admin/movimentoscaixa/') + el.value;


        }
    </script>

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
        header,
        main,
        footer {
            padding-left: 222px;
        }
        @media only screen and (max-width : 992px) {

            header,
            main,
            footer {
                padding-left: 0;
            }
        }
        main {
            background: #D6D6D6;

            margin: 0px 10px 0px 10px;
        }

        .modal {
            width: 100% !important;
            height: 95% !important;
        }
        li:hover{
            background: #E31007;
         
            }
            .botao-transmissoes{
                background: #30415d
            }
            .modal-pequeno{
                left: 0;
                right: 0;
               
                padding: 0;
                width: 50% !important;
                height: 55% !important;
                will-change: top, opacity;
            }




            .cards-list {
                    z-index: 0;
                    width: 100%;
                    display: flex;
                    justify-content: space-around;
                    flex-wrap: wrap;
}

                .card {
                // margin: 30px auto;
                //  width: 300px;
                //  height: 300px;
                border-radius: 25px;
                -4px -2px 14px 2px rgba(138,135,138,1);
                cursor: pointer;
                transition: 0.4s;
                }

                .card .card_image {
                width: inherit;
                height: inherit;
                border-radius: 30px;
                }

                .card .card_image img {
                width: inherit;
                height: inherit;
                border-radius: 20px;
                object-fit: cover;
                }

                .card .card_title {
                text-align: center;
                border-radius: 0px 0px 30px 30px;
                font-family: sans-serif;
                font-weight: bold;
                font-size: 30px;
                margin-top: -80px;
                height: 40px;
                }

                .card:hover {
                transform: scale(1, 1);
                box-shadow: -4px -2px 14px 2px rgba(138,135,138,1);, 
                -4px -2px 44px 2px rgba(138,135,138,1);
                }

                .title-white {
                color: white;
                }

                .title-black {
                color: black;
                }

                .cardMsg{background-color: #fff; 
                        background-image: 
                        linear-gradient(90deg, transparent 40px, #fcc 40px, #fcc 42px, transparent 42px),
                        linear-gradient(#fee .1em, transparent .2em);

                    background-size: 100% 28px

                }



@media all and (max-width: 300px) {
  .card-list {
    /* On small screens, we are no longer using row direction but column */
    flex-direction: column;
  }
}



                
    </style>
        <style type="text/css">
            #h3{
                text-shadow: -4px 0px 0px rgba(183,183,183,0.95);
            }
        </style>


        <style type="text/css">
                #box{
                    /*definimos a largura do box
                    width:800px;*/
                    /* definimos a altura do box */
                    height:60px;
                    /* definimos a cor de fundo do box */
                    background-color:#666;
                    /* definimos o quão arredondado irá ficar nosso box */
                    border-radius: 20px;
                    padding-top:14px;
                    }
        </style>

        <style type="text/css">

            #logo:hover { background-color:transparent;}
           
        </style>

        <style type="text/css">

            #cardMsg {  background-color: #fff; 
                        background-image: 
                        linear-gradient(90deg, transparent 79px, #fcc 79px, #fcc 81px, transparent 81px),
                        linear-gradient(#fee .1em, transparent .1em);

                        background-size: 100% 1.2em}

           

        </style>

        


</head>
<div class="preloader-background">
    <div class="preloader-wrapper big active">
        <div class="spinner-layer spinner-blue-only">
            <div class="circle-clipper left">
                <div class="circle"></div>
            </div>
            <div class="gap-patch">
                <div class="circle"></div>
            </div>
            <div class="circle-clipper right">
                <div class="circle"></div>
            </div>
        </div>
    </div>
</div>
<body>

    <div id="app">

        <ul id="dropdown1" class="dropdown-content">

            <li class="divider"></li>
            @if (!Auth::guest())
            <li><a href="#!">{{ Auth::user()->email }}</a></li>
            @endif

        </ul>
        <header>
        
        <div class="navbar-fixed">
            <nav class=" grey darken-1">
                <div class="nav-wrapper">
                    <div class="row">
                        <div class="col s2 hide-on-large-only">
                            <ul>
                                <li>
                                    <a href="#" data-activates="slide-out" class="button-collapse">
                                        <i class="material-icons">menu</i></a>
                                </li>
                            </ul>
                        </div>
                        

                        <div class="col s7 m3 l4">

                            <a href="#!" class="breadcrumb">
                                @if( isset($title) )
                                {{$title}}
                                @endif</a>
                
                        </div>
        

                        <div class="col s3 m3 l6">

                            <div class="navbar-header">
                                <ul class="right">
                                  
                                    <li><a href="{{ env('URL_ADMIN_LOGOUT') }}" onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();" class="tooltipped" data-position="left" data-delay="50" data-tooltip="Logout">
                                            <i class="large material-icons">input</i>
                                        </a>
                                        <form id="logout-form" action="{{ env('URL_ADMIN_LOGOUT') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    
            <ul id="slide-out" class="side-nav fixed   grey darken-4">
                
                <li id="logo">
                    <div class="userView" >
                        <div align="center">
                            <a class="brand-logo"  href="{{ url('/home') }}">
                                <img class="responsive-img circle"  alt="Logo" src="/admin/images/logoHome.jpeg"  />
                            </a>
                        </div>
                        
                            {{--  Authentication Links --}}
                        @if (Auth::guest())
                            <a href="#!name"><span class="white-text name">Anonimo</span></a>li>
                            @else{{--  menu lateral--}}
                           
                            <div class="row">
                            
                                   <br>

                                            <form id="form-cad-edit" method="post" action="/admin/home/data" enctype="multipart/form-data">
                                                        {{ csrf_field() }}
        
                                                            <select class="browser-default grey darken-2" onchange="test(this)" name="select_ideven">
                                                                <option value="" disabled="disabledls">Selecione uma Região</option>
                                                                @if ($vendedores->count())
                                                                    @foreach($vendedores as $vendedor)
                                                                        <option value="{{ $vendedor->ideven  }}" {{ $vendedor->ideven == $ideven_default  ? 'selected' : '' }}>
                                                                            Vendedor: {{--{{ $vendedor->idven  }} --}}- {{ $vendedor->nomven }} {{--Cidade: {{ $vendedor->cidbas }} --}}
                        
                                                                        </option>
                        
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                            <div class="input-field align:center">
                                                                    <button class="btn btn-block ">Selecionar</button>
                                                            </div>
                                                            
                                            </form>
                                

                            </div>            

                        @endif

                    </div>
                </li>
                <li>
                  
                    <hr>
                </li>

                </li>
                <ul class="collapsible" data-collapsible="accordion">
                    @php $m = 0;
                    foreach ($vendedores as $item){
                    if ($item->inpadrao == 'SIM'){
                    $m = $item->ideven;

                    }
                    }
                    if (session()->has('ideven')){
                    $m = session()->get('ideven');
                    }



                    @endphp

                    @php
                    echo '<li>';
                        echo '<div class="collapsible-header active white-text"><i class="material-icons">queue</i>MAIS USADOS</div>';
                        echo '<div class="collapsible-body black rgba-white-slight ">
                            <span>
                                <ul>';

                                    foreach ($menuMaisUsu as $menu){

                                    echo '<li><a class="waves-effect white-text waves-red " href="'.$menu->route.'/'.$ideven_default.'" id="'.$menu->idref.'" data-position="right" data-delay="50">'.$menu->capact.'</a></li>';

                                    }
                                               

                                    echo '
                                </ul>
                            </span>
                        </div>';

                        echo '</li>';

                    foreach($categorias as $key => $value){
                      //  echo $value;
                     $ico = $value;

                     switch ($ico) {
                         case 'REVENDEDOR':
                             $icone = 'people_outline';
                             break;
                         case 'APOSTAS':
                             $icone = 'format_list_numbered';
                             break;
                         case 'DESCARGA':
                             $icone = 'fast_forward';
                             break;
                         case 'COBRADOR':
                             $icone = 'assignment_ind';
                             break;
                         case 'CAIXA':
                             $icone = 'monetization_on';
                             break;
                         case 'CONSULTA':
                             $icone = 'search';
                             break;
                         case 'MENSAGENS':
                             $icone = 'mail_outline';
                             break;
                         default:
                            $icone = 'menu';
                             break;
                     }
                     
               
                    echo '<li>';
                       
                        echo '<div class="collapsible-header active white-text"><i class="material-icons">'.$icone.'</i>'.$value.'</div>';
                        echo '<div class="collapsible-body black">
                            <span>
                                <ul>';
                                    foreach ($menus as $menu){
                                    if ($menu->catact == $value){
                                    echo '<li><a class="waves-effect white-text waves-light" href="'.$menu->route.'/'.$ideven_default.'" id="'.$menu->idref.'" data-position="right" data-delay="50">'.$menu->capact.'</a></li>';
                                    }}

                                    echo '
                                </ul>
                            </span>
                        </div>';

                        echo '</li>';
     

                    }
                    @endphp
                        
                </ul>
           
    

    </ul>


    </header>


    <main>

        <body>
            <div>
                {{--//{{$ideven_default}}//--> --}}
                @yield('content')
            </div>
        </body>


    </main>


  {{-- <!-- Modal Structure -->--}}
    <div id="modal1" class="modal">
        <div class="modal-content">
            <h4>Modal Header</h4>
            <p>A bunch of text</p>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Agree</a>
        </div>
    </div>
   {{-- <!--fim modal padrão-->
    <!-- Page Layout here -->--}}

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
