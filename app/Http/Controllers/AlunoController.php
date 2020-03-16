<?php

namespace lotecweb\Http\Controllers;

use Illuminate\Http\Request;

use lotecweb\Http\Requests;
use lotecweb\Aluno;

class AlunoController extends Controller
{
    private $aluno, $request;

    public function __construct(Aluno $aluno, Request $request)
    {
        $this->aluno = $aluno;
        $this->request = $request;
    }

    public function aluno()
    {
        $this->smsGo();

//        return view('aluno.add');
    }

    public function addAluno()
    {
        $dadosForm = $this->request->all();
        dd($dadosForm);
    }


    /**
     * @return string
     */
    public function addAlunoGo()
    {
        $dadosForm = $this->request->all();

//        dd($dadosForm);

        $validator = validator($dadosForm, $this->aluno->rules);

        if ($validator->fails()) {
            $messages = $validator->messages();

            $displayErrors = '';

            foreach ($messages->all("<p>:message</p>") as $message) {
                $displayErrors .= $message;
            }

            return $displayErrors;
        }


        //grava dados
        $insert = $this->aluno->create($dadosForm);

        //envio de SMS
//        $sms = $this->enviarSMS($insert->cell);

        if ($insert)

            return $this->enviarSMS($insert->id);

        else
            return 'Falha ao Cadastrar, erro inesperado!';
    }


    public function enviarSMS($id)
    {


        $alunos = $this->aluno->find($id);


        //capturar primeiro nome
        $string = $alunos['name'];
        $string = explode(" ", $string);
        $nome = $string[0];

        //capturar numero de celular
        $cell = $alunos['cell'];

        $mensagem = 'Ola+' . $nome . ',+clique+no+link+http://pronap.info/tst/' . $cell . '+para+acessar+seu+teste+e+concorrer+a+BOLSA+DE+ESTUDO+e+diversos+outros+premios';


        $url = 'http://www.painelsms.com.br/sms.php?i=4551&s=ozqpxz&funcao=enviar&mensagem=' . $mensagem . '&destinatario=' . $cell . '';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, True);
        curl_setopt($curl, CURLOPT_URL, $url);
        $return = curl_exec($curl);
        $valor1 = ['ret' => '1'];
        $valor2 = ['ret' => '2'];
        if ($return != FALSE) {
            return $valor1;
        } else {
            return $valor2;
        }
        curl_close($curl);

    }


    public function editar($id)
    {

        $alunos = $this->aluno->find($id);

        if ($alunos->in_teste != 'false') {
            return view('testes.logico1', compact('alunos'));
        } else {
            return view('testes.feito', compact('alunos'));
        }

    }

    public function editarGo($id)
    {

        $dadosForm = $this->request->all();

        $aluno = $this->aluno->find($id);


        $update = $aluno->update($dadosForm);


        if ($update)

            return '1';

        else
            return 'Falha ao Cadastrar, erro inesperado!';
    }

    public function pesquisar($pesquisa)
    {

        $alunos = $this->aluno
            ->where('cell', 'LIKE', "%{$pesquisa}%")
            ->get();

        return view('testes.index', compact('alunos', 'pesquisa'));
    }

    public function smsGo($urlgo)
    {

        $url = $urlgo;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, True);
        curl_setopt($curl, CURLOPT_URL, $url);
        $return = curl_exec($curl);
        $valor1 = ['ret' => '3'];
        $valor2 = ['ret' => '2'];
        if ($return != FALSE) {
            return $valor1;
        } else {
            return $valor2;
        }
        curl_close($curl);

    }

    public function show($id)
    {

        $alunos = $this->aluno->find($id);

//        dd($alunos);

        return response()->json($alunos);
    }

    public function sms($id)
    {


        $alunos = $this->aluno->find($id);

        $sms = $this->enviarSMS($alunos->id);
//        dd($sms);
        return response()->json($sms);

    }
}

