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
    <link type="text/css" rel="stylesheet" href="{{ asset('materialize/css/materialize.min.css') }}"  media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="{{ asset('materialize/css/material.css') }}"  media="screen,projection"/>

    <link type="text/css" rel="stylesheet" href="{{ asset('admin/css/admin.css') }}"  media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="{{ asset('modallink/jquery.modalLink.css') }}"  media="screen,projection"/>
    <!--<link href="https://cdnjs.cloudflare.com/ajax/libs/material-design-lite/1.1.0/material.min.css" rel="stylesheet">-->
    <link href="https://cdn.datatables.net/1.10.15/css/dataTables.material.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/1.3.1/css/buttons.dataTables.min.css" rel="stylesheet">



    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
    <style>
        header, main, footer {
            padding-left: 300px;
        }
        @media only screen and (max-width : 992px) {
            header, main, footer {
                padding-left: 0;
            }
        }
        main {
            margin: 0px 10px 0px 10px;
        }
    </style>
</head>


<body>
        <div class="card-content">
                @forelse($dataAlterar as $movimento)
                    <div class="row">
                            <h5>Alterar o Valor do Movimento</h5>

                            <h2>Valor Atual</h2>
                            {{ number_format($movimento->vlrmov, 2, ',', '.') }}
                    </div>

                    @empty
           
                    @endforelse
            

                    
                
            </div>

</body>





</html>