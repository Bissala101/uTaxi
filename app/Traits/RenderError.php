<?php


namespace App\Traits;


use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

trait RenderError
{

    function renderSocialError(Request $request, Throwable $e){

        if ($e instanceof AccessDeniedHttpException) {
            if($request->wantsJson()) {
                return response()->json([
                    'estado' => 'erro',
                    'texto' => 'Você não tem permissão para acessar esta página'
                ], 403);
            }
            return response()->view("404", ["titulo"=>"Oops! Pagina não encontrada", "texto"=>"A página que estas a tentar acessar infelizmente não foi encontada.", "codigo"=>404]);
        }

        if ($e instanceof ModelNotFoundException) {

            if($request->wantsJson()) {
                return response()->json([
                    'estado' => 'erro',
                    'texto' => 'Item ou objecto não encontrado!'
                ], 404);
            }

            return response()->view("404", ["titulo"=>"Oops! Pagina não encontrada", "texto"=>"A página que estas a tentar acessar infelizmente não foi encontada.", "codigo"=>404]);

        }

        if ($e instanceof NotFoundHttpException) {

            if($request->wantsJson()) {
                return response()->json([
                    'estado' => 'erro',
                    'texto' => 'Rota, item ou objecto não encontrado!',
                    'url'=>$request->url(),
                ], 404);
            }

            return response()->view("404", ["titulo"=>"Oops! Pagina não encontrada", "texto"=>"A página que estas a tentar acessar infelizmente não foi encontada.", "codigo"=>404]);

        }

        if ($e instanceof MethodNotAllowedHttpException) {

            if($request->wantsJson()) {
                return response()->json([
                    'estado' => 'erro',
                    'texto' => 'Método não aceito',
                ], 405);
            }

            return response()->view("404", ["titulo"=>"Oops! Acesso negado", "texto"=>"Tu não tens permissão para acessar esta página desta forma", "codigo"=>405]);

        }

        if ($e instanceof \Illuminate\Session\TokenMismatchException) {

            if($request->wantsJson()) {
                return response()->json([
                    'estado' => 'erro',
                    'texto' => 'Por favor actualiza a página para continuar',
                ], 419);
            }

            return redirect()->back()->with(["aviso"=>"Por favor tenta de novo"]);
        }

        if ($e instanceof ValidationException) {

            if($request->wantsJson()) {
                return response()->json([
                    'estado' => 'erro',
                    'texto' => $e->validator->errors()->first(),
                    'input_key' => $e->validator->errors()->keys()[0],
                ], $e->status);
            }

            return redirect()->back()->withErrors($e->validator);
        }

        if ($e instanceof QueryException) {

            if($request->wantsJson()) {
                return response()->json([
                    'estado' => 'erro',
                    'texto' => 'Ocorreu um erro interno nos servidores da Wap Angola, por favor tenta mais tarde',
                    'causa' => $e->getMessage()
                ], 500);
            }

            return response()->view("404", ["titulo"=>"Erro interno do servidor", "texto"=>"Ocorreu um erro interno nos servidores, por favor tenta mais tarde", "codigo"=>500]);

        }


        if ($e instanceof HttpException) {

            if($request->wantsJson()) {
                return response()->json([
                    'estado' => 'erro',
                    'texto' => $e->getMessage()  != null ? $e->getMessage():'Você não tem permissão para acessar esta área',
                    'causa' => $e->getMessage()
                ], 401);
            }

            return response()->view("404", ["titulo"=>"Você não tem permissão para acessar esta área", "texto"=>"Necessitas de permissão para acessar esta area", "codigo"=>401]);

        }


        return parent::render($request, $e); // TODO: Change the autogenerated stub
    }
}