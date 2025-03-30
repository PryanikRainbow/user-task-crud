<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\CreateTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    public function __construct(private readonly TaskService $service) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        dd('index');
    }

    public function create(CreateTaskRequest $request): JsonResponse
    {
        // Log::info('kjhgghjk');

        return response()->json(
            TaskResource::make($this->service->create([
                "user_id" => $request->input('user_id'),
                ...$request->validated()
            ])),
            Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        dd('show');
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        dd('update');
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function remove(Task $task)
    {
        dd('remove');
        //
    }
}
