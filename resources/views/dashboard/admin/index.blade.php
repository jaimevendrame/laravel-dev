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
    <link type="text/css" rel="stylesheet" href="{{ asset('admin/css/admin.css') }}"  media="screen,projection"/>

    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

</head>
<body>


    <div class="section">
        <div class="row">
            <div class="col s12">
                <div class="card">
                    <div class="card-content">
                        @forelse($usuario_lotec as $ul)
                        <form class="form-group" id="form-cad-edit" method="post" action="/admin/resumocaixa" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            <div class="row">
                                <div class="input-field col s6 m2">
                                    <input id="name" name="name" type="text" class="text"
                                           placeholder ="Name" value="{{$ul->logusu}}">
                                </div>

                                <div class="input-field col s6 m2">
                                    <input id="email" name="email" type="text" class="text"
                                           placeholder ="Email" value="{{strtolower(str_replace(" ","",$ul->logusu)).'@lotec.com'}}">
                                </div>

                                <div class="input-field col s6 m2">
                                    <input id="password" name="password" type="text" class="text"
                                           placeholder ="Password" value="{{$ul->senusu}}">
                                </div>

                                <div class="input-field col s6 m2">
                                    <input id="Idusu" name="Idusu" type="text" class="text"
                                           placeholder ="Idusu" value="{{$ul->idusu}}">
                                </div>

                                <button class="btn waves-effect waves-light" type="submit" name="action">Ok
                                </button>

                            </div>
                            @empty
                            @endforelse



                        </form>



                        <div class="row">


                    </div>

                </div>
            </div>
        </div>

    </div>


        <!-- Scripts -->
        <!--Import jQuery before materialize.js-->
        <script type="text/javascript" src="{{ asset('materialize/js/jquery-2.1.1.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('materialize/js/materialize.min.js') }}"></script>
        <script src="{{ asset('materialize/lib/pt_BR.js') }}"></script>
</body>
</html>