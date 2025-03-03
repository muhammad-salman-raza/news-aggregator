<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\DTO\UserDTO;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    #[OA\Post(
        path: "/api/login",
        operationId: "login",
        description: "User login and return JWT token",
        summary: "Login user",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/LoginRequest")
        ),
        tags: ["Authentication"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Successful login",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "token", description: "JWT token", type: "string")
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Invalid credentials"),
            new OA\Response(response: 500, description: "Could not create token")
        ]
    )]
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        return response()->json(compact('token'));
    }

    #[OA\Get(
        path: "/api/user",
        operationId: "getUser",
        description: "Get authenticated user details",
        summary: "Get user details",
        tags: ["User"],
        responses: [
            new OA\Response(
                response: 200,
                description: "User details",
                content: new OA\JsonContent(ref: "#/components/schemas/User")
            ),
            new OA\Response(response: 401, description: "Unauthorized")
        ]
    )]
    public function user()
    {
        $user = auth()->user();
        $user->load('preferredAuthors', 'preferredCategories', 'preferredSources');
        return response()->json($user);
    }

    #[OA\Put(
        path: "/api/user",
        operationId: "updateUser",
        description: "Update user details",
        summary: "Update user",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/UpdateUserRequest")
        ),
        tags: ["User"],
        responses: [
            new OA\Response(response: 200, description: "User updated successfully"),
            new OA\Response(response: 401, description: "Unauthorized"),
            new OA\Response(response: 422, description: "Validation error")
        ]
    )]
    public function update(UpdateUserRequest $request)
    {
        $user = auth()->user();
        $userDTO = UserDTO::createFromArray($request->validated());
        $updatedUser = $this->userRepository->updateUser($user, $userDTO);
        return response()->json(['message' => 'User updated successfully!', 'user' => $updatedUser], 200);
    }
}
