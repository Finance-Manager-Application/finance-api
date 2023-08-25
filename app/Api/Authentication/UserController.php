<?php

namespace App\Api\Authentication;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    private $apiToken;
    public function __construct()
    {
        //create token
        $this->apiToken = uniqid(base64_encode(Str::random(40)));
    }
    /** 
     * Register API 
     * 
     * @return \Illuminate\Http\Response 
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $postArray = $request->all();
        $postArray['password'] = bcrypt($postArray['password']);
        $user = User::create($postArray);

        $success['token'] = $this->apiToken;
        $success['full_name'] = $user->first_name . ' ' . $user->last_name;

        return response()->json([
            'status' => 'success',
            'message' => 'User registered successfully.',
            'data' => [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'address' => $user->address,
                'id' => $user->id,
            ],
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $success['token'] = $this->apiToken;
            $success['user'] = [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'address' => $user->address,
                'id' => $user->id,
            ];
    
            return response()->json([
                'status' => 'success',
                'data' => $success
            ]);
        } else {
            $userExists = User::where('email', $request->email)->exists();
            $errorMessage = $userExists ? 'Incorrect email or password' : 'User not found.';
    
            return response()->json([
                'status' => 'error',
                'data' => $errorMessage
            ], 422);
        }
    }    

    public function updateUserDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'first_name' => 'sometimes|required',
            'last_name' => 'sometimes|required',
            'address' => 'sometimes|required',
            'password' => 'sometimes|required',
            'id' => 'sometimes|required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $email = $request->input('email');
        $id = $request->input('id');

        $user = User::where('email', $email)->first();

        if ($id) {
            $user = User::find($id);
        }

        if (!$user) {
            return response()->json(['error' => 'User does not exist.'], 404);
        }

        if ($request->has('first_name')) {
            $user->first_name = $request->input('first_name');
        }

        if ($request->has('last_name')) {
            $user->last_name = $request->input('last_name');
        }

        if ($request->has('address')) {
            $user->address = $request->input('address');
        }

        if ($request->has('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        if ($request->has('email')) {
            // Check if the provided email is already taken by another user
            $existingUser = User::where('email', $request->input('email'))->where('id', '<>', $user->id)->first();
            if ($existingUser) {
                return response()->json(['error' => 'Email is already taken.'], 409);
            }
            $user->email = $request->input('email');
        }

        $user->save();

        $success['token'] = $this->apiToken;
        return response()->json([
            'status' => 'success',
            'message' => 'User details updated successfully.',
            'data' => [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'address' => $user->address,
                'id' => $user->id,
            ],
        ]);
    }
}
