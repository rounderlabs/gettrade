<?php

namespace App\Methods;


use App\Models\BinaryTree;
use App\Models\Tree;
use App\Models\User;

class Downline
{

    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public static function init(User $user): self
    {
        return new static($user);
    }

    public function placeIntoTree(): bool
    {
        if ($this->user->isPlacedIntoTree()) {
            return false;
        }
        $lastChildInTree = $this->findLastChild($this->user->binarySponsor, $this->user->tree->position);

        $this->user->tree()->update([
            'parent_id' => $lastChildInTree->id
        ]);

        $this->user->markUserPlacedIntoTree();

        return true;

    }

    public function findLastChild(User $sponsor, $position): User
    {
        $sponsorUser = $sponsor;
        while (true) {
            $lastDirectChild = BinaryTree::lastChildNode($sponsorUser->id, $position)->first();
            if ($lastDirectChild) {
                $sponsorUser = $lastDirectChild->user;
                continue;
            } else {
                return $sponsorUser;
            }
        }
    }

    public function getLeftCount(bool $active = false, bool $direct = false): int
    {
        if ($active) {
            return $direct ? $this->user->team->active_direct_left : $this->user->team->active_left;
        }
        return $direct ? $this->user->team->direct_left : $this->user->team->left;

    }

    public function getRightCount(bool $active = false, bool $direct = false): int
    {
        if ($active) {
            return $direct ? $this->user->team->active_direct_right : $this->user->team->active_right;
        }
        return $direct ? $this->user->team->direct_right : $this->user->team->right;
    }

    public function getDirectCount(bool $active = false): int
    {
        if ($active) {
            return $this->user->team->active_direct;
        }
        return $this->user->team->direct;
    }

//    public function getDirectMember($position): ?Tree
//    {
//        if (is_null($position)) {
//            return null;
//        }
//        return $this->user->tree->childrens()->with(['sponsor:id,username', 'user.subscriptions' => function ($query) {
//            $query->orderBy('rank_id', 'desc')->first();
//        }, 'user.packageUser.package', 'user.subscriptions.rank:id,name', 'user.team:id,user_id,left,right,active_left,active_right,direct,active_direct'])->wherePosition($position)->first();
//    }

    public function getDirectMember($position): ?Tree
    {
        if (is_null($position)) {
            return null;
        }
        return $this->user->binaryTree->childrens()->with(['user.userBusiness', 'binarySponsor:id,email,name', 'user.team:id,user_id,left,right,active_left,active_right,direct,active_direct'])->wherePosition($position)->first();
    }

}
