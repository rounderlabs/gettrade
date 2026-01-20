<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RankRuleController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Setting/RankRules', [
            'rules' => SiteSetting::get('rank_rules', []),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'rules' => 'required|array',
            'rules.*.rank' => 'required|integer|min:1',
            'rules.*.level1_business' => 'nullable|numeric|min:0',
            'rules.*.required_direct_rank' => 'nullable|integer|min:1',
            'rules.*.required_direct_count' => 'nullable|integer|min:0',
        ]);

        SiteSetting::set('rank_rules', $data['rules']);

        return back()->with('success', 'Rank rules updated');
    }
}
