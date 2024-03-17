<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;

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

    public function register(Request $request){
        //dd($request->all());
        $validate = Validator::make( $request->all(), [
            'userfname' => ['required','string','max:255'],
            'userlname' => ['required','string','max:255'],
            'usermiddle' => ['required','string','max:255'],
            'email' => ['required','string','email','max:255'],
            'contactno' => ['required','string', 'regex:/\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/'],
            'password' => ['required','string'],
            'roles' => ['required','array'],
            'roles.*.roleid' => ['required', 'integer'],
        ]);

        if($validate->fails()){
            return response()->json([['status' => 'bad request'], $validate->errors()], 400);
        }

        if(User::where('email', $request->email)->first() != null) {
            return response()->json([['status' => 'conflict'], "email is already in use."], 409);
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

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            $user
        ], 201);

    }

    public function index()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Users retrieved successfully',
            User::whereHas('user_roles', function(Builder $query){
                $query->where('rolename', '!=', 'Student');
                $query->where('rolename', '!=', 'Admin');
            })
                ->with('user_roles')
                ->latest('created_at')
                ->get()
        ], 200);
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
        $res = User::where('userid', '=', $id)->with('user_roles')->first();

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
            $res->delete();
            return response()->json([
                ['status' => 'success'],
            ], 200);
        }

        return response()->json([
            ['status' => 'not found'],
            ], 404);
    }

    public function update(Request $request, String $id)
    {
        $user = User::where('userid', '=', $id)->first();

        if($user != null) {
            $validate = Validator::make( $request->all(), [
                'userfname' => ['required','string','max:255'],
                'userlname' => ['required','string','max:255'],
                'usermiddle' => ['required','string','max:255'],
                'email' => ['required','string','email','max:255'],
                'contact_no' => ['required','string'],
                'roles' => ['required','array'],
                'roles.*.roleid' => ['required', 'integer'],
            ]);

            if($validate->fails()){
                return response()->json([['status' => 'bad request'], $validate->errors()], 400);
            }

            if($user->email != $request->email && User::where('email', $request->email)->first() != null) {
                return response()->json([['status' => 'conflict'], "email is already in use."], 409);
            }

            $user->user_roles()->detach();

            $user->update([
                'userfname' => $request->userfname,
                'userlname' => $request->userlname,
                'usermiddle' => $request->usermiddle,
                'contact_no' => $request->contact_no,
                'email' => $request->email,
            ]);

            foreach($request->roles as $role) {
                $user->user_roles()->attach($role);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'User updated successfully',
                $user
            ], 200);

        }

        return response()->json([
            ['status' => 'not found'],
            ], 404);

    }
}
