<?php

namespace App\Service\Dto;

readonly class UserUpdateDto
{
    public function __construct(
        public string $id,
        public string $last_name,
        public string $name,
        public string $middle_name,
        public string $email,
        public string $phone,
        public string $password,
    ){}
}
