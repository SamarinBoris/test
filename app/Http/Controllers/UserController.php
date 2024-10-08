<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserAuthRequest;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Responses\ApiSuccessResponse;
use App\Service\Dto\UserCreateDto;
use App\Service\Dto\UserUpdateDto;
use App\Service\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class UserController extends Controller
{
    public function resetPassword(string $id): ApiSuccessResponse
    {
        return new ApiSuccessResponse(['password' => UserService::resetPassword($id)]);
    }

    public function login(UserAuthRequest $request): ApiSuccessResponse
    {
        $user = UserService::getUserByEmail($request->get('email'));
        if (empty($user)) {
            return new ApiSuccessResponse([
                'Пользователь не найден'
            ], 404);
        }

        return new ApiSuccessResponse(
            [
                'status' => true,
                'token' => $user->createToken("UserApiToken")->plainTextToken
            ]
        );
    }

    public function getList(Request $request): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            UserService::getList($request->all())
        );
    }

    public function getById(string $id): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            UserService::getById($id)
        );
    }

    public function getDeletedList(): ApiSuccessResponse
    {
        UserService::getDeletedList();
        return new ApiSuccessResponse(
            'Пользователи удалены'
        );
    }

    public function delete(string $id): ApiSuccessResponse
    {
        UserService::delete($id);
        return new ApiSuccessResponse(
            'Пользователь удален'
        );
    }

    public function deleteForever(string $id): ApiSuccessResponse
    {
        UserService::deleteDb($id);
        return new ApiSuccessResponse(
            'Пользователь удален'
        );
    }

    /**
     * @throws \Throwable
     */
    public function deleteByIds(array $ids): ApiSuccessResponse
    {
        UserService::deleteByIds($ids);
        return new ApiSuccessResponse(
            'Пользователи удалены'
        );
    }

    public function deleteForeverByIds(array $ids): ApiSuccessResponse
    {
        UserService::deleteByIdsDb($ids);
        return new ApiSuccessResponse(
            'Пользователи удалены'
        );
    }

    public function rollbackById(string $id): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            UserService::rollbackById($id)
        );
    }

    /**
     * @throws \Throwable
     */
    public function rollbackByIds(array $ids): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            UserService::rollbackByIds($ids)
        );
    }

    public function create(UserCreateRequest $request): ApiSuccessResponse
    {
        $dataUser = $request->only(['last_name', 'name', 'middle_name', 'email', 'phone', 'password']);
        return new ApiSuccessResponse(
            UserService::create(new UserCreateDto(... $dataUser)),
            Response::HTTP_CREATED
        );

    }

    public function update(string $id, UserUpdateRequest $request): ApiSuccessResponse
    {
        $dataUser = $request->only(['last_name', 'name', 'middle_name', 'email', 'phone', 'password']);
        $dataUser['id'] = $id;
        return new ApiSuccessResponse(
            UserService::update(new UserUpdateDto(... $dataUser))
        );
    }
}
