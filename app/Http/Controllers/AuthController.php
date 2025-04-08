<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuarios;

class AuthController extends Controller
{
    /**
     * Get a JWT via given credentials.
     *
     * @param  Request  $request
     * @return Response
     */
    private function encryption($data): string {
        // need to be Encrypted
        $simple_string = $data; //"Welcome to GeeksforGeeks\n";
        // Store the cipher method
        $ciphering = "AES-128-CTR";
        // Use OpenSSl Encryption method
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;
        // Non-NULL Initialization Vector for encryption
        $encryption_iv = '1234567891011121';
        // Store the encryption key
        $encryption_key = "Test123...";
        // Use openssl_encrypt() function to encrypt the data
        $encryption = openssl_encrypt($simple_string, $ciphering, $encryption_key, $options, $encryption_iv);
        // Display the encrypted string
        return base64_encode($encryption);
    }

    public function login(Request $request)
    {
        //validate incoming request
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['email', 'password']);

        if (! $token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        //$accessToken = auth()->user()->createToken('authToken')->accessToken;

        return response(['hasValueOne' => $this->encryption('index'), 'hasValueTwo' => $this->encryption($token), 'hasValueThree' => $this->encryption(json_encode(auth()->user())), 'hasValueFour' => $this->encryption('6LccpKIhAAAAAISiRyxLjHIRwkzDyThvTuthoq958L3B0')]);
        //return $this->respondWithToken($token);
    }

    public function logout (Request $request) {
        //$request->user()->currentAccessToken()->delete();
        //var_dump($request->user()); exit;
        Auth::logout();
        $response = ['success' => true, 'message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }
}
