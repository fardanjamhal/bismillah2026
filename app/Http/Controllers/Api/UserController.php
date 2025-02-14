<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\User\UserRepository;
use App\Http\Resources\UserResource;
use App\Traits\JsonResponse;

class UserController extends Controller
{
    use JsonResponse;

    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $users = $this->userRepository->getAllWithParams($request);
            return $this->sendResponse(UserResource::collection($users), 'list of all users', 200);
            
        } catch (\Throwable $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
