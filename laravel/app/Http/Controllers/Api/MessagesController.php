<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

use function auth;
use function response;

class MessagesController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function __invoke(): JsonResponse
    {
        $messages = [
            'Hello, world!',
        ];

        /** @var \Tymon\JWTAuth\Payload $payload */
        $payload = auth()->payload();
        $roles = (array) $payload->get('roles');
        if (in_array('admin', $roles)) {
            $messages[] = 'Welcome, admin.';
        }

        return response()->json([
            'messages' => $messages,
        ]);
    }

}
