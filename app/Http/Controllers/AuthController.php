<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator;
use App\User;

Use Image;

class AuthController extends Controller {

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
       $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){
    	$validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (! $token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Either email or password is wrong.'], 401);
        }

        return $this->createNewToken($token);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        $compPic='';
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'fname' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
            'role' => 'required|string',
            'path' => 'nullable',
            'status' => 'required',
            'fileSource' => 'nullable'
        ]);

        if($validator->fails()){
             return response()->json($validator->errors(), 400);
        }

        /**$request->hasFile('fileSource')   in if old */ 
        if($files = $request->file('fileSource')){
            /* $filename=$request->file('fileSource')->getClientOriginalName();
            $fileNameOnly=pathinfo($filename,PATHINFO_FILENAME);
            $extention=$request->file('fileSource')->getClientOriginalExtension();
            $compPic = str_replace('','_',$fileNameOnly).'-'.rand().'_'.time().'.'.$extention;
            $request->file('fileSource')->storeAs('public/storage/profile_pic',$compPic); */

            $ImageUpload = Image::make($files);
            $originalPath = 'public/storage/profile_pic/';
            $ImageUpload->resize(250,250);
            $compPic = time().$files->getClientOriginalName();
            $ImageUpload->save($originalPath.$compPic);
           

        }


        $user = User::create(array_merge(
                    $validator->validated(),
                    ['path' => $compPic ],
                    ['password' => bcrypt($request->password)]
                ));
        
        $user->assignRole(['name' => $request->role]);

       
        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
        
        
        

    }

    public function me()
    {
        return response()->json(auth()->user());
    }
    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth()->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {
        return response()->json(auth()->user());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }

}
