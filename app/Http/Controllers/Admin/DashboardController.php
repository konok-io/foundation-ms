<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MonthlyDue;
use App\Models\EmergencyCollection;
use App\Models\Donation;
use App\Models\Payment;
use App\Models\Event;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = $this->getStats();
        $charts = $this->getCharts();
        $recentActivity = $this->getRecentActivity();
        $upcomingEvents = Event::upcoming()->take(5)->get();
        $recentMembers = Member::latest()->take(5)->get();
        $monthlyTrend = $this->getMonthlyTrend();
        $bloodGroupStats = $this->getBloodGroupStats();
        $paymentCollection = $this->getPaymentCollection();

        $data = [
            'title' => 'Dashboard',
            'page_title' => 'Dashboard',
            'stats' => $stats,
            'charts' => $charts,
            'recentActivity' => $recentActivity,
            'upcomingEvents' => $upcomingEvents,
            'recentMembers' => $recentMembers,
            'monthlyTrend' => $monthlyTrend,
            'bloodGroupStats' => $bloodGroupStats,
            'paymentCollection' => $paymentCollection,
        ];

        return view('admin.dashboard.index', $data);
    }

    protected function getStats()
    {
        $today = Carbon::today();
        $monthStart = $today->copy()->startOfMonth();
        $monthEnd = $today->copy()->endOfMonth();
        $yearStart = $today->copy()->startOfYear();

        return [
            'total_members' => Member::count(),
            'active_members' => Member::active()->count(),
            'new_members_this_month' => Member::whereBetween('created_at', [$monthStart, $monthEnd])->count(),
            'new_members_this_year' => Member::whereBetween('created_at', [$yearStart, $today])->count(),
            
            'total_monthly_due' => MonthlyDue::whereBetween('due_date', [$monthStart, $monthEnd])->sum('amount'),
            'total_monthly_collected' => MonthlyDue::whereBetween('due_date', [$monthStart, $monthEnd])->where('status', 'paid')->sum('paid_amount'),
            'total_due_pending' => MonthlyDue::whereBetween('due_date', [$monthStart, $monthEnd])->where('status', '!=', 'paid')->sum('amount'),
            
            'total_emergency_collections' => EmergencyCollection::whereBetween('created_at', [$yearStart, $today])->sum('amount'),
            'total_emergency_collected' => EmergencyCollection::whereBetween('created_at', [$yearStart, $today])->where('status', 'completed')->sum('amount'),
            
            'total_donations' => Donation::whereBetween('created_at', [$yearStart, $today])->where('payment_status', 'completed')->sum('amount'),
            'total_donors' => Donation::whereBetween('created_at', [$yearStart, $today])->distinct('donor_email')->count('donor_email'),
            
            'total_events' => Event::count(),
            'upcoming_events' => Event::upcoming()->count(),
            'total_activities' => Activity::count(),
            'completed_activities' => Activity::where('status', 'completed')->count(),
            
            'total_blood_donors' => Member::whereNotNull('blood_group')->where('blood_group', '!=', '')->count(),
        ];
    }

    protected function getCharts()
    {
        $today = Carbon::today();

        // Monthly collection chart data (last 12 months)
        $monthlyCollections = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = $today->copy()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();
            
            $monthlyDues = Payment::where('payment_type', 'monthly_due')
                ->whereBetween('payment_date', [$monthStart, $monthEnd])
                ->where('status', 'completed')
                ->sum('amount');
            
            $emergencyPayments = Payment::where('payment_type', 'emergency')
                ->whereBetween('payment_date', [$monthStart, $monthEnd])
                ->where('status', 'completed')
                ->sum('amount');
            
            $donations = Donation::whereBetween('created_at', [$monthStart, $monthEnd])
                ->where('payment_status', 'completed')
                ->sum('amount');
            
            $monthlyCollections[] = [
                'month' => $month->format('M Y'),
                'monthly_due' => (float) $monthlyDues,
                'emergency' => (float) $emergencyPayments,
                'donations' => (float) $donations,
            ];
        }

        // Member growth chart
        $memberGrowth = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = $today->copy()->subMonths($i);
            $monthEnd = $month->copy()->endOfMonth();
            $memberCount = Member::where('created_at', '<=', $monthEnd)->count();
            $memberGrowth[] = [
                'month' => $month->format('M Y'),
                'count' => (int) $memberCount,
            ];
        }

        // Payment status distribution
        $paymentStatus = [
            'paid' => Payment::where('status', 'completed')->count(),
            'pending' => Payment::where('status', 'pending')->count(),
            'failed' => Payment::where('status', 'failed')->count(),
        ];

        return [
            'monthly_collections' => $monthlyCollections,
            'member_growth' => $memberGrowth,
            'payment_status' => $paymentStatus,
        ];
    }

    protected function getRecentActivity()
    {
        $activities = [];
        
        // Recent payments
        $recentPayments = Payment::with('member')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($payment) {
                return [
                    'type' => 'payment',
                    'icon' => 'bi-cash-stack',
                    'color' => 'success',
                    'message' => ($payment->member?->name ?? 'Unknown') . ' paid ' . number_format($payment->amount, 2) . ' SAR',
                    'time' => $payment->created_at->diffForHumans(),
                ];
            });
        
        // Recent members
        $recentMembers = Member::latest()
            ->take(5)
            ->get()
            ->map(function ($member) {
                return [
                    'type' => 'member',
                    'icon' => 'bi-person-plus',
                    'color' => 'primary',
                    'message' => 'New member: ' . $member->name,
                    'time' => $member->created_at->diffForHumans(),
                ];
            });
        
        // Recent donations
        $recentDonations = Donation::where('payment_status', 'completed')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($donation) {
                return [
                    'type' => 'donation',
                    'icon' => 'bi-heart',
                    'color' => 'danger',
                    'message' => $donation->donor_name . ' donated ' . number_format($donation->amount, 2) . ' SAR',
                    'time' => $donation->created_at->diffForHumans(),
                ];
            });

        return $recentPayments->merge($recentMembers)
            ->merge($recentDonations)
            ->sortByDesc('time')
            ->take(10)
            ->values();
    }

    protected function getMonthlyTrend()
    {
        $today = Carbon::today();
        $data = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = $today->copy()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();
            
            $data[] = [
                'month' => $month->format('M'),
                'income' => (float) Payment::whereBetween('payment_date', [$monthStart, $monthEnd])
                    ->where('status', 'completed')
                    ->sum('amount'),
            ];
        }

        return $data;
    }

    protected function getBloodGroupStats()
    {
        $stats = Member::whereNotNull('blood_group')
            ->where('blood_group', '!=', '')
            ->select('blood_group', DB::raw('count(*) as count'))
            ->groupBy('blood_group')
            ->pluck('count', 'blood_group')
            ->toArray();
        
        // Ensure all blood groups are represented
        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];
        $result = [];
        foreach ($bloodGroups as $bg) {
            $result[$bg] = $stats[$bg] ?? 0;
        }
        
        return $result;
    }

    protected function getPaymentCollection()
    {
        $today = Carbon::today();
        $monthStart = $today->copy()->startOfMonth();
        $monthEnd = $today->copy()->endOfMonth();
        
        return [
            'collected' => (float) Payment::whereBetween('payment_date', [$monthStart, $monthEnd])
                ->where('status', 'completed')
                ->sum('amount'),
            'pending' => (float) Payment::whereBetween('payment_date', [$monthStart, $monthEnd])
                ->where('status', 'pending')
                ->sum('amount'),
        ];
    }

    public function profile()
    {
        $data = [
            'title' => 'Profile',
            'page_title' => 'My Profile',
            'user' => Auth::user(),
        ];

        return view('admin.dashboard.profile', $data);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->password = bcrypt($request->password);
        $user->save();

        return redirect()->back()->with('success', 'Password changed successfully.');
    }
}
