<?php

namespace App\Http\Controllers;

use App\Helpers\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Constraint;
use Intervention\Image\Facades\Image;

class StaticController extends Controller
{
    function foto(Request $request, $foto, $width, $folder = "fotos"){

        $path = "../".Utils::$pathFilesName."/$folder/$foto";
        //$path = "../../files/"."/$folder/$foto";


        if($foto === "padrao.png"){

            $img = Image::make(public_path("/imagens/padrao.png"));

            if($width != 0) {
                $img->resize($width, $width, function (Constraint $constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }

            return $img->response(null, 100);

        } else if (File::exists($path)) {

            $img = Image::make($path);

            if($img->getWidth() < $width){
                $width = $img->getWidth();
            }

            if($width != 0) {
                $img->resize($width, $width, function (Constraint $constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }

            return $img->response(null, 90);

        } else {

            $data["estado"] = "erro";
            $data["texto"] = "Esta foto não existe";
            return $data;
        }
    }

    function pdf(Request $request, $pdf, $folder = "pdf"){

        $path = "../".Utils::$pathFilesName."/$folder/$pdf";

        if (File::exists($path)) {
            return response()->file($path);
        } else {
            $data["estado"] = "erro";
            $data["texto"] = "Esta ficheiro não existe";
            return $data;
        }
    }

    function upload(Request  $request){

        $validator = Validator::make($request->all(),
            [
                'file' => 'required|mimes:jpg,png,gif,jpeg|max:48048',
            ], [
                'file.required' => 'Necessitas fazer upload de uma imagem',
                'file.mimes' => 'Tipo de ficheiro não suportado, por favor adiciona somente imagens no formato jpg, png ou gif',
                'file.max' => 'Tamanho do ficheiro excedido, o tamanho do ficheiro é muito grande',
            ]
        );

        if ($validator->fails()){
            $data["estado"] = "erro";
            $data["texto"] = $validator->errors()->first();
            return response()->json($data);
        }


        if ($request->action && method_exists($this, "addFotoUser") && is_callable(array($this, "addFotoUser"))) {
            return call_user_func(array($this, "addFotoUser"), $request, $request->file);
        }

        $path = "temp";

        if($request->f){
            if(!File::exists("../".Utils::$pathFilesName."/".$request->f."")){
                $data["estado"] = "erro";
                $data["texto"] = "Foto inexistente, por favor tenta de novo";
                return response()->json($data);
            }
            $path = $request->f;
        }

        $fileName = md5(uniqid()).'.'.$request->file->extension();
        $request->file->move("../".Utils::$pathFilesName."/".$path."", $fileName);

        $data["estado"] = "ok";
        $data["texto"] = null;
        $data["nome"] = $fileName;
        $data["foto_link"] = Utils::foto_link($fileName, 800, $path);

        return response()->json($data);
    }

    function addFotoUser(Request $request, $file){

        $user = \Auth::user();

        $fileName = md5(uniqid()).'.'.$request->file->extension();
        $file->move("../".Utils::$pathFilesName."/fotos", $fileName);


        $data["estado"] = "ok";
        $data["texto"] = null;
        $data["nome"] = $fileName;
        $data["foto_link"] = Utils::foto_link($fileName, 800);

        $user->foto = $fileName;
        $user->save();
        return response()->json($data);
    }


    function upload_pdf(Request  $request){

        $validator = Validator::make($request->all(),
            [
                'file' => 'required|mimes:pdf|max:48048',
            ], [
                'file.required' => 'Necessitas fazer upload de um ficheiro pdf',
                'file.mimes' => 'Tipo de ficheiro não suportado, por favor adiciona somente documento em pdf',
                'file.max' => 'Tamanho do ficheiro excedido, o tamanho do ficheiro é muito grande',
            ]
        );

        if ($validator->fails()){
            $data["estado"] = "erro";
            $data["texto"] = $validator->errors()->first();
            return response()->json($data);
        }

        $path = "temp";

        if($request->f){
            if(!File::exists("../".Utils::$pathFilesName."/".$request->f."")){
                $data["estado"] = "erro";
                $data["texto"] = "Documento inexistente por favor tenta de novo";
                return response()->json($data);
            }
            $path = $request->f;
        }

        $fileName = md5(uniqid()).'.'.$request->file->extension();
        $request->file->move("../".Utils::$pathFilesName."/".$path."", $fileName);

        $data["estado"] = "ok";
        $data["texto"] = null;
        $data["nome"] = $fileName;
        $data["link"] = "/pdf/$fileName,$path";

        return response()->json($data);
    }
}
