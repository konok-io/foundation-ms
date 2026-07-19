<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = Member::where('status', 'active');

        // Blood Group Filter
        if ($request->has('blood_group') && $request->blood_group) {
            $query->where('blood_group', $request->blood_group);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('member_id', 'like', '%' . $request->search . '%');
            });
        }

        $members = $query->orderBy('name')->paginate(12);
        
        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];

        return view('frontend.members.index', [
            'title' => 'Members',
            'members' => $members,
            'bloodGroups' => $bloodGroups,
        ]);
    }

    public function show(Member $member)
    {
        if ($member->status !== 'active') {
            abort(404);
        }

        return view('frontend.members.show', [
            'title' => $member->name,
            'member' => $member,
        ]);
    }
}
