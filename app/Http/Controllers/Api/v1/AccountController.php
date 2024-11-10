<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Jobs\SendWelcomeEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function signUp(Request $request)
    {
        $rules = [
            'name' => 'string|required',
            'email' => 'nullable|string|email|unique:users,email',
            'password' => 'string|required|min:6',
        ];

        $response = $this->validateWithJSON($request->all(), $rules);

        if ($response === true) {
            try {

                // Create the User
                $user = new User;
                $user->name = $request->name;
                $user->email = $request->email;
                $user->password = bcrypt($request->password);
                $user->save();

                $token = $user->createToken('Token', ['role:user'])->plainTextToken;
                $data = [
                    'token' => $token
                ];
                SendWelcomeEmail::dispatch($user);
                return $this->responseWithSuccess('Registration successful', $data);

            } catch (\Exception $exception) {
                return $this->responseWithError($exception->getMessage());
            }
        }

        return $this->responseWithError('Validation failed', $response);
    }

    public function signIn(Request $request)
    {
        $rules = [
            'email' => 'string|required',
            'password' => 'string|required|min:6'
        ];

        $response = $this->validateWithJSON($request->all(), $rules);

        if ($response === true) {
            try {
                if (Auth::attempt([
                    'email' => $request->email,
                    'password' => $request->password,
                    'id' => [2] //Admin's user id
                ])) {
                    $user = Auth::user();
                    $token = $user->createToken('Token', ['role:admin'])->plainTextToken;

                    return $this->responseWithSuccess('Admin Login successful', ['token' => $token]);
                }else if (Auth::attempt([
                    'email' => $request->email,
                    'password' => $request->password,
                    'id' => [3] //Editor's user id
                ])) {
                    $user = Auth::user();
                    $token = $user->createToken('Token', ['role:editor'])->plainTextToken;

                    return $this->responseWithSuccess('Editor Login successful', ['token' => $token]);
                }else if (Auth::attempt([
                    'email' => $request->email,
                    'password' => $request->password
                ])) {
                    $user = Auth::user();
                    $token = $user->createToken('Token', ['role:user'])->plainTextToken;

                    return $this->responseWithSuccess('Customer Login successful', ['token' => $token]);
                } else {
                    return $this->responseWithError('Invalid Credentials');
                }

            } catch (\Exception $exception) {
                return $this->responseWithError($exception->getMessage());
            }
        }

        return $this->responseWithError('Validation failed', $response);
    }

}
