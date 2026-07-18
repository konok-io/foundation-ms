<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MemberStoreRequest;
use App\Http\Requests\MemberUpdateRequest;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('members.view');

        $query = Member::query()->with('user');

        if ($request->has('search') && $request->search) {
            $query->search($request->search);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status === 'active');
        }

        if ($request->has('member_type') && $request->member_type) {
            $query->where('member_type', $request->member_type);
        }

        if ($request->has('blood_group') && $request->blood_group) {
            $query->where('blood_group', $request->blood_group);
        }

        $members = $query->orderBy('created_at', 'desc')->paginate(15);

        $data = [
            'title' => 'Members',
            'page_title' => 'Member Management',
            'members' => $members,
            'statuses' => Member::STATUSES,
            'memberTypes' => Member::MEMBER_TYPES,
            'bloodGroups' => Member::BLOOD_GROUPS,
            'totalMembers' => Member::count(),
            'activeMembers' => Member::active()->count(),
        ];

        return view('admin.members.index', $data);
    }

    public function create()
    {
        $this->authorize('members.create');

        $data = [
            'title' => 'Add Member',
            'page_title' => 'Create New Member',
            'genders' => Member::GENDERS,
            'bloodGroups' => Member::BLOOD_GROUPS,
            'memberTypes' => Member::MEMBER_TYPES,
            'positions' => Member::POSITIONS,
            'nextMemberId' => Member::generateMemberId(),
        ];

        return view('admin.members.create', $data);
    }

    public function store(MemberStoreRequest $request)
    {
        $this->authorize('members.create');

        try {
            $data = $request->only([
                'name', 'name_bn', 'father_name', 'father_name_bn', 'mother_name', 'mother_name_bn',
                'date_of_birth', 'gender', 'blood_group', 'mobile', 'email', 'national_id',
                'passport_number', 'iqama_number', 'occupation', 'occupation_bn', 'designation',
                'company_name', 'present_address', 'present_address_bn', 'permanent_address',
                'permanent_address_bn', 'emergency_contact_name', 'emergency_contact_phone',
                'emergency_contact_relation', 'join_date', 'member_type', 'position',
                'nominee_name', 'nominee_relation', 'nominee_phone', 'referrer_member_id',
            ]);

            if ($request->hasFile('photo')) {
                $data['photo'] = $request->file('photo')->store('members', 'public');
            }

            $data['status'] = $request->boolean('status');

            $member = Member::create($data);

            Cache::flush();

            Log::info('Member created', ['member_id' => $member->member_id, 'name' => $member->name, 'created_by' => auth()->id()]);

            return redirect()->route('admin.members.index')->with('success', 'Member created successfully. Member ID: ' . $member->member_id);
        } catch (\Exception $e) {
            Log::error('Member creation failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to create member: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Member $member)
    {
        $this->authorize('members.view');

        $data = [
            'title' => 'Member Profile',
            'page_title' => $member->name . ' (' . $member->member_id . ')',
            'member' => $member->load('user'),
            'statuses' => Member::STATUSES,
            'memberTypes' => Member::MEMBER_TYPES,
            'genders' => Member::GENDERS,
            'bloodGroups' => Member::BLOOD_GROUPS,
            'positions' => Member::POSITIONS,
        ];

        return view('admin.members.show', $data);
    }

    public function edit(Member $member)
    {
        $this->authorize('members.edit');

        $data = [
            'title' => 'Edit Member',
            'page_title' => 'Edit: ' . $member->name,
            'member' => $member,
            'genders' => Member::GENDERS,
            'bloodGroups' => Member::BLOOD_GROUPS,
            'memberTypes' => Member::MEMBER_TYPES,
            'positions' => Member::POSITIONS,
        ];

        return view('admin.members.edit', $data);
    }

    public function update(MemberUpdateRequest $request, Member $member)
    {
        $this->authorize('members.edit');

        try {
            $data = $request->only([
                'name', 'name_bn', 'father_name', 'father_name_bn', 'mother_name', 'mother_name_bn',
                'date_of_birth', 'gender', 'blood_group', 'mobile', 'email', 'national_id',
                'passport_number', 'iqama_number', 'occupation', 'occupation_bn', 'designation',
                'company_name', 'present_address', 'present_address_bn', 'permanent_address',
                'permanent_address_bn', 'emergency_contact_name', 'emergency_contact_phone',
                'emergency_contact_relation', 'join_date', 'member_type', 'position',
                'nominee_name', 'nominee_relation', 'nominee_phone', 'referrer_member_id',
            ]);

            if ($request->hasFile('photo')) {
                // Delete old photo
                if ($member->photo) {
                    Storage::disk('public')->delete($member->photo);
                }
                $data['photo'] = $request->file('photo')->store('members', 'public');
            }

            $data['status'] = $request->boolean('status');

            $member->update($data);

            Cache::flush();

            Log::info('Member updated', ['member_id' => $member->member_id, 'updated_by' => auth()->id()]);

            return redirect()->route('admin.members.show', $member)->with('success', 'Member updated successfully.');
        } catch (\Exception $e) {
            Log::error('Member update failed', ['member_id' => $member->member_id, 'error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to update member: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Member $member)
    {
        $this->authorize('members.delete');

        try {
            // Delete photo
            if ($member->photo) {
                Storage::disk('public')->delete($member->photo);
            }

            Log::info('Member deleted', ['member_id' => $member->member_id, 'name' => $member->name, 'deleted_by' => auth()->id()]);

            $member->delete();
            Cache::flush();

            return redirect()->route('admin.members.index')->with('success', 'Member deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Member deletion failed', ['member_id' => $member->member_id, 'error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to delete member.');
        }
    }

    public function card(Member $member)
    {
        $this->authorize('members.card');

        $data = [
            'member' => $member,
        ];

        return view('admin.members.card', $data);
    }

    public function qrCode(Member $member)
    {
        $data = [
            'member' => $member,
        ];

        return view('admin.members.qr', $data);
    }

    public function export(Request $request)
    {
        $this->authorize('members.export');

        $query = Member::query();

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status === 'active');
        }

        if ($request->has('member_type') && $request->member_type) {
            $query->where('member_type', $request->member_type);
        }

        $members = $query->orderBy('member_id')->get();

        return response()->json([
            'success' => true,
            'data' => $members,
            'count' => $members->count(),
        ]);
    }
}
