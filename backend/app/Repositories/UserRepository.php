<?php

namespace App\Repositories;

use App\DTO\UserDTO;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserRepository
{
    public function createUser(UserDTO $userDTO): User
    {
        $user = User::create([
            'id' => Str::uuid(),
            'name' => $userDTO->name,
            'email' => $userDTO->email,
            'password' => Hash::make($userDTO->password),
        ]);

        $user->preferredAuthors()->attach($userDTO->authors);
        $user->preferredCategories()->attach($userDTO->categories);
        $user->preferredSources()->attach($userDTO->sources);

        return $user;
    }

    public function updateUser(User $user, UserDTO $userDTO): User
    {
        if ($userDTO->name) {
            $user->name = $userDTO->name;
        }

        if ($userDTO->email) {
            $user->email = $userDTO->email;
        }

        if ($userDTO->password) {
            $user->password = Hash::make($userDTO->password);
        }

        $user->preferredAuthors()->sync($userDTO->authors);
        $user->preferredCategories()->sync($userDTO->categories);
        $user->preferredSources()->sync($userDTO->sources);

        $user->save();

        return $user;
    }
}
