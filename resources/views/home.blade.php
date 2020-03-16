@extends('dashboard.templates.app')

@section('content')



    @php
    $ideven = 0;
    @endphp

    <div class="section">

                   
     {{--   @if(!empty($data))
            @foreach ( $data as $s )
                
           
            <a id="link-modal" class="waves-effect waves-light btn modal-trigger btn_grid btn_grid_130px" href="#" onclick='openModal1("/admin/senhadia/view/{{$s->baixa_caixa}}")'>

                                       
            @endforeach
            @else
                <p class="white-text">Usuário Administrador</p>
        @endif --}}
    <!--  Metade Div -->

   

<div class="row">
    <div class="col s12 m12 l12">
        @if(!empty($validaMesalidade))
        <div class="card yellow ">
            
              
                @php
                $datven = new DateTime($validaMesalidade->datven);
    
                $datatual = date ("Y-m-d");
                $datatual = new DateTime($datatual);
    
             //   {{--  echo strtotime($datatual->format('Y-m-d'))."<br>";
            //     echo strtotime($datven->format('Y-m-d'));-->--}}
    
                $datatualX = strtotime($datatual->format('Y-m-d'));
                $datvenX = strtotime($datven->format('Y-m-d'));
    
                $umdiaantes = $datatual->modify('-1 day');
    
                @endphp
                @if($datvenX < $datatualX)
                 <div class="row">
                   <div class="col s12 m12 l6">

                      <p></p>
                      <span class="card-title title-black center"><h4>  Atenção</h4></span>
                      
                      
                   </div>
                    <div class="col s12 m12 l6 ">
                      <p></p>
                        <span class="card-title ">
                      
                                <h5>Sua mensalidade venceu em: {{Carbon\Carbon::parse($validaMesalidade->datven)->format('d/m/Y')}} <br/>
                                Evite o bloqueio do acesso efetuando o pagamento até o dia: {{Carbon\Carbon::parse($validaMesalidade->datpro)->modify('-1 day')->format('d/m/Y')}} <br>
                                Para maiores informações falar com setor financeiro <br>
                                Data para bloqueio: {{Carbon\Carbon::parse($validaMesalidade->datpro)->format('d/m/Y')}}
                              </h5>
                            </span>
                    </div>
                
                    @endif
                    @endif
            
    </div>
     </div>
    </div>

        <div class="row">

          <div class="col s12 m12 l1">
          </div>
          
          <div class="col s12 m12 l10">
               
            <div class=" cardMsg card ">
                  <div class="card-content">                                       
                          @if(!empty($consultaMensagemSis))

                            <?php 
                               
                                  $procurar = $consultaMensagemSis->msg;
                                 
                                  $str     = $procurar;
                                  $order   = array("\r\n", "\n", "\r");
                                  $replace = '<br>';
                                  $newstr = str_replace($order, $replace, $str);

                              ?> 
                             {{-- @if($datvalX > $datatualX)--}}
                                  <p><?php echo $newstr; ?>.</p>
                            {{--  @endif--}}
                            @endif
                  </div>
              </div>

              <div class="col s12 m12 l1">
              </div>
 
       
      </div>
  
        

         
         
    <!-- <div class="row">

      <div class="col s12 m12 l6">
        <h3 align="center">Confira as Novidades</h3>

    <div class="card-panel white-text red">
        <div class="row">
          <div class="col s12 m12 l12">
            <a class="waves-effect waves-light btn-large">
              <i class="material-icons right">cloud</i> Movimentos por Cobradores</a>
          </div>
          <div class="divider"></div>
          <div class="col s12 m12 l12">
            <a class="waves-effect waves-light btn-large">
              <i class="material-icons right">cloud</i> Histórico de Vendas Diário</a>
          </div>
          <div class="divider"></div>
          <div class="col s12 m12 l12">
            <a class="waves-effect waves-light btn-large">
              <i class="material-icons right">cloud</i> Histórico de Vendas Mensal</a>
          </div>
          <div class="divider"></div>
          <div class="col s12 m12 l12">
            <a class="waves-effect waves-light btn-large">
              <i class="material-icons right">cloud</i> Instantânea Resultados</a>
          </div>
          <div class="divider"></div>
          <div class="col s12 m12 l12">
            <a class="waves-effect waves-light btn-large">
              <i class="material-icons right">cloud</i> Cancelar Apostas</a>
          </div>
          
          <div class="divider"></div>
          <div class="section">
            <a class="waves-effect waves-light btn-large">
              <i class="material-icons right">cloud</i> Movimentos por Cobradores</a>
          </div>
          <div class="divider"></div>
          <div class="section">
            <a class="waves-effect waves-light btn-large">
              <i class="material-icons right">cloud</i> Histórico de Vendas Diário</a>
          </div>
          <div class="divider"></div>
          <div class="section">
            <a class="waves-effect waves-light btn-large">
              <i class="material-icons right">cloud</i> Histórico de Vendas Mensal</a>
          </div>
          <div class="divider"></div>
          <div class="section">
            <a class="waves-effect waves-light btn-large">
              <i class="material-icons right">cloud</i> Instantânea Resultados</a>
          </div>
          <div class="divider"></div>
          <div class="section">
            <a class="waves-effect waves-light btn-large">
              <i class="material-icons right">cloud</i> Cancelar Apostas</a>
          </div> 
        

        </div>
    
    </div>




          <div class="card-panel card white-text red">
            <i class="material-icons left medium">motorcycle</i>
            <a class="transparent white-text btn-flat">
            Movimentos por Cobradores</a>
          </div>
          <div class="card-panel card white-text red darken-1">

            <i class="material-icons left medium">date_range</i>
            <a class="transparent white-text btn-flat">
              Histórico de Vendas Diário</a>
          </div>
          <div class="card-panel card white-text red darken-2">
            <i class="material-icons left medium">date_range</i>
            <a class="transparent white-text btn-flat">
              Histórico de Vendas Mensal</a>
            </div>
          <div class="card-panel card white-text red darken-3">
            <i class="material-icons left medium">query_builder</i>
            <a class="transparent white-text btn-flat">
              Instantânea Resultados</a>
          </div>
          <div class="card-panel card white-text red darken-4">
            <i class="material-icons left medium">close</i>
            <a class="transparent white-text btn-flat">
              Cancelar Apostas</a>
          </div>


          </div>-->


