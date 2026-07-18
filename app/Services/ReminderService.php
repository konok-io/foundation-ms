<?php

namespace App\Services;

use App\Models\Member;
use App\Models\MemberNotification;
use App\Models\MonthlyDue;
use Carbon\Carbon;

class ReminderService
{
    public function sendBirthdayReminders()
    {
        $today = Carbon::today();
        $members = Member::active()
            ->whereMonth('date_of_birth', $today->month)
            ->whereDay('date_of_birth', $today->day)
            ->get();

        foreach ($members as $member) {
            MemberNotification::updateOrCreate(
                [
                    'member_id' => $member->id,
                    'type' => 'birthday',
                    'title' => 'Happy Birthday!',
                    'created_at' => $today->startOfDay(),
                ],
                [
                    'message' => "Happy Birthday, {$member->name}! Wishing you a wonderful day filled with joy and blessings.",
                    'is_read' => false,
                    'created_by' => 1,
                ]
            );
        }

        return $members->count();
    }

    public function sendMembershipExpiryReminders()
    {
        $in30Days = Carbon::today()->addDays(30);
        $members = Member::active()
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', $in30Days)
            ->where('expiry_date', '>=', Carbon::today())
            ->get();

        foreach ($members as $member) {
            $daysUntilExpiry = Carbon::today()->diffInDays($member->expiry_date);
            
            MemberNotification::updateOrCreate(
                [
                    'member_id' => $member->id,
                    'type' => 'expiry',
                    'title' => 'Membership Expiry Reminder',
                    'created_at' => Carbon::today()->startOfDay(),
                ],
                [
                    'message' => "Your membership will expire in {$daysUntilExpiry} days (on {$member->expiry_date->format('d M Y')}). Please renew your membership to continue enjoying benefits.",
                    'is_read' => false,
                    'created_by' => 1,
                ]
            );
        }

        return $members->count();
    }

    public function sendDueReminders()
    {
        $today = Carbon::today();
        $members = Member::active()->get();

        $remindedCount = 0;

        foreach ($members as $member) {
            $unpaidDues = MonthlyDue::where('member_id', $member->id)
                ->whereIn('status', ['pending', 'partial'])
                ->where('due_date', '<=', $today)
                ->get();

            if ($unpaidDues->count() > 0) {
                $totalDue = $unpaidDues->sum('amount');
                $totalPaid = $unpaidDues->sum('paid_amount');
                $totalRemaining = $totalDue - $totalPaid;

                MemberNotification::create([
                    'member_id' => $member->id,
                    'title' => 'Monthly Contribution Due Reminder',
                    'message' => "You have {$unpaidDues->count()} unpaid monthly contribution(s) totaling " . number_format($totalRemaining, 2) . " SAR. Please clear your dues at the earliest.",
                    'type' => 'due_reminder',
                    'created_by' => 1,
                ]);

                $remindedCount++;
            }
        }

        return $remindedCount;
    }

    public function sendBulkReminder($memberIds, $title, $message, $type = 'general')
    {
        foreach ($memberIds as $memberId) {
            MemberNotification::create([
                'member_id' => $memberId,
                'title' => $title,
                'message' => $message,
                'type' => $type,
                'created_by' => auth()->id(),
            ]);
        }

        return count($memberIds);
    }

    public function getUnreadCount($memberId = null)
    {
        $query = MemberNotification::where('is_read', false);
        
        if ($memberId) {
            $query->where('member_id', $memberId);
        }

        return $query->count();
    }

    public function getMemberNotifications($memberId, $limit = 10)
    {
        return MemberNotification::where('member_id', $memberId)
            ->latest()
            ->take($limit)
            ->get();
    }
}
