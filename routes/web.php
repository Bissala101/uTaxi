<?php

use App\Http\Controllers\Cliente\IndexController;
use App\Http\Controllers\LoginController;
use App\Http\Middleware\CheckViagemClienteMiddleware;
use App\Http\Middleware\CheckViagemMotoristaMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/foto/{foto},{width},{folder}', [\App\Http\Controllers\StaticController::class, 'foto']);

Route::prefix('admin')->group(function(){

    Route::prefix("clientes")->group(function(){
        Route::get("/", [\App\Http\Controllers\Admin\ClientesController::class, "index"]);
        Route::get("/add", [\App\Http\Controllers\Admin\ClientesController::class, "add"]);
        Route::post("/add", [\App\Http\Controllers\Admin\ClientesController::class, "store"]);
        Route::get("/editar/{id}", [\App\Http\Controllers\Admin\ClientesController::class, "update"]);
        Route::post("/editar/{id}", [\App\Http\Controllers\Admin\ClientesController::class, "actualizar"]);
        Route::get("/eliminar/{id}", [\App\Http\Controllers\Admin\ClientesController::class, "destroy"]);
    });

    Route::prefix("relatorios")->group(function(){
        Route::get("/clientes", [\App\Http\Controllers\Admin\HomeController::class, "relatorio_clientes"]);
        Route::get("/motoristas", [\App\Http\Controllers\Admin\HomeController::class, "relatorio_motoristas"]);
    });

    Route::prefix("motoristas")->group(function(){
        Route::get("/", [\App\Http\Controllers\Admin\MotoristasController::class, "index"]);
        Route::get("/add", [\App\Http\Controllers\Admin\MotoristasController::class, "add"]);
        Route::post("/add", [\App\Http\Controllers\Admin\MotoristasController::class, "store"]);
        Route::get("/editar/{id}", [\App\Http\Controllers\Admin\MotoristasController::class, "update"]);
        Route::post("/editar/{id}", [\App\Http\Controllers\Admin\MotoristasController::class, "actualizar"]);
        Route::get("/eliminar/{id}", [\App\Http\Controllers\Admin\MotoristasController::class, "destroy"]);
    });

    Route::prefix("carros")->group(function(){
        Route::get("/", [\App\Http\Controllers\Admin\CarrosController::class, "index"]);
        Route::get("/add", [\App\Http\Controllers\Admin\CarrosController::class, "add"]);
        Route::post("/add", [\App\Http\Controllers\Admin\CarrosController::class, "store"]);
        Route::get("/editar/{id}", [\App\Http\Controllers\Admin\CarrosController::class, "update"]);
        Route::post("/editar/{id}", [\App\Http\Controllers\Admin\CarrosController::class, "actualizar"]);
        Route::get("/eliminar/{id}", [\App\Http\Controllers\Admin\CarrosController::class, "destroy"]);
        Route::get("/associar", [\App\Http\Controllers\Admin\CarrosController::class, "associar"]);
        Route::post("/associar/add", [\App\Http\Controllers\Admin\CarrosController::class, "associarAdd"]);
        Route::get("/associar/eliminar/{id}", [\App\Http\Controllers\Admin\CarrosController::class, "eliminarAssociao"]);
    });

    Route::get("/sair", [\App\Http\Controllers\Admin\HomeController::class, "sair"]);
    Route::post("/login", [LoginController::class, "loginAdmin"]);
    Route::get("/login", [LoginController::class, "loginAdminView"]);
    Route::get("/", [\App\Http\Controllers\Admin\HomeController::class, "index"])->middleware(["auth:admin"]);
});


