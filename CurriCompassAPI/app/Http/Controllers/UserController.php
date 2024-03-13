<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

//TODO: implement necessary methods
//FIXME: Fix adding/editing/deleting roles of users
//TODO: Add Documentation
//TODO: Add Role-based access
class UserController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth:api', ['except' => ['login']]);
    }

    //Public Methods
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();
        return response()->json([
                'status' => 'success',
                'user' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
        ]);
    }

    //FIXED
    public function register(Request $request){
        //dd($request->all());
        $validate = Validator::make( $request->all(), [
            'userfname' => ['required','string','max:255'],
            'userlname' => ['required','string','max:255'],
            'usermiddle' => ['required','string','max:255'],
            'email' => ['required','string','email','max:255','unique:users'],
            'contactno' => ['required','string'],
            'password' => ['required','string'],
            'roles' => ['required','array'],
            'roles.*.roleid' => ['required', 'integer'],
        ]);

        if($validate->fails()){
            return response()->json([['status' => 'bad request'], $validate->errors()] ,400);
        }

        $user = User::create([
            'userfname' => $request->userfname,
            'userlname' => $request->userlname,
            'usermiddle' => $request->usermiddle,
            'contact_no' => $request->contactno,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        foreach($request->roles as $role) {
            $user->user_roles()->attach($role);
        }
        //$user->user_roles()->attach($request->roles);

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            $user
        ], 201);

    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

    public function show(Request $request, String $id)
    {
        $res = User::where('userid', '=', $id)->first();

        if($res != null) {
            return response()->json([
                ['status' => 'success'],
                $res], 200);
        }
        return response()->json([
            ['status' => 'not found'],
            ], 404);
    }

    public function destroy(Request $request, String $id)
    {
        $res = User::where('userid', '=', $id)->first();

        if($res != null) {
            return response()->json([
                ['status' => 'success'],
                $res->delete()
            ], 200);
        }

        return response()->json([
            ['status' => 'not found'],
            ], 404);
    }

    //FIXME: Implement update
    public function update(Request $request, String $id)
    {
        $request->validate([
            'userfname' => 'required|string|max:255',
            'userlname' => 'required|string|max:255',
            'usermiddle' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);
    }
}
