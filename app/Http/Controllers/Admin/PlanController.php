<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PlanController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Plans/Index', [
            'plans' => Plan::latest()->get(),
        ]);
    }
    public function create()
    {
        return Inertia::render('Admin/Plans/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0',
            'monthly_roi_amount' => 'required|numeric|min:0',
            'tenure' => 'required|numeric|min:0',
            'principle_amount' => 'required|numeric|min:0',
            'is_active' => 'required|boolean',
            'allow_to_user' => 'required|boolean',
        ]);

        Plan::create($data);

        return redirect()
            ->route('admin.plans.index')
            ->with('success', 'Plan created successfully');
    }


    public function edit(Plan $plan)
    {
        return Inertia::render('Admin/Plans/Edit', [
            'plan' => $plan,
        ]);
    }

    public function update(Request $request, Plan $plan)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0',
            'monthly_roi_amount' => 'required|numeric|min:0',
            'tenure' => 'required|numeric|min:0',
            'principle_amount' => 'required|numeric|min:0',
            'is_active' => 'required|boolean',
            'allow_to_user' => 'required|boolean',
        ]);

        $plan->update($data);

        return redirect()
            ->route('admin.plans.index')
            ->with('success', 'Plan updated successfully');
    }

    public function destroy(Plan $plan)
    {
        $plan->delete();

        return back()->with('success', 'Plan deleted successfully');
    }
}
