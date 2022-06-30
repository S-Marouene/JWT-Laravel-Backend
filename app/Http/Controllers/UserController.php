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

    public function update($id)
    {
        $user = User::find($id);
        if( $user->fill(request()->input())->save() ){
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
}
