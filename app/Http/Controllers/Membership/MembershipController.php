<?php

namespace App\Http\Controllers\Membership;  

use App\Http\Controllers\Controller;
use App\Models\Membership\Member;
use App\Models\Services\Membership;
use App\Models\Services\Subscription;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function index()
    {
        $members = Member::with(['user', 'department'])->paginate(10);
        $memberships = Membership::with(['member.user'])->paginate(10);
        $subscriptions = Subscription::with(['membership.member.user'])->paginate(10);
        
        return view('Membership.index', compact('members', 'memberships', 'subscriptions'));
    }
}