<!--
            <div class="col s12 m12 l6">

                <div class="slider">
                    <ul class="slides">
                      <li>
                        
                        <img src="images/testes5.jpeg" 
                        height: auto;>  random image 
                        <div class="caption  center-align">
                          <h3 style="text-shadow: 1px 0px 0px black, 
                          -1px 0px 0px black, 
                          0px 1px 0px black, 
                          0px -1px 0px black;
             color: #FFF;" >Confira as novidades!!</h3>
                          
                        </div>
                      </li>
                      <li>
                        <img src="images/fot1.jpeg">>  random image 
                        <div class="caption left-align">
                          <h3 style="text-shadow: 1px 0px 0px black, 
                          -1px 0px 0px black, 
                          0px 1px 0px black, 
                          0px -1px 0px black;
             color: #FFF;">Liberado a tela de Consulta Histórico de Vendas</h3>
                          <h5 class="light grey-text text-lighten-3">Podendo consultar Mensal, ou Diário!!</h5>
                        </div>
                      </li>
                      <li>
                        <img src="images/fot2.jpeg">>  random image 
                        <div class="caption right-align">
                          <h3>Movimentos Por Cobrador</h3>
                          <h5 class="light grey-text text-lighten-3">Fazer a liberação dos movimentos dos cobradores!</h5>
                        </div>
                      </li>
                      <li>
                        <img src="https://static.pexels.com/photos/1848/nature-sunny-red-flowers-medm.jpg"> random image 
                        <div class="caption center-align">
                          <h3>Consulta das Instantâneas</h3>
                          <h5 class="light grey-text text-lighten-3">Visualizar transmissões e resultados!</h5>
                        </div>
                      </li>
                      <li>
                        <img src="https://static.pexels.com/photos/1848/nature-sunny-red-flowers-medm.jpg"> random image 
                        <div class="caption center-align">
                          <h3>Ajustes e melhorias em todo o site!!</h3>
                          <h5 class="light black-text text-lighten-3">Para sempre melhor atender, precisamos de sujestôes de melhorias!</h5>
                        </div>
                      </li>
                    </ul>
                  </div>

            </div>
         
          </div>-->
    </div>

    
            
        <div class="row">                
                <div class="col s6">
                        <div class="col s12 m12 l12 ">                      
                            <span> Usuário:  {{ $usuario_lotec->idusu .' - '. $usuario_lotec->nomusu}}</span>                             
                        </div>                      
                </div>
        </div>
            
        </div>

       

    </div>

@endsection

@push('modal')



<script>

$(document).ready(function(){
      $('.slider').slider();
    });

$(document).ready(function(){
  $('.carousel').carousel(
  {
    dist: 0,
    padding: 100,
    
    indicators: true,
    duration: 100,
   
  }
  );


  $('.carousel.carousel-slider').carousel({
    fullWidth: true
  });
});

autoplay()   
function autoplay() {
    $('.carousel').carousel('next');
    setTimeout(autoplay, 4500);
}

</script>
<script type="application/javascript">


        //init the modal
        $('.modal').modal();
function openModal1(url) {

    $('#senhadia').modal('open');


    };

</script>
@endpush

{{--@push('jivosite')
<!-- BEGIN JIVOSITE CODE {literal} -->
<script type='text/javascript'>
    (function(){ var widget_id = 'yE43OgkstV';var d=document;var w=window;function l(){
        var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = '//code.jivosite.com/script/widget/'+widget_id; var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss);}if(d.readyState=='complete'){l();}else{if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})();</script>
<!-- {/literal} END JIVOSITE CODE -->
@endpush--}}



