<?php

namespace App\Http\Controllers;

use App\Helpers\PaginationHelper;
use App\Http\Requests\Task\CreateTaskRequest;
use App\Http\Requests\Task\GetListTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;

class TaskController extends Controller
{
    public function __construct(
        private readonly TaskService $service,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(GetListTaskRequest $request, int $userId): JsonResponse
    {
        /** @var LengthAwarePaginator $list  */
        $list = $this->service->getList($request->validated(), $userId);

        return response()->json([
            'data' => TaskResource::collection($list),
            'meta' => PaginationHelper::paginationDetails($list),
        ], Response::HTTP_OK);
    }

    public function create(CreateTaskRequest $request, int $userId): JsonResponse
    {
        $task = $this->service->create([
            "user_id" => $userId,
            ...$request->validated()
        ]);

        return response()->json(
            TaskResource::make($task),
            Response::HTTP_CREATED
        );
    }

    public function show(int $userId, int $taskId): JsonResponse
    {
        $task = Task::find($taskId);

        if (!$task) {
            return $this->taskNotFoundResponse();
        }

        if ($task->user_id !== $userId) {
            return $this->taskNotOwnedByUserResponse($taskId, $userId);
        }

        return response()->json(
            TaskResource::make($task),
            Response::HTTP_CREATED
        );
    }

    public function update(UpdateTaskRequest $request, int $userId, int $taskId): JsonResponse
    {
        $task = Task::find($taskId);

        if (!$task) {
            return $this->taskNotFoundResponse();
        }

        if ($task->user_id !== $userId) {
            return $this->taskNotOwnedByUserResponse($taskId, $userId);
        }

        return response()->json(
            TaskResource::make($this->service->update($task, $request->validated())),
            Response::HTTP_OK,
        );
    }

    public function remove(int $userId, int $taskId): JsonResponse
    {
        $task = Task::find($taskId);

        if (!$task) {
            return $this->taskNotFoundResponse();
        }

        if ($task->user_id !== $userId) {
            return $this->taskNotOwnedByUserResponse($taskId, $userId);
        }

        $this->service->delete($task);

        return response()->json([], Response::HTTP_OK,);
    }

    public function removeUnprocessed(int $userId): JsonResponse
    {
        $this->service->removeUnprocessedByUserId($userId);

        return response()->json([], Response::HTTP_OK,);
    }

    public function userTaskStats(int $userId): JsonResponse
    {
        return response()->json(['data' => $this->service->taskStatsByUserId($userId)], Response::HTTP_OK,);
    }

    public function taskStats(): JsonResponse
    {
       return response()->json(['data' => $this->service->taskStats()], Response::HTTP_OK,);
    }

    protected static function taskNotFoundResponse(): JsonResponse
    {
        return response()->json(
            ['message' => "Task not found"],
            Response::HTTP_NOT_FOUND
        );
    }

    protected static function taskNotOwnedByUserResponse(int $taskId, int $userId): JsonResponse
    {
        return response()->json(
            ['message' => "Task #{$taskId} does not belong to user with ID {$userId}"],
            Response::HTTP_FORBIDDEN
        );
    }
}
