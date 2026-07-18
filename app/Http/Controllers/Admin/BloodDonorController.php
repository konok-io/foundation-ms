<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;

class BloodDonorController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('blood_donors.view');

        $query = Member::bloodDonors();

        if ($request->has('blood_group') && $request->blood_group) {
            $query->where('blood_group', $request->blood_group);
        }

        if ($request->has('availability') && $request->availability) {
            $query->where('donation_availability', $request->availability);
        }

        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('member_id', 'like', '%' . $request->search . '%')
                  ->orWhere('mobile', 'like', '%' . $request->search . '%');
            });
        }

        $donors = $query->orderBy('name')->paginate(20);

        $stats = [
            'total' => Member::bloodDonors()->count(),
            'available' => Member::where('is_blood_donor', true)
                ->where('donation_availability', 'available')->count(),
        ];

        $byBloodGroup = Member::bloodDonors()
            ->selectRaw('blood_group, COUNT(*) as count')
            ->groupBy('blood_group')
            ->pluck('count', 'blood_group');

        return view('admin.blood-donors.index', [
            'title' => 'Blood Donors',
            'page_title' => 'Blood Donor Management',
            'donors' => $donors,
            'stats' => $stats,
            'byBloodGroup' => $byBloodGroup,
        ]);
    }

    public function publicSearch(Request $request)
    {
        $bloodGroup = $request->blood_group;
        
        $donors = Member::availableDonors()
            ->where('blood_group', $bloodGroup)
            ->select(['name', 'mobile', 'blood_group', 'donation_availability'])
            ->get();

        return view('public.blood-donor-search', [
            'title' => 'Blood Donor Search',
            'bloodGroup' => $bloodGroup,
            'donors' => $donors,
        ]);
    }

    public function updateAvailability(Request $request, Member $member)
    {
        $this->authorize('blood_donors.edit');

        $request->validate([
            'donation_availability' => 'required|in:available,unavailable,temporarily_unavailable',
            'last_donation_date' => 'nullable|date',
        ]);

        $member->update([
            'donation_availability' => $request->donation_availability,
            'last_donation_date' => $request->last_donation_date,
        ]);

        return redirect()->back()->with('success', 'Availability updated successfully.');
    }

    public function toggleDonorStatus(Member $member)
    {
        $this->authorize('blood_donors.edit');

        $member->update([
            'is_blood_donor' => !$member->is_blood_donor,
        ]);

        return redirect()->back()->with('success', 'Blood donor status updated.');
    }
}
