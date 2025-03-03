<?php

namespace App\Http\Controllers\API;

use App\DTO\UserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Repositories\UserRepository;
use OpenApi\Attributes as OA;

class RegisterController extends Controller
{
    public function __construct(private UserRepository $userRepository) {}


    #[OA\Post(
        path: "/api/register",
        operationId: "register",
        description: "Register a new user",
        summary: "User registration",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/RegisterRequest")
        ),
        tags: ["Authentication"],
        responses: [
            new OA\Response(
                response: 201,
                description: "User registered successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", description: "Success message", type: "string"),
                        new OA\Property(property: "user", ref: "#/components/schemas/User")
                    ]
                )
            ),
            new OA\Response(response: 422, description: "Validation error")
        ]
    )]
    public function register(RegisterRequest $request)
    {
        $user = $this->userRepository->createUser(
            UserDTO::createFromArray($request->validated())
        );

        return response()->json(['message' => 'User registered successfully!', 'user' => $user], 201);
    }
}
