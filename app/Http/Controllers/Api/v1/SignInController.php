<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Auth\SignInRequest;
use App\Services\AuthenticationService;

class SignInController extends Controller
{
    public function __construct(private readonly AuthenticationService $authService)
    {
    }

    public function __invoke(SignInRequest $request)
    {
        $form = $request->validated();

        try {
            $user = $this->authService->logInViaWeb(email: $form["email"], password: $form["password"]);
        } catch (\Exception $e) {
            return response()->json([
                "code" => 401,
                "status" => "UNAUTHORIZED",
                "message" => "Invalid email or password",
                "errors" => [
                    "email" => "Invalid email or password",
                    "password" => "Invalid email or password"
                ]
            ], 401);
        }
    }
}
