<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

use function auth, response;

class MessagesController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
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
