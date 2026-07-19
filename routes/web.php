<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CmsController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PageController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/page/{slug}', [PageController::class, 'show'])->name('frontend.page');

// Blood Donor Public Search
Route::get('/blood-donors/search', [App\Http\Controllers\Admin\BloodDonorController::class, 'publicSearch'])->name('blood-donors.search');

// Public Events
Route::get('/events', [App\Http\Controllers\Admin\EventController::class, 'publicIndex'])->name('public.events.index');
Route::get('/events/{event}', [App\Http\Controllers\Admin\EventController::class, 'publicShow'])->name('public.events.show');
Route::post('/events/{event}/register', [App\Http\Controllers\Admin\EventController::class, 'register'])->name('public.events.register');

// Alias routes for frontend
Route::get('/frontend/events', [App\Http\Controllers\Admin\EventController::class, 'publicIndex'])->name('frontend.events');
Route::get('/frontend/notices', [App\Http\Controllers\Admin\NoticeController::class, 'publicIndex'])->name('frontend.notices');
Route::get('/frontend/gallery', [App\Http\Controllers\Admin\GalleryController::class, 'publicIndex'])->name('frontend.gallery');
Route::get('/frontend/activities', [App\Http\Controllers\Admin\ActivityController::class, 'publicIndex'])->name('frontend.activities');

// Public Notices
Route::get('/notices', [App\Http\Controllers\Admin\NoticeController::class, 'publicIndex'])->name('public.notices.index');

// Public Gallery
Route::get('/gallery', [App\Http\Controllers\Admin\GalleryController::class, 'publicIndex'])->name('public.gallery.index');
Route::get('/gallery/{album}', [App\Http\Controllers\Admin\GalleryController::class, 'publicShow'])->name('public.gallery.show');

// Public Activities
Route::get('/activities', [App\Http\Controllers\Admin\ActivityController::class, 'publicIndex'])->name('public.activities.index');
Route::get('/activities/{activity}', [App\Http\Controllers\Admin\ActivityController::class, 'publicShow'])->name('public.activities.show');

// Public Members
Route::get('/members', [App\Http\Controllers\Frontend\MemberController::class, 'index'])->name('public.members.index');
Route::get('/members/{member}', [App\Http\Controllers\Frontend\MemberController::class, 'show'])->name('public.members.show');

// Public Committee
Route::get('/committee', [App\Http\Controllers\Frontend\CommitteeController::class, 'index'])->name('public.committee.index');
Route::get('/committee/{id}', [App\Http\Controllers\Frontend\CommitteeController::class, 'show'])->name('public.committee.show');

// QR Verification
Route::get('/verify/member/{code}', [App\Http\Controllers\QRVerificationController::class, 'verify'])->name('verify.member');
Route::get('/verify/payment/{receiptNumber}', [App\Http\Controllers\QRVerificationController::class, 'paymentVerify'])->name('verify.payment');

