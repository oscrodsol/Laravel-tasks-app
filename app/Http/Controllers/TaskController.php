<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{

    public function getAllTasks()
    {
        $userId = auth()->user()->id;
        try {
            // $tasks = Task::query()->where('user_id','=',$userId)->get()->toArray();

            $tasks = User::query()->find($userId)->tasks; //One to many 

            return response()->json([
                'success' => true,
                'message' => 'Tasks retrieved successfully',
                'data' => $tasks
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving ' . $exception->getMessage()
            ]);
        }
        return ['Get task with the id ' . $userId];
    }

    public function createTask(Request $request)
    {
        try {
            Log::info("Creating a task");

            $validator = Validator::make($request->all(), [
                'title' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(
                    [
                        "success" => false,
                        "message" => $validator->errors()
                    ],
                    400
                );
            };

            $title = $request->input('title');
            $userId = auth()->user()->id;

            $task = new Task();
            $task->title = $title;
            $task->user_id = $userId;

            $task->save();


            return response()->json(
                [
                    'success' => true,
                    'message' => "Task created"
                ],
                200
            );
        } catch (\Exception $exception) {
            Log::error("Error creating task: " . $exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => "Error creating tasks"
                ],
                500
            );
        }
    }

    public function getTaskById($id)
    {

        $userId = auth()->user()->id;
        try {
            $tasks = Task::query()->findOrFail($userId)->where('user_id', $userId)->where('id', $id)->get()->toArray();

            return response()->json([
                'success' => true,
                'message' => 'Tasks retrieved successfully',
                'data' => $tasks
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving ' . $exception->getMessage()
            ]);
        }
        return ['Get task with the id ' . $id];
    }

    public function deleteTaskById($id)
    {
        $userId = auth()->user()->id;
        try {
            Log::info('Delete task with the id ' . $id);

            $task = Task::find($id)->where('id', $id)->where('user_id', $userId);

            if (!$task) {
                return response()->json([
                    'success' => false,
                    'message' => "The task doesn't exist"
                ], 200);
            }

            $task->delete();

            return response()->json([
                'success' => true,
                'message' => 'Task ' . $id . ' deleted successfully'
            ], 200);
        } catch (\Exception $exception) {
            Log::error('Updating task ' . $exception->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error deleting tasks'
            ], 500);
        }
    }

    public function modifyTaskById(Request $request, $id)
    {

        $userId = auth()->user()->id;
        try {
            Log::info("Updating task");

            // $task = Task::find($id)->where('id','=',$id)->where('user_id','=',$userId);

            $task = Task::query()->where('id', '=', $id)->where('user_id', '=', $userId)->first();

            // dd($task);

            $validator = Validator::make($request->all(), [
                'title' => ['required', 'string'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()
                ]);
            }

            $title = $request->input('title');
            $status = $request->input('status');

            $task->title = $title;
            $task->status = $status;
            // $task->user_id = $userId;

            $task->save();

            return response()->json([
                'success' => true,
                'message' => 'Task ' . $id . ' updated successfully'
            ], 200);
        } catch (\Exception $exception) {
            Log::error('Updating task ' . $exception->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error updating tasks'
            ], 500);
        }
    }

    public function getUserByIdTask($id)
    {
        try {
            $task = Task::query()->find($id);

            $user = $task->user;

            return response()->json([
                'success' => true,
                'message' => 'Tasks retrieved successfully',
                'data' => $user
            ]);
        } catch (\Exception $exception) {
            Log::error('Updating task ' . $exception->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error updating tasks'
            ], 500);
        }
    }
}
