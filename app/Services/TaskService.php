<?php

namespace App\Services;

use App\Exceptions\AppException;
use App\Helpers\PaginationHelper;
use App\Models\Task;
use App\Models\User;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TaskService
{
    public function getList(array $input)
    {
        // try {
        //     return User::query()
        //         ->when(isset($input['order_by']), function ($query) use ($input) {
        //             $query->orderBy($input['order_by'], $input['order_dir'] ?? 'asc');
        //         })
        //         ->paginate($input['per_page'] ?? PaginationHelper::DEFAULT_SIZE);
        // } catch (Exception $e) {
        //     DB::rollBack();

        //     Log::error(
        //         'Something went wrong: Attempt to get user list failed',
        //         [
        //             'message' => $e->getMessage(),
        //             'input'   => $input,
        //         ]
        //     );

        //     throw new AppException('Something went wrong: Attempt to get user list failed');
        // }
    }

    public function create(array $data): Task
    {
        Log::info(' service');
        try {
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

    public function update(User $user, array $data)
    {
        // try {
        //     $user->update($data);

        //     return $user;
        // } catch (Exception $e) {
        //     Log::error(
        //         'Something went wrong: Attempt to update user failed',
        //         [
        //             'message' => $e->getMessage(),
        //             'user_id' => $user->id,
        //             'input'   => $data,
        //         ]
        //     );

        //     throw new AppException('Something went wrong: Attempt to update user failed');
        // }
    }

    /**
     * @param User $user
     * 
     * @return void
     */
    public function delete(User $user): void
    {
        // try {
        //     $user->delete();
        // } catch (Exception $e) {
        //     DB::rollBack();

        //     Log::error(
        //         'Something went wrong: Attempt to delete user failed',
        //         [
        //             'message' => $e->getMessage(),
        //             'user_id' => $user->id,
        //         ]
        //     );

        //     throw new AppException('Something went wrong: Attempt to delete user failed');
        // }
    }
}
