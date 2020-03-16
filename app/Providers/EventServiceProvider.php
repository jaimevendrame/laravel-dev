<?php

namespace lotecweb\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

/*adicionei estes para registrar os acessos de usuario*/
use Closure;
use DateTime;
use Illuminate\Support\Facades\DB;
use lotecweb\Models\Usuario;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'lotecweb\Events\SomeEvent' => [
            'lotecweb\Listeners\EventListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
        \Event::listen(\Illuminate\Auth\Events\Authenticated::class, function ($request) {

           // dd('Evento de Authenticated está sendo escutado');
            
    
        });

        \Event::listen(\Illuminate\Auth\Events\Login::class, function ($request) {
            $idusu = auth()->user()->idusu;
            $log = $this->logUsuario($idusu);   
        });

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


        $insert = DB::table('usulog')->insert($data_array);

        if ($insert)

            return $insert;

        else
            return 2;

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
}
