<?php

namespace App\Service\Interfaces;

use App\Models\User;
use App\Service\Dto\UserCreateDto;
use App\Service\Dto\UserUpdateDto;
use Illuminate\Support\Collection;

interface UserServiceInterface
{
    public static function resetPassword(string $id): string;

    public static function create(UserCreateDto $userCreateDto): ?User;

    public static function getList(): Collection;

    public static function getDeletedList(): Collection;

    public static function rollbackById(string $id): User;

    public static function getById(string $uuid): User;

    public static function update(UserUpdateDto $userUpdateDto): ?User;

    public static function delete(string $uuid): void;

    public static function deleteDb(string $uuid): void;

    public static function deleteByIds(array $uuids): void;

    public static function deleteByIdsDb(array $uuids): void;

    public static function rollbackByIds(array $ids): array;

    public static function getUserByEmail(string $email): ?User;
}
