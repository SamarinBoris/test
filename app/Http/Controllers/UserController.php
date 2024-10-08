<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserAuthRequest;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Service\Dto\UserCreateDto;
use App\Service\Dto\UserUpdateDto;
use App\Service\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Whoops\Handler\JsonResponseHandler;

class UserController
{
    public function resetPassword(string $id): JsonResponse
    {
        return response()->json(['password' =>UserService::resetPassword($id)]);
    }
    public function login(UserAuthRequest $request): JsonResponse
    {
        $user = UserService::getUserByEmail($request->get('email'));
        if (empty($user)) {
            return response()->json([
                'Пользователь не найден'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'token' => $user->createToken("UserApiToken")->plainTextToken
        ], 200);
    }

    public function getList(): Collection
    {
        return UserService::getList();
    }

    public function getById(string $id): User
    {
        return UserService::getById($id);
    }

    public function getDeletedList(): Collection
    {
        return UserService::getDeletedList();
    }

    public function delete(string $id): void
    {
        UserService::delete($id);
    }

    public function deleteForever(string $id): void
    {
        UserService::deleteDb($id);
    }

    public function deleteByIds(array $ids): void
    {
        UserService::deleteByIds($ids);
    }

    public function deleteForeverByIds(array $ids): void
    {
        UserService::deleteByIdsDb($ids);
    }

    public function rollbackById(string $id): JsonResponse
    {
        return response()->json(UserService::rollbackById($id));
    }

    public function rollbackByIds(array $ids): JsonResponse
    {
        return response()->json(UserService::rollbackByIds($ids));
    }

    public function create(UserCreateRequest $request): JsonResponse
    {
        $dataUser = $request->only(['last_name', 'name', 'middle_name', 'email', 'phone', 'password']);
        return response()->json(UserService::create(new UserCreateDto(... $dataUser)));
    }

    public function update(string $id, UserUpdateRequest $request): JsonResponse
    {
        $dataUser = $request->only(['last_name', 'name', 'middle_name', 'email', 'phone', 'password']);
        $dataUser['id'] = $id;
        return response()->json(UserService::update(new UserUpdateDto(... $dataUser)));
    }
}
