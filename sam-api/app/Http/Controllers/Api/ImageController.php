<?php

namespace App\Http\Controllers\Api;

use App\Domain\Enums\ErrorContext;
use App\Domain\Exceptions\ImageException;
use App\Domain\Exceptions\NotFoundException;

use App\Application\Services\CryptoService;
use App\Http\Controllers\Controller;

use App\Http\Utils\ApiResponse;
use Illuminate\Support\Facades\Storage;

use Exception;

class ImageController extends Controller
{
    public function __construct(private CryptoService $cryptoService) {}

    public function show(string $hash)
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
