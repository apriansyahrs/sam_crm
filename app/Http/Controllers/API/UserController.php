<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Fetch.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetch(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
        ]);

        $user = User::with(['region', 'cluster', 'position', 'division', 'businessEntity', 'tm'])
            ->where('username', $request->username)
            ->first();

        return ResponseFormatter::success([
            'user' => $user,
        ],'Data profile user berhasil diambil');
    }

    /**
     * Login.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function login(Request $request)
    {
        $request->validate([
            /**
             * Version of the app.
             * @var string
             * @example 1.0.3
             */
            'version' => 'required|string',

            /**
             * Username for login.
             * @var string
             * @example adiasm
             */
            'username' => 'required|string',

            /**
             * Password for login.
             * @var string
             * @example complete123
             */
            'password' => 'required|string',

            /**
             * Notification ID for push notifications.
             * @var string
             * @example 68a4636e-c000-4dbf-bff9-c374e4a8c5ff
             */
            'notif_id' => 'required|string',
        ]);

        try {
            // Check version
            if ($request->version !== '1.0.3') {
                return ResponseFormatter::error(null, 'Gagal login, Update versi aplikasi SAM anda ke V1.0.3.', 401);
            }

            // Attempt to log the user in
            $credentials = $request->only(['username', 'password']);
            if (!Auth::attempt($credentials)) {
                return ResponseFormatter::error(null, 'Gagal login, cek kembali username dan password anda', 401);
            }

            $user = User::with(['region', 'cluster', 'position', 'division', 'businessEntity', 'tm'])
                ->where('username', $request->username)
                ->first();

            if (!Hash::check($request->password, $user->password)) {
                return ResponseFormatter::error(null, 'Invalid Credentials', 401);
            }

            // Update notification ID
            $user->update(['id_notif' => $request->notif_id]);

            $tokenResult = $user->createToken('authToken')->plainTextToken;

            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ], 'Authenticated');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error->getMessage(),
            ], 'Authentication Failed', 500);
        }
    }

    /**
     * Logout
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return ResponseFormatter::success(null, 'Token Revoked');
    }

    /**
     * Register new user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function register(Request $request)
    {
        try {
            $request->validate([
                /**
                 * Username for login.
                 * @var string
                 * @example soleh123456
                 */
                'username' => 'required|string|min:3|max:255|unique:users',

                /**
                 * Full name of the user.
                 * @var string
                 * @example Soleh Somad
                 */
                'name' => 'required|string',

                /**
                 * Region of the user.
                 * @var string
                 * @example Jogja
                 */
                'region' => 'required|string',

                /**
                 * Cluster ID.
                 * @var int
                 * @example 1
                 */
                'cluster_id' => 'required|integer',

                /**
                 * Password for login.
                 * @var string
                 * @example janggut123
                 */
                'password' => 'required|string|min:8|confirmed',
            ]);

            // Create the user
            $user = User::create([
                'username' => $request->username,
                'name' => $request->name,
                'region' => $request->region,
                'cluster_id' => $request->cluster_id,
                'password' => Hash::make($request->password),
            ]);

            // Generate token for the newly created user
            $tokenResult = $user->createToken('authToken')->plainTextToken;

            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ], 'User Registered');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error->getMessage(),
            ], 'Registration Failed', 500);
        }
    }
}
