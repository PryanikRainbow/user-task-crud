<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Helpers\PaginationHelper;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\GetListUserRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function __construct(private readonly UserService $service) {}

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function index(GetListUserRequest $request): JsonResponse
    {
        /** @var LengthAwarePaginator $list  */
        $list = $this->service->getList($request->validated());

        return response()->json([
            'data' => UserResource::collection($list),
            'meta' => PaginationHelper::paginationDetails($list),
        ], Response::HTTP_OK);
    }

    public function show(int $userId): JsonResponse
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(
                ['message' => 'User not found'],
                Response::HTTP_NOT_FOUND
            );
        }

        return response()->json(
            UserResource::make($user),
            Response::HTTP_OK,
        );
    }

    public function create(CreateUserRequest $request): JsonResponse
    {
        return response()->json(
            UserResource::make($this->service->create($request->validated())),
            Response::HTTP_CREATED
        );
    }

    public function update(UpdateRequest $request, int $userId): JsonResponse
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(
                ['message' => 'User not found'],
                Response::HTTP_NOT_FOUND
            );
        }

        return response()->json(
            UserResource::make($this->service->update($user, $request->validated())),
            Response::HTTP_OK,
        );
    }

    /**
     * @param Book $book
     *
     * @return JsonResonse
     */
    public function remove(int $userId): JsonResponse
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(
                ['message' => 'User not found'],
                Response::HTTP_NOT_FOUND
            );
        }

        $this->service->delete($user);

        return response()->json([], Response::HTTP_OK,);
    }
}
