<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

readonly class ApiSuccessResponse implements Responsable
{
    /**
     * @param  mixed  $data
     * @param  array  $metadata
     * @param  int  $code
     * @param  array  $headers
     */
    public function __construct(
        private mixed $data,
        private int   $code = ResponseAlias::HTTP_OK,
        private array $headers = []
    ) {}

    /**
     * @param  $request
     * @return JsonResponse
     */
    public function toResponse($request): JsonResponse
    {
        return response()->json(
            [
                'data' => $this->data,
            ],
            $this->code,
            $this->headers
        );
    }
}
