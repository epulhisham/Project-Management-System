<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $totalPendingTasks = Task::query()
            ->where('status', 'pending')
            ->count();

        $myPendingTasks = Task::query()
            ->where([
                ['status', 'pending'],
                ['assigned_user_id', $user->id]
            ])
            ->count();

        $totalProgressTasks = Task::query()
            ->where('status', 'in_progress')
            ->count();

        $myProgressTasks = Task::query()
            ->where([
                ['status', 'in_progress'],
                ['assigned_user_id', $user->id]
            ])
            ->count();

        $totalCompletedTasks = Task::query()
            ->where('status', 'completed')
            ->count();

        $myCompletedTasks = Task::query()
            ->where([
                ['status', 'completed'],
                ['assigned_user_id', $user->id]
            ])
            ->count();

        $activeTasks = Task::query()
                ->whereIn('status', ['pending', 'in_progress'])
                ->where('assigned_user_id', $user->id)
                ->limit(10)
                ->get();
        $activeTasks = TaskResource::collection($activeTasks);

        return inertia('Dashboard', [
            'totalPendingTasks' => $totalPendingTasks,
            'myPendingTasks' => $myPendingTasks,
            'totalProgressTasks' => $totalProgressTasks,
            'myProgressTasks' => $myProgressTasks,
            'totalCompletedTasks' => $totalCompletedTasks,
            'myCompletedTasks' => $myCompletedTasks,
            'activeTasks' => $activeTasks
        ]);
    }
}
