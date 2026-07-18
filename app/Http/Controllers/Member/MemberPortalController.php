<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Http\Requests\MemberProfileUpdateRequest;
use App\Http\Requests\MemberPasswordUpdateRequest;
use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MemberPortalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->member = $this->getMember();
            if (!$this->member) {
                return redirect()->route('home')->with('error', 'No member profile associated with this account.');
            }
            return $next($request);
        });
    }

    protected function getMember()
    {
        $user = Auth::user();
        
        if ($user && $user->member) {
            return $user->member;
        }
        
        if ($user) {
            return Member::where('email', $user->email)
                ->orWhere('mobile', $user->phone)
                ->first();
        }
        
        return null;
    }

    public function dashboard()
    {
        $member = $this->member;

        $data = [
            'title' => 'Member Dashboard',
            'member' => $member,
            'recentNotices' => [],
            'pendingDues' => 0,
            'totalContributions' => 0,
        ];

        return view('member.dashboard', $data);
    }

    public function profile()
    {
        $member = $this->member;

        $data = [
            'title' => 'My Profile',
            'member' => $member,
            'genders' => Member::GENDERS,
            'bloodGroups' => Member::BLOOD_GROUPS,
            'memberTypes' => Member::MEMBER_TYPES,
            'positions' => Member::POSITIONS,
        ];

        return view('member.profile', $data);
    }

    public function profileUpdate(MemberProfileUpdateRequest $request)
    {
        $member = $this->member;

        try {
            $data = $request->only([
                'name', 'name_bn', 'email', 'mobile', 'present_address', 
                'present_address_bn', 'permanent_address', 'permanent_address_bn',
                'emergency_contact_name', 'emergency_contact_phone', 'emergency_contact_relation',
                'nominee_name', 'nominee_relation', 'nominee_phone',
            ]);

            if ($request->hasFile('photo')) {
                if ($member->photo) {
                    Storage::disk('public')->delete($member->photo);
                }
                $data['photo'] = $request->file('photo')->store('members', 'public');
            }

            $member->update($data);

            if ($request->has('email') && $request->email !== Auth::user()->email) {
                Auth::user()->update(['email' => $request->email]);
            }

            Log::info('Member profile updated', ['member_id' => $member->member_id]);

            return redirect()->route('member.profile')->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            Log::error('Member profile update failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to update profile: ' . $e->getMessage());
        }
    }

    public function showChangePassword()
    {
        $data = [
            'title' => 'Change Password',
            'member' => $this->member,
        ];

        return view('member.change-password', $data);
    }

    public function updatePassword(MemberPasswordUpdateRequest $request)
    {
        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('error', 'Current password is incorrect.');
        }

        try {
            $user->update([
                'password' => Hash::make($request->new_password),
            ]);

            Log::info('Member password changed', ['user_id' => $user->id, 'member_id' => $this->member->member_id]);

            return redirect()->route('member.profile')->with('success', 'Password changed successfully.');
        } catch (\Exception $e) {
            Log::error('Password change failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to change password.');
        }
    }

    public function memberCard()
    {
        $member = $this->member;

        $data = [
            'member' => $member,
        ];

        return view('member.card', $data);
    }

    public function downloadCard()
    {
        return redirect()->route('member.card');
    }

    public function payments()
    {
        $member = $this->member;

        $data = [
            'title' => 'My Payments',
            'member' => $member,
            'payments' => [],
        ];

        return view('member.payments', $data);
    }

    public function contributions()
    {
        $member = $this->member;

        $data = [
            'title' => 'My Contributions',
            'member' => $member,
            'contributions' => [],
        ];

        return view('member.contributions', $data);
    }

    public function emergencyCollections()
    {
        $member = $this->member;

        $data = [
            'title' => 'Emergency Collections',
            'member' => $member,
            'collections' => [],
        ];

        return view('member.emergency-collections', $data);
    }

    public function notices()
    {
        $member = $this->member;

        $data = [
            'title' => 'Notices',
            'member' => $member,
            'notices' => [],
        ];

        return view('member.notices', $data);
    }

    public function donations()
    {
        $member = $this->member;

        $data = [
            'title' => 'My Donations',
            'member' => $member,
            'donations' => [],
        ];

        return view('member.donations', $data);
    }
}