// Language Switch
Route::get('/language/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'bn'])) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
    }
    return redirect()->back();
})->name('language.switch');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store'])->name('login.store');
    Route::get('forgot-password', [ForgotPasswordController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'store'])->name('password.email');
    Route::get('reset-password/{token}', [ResetPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [ResetPasswordController::class, 'store'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::post('/profile', [DashboardController::class, 'updateProfile'])->name('profile.update');
    Route::post('/change-password', [DashboardController::class, 'changePassword'])->name('change-password');

    // User Management
    Route::middleware('permission:users.view')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    });
    
    Route::middleware('permission:users.create')->group(function () {
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
    });
    
    Route::middleware('permission:users.edit')->group(function () {
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::patch('/users/{user}/status', [UserController::class, 'updateStatus'])->name('users.status');
    });
    
    Route::middleware('permission:users.delete')->group(function () {
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // Role Management
    Route::middleware('permission:roles.view')->group(function () {
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::get('/roles/{role}', [RoleController::class, 'show'])->name('roles.show');
    });
    
    Route::middleware('permission:roles.create')->group(function () {
        Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
        Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    });
    
    Route::middleware('permission:roles.edit')->group(function () {
        Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
        Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
        Route::get('/roles/{role}/permissions', [RoleController::class, 'getPermissions'])->name('roles.permissions');
    });
    
    Route::middleware('permission:roles.delete')->group(function () {
        Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
    });

    // Settings
    Route::middleware('permission:settings.view')->group(function () {
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::get('/settings/activity-logs', [SettingsController::class, 'activityLogs'])->name('settings.activity-logs');
    });
    
    Route::middleware('permission:settings.update')->group(function () {
        Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
        Route::post('/settings/clear-cache', [SettingsController::class, 'clearCache'])->name('settings.clear-cache');
    });

    // Audit Logs
    Route::middleware('permission:audit-logs.view')->group(function () {
        Route::get('/audit-logs', [App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('audit-logs.index');
        Route::get('/audit-logs/export', [App\Http\Controllers\Admin\AuditLogController::class, 'export'])->name('audit-logs.export');
        Route::get('/audit-logs/{log}', [App\Http\Controllers\Admin\AuditLogController::class, 'show'])->name('audit-logs.show');
    });
    Route::middleware('permission:audit-logs.delete')->group(function () {
        Route::delete('/audit-logs/{log}', [App\Http\Controllers\Admin\AuditLogController::class, 'destroy'])->name('audit-logs.destroy');
    });

    // CMS Management
    Route::middleware('permission:settings.cms')->group(function () {
        Route::get('/cms', [CmsController::class, 'index'])->name('cms.index');
        Route::get('/cms/create', [CmsController::class, 'create'])->name('cms.create');
        Route::post('/cms', [CmsController::class, 'store'])->name('cms.store');
        Route::get('/cms/{cms}', [CmsController::class, 'show'])->name('cms.show');
        Route::get('/cms/{cms}/edit', [CmsController::class, 'edit'])->name('cms.edit');
        Route::put('/cms/{cms}', [CmsController::class, 'update'])->name('cms.update');
        Route::delete('/cms/{cms}', [CmsController::class, 'destroy'])->name('cms.destroy');
        Route::post('/cms/{cms}/quick-edit', [CmsController::class, 'quickEdit'])->name('cms.quick-edit');
    });

    // Member Management
    Route::middleware('permission:members.view')->group(function () {
        Route::get('/members', [MemberController::class, 'index'])->name('members.index');
        Route::get('/members/{member}', [MemberController::class, 'show'])->name('members.show');
        Route::get('/members/{member}/card', [MemberController::class, 'card'])->name('members.card');
        Route::get('/members/{member}/qr', [MemberController::class, 'qrCode'])->name('members.qr');
    });

    Route::middleware('permission:members.create')->group(function () {
        Route::get('/members/create', [MemberController::class, 'create'])->name('members.create');
        Route::post('/members', [MemberController::class, 'store'])->name('members.store');
    });

    Route::middleware('permission:members.edit')->group(function () {
        Route::get('/members/{member}/edit', [MemberController::class, 'edit'])->name('members.edit');
        Route::put('/members/{member}', [MemberController::class, 'update'])->name('members.update');
    });

    Route::middleware('permission:members.delete')->group(function () {
        Route::delete('/members/{member}', [MemberController::class, 'destroy'])->name('members.destroy');
    });

    Route::middleware('permission:members.export')->group(function () {
        Route::get('/members/export', [MemberController::class, 'export'])->name('members.export');
    });

    // Contribution Management
    Route::middleware('permission:contributions.view')->group(function () {
        Route::get('/contributions', [App\Http\Controllers\Admin\ContributionController::class, 'index'])->name('contributions.index');
        Route::get('/contributions/{contribution}', [App\Http\Controllers\Admin\ContributionController::class, 'show'])->name('contributions.show');
    });

    Route::middleware('permission:contributions.create')->group(function () {
        Route::get('/contributions/create', [App\Http\Controllers\Admin\ContributionController::class, 'create'])->name('contributions.create');
        Route::post('/contributions', [App\Http\Controllers\Admin\ContributionController::class, 'store'])->name('contributions.store');
        Route::post('/contributions/generate', [App\Http\Controllers\Admin\ContributionController::class, 'generateMonthly'])->name('contributions.generate');
    });

    Route::middleware('permission:contributions.edit')->group(function () {
        Route::get('/contributions/{contribution}/edit', [App\Http\Controllers\Admin\ContributionController::class, 'edit'])->name('contributions.edit');
        Route::put('/contributions/{contribution}', [App\Http\Controllers\Admin\ContributionController::class, 'update'])->name('contributions.update');
        Route::post('/contributions/{contribution}/payment', [App\Http\Controllers\Admin\ContributionController::class, 'recordPayment'])->name('contributions.record-payment');
        Route::post('/contributions/mark-overdue', [App\Http\Controllers\Admin\ContributionController::class, 'markOverdue'])->name('contributions.mark-overdue');
        Route::post('/contributions/bulk-payment', [App\Http\Controllers\Admin\ContributionController::class, 'bulkPayment'])->name('contributions.bulk-payment');
    });

    Route::middleware('permission:contributions.delete')->group(function () {
        Route::delete('/contributions/{contribution}', [App\Http\Controllers\Admin\ContributionController::class, 'destroy'])->name('contributions.destroy');
    });

    // Emergency Collection Management
    Route::middleware('permission:emergency_collections.view')->group(function () {
        Route::get('/emergency-collections', [App\Http\Controllers\Admin\EmergencyCollectionController::class, 'index'])->name('emergency-collections.index');
        Route::get('/emergency-collections/{emergencyCollection}', [App\Http\Controllers\Admin\EmergencyCollectionController::class, 'show'])->name('emergency-collections.show');
    });

    Route::middleware('permission:emergency_collections.create')->group(function () {
        Route::get('/emergency-collections/create', [App\Http\Controllers\Admin\EmergencyCollectionController::class, 'create'])->name('emergency-collections.create');
        Route::post('/emergency-collections', [App\Http\Controllers\Admin\EmergencyCollectionController::class, 'store'])->name('emergency-collections.store');
    });

    Route::middleware('permission:emergency_collections.edit')->group(function () {
        Route::get('/emergency-collections/{emergencyCollection}/edit', [App\Http\Controllers\Admin\EmergencyCollectionController::class, 'edit'])->name('emergency-collections.edit');
        Route::put('/emergency-collections/{emergencyCollection}', [App\Http\Controllers\Admin\EmergencyCollectionController::class, 'update'])->name('emergency-collections.update');
        Route::post('/emergency-collections/{emergencyCollection}/assign-members', [App\Http\Controllers\Admin\EmergencyCollectionController::class, 'assignMembers'])->name('emergency-collections.assign-members');
        Route::post('/emergency-collections/{emergencyCollection}/close', [App\Http\Controllers\Admin\EmergencyCollectionController::class, 'closeCollection'])->name('emergency-collections.close-collection');
        Route::post('/emergency-collections/{payment}/record-payment', [App\Http\Controllers\Admin\EmergencyCollectionController::class, 'recordPayment'])->name('emergency-collections.record-payment');
        Route::post('/emergency-collections/bulk-payment', [App\Http\Controllers\Admin\EmergencyCollectionController::class, 'bulkPayment'])->name('emergency-collections.bulk-payment');
    });

    Route::middleware('permission:emergency_collections.delete')->group(function () {
        Route::delete('/emergency-collections/{emergencyCollection}', [App\Http\Controllers\Admin\EmergencyCollectionController::class, 'destroy'])->name('emergency-collections.destroy');
    });

    // Payment Management
    Route::middleware('permission:payments.view')->group(function () {
        Route::get('/payments', [App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('payments.index');
        Route::get('/payments/{payment}', [App\Http\Controllers\Admin\PaymentController::class, 'show'])->name('payments.show');
    });

    Route::middleware('permission:payments.refund')->group(function () {
        Route::post('/payments/{payment}/refund', [App\Http\Controllers\Admin\PaymentController::class, 'refund'])->name('payments.refund');
    });

    Route::middleware('permission:payments.export')->group(function () {
        Route::get('/payments/export', [App\Http\Controllers\Admin\PaymentController::class, 'export'])->name('payments.export');
    });

    // Payment Webhooks
    Route::post('/webhooks/stripe', [App\Http\Controllers\Admin\PaymentController::class, 'webhookStripe'])->name('webhooks.stripe');
    Route::post('/webhooks/paypal', [App\Http\Controllers\Admin\PaymentController::class, 'webhookPaypal'])->name('webhooks.paypal');

    // Receipt Management
    Route::middleware('permission:receipts.view')->group(function () {
        Route::get('/receipts', [App\Http\Controllers\Admin\ReceiptController::class, 'index'])->name('receipts.index');
        Route::get('/receipts/{receipt}', [App\Http\Controllers\Admin\ReceiptController::class, 'show'])->name('receipts.show');
    });

    Route::middleware('permission:receipts.download')->group(function () {
        Route::get('/receipts/{receipt}/download', [App\Http\Controllers\Admin\ReceiptController::class, 'download'])->name('receipts.download');
        Route::get('/receipts/{receipt}/print', [App\Http\Controllers\Admin\ReceiptController::class, 'print'])->name('receipts.print');
    });

    Route::middleware('permission:receipts.email')->group(function () {
        Route::post('/receipts/{receipt}/email', [App\Http\Controllers\Admin\ReceiptController::class, 'email'])->name('receipts.email');
        Route::post('/receipts/bulk-email', [App\Http\Controllers\Admin\ReceiptController::class, 'bulkEmail'])->name('receipts.bulk-email');
    });

    Route::middleware('permission:receipts.export')->group(function () {
        Route::get('/receipts/export', [App\Http\Controllers\Admin\ReceiptController::class, 'export'])->name('receipts.export');
    });

    // Donation Management
    Route::middleware('permission:donations.view')->group(function () {
        Route::get('/donations', [App\Http\Controllers\Admin\DonationController::class, 'index'])->name('donations.index');
        Route::get('/donations/{donation}', [App\Http\Controllers\Admin\DonationController::class, 'show'])->name('donations.show');
    });

    Route::middleware('permission:donations.create')->group(function () {
        Route::get('/donations/create', [App\Http\Controllers\Admin\DonationController::class, 'create'])->name('donations.create');
        Route::post('/donations', [App\Http\Controllers\Admin\DonationController::class, 'store'])->name('donations.store');
    });

    Route::middleware('permission:donations.edit')->group(function () {
        Route::get('/donations/{donation}/edit', [App\Http\Controllers\Admin\DonationController::class, 'edit'])->name('donations.edit');
        Route::put('/donations/{donation}', [App\Http\Controllers\Admin\DonationController::class, 'update'])->name('donations.update');
    });

    Route::middleware('permission:donations.delete')->group(function () {
        Route::delete('/donations/{donation}', [App\Http\Controllers\Admin\DonationController::class, 'destroy'])->name('donations.destroy');
    });

    Route::middleware('permission:donations.export')->group(function () {
        Route::get('/donations/export', [App\Http\Controllers\Admin\DonationController::class, 'export'])->name('donations.export');
    });

    // Accounting - Income Categories
    Route::middleware('permission:income_categories.view')->group(function () {
        Route::get('/accounting/income-categories', [App\Http\Controllers\Admin\IncomeCategoryController::class, 'index'])->name('income-categories.index');
    });
    Route::middleware('permission:income_categories.create')->group(function () {
        Route::post('/accounting/income-categories', [App\Http\Controllers\Admin\IncomeCategoryController::class, 'store'])->name('income-categories.store');
    });
    Route::middleware('permission:income_categories.edit')->group(function () {
        Route::put('/accounting/income-categories/{category}', [App\Http\Controllers\Admin\IncomeCategoryController::class, 'update'])->name('income-categories.update');
        Route::get('/accounting/income-categories/{category}/toggle', [App\Http\Controllers\Admin\IncomeCategoryController::class, 'toggleStatus'])->name('income-categories.toggle');
    });
    Route::middleware('permission:income_categories.delete')->group(function () {
        Route::delete('/accounting/income-categories/{category}', [App\Http\Controllers\Admin\IncomeCategoryController::class, 'destroy'])->name('income-categories.destroy');
    });

    // Accounting - Expense Categories
    Route::middleware('permission:expense_categories.view')->group(function () {
        Route::get('/accounting/expense-categories', [App\Http\Controllers\Admin\ExpenseCategoryController::class, 'index'])->name('expense-categories.index');
    });
    Route::middleware('permission:expense_categories.create')->group(function () {
        Route::post('/accounting/expense-categories', [App\Http\Controllers\Admin\ExpenseCategoryController::class, 'store'])->name('expense-categories.store');
    });
    Route::middleware('permission:expense_categories.edit')->group(function () {
        Route::put('/accounting/expense-categories/{category}', [App\Http\Controllers\Admin\ExpenseCategoryController::class, 'update'])->name('expense-categories.update');
        Route::get('/accounting/expense-categories/{category}/toggle', [App\Http\Controllers\Admin\ExpenseCategoryController::class, 'toggleStatus'])->name('expense-categories.toggle');
    });
    Route::middleware('permission:expense_categories.delete')->group(function () {
        Route::delete('/accounting/expense-categories/{category}', [App\Http\Controllers\Admin\ExpenseCategoryController::class, 'destroy'])->name('expense-categories.destroy');
    });

    // Accounting - Income
    Route::middleware('permission:incomes.view')->group(function () {
        Route::get('/accounting/incomes', [App\Http\Controllers\Admin\IncomeController::class, 'index'])->name('incomes.index');
        Route::get('/accounting/incomes/{income}', [App\Http\Controllers\Admin\IncomeController::class, 'show'])->name('incomes.show');
    });
    Route::middleware('permission:incomes.create')->group(function () {
        Route::get('/accounting/incomes/create', [App\Http\Controllers\Admin\IncomeController::class, 'create'])->name('incomes.create');
        Route::post('/accounting/incomes', [App\Http\Controllers\Admin\IncomeController::class, 'store'])->name('incomes.store');
    });
    Route::middleware('permission:incomes.edit')->group(function () {
        Route::get('/accounting/incomes/{income}/edit', [App\Http\Controllers\Admin\IncomeController::class, 'edit'])->name('incomes.edit');
        Route::put('/accounting/incomes/{income}', [App\Http\Controllers\Admin\IncomeController::class, 'update'])->name('incomes.update');
    });
    Route::middleware('permission:incomes.delete')->group(function () {
        Route::delete('/accounting/incomes/{income}', [App\Http\Controllers\Admin\IncomeController::class, 'destroy'])->name('incomes.destroy');
    });

    // Accounting - Expense
    Route::middleware('permission:expenses.view')->group(function () {
        Route::get('/accounting/expenses', [App\Http\Controllers\Admin\ExpenseController::class, 'index'])->name('expenses.index');
        Route::get('/accounting/expenses/{expense}', [App\Http\Controllers\Admin\ExpenseController::class, 'show'])->name('expenses.show');
    });
    Route::middleware('permission:expenses.create')->group(function () {
        Route::get('/accounting/expenses/create', [App\Http\Controllers\Admin\ExpenseController::class, 'create'])->name('expenses.create');
        Route::post('/accounting/expenses', [App\Http\Controllers\Admin\ExpenseController::class, 'store'])->name('expenses.store');
    });
    Route::middleware('permission:expenses.edit')->group(function () {
        Route::get('/accounting/expenses/{expense}/edit', [App\Http\Controllers\Admin\ExpenseController::class, 'edit'])->name('expenses.edit');
        Route::put('/accounting/expenses/{expense}', [App\Http\Controllers\Admin\ExpenseController::class, 'update'])->name('expenses.update');
    });
    Route::middleware('permission:expenses.delete')->group(function () {
        Route::delete('/accounting/expenses/{expense}', [App\Http\Controllers\Admin\ExpenseController::class, 'destroy'])->name('expenses.destroy');
    });

    // Accounting - Ledger
    Route::middleware('permission:ledger.view')->group(function () {
        Route::get('/accounting/ledger', [App\Http\Controllers\Admin\LedgerController::class, 'index'])->name('ledger.index');
        Route::get('/accounting/ledger/{type}/{id}', [App\Http\Controllers\Admin\LedgerController::class, 'accountLedger'])->name('ledger.account');
    });
    Route::middleware('permission:ledger.export')->group(function () {
        Route::get('/accounting/ledger/export', [App\Http\Controllers\Admin\LedgerController::class, 'export'])->name('ledger.export');
    });

    // Accounting - Reports
    Route::middleware('permission:reports.view')->group(function () {
        Route::get('/accounting/reports/income-statement', [App\Http\Controllers\Admin\LedgerController::class, 'incomeStatement'])->name('reports.income-statement');
    });

    // Financial Reports
    Route::middleware('permission:reports.view')->group(function () {
        Route::get('/reports/daily', [App\Http\Controllers\Admin\ReportController::class, 'dailyReport'])->name('reports.daily');
        Route::get('/reports/monthly', [App\Http\Controllers\Admin\ReportController::class, 'monthlyReport'])->name('reports.monthly');
        Route::get('/reports/yearly', [App\Http\Controllers\Admin\ReportController::class, 'yearlyReport'])->name('reports.yearly');
        Route::get('/reports/member-contribution', [App\Http\Controllers\Admin\ReportController::class, 'memberContributionReport'])->name('reports.member-contribution');
        Route::get('/reports/emergency-fund', [App\Http\Controllers\Admin\ReportController::class, 'emergencyFundReport'])->name('reports.emergency-fund');
        Route::get('/reports/donation', [App\Http\Controllers\Admin\ReportController::class, 'donationReport'])->name('reports.donation');
        Route::get('/reports/outstanding-due', [App\Http\Controllers\Admin\ReportController::class, 'outstandingDueReport'])->name('reports.outstanding-due');
    });

    // Blood Donors
    Route::middleware('permission:blood_donors.view')->group(function () {
        Route::get('/blood-donors', [App\Http\Controllers\Admin\BloodDonorController::class, 'index'])->name('blood-donors.index');
    });
    Route::middleware('permission:blood_donors.edit')->group(function () {
        Route::post('/blood-donors/{member}/availability', [App\Http\Controllers\Admin\BloodDonorController::class, 'updateAvailability'])->name('blood-donors.update-availability');
        Route::get('/blood-donors/{member}/toggle', [App\Http\Controllers\Admin\BloodDonorController::class, 'toggleDonorStatus'])->name('blood-donors.toggle');
    });

    // Events
    Route::middleware('permission:events.view')->group(function () {
        Route::get('/events', [App\Http\Controllers\Admin\EventController::class, 'index'])->name('events.index');
        Route::get('/events/{event}', [App\Http\Controllers\Admin\EventController::class, 'show'])->name('events.show');
    });
    Route::middleware('permission:events.create')->group(function () {
        Route::get('/events/create', [App\Http\Controllers\Admin\EventController::class, 'create'])->name('events.create');
        Route::post('/events', [App\Http\Controllers\Admin\EventController::class, 'store'])->name('events.store');
    });
    Route::middleware('permission:events.edit')->group(function () {
        Route::get('/events/{event}/edit', [App\Http\Controllers\Admin\EventController::class, 'edit'])->name('events.edit');
        Route::put('/events/{event}', [App\Http\Controllers\Admin\EventController::class, 'update'])->name('events.update');
        Route::get('/events/{event}/toggle', [App\Http\Controllers\Admin\EventController::class, 'toggleStatus'])->name('events.toggle');
        Route::put('/events/{event}/registrations/{registration}', [App\Http\Controllers\Admin\EventController::class, 'updateRegistration'])->name('events.registration.update');
    });
    Route::middleware('permission:events.delete')->group(function () {
        Route::delete('/events/{event}', [App\Http\Controllers\Admin\EventController::class, 'destroy'])->name('events.destroy');
    });

    // Notices
    Route::middleware('permission:notices.view')->group(function () {
        Route::get('/notices', [App\Http\Controllers\Admin\NoticeController::class, 'index'])->name('notices.index');
        Route::get('/notices/{notice}', [App\Http\Controllers\Admin\NoticeController::class, 'show'])->name('notices.show');
    });
    Route::middleware('permission:notices.create')->group(function () {
        Route::get('/notices/create', [App\Http\Controllers\Admin\NoticeController::class, 'create'])->name('notices.create');
        Route::post('/notices', [App\Http\Controllers\Admin\NoticeController::class, 'store'])->name('notices.store');
    });
    Route::middleware('permission:notices.edit')->group(function () {
        Route::get('/notices/{notice}/edit', [App\Http\Controllers\Admin\NoticeController::class, 'edit'])->name('notices.edit');
        Route::put('/notices/{notice}', [App\Http\Controllers\Admin\NoticeController::class, 'update'])->name('notices.update');
        Route::get('/notices/{notice}/toggle', [App\Http\Controllers\Admin\NoticeController::class, 'toggleStatus'])->name('notices.toggle');
    });
    Route::middleware('permission:notices.delete')->group(function () {
        Route::delete('/notices/{notice}', [App\Http\Controllers\Admin\NoticeController::class, 'destroy'])->name('notices.destroy');
    });

    // Documents
    Route::middleware('permission:documents.view')->group(function () {
        Route::get('/documents', [App\Http\Controllers\Admin\DocumentController::class, 'index'])->name('documents.index');
        Route::get('/documents/{document}', [App\Http\Controllers\Admin\DocumentController::class, 'show'])->name('documents.show');
        Route::get('/documents/{document}/download', [App\Http\Controllers\Admin\DocumentController::class, 'download'])->name('documents.download');
        Route::get('/members/{member}/documents', [App\Http\Controllers\Admin\DocumentController::class, 'memberDocuments'])->name('documents.member');
    });
    Route::middleware('permission:documents.create')->group(function () {
        Route::get('/members/{member}/documents/create', [App\Http\Controllers\Admin\DocumentController::class, 'create'])->name('documents.create');
        Route::post('/members/{member}/documents', [App\Http\Controllers\Admin\DocumentController::class, 'store'])->name('documents.store');
    });
    Route::middleware('permission:documents.verify')->group(function () {
        Route::get('/documents/{document}/verify', [App\Http\Controllers\Admin\DocumentController::class, 'verify'])->name('documents.verify');
    });
    Route::middleware('permission:documents.delete')->group(function () {
        Route::delete('/documents/{document}', [App\Http\Controllers\Admin\DocumentController::class, 'destroy'])->name('documents.destroy');
    });

    // Gallery
    Route::middleware('permission:gallery.manage')->group(function () {
        Route::get('/gallery', [App\Http\Controllers\Admin\GalleryController::class, 'index'])->name('gallery.index');
        Route::get('/gallery/create', [App\Http\Controllers\Admin\GalleryController::class, 'create'])->name('gallery.create');
        Route::post('/gallery', [App\Http\Controllers\Admin\GalleryController::class, 'store'])->name('gallery.store');
        Route::get('/gallery/{album}', [App\Http\Controllers\Admin\GalleryController::class, 'show'])->name('gallery.show');
        Route::get('/gallery/{album}/edit', [App\Http\Controllers\Admin\GalleryController::class, 'edit'])->name('gallery.edit');
        Route::put('/gallery/{album}', [App\Http\Controllers\Admin\GalleryController::class, 'update'])->name('gallery.update');
        Route::delete('/gallery/{album}', [App\Http\Controllers\Admin\GalleryController::class, 'destroy'])->name('gallery.destroy');
        Route::get('/gallery/{album}/toggle', [App\Http\Controllers\Admin\GalleryController::class, 'toggleStatus'])->name('gallery.toggle');
        Route::post('/gallery/{album}/upload', [App\Http\Controllers\Admin\GalleryController::class, 'uploadImages'])->name('gallery.upload');
        Route::post('/gallery/{album}/video', [App\Http\Controllers\Admin\GalleryController::class, 'addVideo'])->name('gallery.add-video');
        Route::delete('/gallery/images/{image}', [App\Http\Controllers\Admin\GalleryController::class, 'deleteImage'])->name('gallery.delete-image');
        Route::get('/gallery/images/{image}/featured', [App\Http\Controllers\Admin\GalleryController::class, 'toggleFeatured'])->name('gallery.toggle-featured');
    });

    // Activities
    Route::middleware('permission:activities.view')->group(function () {
        Route::get('/activities', [App\Http\Controllers\Admin\ActivityController::class, 'index'])->name('activities.index');
        Route::get('/activities/{activity}', [App\Http\Controllers\Admin\ActivityController::class, 'show'])->name('activities.show');
    });
    Route::middleware('permission:activities.create')->group(function () {
        Route::get('/activities/create', [App\Http\Controllers\Admin\ActivityController::class, 'create'])->name('activities.create');
        Route::post('/activities', [App\Http\Controllers\Admin\ActivityController::class, 'store'])->name('activities.store');
    });
    Route::middleware('permission:activities.edit')->group(function () {
        Route::get('/activities/{activity}/edit', [App\Http\Controllers\Admin\ActivityController::class, 'edit'])->name('activities.edit');
        Route::put('/activities/{activity}', [App\Http\Controllers\Admin\ActivityController::class, 'update'])->name('activities.update');
        Route::post('/activities/{activity}/status', [App\Http\Controllers\Admin\ActivityController::class, 'updateStatus'])->name('activities.status');
    });
    Route::middleware('permission:activities.delete')->group(function () {
        Route::delete('/activities/{activity}', [App\Http\Controllers\Admin\ActivityController::class, 'destroy'])->name('activities.destroy');
    });

    // Member Notifications
    Route::middleware('permission:notifications.view')->group(function () {
        Route::get('/notifications', [App\Http\Controllers\Admin\MemberNotificationController::class, 'index'])->name('notifications.index');
        Route::get('/notifications/{notification}/read', [App\Http\Controllers\Admin\MemberNotificationController::class, 'markAsRead'])->name('notifications.mark-read');
        Route::get('/notifications/mark-all-read', [App\Http\Controllers\Admin\MemberNotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    });
    Route::middleware('permission:notifications.create')->group(function () {
        Route::get('/notifications/create', [App\Http\Controllers\Admin\MemberNotificationController::class, 'create'])->name('notifications.create');
        Route::post('/notifications', [App\Http\Controllers\Admin\MemberNotificationController::class, 'store'])->name('notifications.store');
        Route::post('/notifications/bulk', [App\Http\Controllers\Admin\MemberNotificationController::class, 'sendBulk'])->name('notifications.bulk');
    });
    Route::middleware('permission:notifications.delete')->group(function () {
        Route::delete('/notifications/{notification}', [App\Http\Controllers\Admin\MemberNotificationController::class, 'destroy'])->name('notifications.destroy');
    });
    
    // QR Codes
    Route::middleware('permission:members.view')->group(function () {
        Route::get('/members/{member}/qr-code', [App\Http\Controllers\QRVerificationController::class, 'generateMemberQR'])->name('members.qr-code');
        Route::get('/members/{member}/qr-code/download', [App\Http\Controllers\QRVerificationController::class, 'downloadMemberQR'])->name('members.qr-code.download');
    });
    Route::middleware('permission:payments.view')->group(function () {
        Route::get('/payments/{payment}/qr-code', [App\Http\Controllers\QRVerificationController::class, 'generatePaymentQR'])->name('payments.qr-code');
        Route::get('/payments/{payment}/qr-code/download', [App\Http\Controllers\QRVerificationController::class, 'downloadPaymentQR'])->name('payments.qr-code.download');
    });
});

// Member Portal Routes
Route::prefix('member')->name('member.')->middleware('auth')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Member\MemberPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [App\Http\Controllers\Member\MemberPortalController::class, 'profile'])->name('profile');
    Route::put('/profile', [App\Http\Controllers\Member\MemberPortalController::class, 'profileUpdate'])->name('profile.update');
    Route::get('/change-password', [App\Http\Controllers\Member\MemberPortalController::class, 'showChangePassword'])->name('change-password');
    Route::put('/change-password', [App\Http\Controllers\Member\MemberPortalController::class, 'updatePassword'])->name('password.update');
    Route::get('/card', [App\Http\Controllers\Member\MemberPortalController::class, 'memberCard'])->name('card');
    Route::get('/card/download', [App\Http\Controllers\Member\MemberPortalController::class, 'downloadCard'])->name('card.download');
    Route::get('/payments', [App\Http\Controllers\Member\MemberPortalController::class, 'payments'])->name('payments');
    Route::get('/contributions', [App\Http\Controllers\Member\MemberPortalController::class, 'contributions'])->name('contributions');
    Route::get('/emergency-collections', [App\Http\Controllers\Member\MemberPortalController::class, 'emergencyCollections'])->name('emergency-collections');
    Route::get('/notices', [App\Http\Controllers\Member\MemberPortalController::class, 'notices'])->name('notices');
    Route::get('/donations', [App\Http\Controllers\Member\MemberPortalController::class, 'donations'])->name('donations');
});

// Online Payment Routes
Route::prefix('payment')->name('payment.')->middleware('auth')->group(function () {
    Route::post('/checkout', [App\Http\Controllers\OnlinePaymentController::class, 'checkout'])->name('checkout');
    Route::get('/success', [App\Http\Controllers\OnlinePaymentController::class, 'success'])->name('success');
    Route::get('/cancel', [App\Http\Controllers\OnlinePaymentController::class, 'cancel'])->name('cancel');
});

// Public Receipt Verification
Route::get('/verify/receipt/{receipt_no}', [App\Http\Controllers\Admin\ReceiptController::class, 'verify'])->name('receipt.verify');

// Public Donation Routes
Route::get('/donate', [App\Http\Controllers\PublicDonationController::class, 'index'])->name('donate');
Route::post('/donate', [App\Http\Controllers\PublicDonationController::class, 'store'])->name('donation.store');
Route::get('/donate/success/{donation}', [App\Http\Controllers\PublicDonationController::class, 'paymentSuccess'])->name('donation.payment.success');
Route::get('/donate/cancel/{donation}', [App\Http\Controllers\PublicDonationController::class, 'paymentCancel'])->name('donation.payment.cancel');
