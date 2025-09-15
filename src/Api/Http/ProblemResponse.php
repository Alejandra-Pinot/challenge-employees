<?php

declare(strict_types=1);

namespace App\Api\Http;

use Symfony\Component\HttpFoundation\JsonResponse;

final class ProblemResponse
{
    /**
     * @param array<string, array<int, string>> $errors
    */
    public static function validation(array $errors, int $status = 422): JsonResponse
    {
        return new JsonResponse([
            'type'   => 'about:blank',
            'title'  => 'Validation failed',
            'status' => $status,
            'errors' => $errors,
        ], $status);
    }

    public static function badRequest(string $detail): JsonResponse
    {
        return new JsonResponse([
            'type'   => 'about:blank',
            'title'  => 'Bad Request',
            'status' => 400,
            'detail' => $detail,
        ], 400);
    }

    public static function notFound(string $detail = 'Not found'): JsonResponse
    {
        return new JsonResponse([
            'type'   => 'about:blank',
            'title'  => 'Not Found',
            'status' => 404,
            'detail' => $detail,
        ], 404);
    }

    public static function conflict(string $detail): JsonResponse
    {
        return new JsonResponse([
            'type'   => 'about:blank',
            'title'  => 'Conflict',
            'status' => 409,
            'detail' => $detail,
        ], 409);
    }
}
