<?php

namespace App\Http\Controllers\Api;

use App\Infrastructure\Services\CryptoService;

use App\Domain\Enums\ErrorContext;
use App\Domain\Exceptions\ImageException;
use App\Domain\Exceptions\NotFoundException;

use App\Http\Controllers\Controller;
use App\Http\Utils\ApiResponse;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

use Exception;

class ImageController extends Controller
{
    public function __construct(private CryptoService $cryptoService) {}

    public function show(string $hash): BinaryFileResponse | JsonResponse
    {
        try {
            
            $path = $this->cryptoService->decryptUrl($hash);

            if (!Storage::disk('public')->exists($path))
            {
                return ApiResponse::error(new NotFoundException(ErrorContext::IMAGE));
            }

            return response()->file(Storage::disk('public')->path($path));

        } catch (Exception $e) {
            return ApiResponse::error(new ImageException(ErrorContext::IMAGE));
        }
    }
}
