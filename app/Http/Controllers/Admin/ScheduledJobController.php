<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScheduledJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Inertia\Inertia;

class ScheduledJobController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/ScheduledJobs/Index', [
            'jobs' => ScheduledJob::latest()->paginate(20),
            'commands' => config('admin-scheduler.allowed_commands'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'command' => 'required|string',
            'schedule_type' => 'required|in:once,daily,weekly,monthly',
            'run_date' => 'nullable|date',
            'run_time' => 'required',
            'days_of_week' => 'nullable|array',
            'day_of_month' => 'nullable|integer|min:1|max:31',
            'parameters' => 'nullable|array',
        ]);

        ScheduledJob::create([
            ...$data,
            'created_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Job scheduled successfully');
    }

    public function toggle(ScheduledJob $scheduledJob)
    {
        $scheduledJob->update([
            'is_active' => ! $scheduledJob->is_active
        ]);

        return back();
    }

    public function runNow(ScheduledJob $scheduledJob)
    {
        if (! in_array($scheduledJob->command, config('admin-scheduler.allowed_commands'))) {
            abort(403);
        }

        $params = $scheduledJob->parameters ?? [];

        if (
            in_array($scheduledJob->command, [
                'generate:roi',
                'generate:non-working-withdraw',
                'generate:working-withdraw',
            ])
        ) {
            // 🔒 Respect command signature
            if (isset($params['date'])) {
                // already provided
            } else {
                $params['date'] = now()->format('Y-m-d');
            }
        }

        Artisan::call($scheduledJob->command, $params);

        return back()->with('success', 'Job executed immediately');
    }

    public function logs(ScheduledJob $scheduledJob)
    {
        return response()->json(
            $scheduledJob->logs()
                ->latest('ran_at')
                ->paginate(10)
        );
    }

}
