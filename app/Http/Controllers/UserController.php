<?php

namespace App\Http\Controllers;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use App\Api\V1\Requests\LoginRequest;
use App\User;
use Illuminate\Http\Response as HttpResponse;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response as FacadesResponse;
use Illuminate\Http\Request;
Use Image;
Use Illuminate\Support\Facades\File;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('jwt.auth', []);
    }

    public function GetAllUser()
    {
        return response()->json(User::get());
    }

   

    public function delete($id)
    {
        $user = User::findOrFail($id);
        if ($user){
            $path=$user->path;
            if(\Storage::exists('public/storage/profile_pic/'.$path)){
                \Storage::delete('public/storage/profile_pic/'.$path);
            }
            $user->delete();
        }
        else
            return response()->json("error");
        return response()->json("Success delete");
    }

    public function update_user(Request $request)
    {
        $compPic=null;
        $user = User::find($request->id);

        $originalPath = 'public/storage/profile_pic/';
        $old_path=$originalPath.$user->path;

        if($files = $request->file('fileSource')){
            if(File::exists($old_path)) {
                File::delete($old_path);
            }
            $ImageUpload = Image::make($files);
            $ImageUpload->resize(250,250);
            $compPic = time().$files->getClientOriginalName();
            $ImageUpload->save($originalPath.$compPic);
        }

        if($compPic){
            $data_updated=[
                'path' => $compPic,
                'name' => $request->name ,
                'fname' => $request->fname ,
                'status' => $request->status ,
                'role' => $request->role ,
            ];
        }else{
            $data_updated=[
                'name' => $request->name ,
                'fname' => $request->fname ,
                'status' => $request->status ,
                'role' => $request->role ,
            ];
        }
        

        if($user->update($data_updated) ) {
            $role = Role::where('name','=',request()->input('role'))->first();
            $user->roles()->sync([$role->id]);
            return response()->json("updated item succesfuly");
        }

        return response()->json("Error"); 
    }

    public function GetUserByToken()
    {
        if (Auth::check()) {
            return response()->json(auth()->user());
        }else {
            return response()->json("non connecter");
        }
    }


    public function GetPermissionByRole()
    {
        $user =  auth()->user();
        $roles = $user->getRoleNames();
        $permissions = $user->getAllPermissions();

        return response()->json(['RoleName' => $roles, 'AllPermission' => $permissions]);
    }


    public function ImgProfil_update(Request $request) {
        
        $user = User::find($request->id);
        $originalPath = 'public/storage/profile_pic/';

        $old_path=$originalPath.$user->path;

        if($files = $request->file('fileSource')){
            if(File::exists($old_path)) {
                File::delete($old_path);
            }
            
            $ImageUpload = Image::make($files);
            $ImageUpload->resize(250,250);
            $compPic = time().$files->getClientOriginalName();
            $ImageUpload->save($originalPath.$compPic);
        }
                
        $user->update([
            'path' => $compPic,
        ]);

        return response()->json([
            'message' => 'photo successfully updated',
        ], 201);

    }
}