Route::prefix('motorista')->middleware([CheckViagemMotoristaMiddleware::class])->group(function(){

    Route::get("/sair", [\App\Http\Controllers\Motorista\HomeMotorista::class, "sair"]);

    Route::get('/nova_viagem', [\App\Http\Controllers\Motorista\ViagensController::class, "nova_viagem"]);
    Route::post('/mudar_estado', [\App\Http\Controllers\Motorista\HomeMotorista::class, "mudar_estado"]);

    Route::prefix("viagens")->group(function(){
        Route::post('/processar', [\App\Http\Controllers\Motorista\ViagensController::class, "processViagem"]);
        Route::post('/confirmar', [\App\Http\Controllers\Motorista\ViagensController::class, "viagemConfirmar"]);
        Route::post('/publicar', [\App\Http\Controllers\Motorista\ViagensController::class, "publicarViagem"]);

        Route::get('/fila', [\App\Http\Controllers\Motorista\ViagensController::class, "fila"]);
        Route::get('/realizadas', [\App\Http\Controllers\Motorista\ViagensController::class, "realizadas"]);

        Route::get('/{id}', [\App\Http\Controllers\Motorista\ViagensController::class, "show"])->withoutMiddleware([CheckViagemMotoristaMiddleware::class]);
        Route::post('/mudar_preco/{id}', [\App\Http\Controllers\Motorista\ViagensController::class, "alterarPreco"])->withoutMiddleware([CheckViagemMotoristaMiddleware::class]);
        Route::post('/concluido/{id}', [\App\Http\Controllers\Motorista\ViagensController::class, "marcarConcluido"])->withoutMiddleware([CheckViagemMotoristaMiddleware::class]);
        Route::get('/atender/{id}', [\App\Http\Controllers\Motorista\ViagensController::class, "atender"]);
    })->middleware(["auth:motorista"]);

    Route::post("/login", [LoginController::class, "loginMotorista"])->withoutMiddleware([CheckViagemMotoristaMiddleware::class]);
    Route::get("/login", [LoginController::class, "loginMotoristaView"])->withoutMiddleware([CheckViagemMotoristaMiddleware::class]);

    Route::get("/register", [LoginController::class, "registerMotoristaView"])->withoutMiddleware([CheckViagemMotoristaMiddleware::class]);
    Route::post("/register", [LoginController::class, "registerMotorista"])->withoutMiddleware([CheckViagemMotoristaMiddleware::class]);

    Route::get("/", [\App\Http\Controllers\Motorista\HomeMotorista::class, "index"])->middleware(["auth:motorista"]);
});


Route::get("/sair", [IndexController::class, "sair"]);
Route::post("/login", [LoginController::class, "loginCliente"]);
Route::get("/login", [LoginController::class, "loginClienteView"]);
Route::get("/register", [LoginController::class, "registerView"]);
Route::post("/register", [LoginController::class, "registerCliente"]);

Route::middleware([CheckViagemClienteMiddleware::class])->group(function(){
    Route::get('/', [IndexController::class, "index"])->middleware(["auth"]);
    Route::get('/nova_viagem', [\App\Http\Controllers\Cliente\ViagensController::class, "nova_viagem"])->middleware(["auth"]);

    Route::prefix("viagens")->group(function(){

        Route::post('/processar', [\App\Http\Controllers\Cliente\ViagensController::class, "processViagem"]);
        Route::post('/confirmar', [\App\Http\Controllers\Cliente\ViagensController::class, "viagemConfirmar"]);
        Route::post('/publicar', [\App\Http\Controllers\Cliente\ViagensController::class, "publicarViagem"]);
        Route::get('/fila', [\App\Http\Controllers\Cliente\ViagensController::class, "fila"]);
        Route::get('/realizadas', [\App\Http\Controllers\Cliente\ViagensController::class, "realizadas"]);
        Route::post('/avaliar/{id}', [\App\Http\Controllers\Cliente\ViagensController::class, "classifiar_motorista"]);
        Route::get('/{id}', [\App\Http\Controllers\Cliente\ViagensController::class, "show"])->withoutMiddleware([CheckViagemClienteMiddleware::class]);

    })->middleware(["auth"]);

});
