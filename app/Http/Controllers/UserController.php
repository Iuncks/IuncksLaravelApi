<?php

namespace App\Http\Controllers;

use App\Models\user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\validator;
use Illuminate\Support\Facades\Credentials;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\token;
use Illuminate\Support\Facades\userData;
use Illuminate\Support\Facades\newToken;
use Illuminate\Support\Facades\authUser;


    Class UserController extends Controller
    
{
    public function index() {

        $users = user::all();

        return response()->json([
            'mensage' => count($users).' Users found',
            'data' => $users,
            'status' => true
        ],200);


    }
    public function show($id){
        $user = user::find($id);
        if ($user != null) {
            return response()->json([
                'message' => 'record found',
                'data' => $user,
                'status' => true
            ],200);
        } else {
            return response()->json([
                'message' => 'record not found',
                'data' => [],
                'status' => true
            ],200);
        }  
    } 

    public function store(Request $request) {
        $validator = validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'message' => 'please fix the errors',
                'errors' => $validator->errors(),
                'status' => false
            ],200);
        }

        $user = new user;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->save();

        return response()->json([
            'message' => 'User added successfully',
            'data' => $user,
            'status' => true
        ],200);
        
    }


    
    public function update(Request $request, string $id) 
    {
        $user = auth()->user();
        

        if ((int) auth()->user()->id !== (int) $id) {
            return response()->json(['error' => 'não aceito']);
        }
        
        $request->validate([
            'user' => 'sometimes|string|max:128',
            'email' => 'sometimes|string|email|max:512|unique:users,email,' . $id,
            
        ]);

    }
    public function destroy($id) {
        $user = user::find($id);

        if ($user == null) {
            return response()->json([
                'message' => 'User not found',
                'status' => false
            ],200);
        } 

        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully',
            'status' => true
        ],200);
    }


    public function upload(Request $request) {
        $validator = validator::make($request->all(),[
            'image' => 'required|mimes:png,jpg,jpeg,gif'
        ]);
    


        if($validator->fails()) { 
            return response()->json([
                'status' => false,
                'message' => 'Please fix the errors',
                'errors' => $validator->errors()
            ],200);
        }

        $img = new image;
        $ext = $img->getClientOriginalExtension();
        $imageName = time().'.'.$ext;
        $img->move(public_path().'/uploads/', $imageName);

        $image = new image;
        $image->name = $imageName;
        $image->save();

        return response()->json([
            'status' => true,
            'message' => 'Image uploaded successfully',
            'data' => $image
        ],200);
    }


 //API LOGIN TEST\\

    // Register API (POST, formdata)
    public function register(Request $request) {

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);

        user::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        //Response
        return response()->json([
            'status' => true,
            'message' => 'User created succesfully'
        ]);

    }
    
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if (Auth::attempt($credentials)) {
            $user = $request->user();
            $token = $user->createToken('authToken')->plainTextToken;
            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user
            ]);
        }
        return response()->json(['message' => 'Unauthorized'], 401);
        
    }

    
    //profile API
    public function profile(){

        $userData = auth()->user();

        return response()->json([
            'status' => true,
            'mensage' => 'Profile data',
            'user' => $userData
        ]);

    }
    // Refresh Token API (GET)
    public function refreshtoken(){

        $newToken = auth()->refresh();

        return response()->json([
            'status' => true,
            'menssage' => 'New Acces token generated',
            'token' => $newToken
        ]);
    }
    

    //API LOGIN TEST\\ register
    public function logout(){
        auth()->logout();

        return response()->json([
            'status' => true,
            'message' => 'User logged out successfully'
        ]);
    }
}
