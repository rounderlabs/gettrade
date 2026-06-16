<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RewardController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Rewards/Index', [
            'rewards' => Reward::latest()->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Rewards/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'rank_name' => 'required|string|max:255',
            'matching_leg_business' => 'required|numeric|min:0',
            'reward_amount' => 'required|numeric|min:0',
            'salary_amount' => 'required|numeric|min:0',
            'salary_tenure' => 'required|integer|min:0',
            'reward_text' => 'nullable|string',
        ]);

        Reward::create($data);

        return redirect()
            ->route('admin.rewards.index')
            ->with('success', 'Reward created successfully');
    }

    public function edit(Reward $reward)
    {
        return Inertia::render('Admin/Rewards/Edit', [
            'reward' => $reward,
        ]);
    }

    public function update(Request $request, Reward $reward)
    {
        $data = $request->validate([
            'rank_name' => 'required|string|max:255',
            'matching_leg_business' => 'required|numeric|min:0',
            'reward_amount' => 'required|numeric|min:0',
            'salary_amount' => 'required|numeric|min:0',
            'salary_tenure' => 'required|integer|min:0',
            'reward_text' => 'nullable|string',
        ]);

        $reward->update($data);

        return redirect()
            ->route('admin.rewards.index')
            ->with('success', 'Reward updated successfully');
    }

    public function destroy(Reward $reward)
    {
        $reward->delete();

        return back()->with('success', 'Reward deleted successfully');
    }
}