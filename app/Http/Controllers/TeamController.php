<?php

namespace App\Http\Controllers;

use App\Models\Tree;
use App\Models\User;
use App\Methods\UserLevelMethods;
use App\Models\UserLevelStat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class TeamController extends Controller
{
    public function seachDownline(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email']
        ]);
        $downlineUser = User::where('email', strtolower($request->email))->first();
        if (BinaryTree::init($downlineUser)->isBelongToTeam(auth()->user())) {
            $rootUser = $downlineUser;
            return redirect()->route('team.genealogy', [$rootUser->id]);
        }
        return redirect()->back()->with('notification', ['User does not belod to your team', 'danger']);
    }

    public function showDirectPartner()
    {
        return Inertia::render('Network/Direct', [
            'team' => auth()->user()->team
        ]);
    }

    public function getDirectPartners()
    {
        $user = auth()->user();
        $directDownlind = Tree::with(['user.team','user.subscriptions','user.userBusiness'])->where('sponsor_id', $user->id)->simplePaginate(10);
        return response()->json($directDownlind);
    }

    public function showActiveDirectPartner()
    {

        return Inertia::render('Network/DirectActive', [
            'team' => auth()->user()->team
        ]);
    }

    public function getActiveDirectPartners()
    {
        $user = auth()->user();
        $directDownlind = Tree::with(['user.team','user.subscriptions','user.userBusiness'])->where('sponsor_id', $user->id)->whereRelation('user.subscriptions','is_active','=',true)->simplePaginate(10);
        return response()->json($directDownlind);
    }

    public function showActiveTeamByLevel()
    {
        $user = auth()->user();
        return Inertia::render('Network/ActiveTeamByLevel', [
            'team' => auth()->user()->team,
            'allLevel' => UserLevelStat::where('user_id', $user->id)->groupBy('level')->orderBy('level', 'ASC')->get()
        ]);
    }

    public function getActiveTeamByLevel($level = 1)
    {
        $user = auth()->user();
        $userByLevel = UserLevelStat::with(['downlineUser:id,name,email,username,created_at','downlineUser.team','downlineUser.subscriptions','downlineUser.userBusiness'])->where('user_id', $user->id)->where('level', $level)->whereRelation('downlineUser.subscriptions','is_active',true)->simplePaginate(10);
        return response()->json($userByLevel);
    }

    public function showTeamByLevel()
    {
        $user = auth()->user();
        return Inertia::render('Network/TeamByLevel', [
            'team' => auth()->user()->team,
            'allLevel' => UserLevelStat::where('user_id', $user->id)->groupBy('level')->orderBy('level', 'ASC')->get()
        ]);
    }

    public function getTeamByLevel($level = 1)
    {
        $user = auth()->user();
        $userByLevel = UserLevelStat::with(['downlineUser:id,name,email,username,created_at','downlineUser.team','downlineUser.subscriptions','downlineUser.userBusiness','downlineUser.tree.sponsor'])->where('user_id', $user->id)->where('level', $level)->simplePaginate(10);
        return response()->json($userByLevel);
    }

    public function showGenealogy(Request $request, $user = null, $position = null)
    {
        $rootUser = auth()->user();
        if (is_null($user) && is_null($position)) {
            return redirect()->route('team.genealogy.sub', [$rootUser->id, 1]);
        }
        if (is_null($position)) {
            $position = 1;
        }
        if (!is_null($user)) {
            $validator = Validator::make(['user' => $user], [
                'user' => ['required', 'numeric', 'exists:users,id']
            ]);
            if ($validator->fails()) {
                $rootUser = auth()->user();
            } else {
                $downlineUser = User::find($user);
                UserLevelMethods::init($downlineUser)->eachSponsor(function ($parentUser) use (&$downlineUser, &$rootUser) {
                    if (is_null($parentUser)) {
                        return;
                    }
                    if ($parentUser->id == auth()->user()->id) {
                        $rootUser = $downlineUser;
                    }
                });

            }
        }
        $rootUserTree = User::with(['team', 'userBusiness','subscriptions'])->where('id', $rootUser->id)->first();
        $firstLevel1st = $this->getDirectMember($rootUser, $position);
        $firstLevel2nd = $this->getDirectMember($rootUser, $position + 1);
        $firstLevel3rd = $this->getDirectMember($rootUser, $position + 2);
        $secondLevel1st = null;
        $secondLevel2nd = null;
        $secondLevel3rd = null;
        $secondLevel4th = null;
        $secondLevel5th = null;
        $secondLevel6th = null;
        $secondLevel7th = null;
        $secondLevel8th = null;
        $secondLevel9th = null;

        if (!is_null($firstLevel1st)) {
            $secondLevel1st = $this->getDirectMember($firstLevel1st->user, 1);
            $secondLevel2nd = $this->getDirectMember($firstLevel1st->user, 2);
            $secondLevel3rd = $this->getDirectMember($firstLevel1st->user, 3);
        }
        if (!is_null($firstLevel2nd)) {
            $secondLevel4th = $this->getDirectMember($firstLevel2nd->user, 1);
            $secondLevel5th = $this->getDirectMember($firstLevel2nd->user, 2);
            $secondLevel6th = $this->getDirectMember($firstLevel2nd->user, 3);
        }
        if (!is_null($firstLevel3rd)) {
            $secondLevel7th = $this->getDirectMember($firstLevel3rd->user, 1);
            $secondLevel8th = $this->getDirectMember($firstLevel3rd->user, 2);
            $secondLevel9th = $this->getDirectMember($firstLevel3rd->user, 3);
        }

        $genealogy = [
            'root' => $rootUserTree,
            'firstLevel' => [
                $firstLevel1st,
                $firstLevel2nd,
                $firstLevel3rd
            ],
            'secondLevel' => [
                $secondLevel1st,
                $secondLevel2nd,
                $secondLevel3rd,
                $secondLevel4th,
                $secondLevel5th,
                $secondLevel6th,
                $secondLevel7th,
                $secondLevel8th,
                $secondLevel9th,
            ],
        ];
        return Inertia::render('Network/Tree', [
            'tree' => $genealogy
        ]);
    }

    protected function getDirectMember(User $user, $position)
    {
        return Tree::with(['user.team', 'user.userBusiness','user.subscriptions'])->where('sponsor_id', $user->id)->where('position', $position)->first();
    }
}
