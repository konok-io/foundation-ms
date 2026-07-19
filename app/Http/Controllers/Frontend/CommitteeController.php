<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;

class CommitteeController extends Controller
{
    public function index()
    {
        // Get committee members (active members with positions)
        $committeeMembers = Member::where('status', 'active')
            ->whereNotNull('position')
            ->where('position', '!=', 'Member')
            ->orderBy('position', 'asc')
            ->get();

        // Group by position for better display
        $positions = [
            'Chairman' => [],
            'Vice Chairman' => [],
            'Secretary' => [],
            'Joint Secretary' => [],
            'Treasurer' => [],
            'Executive Member' => [],
            'Other' => [],
        ];

        foreach ($committeeMembers as $member) {
            $position = $member->position ?? 'Other';
            
            if (str_contains(strtolower($position), 'chairman') && !str_contains(strtolower($position), 'vice')) {
                $positions['Chairman'][] = $member;
            } elseif (str_contains(strtolower($position), 'vice')) {
                $positions['Vice Chairman'][] = $member;
            } elseif (str_contains(strtolower($position), 'secretary') && str_contains(strtolower($position), 'joint')) {
                $positions['Joint Secretary'][] = $member;
            } elseif (str_contains(strtolower($position), 'secretary')) {
                $positions['Secretary'][] = $member;
            } elseif (str_contains(strtolower($position), 'treasurer')) {
                $positions['Treasurer'][] = $member;
            } elseif (str_contains(strtolower($position), 'executive')) {
                $positions['Executive Member'][] = $member;
            } else {
                $positions['Other'][] = $member;
            }
        }

        // Remove empty positions
        $positions = array_filter($positions);

        return view('frontend.committee.index', [
            'title' => 'Executive Committee',
            'positions' => $positions,
            'committeeMembers' => $committeeMembers,
        ]);
    }

    public function show($id)
    {
        $member = Member::where('id', $id)
            ->where('status', 'active')
            ->firstOrFail();

        return view('frontend.committee.show', [
            'title' => $member->name,
            'member' => $member,
        ]);
    }
}
