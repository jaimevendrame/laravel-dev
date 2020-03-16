@extends('dashboard.templates.app')

@section('content')
    <div class="section">

        @foreach($tipoSenha as $tipo)
    
       <input type="hidden" name="tiposenha" id="tiposenha" value="{{$tipo->tipo_senha_cobrador}}">
       @endforeach

        <div class="row" id="div_geral">
            <div class="col s12 m8 l6">
                <div class="card">

                    <div class="card-content grey darken-3">
                        <span class="card-title center white-text"><b>Movimentar Caixa:</b></span>
                        <div class="card-action">
                            @if(!empty($data))
                                @forelse($data as $s)
                                        <h1 class="center-align white-text"><b>{{$s->baixa_caixa}}</b></h1>
                                    @empty
                                @endforelse
                            @else
                                <p class="white-text">Usuário Administrador</p>
                            @endif
                        </div>

                    </div>


                </div>
            </div>

        </div>



        <div class="row" id="div_individual">
            <div class="col m12 s12 l6">
                <div class="card  grey lighten-2" >
                    <div class="card-content  ">
                        <span class="card-title center black-text"><b>Senha Cobrador</b></span>
                       
                        <div class="row">
                            <div col s12 l12>
                                @if(!empty($individual))

                                <table class="bordered striped highlight  black-text" id="example" cellspacing="0" width="100%">
                                    <thead>
                                    <tr style="font-size: 15px;color:black;">
                                       
                                        <th>Identificação</th>
                                        <th>Nome do Cobrador</th>
                                        <th>Senha do dia</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    {{--//#fffde7--}}
    
                                    @forelse($individual as $senha)
                                        <tr>
                                            
                                            <td>{{$senha->idecobra}}</td>
                                            <td>{{$senha->nomcobra}}</td>
                                            <td style="font-size: 20px;color:red;">{{$senha->baixa_caixa}}</td>
                                        </tr>
    
                                    @empty
                                        <tr>
                                            Nenhum registro encontrado!
                                        </tr>
                                    @endforelse
                                    </tbody>
                                    
                                </table>
                                
                            @else
                            <p>Nenhum registro encontrado!</p>
                            @endif
                            </div>

                        </div>
                       
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script type="text/javascript" src="{{url('js/jquery.mask.js')}}"></script>
    <script>
        $(function() {
           
            var tipoSenha = document.getElementById('tiposenha').value;
            var visibilidade = true;
           
            if (tipoSenha == "GERAL"){
                visibilidade = false;
                document.getElementById("div_geral").style.display = "block";
                document.getElementById("div_individual").style.display = "none";
            
            }else{
                visibilidade = true;
                document.getElementById("div_geral").style.display = "none";
                document.getElementById("div_individual").style.display = "block";
            }

        });
        
    </script>

@endsection





