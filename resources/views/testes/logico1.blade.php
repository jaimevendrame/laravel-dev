<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>PRONAP</title>
    <!--CSS Personalizado-->
    <link rel="stylesheet" type="text/css" href="{{url('assets/css/pronap.css')}}">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="{{url('assets/css/bootstrap.min.css')}}">
    <!-- Optional theme -->
    <link rel="stylesheet" href="{{url('assets/font-awesome/css/font-awesome.min.css')}}">
    <!-- Optional theme -->
    <link rel="stylesheet" href="{{url('assets/css/bootstrap-theme.min.css')}}">
    <!-- Chamadas JS -->
    <!--jQuery-->
    <script src="{{url('assets/js/jquery-3.2.0.min.js')}}"></script>
    <script src="{{url('assets/js/bootstrap.min.js')}}"></script>

</head>
<body>
<div class="container col-md-12">
    <div class="col-md-10 text-center">

        @forelse($alunos as $aluno)
            <h1>Olá, {{$alunos->name}}</h1>
            <h2>Vamos ao teste lógico agora...</h2>
            @if($alunos->inteste !='false'){

             }

        @empty
            <div class="errors-msg alert alert-danger" style="display:none;">

                <h2>Desculpe, mas o número: {{$pesquisa}}, não está cadastrado.</h2>

            </div>
        @endforelse


        <div class="errors-msg alert alert-danger" style="display:none;"></div>
        <div class="success-msg alert alert-success" style="display:none;"></div>
        <form>

<!-- Template -->
            <strong> 1) Em uma corrida, você ultrapassa 2º colocado. Qual lugar você fica? </strong>
            <div class="radio">
                <label>
                    <input type="radio" name="questao1" id="1" class="soma" value="0">
                    1º lugar
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="questao1" id="1" class="soma" value="1">
                    2º lugar
                </label>
            </div>
            <div class="radio form-group">
                <label>
                    <input type="radio" name="questao1" id="1" class="soma" value="0">
                    3º lugar
                </label>
            </div>

            <strong> 2) Quantos algarismos 1 (um) existem entre 0 e 20? </strong>
            <div class="radio form-group">
                <label>
                    <input type="radio" name="questao2" id="2" class="soma" value="0">
                    01
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="questao2" id="2" class="soma" value="1">
                    11
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="questao2" id="2" class="soma" value="0">
                    12
                </label>
            </div>

            <strong> 3) Alguns meses tem 30 dias, outros 31. Quantos meses tem 28 dias? </strong>
            <div class="radio">
                <label>
                    <input type="radio" name="questao3" id="3" class="soma" value="0">
                    01
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="questao3" id="3" class="soma" value="0">
                    11
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="questao3" id="3" class="soma" value="1">
                    12
                </label>
            </div>

            <strong> 4) Qual o rio mais extenso do mundo? </strong>
            <div class="radio">
                <label>
                    <input type="radio" name="questao4" id="4" class="soma" value="1">
                    Rio Nilo
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="questao4" id="4" class="soma"value="0">
                    Rio Amazonas
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="questao4" id="4" class="soma" value="0">
                    Rio Missisipe
                </label>
            </div>

            <strong> 5) Quantos animais de cada espécie Moisés levou na arca? </strong>
            <div class="radio">
                <label>
                    <input type="radio" name="questao5" id="5" class="soma" value="0">
                    nenhum
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="questao5" id="5" class="soma" value="0">
                    1
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="questao5" id="5" class="soma" value="1">
                    2
                </label>
            </div>

        </form>
<!--fim template teste 01 -->
        <form method="post" action="/editar-aluno/{{$alunos->id}}" id="form-edit-aluno" send="/editar-aluno/{{$alunos->id}}">
            {{csrf_field()}}
            <input class="input-hidden" type="text" id="input_resultado" name="in_teste" value="0" disabled="disabled" >
            {{--resultado <input type="hidden" id="input_resultado" name="in_teste" value="0">--}}
            <div class="text-center">
            <button type="submit" class="btn btn-primary btn-lg">Enviar</button>
            </div>
        </form>

    </div>
</div>

<script>
    $(document).ready(function() {

        $(".soma").bind("blur change check", function() {
            total = 0;

            $("input[type='checkbox']:checked,input[type='radio']:checked").each(function() {
                total+=parseFloat($(this).val());
            });

            $(".soma").each(function() {
                if( $(this).attr('type')=='text' || $(this).is('select') ) {
                    total+=parseFloat($(this).val());
                }
            });

            $("#input_resultado").val(total.toFixed(2));
            $("#resultado").html(total.toFixed(2));
        });

    });

    $(function () {
        jQuery("#form-edit-aluno").submit(function (){
            var dadosForm = jQuery(this).serialize();

            alert(dadosForm);

            jQuery.ajax({
                url:jQuery(this).attr('send'),
                data: dadosForm,
                method: 'POST'

            }).done(function (data) {
                if ( data == '1' ){
                    jQuery(".errors-msg").hide();

                    jQuery(".screen").hide();

                    jQuery(".success-msg").html(
                        "<h1>PARABÉNS! Seu Teste Lógico foi enviado com sucesso!</h1><br>" +
                        "<h4>Aguarde nosso contato.</h4>"
                    );
                    jQuery(".success-msg").show();

                    setTimeout("location.reload();", 5000);
                } else {
                    jQuery(".errors-msg").html(data);
                    jQuery(".errors-msg").show();
                }
            }).fail(function () {
                alert('Falha ao enviar dados!!');
            });


            return false;
        });

    });
</script>
</body>
</html>