<?php

namespace App\Service;

use App\Models\User;
use App\Service\Dto\UserCreateDto;
use App\Service\Dto\UserUpdateDto;
use App\Service\Interfaces\UserServiceInterface;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Str;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class UserService implements UserServiceInterface
{
    public static function resetPassword(string $id): string
    {
        $password = Str::password(8);
        $user = User::find($id);
        if (is_null($user)) {
            throw new NotFoundHttpException('Пользователь не существует');
        }
        $user->password = $password;
        return $password;
    }

    /**
     * @param UserCreateDto $userCreateDto
     * @return User|null
     */
    public static function create(UserCreateDto $userCreateDto): ?User
    {
        $user = (new User());
        $user->last_name = $userCreateDto->last_name;
        $user->name = $userCreateDto->name;
        $user->middle_name = $userCreateDto->middle_name;
        $user->email = $userCreateDto->email;
        $user->phone = $userCreateDto->phone;
        $user->password = $userCreateDto->password;
        try {
            $user->save();
        } catch (QueryException $e) {
            throw new BadRequestException('Пользователь уже существует', 422);
        }


        return $user;
    }

    /**
     * @return Collection
     */
    public static function getList(): Collection
    {
        return User::all();
    }

    /**
     * @return Collection
     */
    public static function getDeletedList(): Collection
    {
        return User::whereNotNull('deleted_at')->get();
    }

    /**
     * @param string $id
     * @return User
     */
    public static function rollbackById(string $id): User
    {
        $user = User::find($id);
        if (is_null($user)) {
            throw new NotFoundHttpException('Пользователь не существует');
        }

        $user->deeleted_at = null;
        $user->save();

        return $user;
    }


    /**
     * @param string $uuid
     * @return User
     */
    public static function getById(string $uuid): User
    {
        $user = User::find($uuid);
        if (is_null($user)) {
            throw new NotFoundHttpException('Пользователь не существует');
        }
        return $user;
    }

    /**
     * @param UserUpdateDto $userUpdateDto
     * @return User|null
     */
    public static function update(UserUpdateDto $userUpdateDto): ?User
    {
        $user = User::find($userUpdateDto->id);
        if (is_null($user)) {
            throw new NotFoundHttpException('Пользователь не существует');
        }
        $user->last_name = $userUpdateDto->last_name;
        $user->name = $userUpdateDto->name;
        $user->middle_name = $userUpdateDto->middle_name;
        $user->email = $userUpdateDto->email;
        $user->phone = $userUpdateDto->phone;
        $user->password = $userUpdateDto->password;
        $user->save();

        return $user;
    }

    /**
     * @param string $uuid
     * @return void
     */
    public static function delete(string $uuid): void
    {
        $user = User::find($uuid);
        if (is_null($user)) {
            throw new NotFoundHttpException('Пользователь не существует');
        }
        $user->deleted_at = (new \DateTime())->format('Y-m-d H:i:s');
        $user->save();
    }

    /**
     * @param string $uuid
     * @return void
     */
    public static function deleteDb(string $uuid): void
    {
        try {
            User::find($uuid)->delete();
        } catch (QueryException $e) {
            throw new NotFoundHttpException('Пользователь не существует');
        }
    }

    /**
     * @param array $uuids
     * @return void
     * @throws Throwable
     */
    public static function deleteByIds(array $uuids): void
    {
        DB::beginTransaction();
        foreach ($uuids as $uuid) {
            self::delete($uuid);
        }
        DB::commit();
    }

    /**
     * @param array $uuids
     * @return void
     */
    public static function deleteByIdsDb(array $uuids): void
    {
        User::whereIn('id', $uuids)->delete();
    }

    /**
     * @param array $ids
     * @return array
     * @throws Throwable
     */
    public static function rollbackByIds(array $ids): array
    {
        DB::beginTransaction();
        $users = [];
        foreach ($ids as $uuid) {
            $users[] = self::rollbackById($uuid);
        }
        DB::commit();
        return $users;
    }

    public static function getUserByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }
}
