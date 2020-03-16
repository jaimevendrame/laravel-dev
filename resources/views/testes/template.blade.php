<!DOCTYPE html>
<html lang="pt-br">
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
        <div class="errors-msg alert alert-danger" style="display:none;"></div>
        <div class="success-msg alert alert-success" style="display:none;"></div>
        @yield('teste')
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

//            alert(dadosForm);

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