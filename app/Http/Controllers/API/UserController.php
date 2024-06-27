<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Exception;

use App\Helpers\ResponseFormatter;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function fetch(Request $request){
        $user = User::with(['cluster','region','position','division','businessEntity'])->where('id',Auth::user()->id)->first();
        return ResponseFormatter::success([ 'user' => $user, 'message' => 'Data profile user berhasil diambil']);
    }

    public function login(Request $request){
        try {
            if($request->version != '1.0.3')
            {
                return ResponseFormatter::error([
                    'message' => 'Unauthorized'
                ],'Gagal login, Update versi aplikasi SAM anda ke V1.0.3.', 500);
            }
            $request->validate([
                'username' => 'required',
                'password' => 'required',
                'notif_id' => 'required',
            ]);

            $credentials = request(['username', 'password']);
            if (!Auth::attempt($credentials)) {
                return ResponseFormatter::error([
                    'message' => 'Unauthorized'
                ],'Gagal login, cek kembali username dan password anda', 500);
            }

            $user = User::with(['region','cluster','position','division','businessEntity','tm'])->where('username', $request->username)->first();
            if ( !Hash::check($request->password, $user->password, [])) {
                throw new Exception('Invalid Credentials');
            }
            $user->id_notif = $request->notif_id;
            $user->update();

            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ],'Authenticated');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error,
            ],'Authentication Failed', 500);
        }
    }

    public function logout(Request $request){
        $token = $request->user()->currentAccessToken()->delete();
        return ResponseFormatter::success($token,'Token Revoked');
    }

    public function register(Request $request){
        try {
            $request->validate([
                'username' => ['required', 'string', 'min:3','max:255','unique:users'],
                'name' => ['required', 'string'],
                'region_id' => ['required','string'],
                'cluster_id' => ['required'],
                'password' => $this->passwordRules()
            ]);

                User::create([
                    'username' => $request->username,
                    'name' => $request->nama_lengkap,
                    'region_id' => $request->region_id,
                    'cluster_id' => $request->cluster_id,
                    'password' => Hash::make($request->password),
                ]);



            $user = User::where('username', $request->username)->first();

            $tokenResult = $user->createToken('authToken')->plainTextToken;

            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ],'User Registered');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error,
            ],'Authentication Failed', 500);
        }
    }
}
