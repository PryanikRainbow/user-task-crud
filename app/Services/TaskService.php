<?php

namespace App\Services;

use App\Exceptions\AppException;
use App\Helpers\PaginationHelper;
use App\Models\Task;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TaskService
{
    public function getList(array $input, int $userId): LengthAwarePaginator
    {

        try {
            return Task::query()
                ->when(isset($input['order_by']), function ($query) use ($input) {
                    $query->orderBy($input['order_by'], $input['order_dir'] ?? 'asc');
                })
                ->where('user_id', $userId)
                ->paginate($input['per_page'] ?? PaginationHelper::DEFAULT_SIZE);
        } catch (Exception $e) {
            Log::error(
                'Something went wrong: Attempt to get user list failed',
                [
                    'message' => $e->getMessage(),
                    'input'   => $input,
                ]
            );

            throw new AppException('Something went wrong: Attempt to get user list failed');
        }
    }

    public function create(array $data): Task
    {
        try {
            if (!isset($data['status'])) {
                $data['status'] = Task::NEW_STATUS;
            }

            $task = new Task($data);
            $task->save();

            return $task;
        } catch (Exception $e) {
            Log::error(
                'Something went wrong: Attempt to create task failed',
                [
                    'message' => $e->getMessage(),
                    'input'   => $data,
                ]
            );

            throw new AppException('Something went wrong: Attempt to create task failed');
        }
    }

    public function update(Task $task, array $data)
    {
        $currentStatus = $task->status;

        if (isset($data['status']) && $data['status'] !== $currentStatus) {
            $newStatus = $data['status'];

            if (!in_array($newStatus, Task::VALID_STATUSES_TO_CHANGES[$currentStatus])) {
                throw new AppException(
                    "Status change from {$currentStatus} to {$newStatus} is not allowed.",
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }
        }

        try {
            $currentStatus = $task->status;

            $task->update($data);

            return $task;
        } catch (Exception $e) {
            Log::error(
                'Something went wrong: Attempt to update task failed',
                [
                    'message' => $e->getMessage(),
                    'task_id' => $task->id,
                    'input'   => $data,
                ]
            );

            throw new AppException('Something went wrong: Attempt to update task failed');
        }
    }

    public function delete(Task $task): void
    {
        if ($task->status !== Task::NEW_STATUS) {
            throw new AppException(
                'Only unprocessed (new) tasks can be deleted',
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        try {
            $task->delete();
        } catch (Exception $e) {
            Log::error(
                'Something went wrong: Attempt to delete task failed',
                [
                    'message' => $e->getMessage(),
                    'task_id' => $task->id,
                ]
            );

            throw new AppException('Something went wrong: Attempt to delete task failed');
        }
    }

    public function removeUnprocessedByUserId(int $userId): void
    {
        try {
            DB::beginTransaction();

            Task::where('user_id', $userId)
                ->where('status', Task::NEW_STATUS)
                ->delete();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            Log::error(
                'Something went wrong: Attempt to delete list of unprocessed tasks failed',
                [
                    'message' => $e->getMessage(),
                    'user_id' => $userId,
                ]
            );

            throw new AppException('Something went wrong: Attempt to delete list of unprocessed tasks failed');
        }
    }

    public function taskStatsByUserId(int $userId): array
    {
        try {
            $statsCollection = Task::where('user_id', $userId)
                ->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->get();

            return $this->formatStatsByStatusDetails($statsCollection);
        } catch (Exception $e) {
            Log::error(
                'Something went wrong: Attempt to get statistics by status task for user failed',
                [
                    'message' => $e->getMessage(),
                    'user_id' => $userId,
                ]
            );

            throw new AppException('Something went wrong: Attempt to get statistics by status task for user failed');
        }
    }

    public function taskStats(): array
    {
        try {
            $statsCollection = Task::query()
                ->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->get();

            return $this->formatStatsByStatusDetails($statsCollection);
        } catch (Exception $e) {
            Log::error(
                'Something went wrong: Attempt to get statistics by status task failed',
                [
                    'message' => $e->getMessage(),
                ]
            );

            throw new AppException('Something went wrong: Attempt to get statistics by status task failed');
        }
    }

    public function formatStatsByStatusDetails(Collection $statsCollection): array
    {
        return [
            [
                'status' => Task::NEW_STATUS,
                'count'  => $statsCollection->firstWhere('status', Task::NEW_STATUS)?->count ?? 0
            ],
            [
                'status' => Task::IN_PROGRESS_STATUS,
                'count'  => $statsCollection->firstWhere('status', Task::IN_PROGRESS_STATUS)?->count ?? 0
            ],
            [
                'status' => Task::FINISHED_STATUS,
                'count'  => $statsCollection->firstWhere('status', Task::FINISHED_STATUS)?->count ?? 0
            ],
            [
                'status' => Task::FAILED_STATUS,
                'count'  => $statsCollection->firstWhere('status', Task::FAILED_STATUS)?->count ?? 0
            ],
        ];
    }
}
