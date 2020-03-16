<?php

namespace lotecweb\Http\Middleware;

use Closure;
use DateTime;
use Illuminate\Support\Facades\DB;
use lotecweb\Models\Usuario;

class CheckMensalidade
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        /*
        * Verifica menssalidade
        */
        // Recupera idusu do usuário logado
        $idusu = auth()->user()->idusu;

        $admin = Usuario::where('idusu', '=', $idusu)->first();
//        dd($admin->inadim);

        if ($admin->inadim != 'SIM'){
            $d = $this->validarMensalidade($idusu);
            $dataAtual = date ("Y-m-d");
//            dd($d);
            if ($d != Null){
                // Verifica validade da mensalidade                
                if ( $d->datpro <= $dataAtual)
                    return redirect('/expired');
            }
        }


      //  $log = $this->logUsuario($idusu);

//        dd($log);


        // Permite que continue (Caso não entre em nenhum dos if acima)...
        return $next($request);
    }

    public function validarMensalidade($idusu){

        $p = $this->returnBaseIdvenDefault($idusu);
        $d = date ("Y-m-d");

        $data = DB::table('cobranca')
            ->select('datven','datpro' )
            ->where([
                ['idbase','=', $p->idbase],
                ['idven', '=', $p->idven],
                ['sitcob', '=', 'ABERTO']
            ])
            ->first();

        return $data;
    }

    public function returnBaseIdvenDefault($idusu){



        $data = DB::table('usuario_ven')
            ->where([
                ['inpadrao', '=', 'SIM'],
                ['idusu', '=', $idusu]
            ])
            ->first();

        return $data;
    }

    public function logUsuario($idusu){

        $usuario = DB::table('USUARIO')
                    ->where('IDUSU','=', $idusu)->first();


        $idusulog = DB::select("SELECT MAX(USULOG.IDUSULOG) AS IDUSULOG  FROM USULOG");

        $idusulog = $idusulog[0]->idusulog + 1;



        $p = $this->returnBaseIdvenDefault($idusu);


        if ($p == Null){
            $idbase = "";
            $idven = "";
        } else{
            $idbase = $p->idbase;
            $idven = $p->idven;
        }

        $data_array = [
            "idusulog" => $idusulog,
            "idusu" => $usuario->idusu,
            "datlog" => date('Y-m-d'),
            "horlog" => date('Y-m-d H:i:s'),
            "inadmin" => $usuario->inadim,
            "versis" => 'WEB',
            "idbase" =>$idbase,
            "idven" =>$idven,

        ];

//        dd($data_array);

        $insert = DB::table('usulog')->insert($data_array);

        if ($insert)

            return $insert;

        else
            return 2;


    }



}
