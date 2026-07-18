<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Income;
use App\Models\Expense;
use App\Models\MonthlyContribution;
use App\Models\EmergencyCollection;
use App\Models\EmergencyCollectionPayment;
use App\Models\Donation;
use App\Models\Member;
use App\Models\GeneralSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function dailyReport(Request $request)
    {
        $this->authorize('reports.view');

        $date = $request->date ?? date('Y-m-d');

        $incomes = Income::whereDate('date', $date)->with('category')->get();
        $expenses = Expense::whereDate('date', $date)->with('category')->get();

        $totalIncome = $incomes->sum('amount');
        $totalExpense = $expenses->sum('amount');

        $data = [
            'title' => 'Daily Report',
            'page_title' => 'Daily Financial Report - ' . date('d M Y', strtotime($date)),
            'date' => $date,
            'incomes' => $incomes,
            'expenses' => $expenses,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'netBalance' => $totalIncome - $totalExpense,
            'settings' => $this->getSettings(),
        ];

        if ($request->has('pdf')) {
            return $this->exportDailyPdf($data);
        }

        return view('admin.reports.daily', $data);
    }

    public function monthlyReport(Request $request)
    {
        $this->authorize('reports.view');

        $year = $request->year ?? date('Y');
        $month = $request->month ?? date('m');

        $startDate = "{$year}-{$month}-01";
        $endDate = date('Y-m-t', strtotime($startDate));

        $incomes = Income::whereBetween('date', [$startDate, $endDate])->with('category')->get();
        $expenses = Expense::whereBetween('date', [$startDate, $endDate])->with('category')->get();

        $incomeByCategory = $incomes->groupBy('category_id')->map(function ($items) {
            return $items->sum('amount');
        });

        $expenseByCategory = $expenses->groupBy('category_id')->map(function ($items) {
            return $items->sum('amount');
        });

        $totalIncome = $incomes->sum('amount');
        $totalExpense = $expenses->sum('amount');

        $data = [
            'title' => 'Monthly Report',
            'page_title' => 'Monthly Report - ' . date('F Y', strtotime($startDate)),
            'year' => $year,
            'month' => $month,
            'monthName' => date('F Y', strtotime($startDate)),
            'incomes' => $incomes,
            'expenses' => $expenses,
            'incomeByCategory' => $incomeByCategory,
            'expenseByCategory' => $expenseByCategory,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'netBalance' => $totalIncome - $totalExpense,
            'settings' => $this->getSettings(),
        ];

        if ($request->has('pdf')) {
            return $this->exportMonthlyPdf($data);
        }

        return view('admin.reports.monthly', $data);
    }

    public function yearlyReport(Request $request)
    {
        $this->authorize('reports.view');

        $year = $request->year ?? date('Y');

        $incomes = Income::whereYear('date', $year)->with('category')->get();
        $expenses = Expense::whereYear('date', $year)->with('category')->get();

        $incomeByMonth = DB::table('incomes')
            ->whereYear('date', $year)
            ->select(DB::raw('MONTH(date) as month'), DB::raw('SUM(amount) as total'))
            ->groupBy(DB::raw('MONTH(date)'))
            ->pluck('total', 'month');

        $expenseByMonth = DB::table('expenses')
            ->whereYear('date', $year)
            ->select(DB::raw('MONTH(date) as month'), DB::raw('SUM(amount) as total'))
            ->groupBy(DB::raw('MONTH(date)'))
            ->pluck('total', 'month');

        $incomeByCategory = $incomes->groupBy('category_id')->map(function ($items) {
            return $items->sum('amount');
        });

        $expenseByCategory = $expenses->groupBy('category_id')->map(function ($items) {
            return $items->sum('amount');
        });

        $totalIncome = $incomes->sum('amount');
        $totalExpense = $expenses->sum('amount');

        $data = [
            'title' => 'Yearly Report',
            'page_title' => 'Yearly Report - ' . $year,
            'year' => $year,
            'incomes' => $incomes,
            'expenses' => $expenses,
            'incomeByMonth' => $incomeByMonth,
            'expenseByMonth' => $expenseByMonth,
            'incomeByCategory' => $incomeByCategory,
            'expenseByCategory' => $expenseByCategory,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'netBalance' => $totalIncome - $totalExpense,
            'settings' => $this->getSettings(),
        ];

        if ($request->has('pdf')) {
            return $this->exportYearlyPdf($data);
        }

        return view('admin.reports.yearly', $data);
    }

    public function memberContributionReport(Request $request)
    {
        $this->authorize('reports.view');

        $year = $request->year ?? date('Y');
        $month = $request->month ?? null;

        $query = MonthlyContribution::with(['member', 'creator']);

        if ($month) {
            $query->where('month', $month)->where('year', $year);
        } else {
            $query->where('year', $year);
        }

        $contributions = $query->orderBy('year', 'desc')->orderBy('month', 'desc')->get();

        $stats = [
            'total' => $contributions->count(),
            'paid' => $contributions->where('status', 'paid')->count(),
            'partial' => $contributions->where('status', 'partial')->count(),
            'unpaid' => $contributions->where('status', 'unpaid')->count(),
            'totalAmount' => $contributions->sum('amount'),
            'totalPaid' => $contributions->whereIn('status', ['paid', 'partial'])->sum('paid_amount'),
            'totalDue' => $contributions->whereIn('status', ['unpaid', 'partial'])->sum('due_amount'),
        ];

        $data = [
            'title' => 'Member Contribution Report',
            'page_title' => 'Member Contribution Report',
            'year' => $year,
            'month' => $month,
            'contributions' => $contributions,
            'stats' => $stats,
            'settings' => $this->getSettings(),
        ];

        if ($request->has('pdf')) {
            return $this->exportContributionPdf($data);
        }

        return view('admin.reports.member-contribution', $data);
    }

    public function emergencyFundReport(Request $request)
    {
        $this->authorize('reports.view');

        $collections = EmergencyCollection::with('payments')->get();

        $stats = [];
        foreach ($collections as $collection) {
            $stats[$collection->id] = [
                'total' => $collection->amount * $collection->activeMembersCount(),
                'collected' => $collection->payments->sum('paid_amount'),
                'due' => ($collection->amount * $collection->activeMembersCount()) - $collection->payments->sum('paid_amount'),
                'paid_count' => $collection->payments->where('status', 'paid')->count(),
                'unpaid_count' => $collection->activeMembersCount() - $collection->payments->where('status', 'paid')->count(),
            ];
        }

        $data = [
            'title' => 'Emergency Fund Report',
            'page_title' => 'Emergency Collection Report',
            'collections' => $collections,
            'stats' => $stats,
            'settings' => $this->getSettings(),
        ];

        if ($request->has('pdf')) {
            return $this->exportEmergencyPdf($data);
        }

        return view('admin.reports.emergency-fund', $data);
    }

    public function donationReport(Request $request)
    {
        $this->authorize('reports.view');

        $dateFrom = $request->date_from ?? date('Y-01-01');
        $dateTo = $request->date_to ?? date('Y-m-d');

        $query = Donation::whereBetween('received_at', [$dateFrom, $dateTo])->with('member');

        if ($request->has('purpose') && $request->purpose) {
            $query->where('purpose', $request->purpose);
        }

        $donations = $query->orderBy('received_at', 'desc')->get();

        $byPurpose = $donations->groupBy('purpose')->map(function ($items) {
            return $items->sum('amount');
        });

        $data = [
            'title' => 'Donation Report',
            'page_title' => 'Donation Report',
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'donations' => $donations,
            'byPurpose' => $byPurpose,
            'totalDonations' => $donations->sum('amount'),
            'totalCount' => $donations->count(),
            'settings' => $this->getSettings(),
        ];

        if ($request->has('pdf')) {
            return $this->exportDonationPdf($data);
        }

        return view('admin.reports.donation', $data);
    }

    public function outstandingDueReport(Request $request)
    {
        $this->authorize('reports.view');

        $members = Member::where('membership_status', 'active')->with('contributions')->get();

        $membersWithDue = $members->map(function ($member) {
            $dueContributions = $member->contributions->filter(function ($c) {
                return $c->status !== 'paid';
            });
            
            return [
                'member' => $member,
                'due_count' => $dueContributions->count(),
                'due_amount' => $dueContributions->sum('due_amount'),
            ];
        })->filter(function ($item) {
            return $item['due_count'] > 0;
        })->sortByDesc('due_amount');

        $totalDue = $membersWithDue->sum('due_amount');

        $data = [
            'title' => 'Outstanding Due Report',
            'page_title' => 'Outstanding Due Report',
            'membersWithDue' => $membersWithDue,
            'totalDue' => $totalDue,
            'memberCount' => $membersWithDue->count(),
            'settings' => $this->getSettings(),
        ];

        if ($request->has('pdf')) {
            return $this->exportOutstandingPdf($data);
        }

        return view('admin.reports.outstanding-due', $data);
    }

    protected function getSettings(): array
    {
        return [
            'site_name' => GeneralSetting::getSetting('site_name', 'Foundation'),
            'site_address' => GeneralSetting::getSetting('address', ''),
            'site_phone' => GeneralSetting::getSetting('phone', ''),
            'site_email' => GeneralSetting::getSetting('email', ''),
        ];
    }

    protected function exportDailyPdf($data)
    {
        $pdf = Pdf::loadView('admin.reports.pdf.daily', $data);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download('Daily-Report-' . $data['date'] . '.pdf');
    }

    protected function exportMonthlyPdf($data)
    {
        $pdf = Pdf::loadView('admin.reports.pdf.monthly', $data);
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->download('Monthly-Report-' . $data['monthName'] . '.pdf');
    }

    protected function exportYearlyPdf($data)
    {
        $pdf = Pdf::loadView('admin.reports.pdf.yearly', $data);
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->download('Yearly-Report-' . $data['year'] . '.pdf');
    }

    protected function exportContributionPdf($data)
    {
        $pdf = Pdf::loadView('admin.reports.pdf.contribution', $data);
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->download('Contribution-Report-' . $data['year'] . '.pdf');
    }

    protected function exportEmergencyPdf($data)
    {
        $pdf = Pdf::loadView('admin.reports.pdf.emergency', $data);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download('Emergency-Fund-Report.pdf');
    }

    protected function exportDonationPdf($data)
    {
        $pdf = Pdf::loadView('admin.reports.pdf.donation', $data);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download('Donation-Report.pdf');
    }

    protected function exportOutstandingPdf($data)
    {
        $pdf = Pdf::loadView('admin.reports.pdf.outstanding', $data);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download('Outstanding-Due-Report.pdf');
    }
}
