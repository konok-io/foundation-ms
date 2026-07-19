<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\MemberController as FrontendMemberController;
use App\Http\Controllers\Frontend\CommitteeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\ContributionController;
use App\Http\Controllers\Admin\EmergencyCollectionController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\DonationController;
use App\Http\Controllers\Admin\ReceiptController;
use App\Http\Controllers\Admin\IncomeController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\IncomeCategoryController;
use App\Http\Controllers\Admin\ExpenseCategoryController;
use App\Http\Controllers\Admin\LedgerController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\BloodDonorController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\NoticeController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\CmsController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\MemberNotificationController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Member\MemberPortalController;
use App\Http\Controllers\PublicDonationController;
use App\Http\Controllers\OnlinePaymentController;
use App\Http\Controllers\QRVerificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/page/{slug}', [PageController::class, 'show'])->name('frontend.page');

// Blood Donor Public Search
Route::get('/blood-donors/search', [BloodDonorController::class, 'publicSearch'])->name('blood-donors.search');

// Public Events
Route::get('/events', [EventController::class, 'publicIndex'])->name('public.events.index');
Route::get('/events/{event}', [EventController::class, 'publicShow'])->name('public.events.show');
Route::post('/events/{event}/register', [EventController::class, 'register'])->name('public.events.register');

// Alias routes for frontend
Route::get('/frontend/events', [EventController::class, 'publicIndex'])->name('frontend.events');
Route::get('/frontend/notices', [NoticeController::class, 'publicIndex'])->name('frontend.notices');
Route::get('/frontend/gallery', [GalleryController::class, 'publicIndex'])->name('frontend.gallery');
Route::get('/frontend/activities', [ActivityController::class, 'publicIndex'])->name('frontend.activities');

// Public Notices
Route::get('/notices', [NoticeController::class, 'publicIndex'])->name('public.notices.index');

// Public Gallery
Route::get('/gallery', [GalleryController::class, 'publicIndex'])->name('public.gallery.index');
Route::get('/gallery/{album}', [GalleryController::class, 'publicShow'])->name('public.gallery.show');

// Public Activities
Route::get('/activities', [ActivityController::class, 'publicIndex'])->name('public.activities.index');
Route::get('/activities/{activity}', [ActivityController::class, 'publicShow'])->name('public.activities.show');

// Public Members
Route::get('/members', [FrontendMemberController::class, 'index'])->name('public.members.index');
Route::get('/members/{member}', [FrontendMemberController::class, 'show'])->name('public.members.show');

// Public Committee
Route::get('/committee', [CommitteeController::class, 'index'])->name('public.committee.index');
Route::get('/committee/{id}', [CommitteeController::class, 'show'])->name('public.committee.show');

// QR Verification
Route::get('/verify/member/{code}', [QRVerificationController::class, 'verify'])->name('verify.member');
Route::get('/verify/payment/{receiptNumber}', [QRVerificationController::class, 'paymentVerify'])->name('verify.payment');

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
    // Admin Login
    Route::get('admin/login', [LoginController::class, 'create'])->name('login');
    Route::post('admin/login', [LoginController::class, 'store'])->name('login.store');
    
    // Member Portal Login
    Route::get('portal/login', [LoginController::class, 'portalLogin'])->name('member.login');
    Route::post('portal/login', [LoginController::class, 'memberStore'])->name('member.login.store');
    
    // Password Reset Routes
    Route::get('forgot-password', [ForgotPasswordController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'store'])->name('password.email');
    Route::get('reset-password/{token}', [ResetPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [ResetPasswordController::class, 'store'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::post('/profile', [DashboardController::class, 'updateProfile'])->name('profile.update');
    Route::post('/change-password', [DashboardController::class, 'changePassword'])->name('change-password');

    // Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::patch('/users/{user}/status', [UserController::class, 'updateStatus'])->name('users.status');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Roles
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{role}', [RoleController::class, 'show'])->name('roles.show');
    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::get('/roles/{role}/permissions', [RoleController::class, 'getPermissions'])->name('roles.permissions');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

    // Members
    Route::get('/members', [MemberController::class, 'index'])->name('members.index');
    Route::get('/members/create', [MemberController::class, 'create'])->name('members.create');
    Route::post('/members', [MemberController::class, 'store'])->name('members.store');
    Route::get('/members/{member}', [MemberController::class, 'show'])->name('members.show');
    Route::get('/members/{member}/edit', [MemberController::class, 'edit'])->name('members.edit');
    Route::put('/members/{member}', [MemberController::class, 'update'])->name('members.update');
    Route::delete('/members/{member}', [MemberController::class, 'destroy'])->name('members.destroy');
    Route::get('/members/{member}/card', [MemberController::class, 'card'])->name('members.card');
    Route::get('/members/{member}/qr-code', [MemberController::class, 'qrCode'])->name('members.qr-code');
    Route::get('/members/export', [MemberController::class, 'export'])->name('members.export');

    // Contributions
    Route::get('/contributions', [ContributionController::class, 'index'])->name('contributions.index');
    Route::get('/contributions/create', [ContributionController::class, 'create'])->name('contributions.create');
    Route::post('/contributions', [ContributionController::class, 'store'])->name('contributions.store');
    Route::get('/contributions/{contribution}', [ContributionController::class, 'show'])->name('contributions.show');
    Route::get('/contributions/{contribution}/edit', [ContributionController::class, 'edit'])->name('contributions.edit');
    Route::put('/contributions/{contribution}', [ContributionController::class, 'update'])->name('contributions.update');
    Route::delete('/contributions/{contribution}', [ContributionController::class, 'destroy'])->name('contributions.destroy');
    Route::get('/contributions/generate-monthly', [ContributionController::class, 'generateMonthly'])->name('contributions.generate-monthly');
    Route::post('/contributions/record-payment', [ContributionController::class, 'recordPayment'])->name('contributions.record-payment');
    Route::post('/contributions/mark-overdue', [ContributionController::class, 'markOverdue'])->name('contributions.mark-overdue');
    Route::post('/contributions/bulk-payment', [ContributionController::class, 'bulkPayment'])->name('contributions.bulk-payment');

    // Emergency Collections
    Route::get('/emergency-collections', [EmergencyCollectionController::class, 'index'])->name('emergency-collections.index');
    Route::get('/emergency-collections/create', [EmergencyCollectionController::class, 'create'])->name('emergency-collections.create');
    Route::post('/emergency-collections', [EmergencyCollectionController::class, 'store'])->name('emergency-collections.store');
    Route::get('/emergency-collections/{collection}', [EmergencyCollectionController::class, 'show'])->name('emergency-collections.show');
    Route::get('/emergency-collections/{collection}/edit', [EmergencyCollectionController::class, 'edit'])->name('emergency-collections.edit');
    Route::put('/emergency-collections/{collection}', [EmergencyCollectionController::class, 'update'])->name('emergency-collections.update');
    Route::delete('/emergency-collections/{collection}', [EmergencyCollectionController::class, 'destroy'])->name('emergency-collections.destroy');
    Route::get('/emergency-collections/{collection}/assign', [EmergencyCollectionController::class, 'assignMembers'])->name('emergency-collections.assign');
    Route::post('/emergency-collections/{collection}/record-payment', [EmergencyCollectionController::class, 'recordPayment'])->name('emergency-collections.record-payment');
    Route::post('/emergency-collections/{collection}/bulk-payment', [EmergencyCollectionController::class, 'bulkPayment'])->name('emergency-collections.bulk-payment');
    Route::get('/emergency-collections/{collection}/close', [EmergencyCollectionController::class, 'closeCollection'])->name('emergency-collections.close');

    // Payments
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
    Route::post('/payments/{payment}/refund', [PaymentController::class, 'refund'])->name('payments.refund');

    // Donations
    Route::get('/donations', [DonationController::class, 'index'])->name('donations.index');
    Route::get('/donations/create', [DonationController::class, 'create'])->name('donations.create');
    Route::post('/donations', [DonationController::class, 'store'])->name('donations.store');
    Route::get('/donations/{donation}', [DonationController::class, 'show'])->name('donations.show');
    Route::get('/donations/{donation}/edit', [DonationController::class, 'edit'])->name('donations.edit');
    Route::put('/donations/{donation}', [DonationController::class, 'update'])->name('donations.update');
    Route::delete('/donations/{donation}', [DonationController::class, 'destroy'])->name('donations.destroy');
    Route::get('/donations/export', [DonationController::class, 'export'])->name('donations.export');

    // Receipts
    Route::get('/receipts', [ReceiptController::class, 'index'])->name('receipts.index');
    Route::get('/receipts/{receipt}', [ReceiptController::class, 'show'])->name('receipts.show');
    Route::get('/receipts/{receipt}/download', [ReceiptController::class, 'download'])->name('receipts.download');
    Route::get('/receipts/{receipt}/print', [ReceiptController::class, 'print'])->name('receipts.print');
    Route::get('/receipts/{receipt}/email', [ReceiptController::class, 'email'])->name('receipts.email');
    Route::get('/receipts/verify/{receipt_no}', [ReceiptController::class, 'verify'])->name('receipts.verify');

    // Income Categories
    Route::get('/income-categories', [IncomeCategoryController::class, 'index'])->name('income-categories.index');
    Route::get('/income-categories/create', [IncomeCategoryController::class, 'create'])->name('income-categories.create');
    Route::post('/income-categories', [IncomeCategoryController::class, 'store'])->name('income-categories.store');
    Route::get('/income-categories/{category}/edit', [IncomeCategoryController::class, 'edit'])->name('income-categories.edit');
    Route::put('/income-categories/{category}', [IncomeCategoryController::class, 'update'])->name('income-categories.update');
    Route::delete('/income-categories/{category}', [IncomeCategoryController::class, 'destroy'])->name('income-categories.destroy');

    // Expense Categories
    Route::get('/expense-categories', [ExpenseCategoryController::class, 'index'])->name('expense-categories.index');
    Route::get('/expense-categories/create', [ExpenseCategoryController::class, 'create'])->name('expense-categories.create');
    Route::post('/expense-categories', [ExpenseCategoryController::class, 'store'])->name('expense-categories.store');
    Route::get('/expense-categories/{category}/edit', [ExpenseCategoryController::class, 'edit'])->name('expense-categories.edit');
    Route::put('/expense-categories/{category}', [ExpenseCategoryController::class, 'update'])->name('expense-categories.update');
    Route::delete('/expense-categories/{category}', [ExpenseCategoryController::class, 'destroy'])->name('expense-categories.destroy');

    // Incomes
    Route::get('/incomes', [IncomeController::class, 'index'])->name('incomes.index');
    Route::get('/incomes/create', [IncomeController::class, 'create'])->name('incomes.create');
    Route::post('/incomes', [IncomeController::class, 'store'])->name('incomes.store');
    Route::get('/incomes/{income}/edit', [IncomeController::class, 'edit'])->name('incomes.edit');
    Route::put('/incomes/{income}', [IncomeController::class, 'update'])->name('incomes.update');
    Route::delete('/incomes/{income}', [IncomeController::class, 'destroy'])->name('incomes.destroy');

    // Expenses
    Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::get('/expenses/create', [ExpenseController::class, 'create'])->name('expenses.create');
    Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::get('/expenses/{expense}/edit', [ExpenseController::class, 'edit'])->name('expenses.edit');
    Route::put('/expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
    Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');

    // Ledger
    Route::get('/ledger', [LedgerController::class, 'index'])->name('ledger.index');
    Route::get('/ledger/account/{account}', [LedgerController::class, 'accountLedger'])->name('ledger.account');
    Route::get('/ledger/income-statement', [LedgerController::class, 'incomeStatement'])->name('ledger.income-statement');
    Route::get('/ledger/export', [LedgerController::class, 'export'])->name('ledger.export');

    // Reports
    Route::get('/reports/daily', [ReportController::class, 'dailyReport'])->name('reports.daily');
    Route::get('/reports/monthly', [ReportController::class, 'monthlyReport'])->name('reports.monthly');
    Route::get('/reports/yearly', [ReportController::class, 'yearlyReport'])->name('reports.yearly');
    Route::get('/reports/member-contribution', [ReportController::class, 'memberContributionReport'])->name('reports.member-contribution');
    Route::get('/reports/emergency-fund', [ReportController::class, 'emergencyFundReport'])->name('reports.emergency-fund');
    Route::get('/reports/donation', [ReportController::class, 'donationReport'])->name('reports.donation');
    Route::get('/reports/outstanding-due', [ReportController::class, 'outstandingDueReport'])->name('reports.outstanding-due');

    // Blood Donors
    Route::get('/blood-donors', [BloodDonorController::class, 'index'])->name('blood-donors.index');
    Route::get('/blood-donors/{member}/update-availability', [BloodDonorController::class, 'updateAvailability'])->name('blood-donors.update-availability');
    Route::get('/blood-donors/toggle/{member}', [BloodDonorController::class, 'toggleDonorStatus'])->name('blood-donors.toggle');

    // Events
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
    Route::get('/events/{event}/toggle', [EventController::class, 'toggleStatus'])->name('events.toggle');

    // Notices
    Route::get('/notices', [NoticeController::class, 'index'])->name('notices.index');
    Route::get('/notices/create', [NoticeController::class, 'create'])->name('notices.create');
    Route::post('/notices', [NoticeController::class, 'store'])->name('notices.store');
    Route::get('/notices/{notice}', [NoticeController::class, 'show'])->name('notices.show');
    Route::get('/notices/{notice}/edit', [NoticeController::class, 'edit'])->name('notices.edit');
    Route::put('/notices/{notice}', [NoticeController::class, 'update'])->name('notices.update');
    Route::delete('/notices/{notice}', [NoticeController::class, 'destroy'])->name('notices.destroy');
    Route::get('/notices/{notice}/toggle', [NoticeController::class, 'toggleStatus'])->name('notices.toggle');

    // Gallery
    Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
    Route::get('/gallery/create', [GalleryController::class, 'create'])->name('gallery.create');
    Route::post('/gallery', [GalleryController::class, 'store'])->name('gallery.store');
    Route::get('/gallery/{album}', [GalleryController::class, 'show'])->name('gallery.show');
    Route::get('/gallery/{album}/edit', [GalleryController::class, 'edit'])->name('gallery.edit');
    Route::put('/gallery/{album}', [GalleryController::class, 'update'])->name('gallery.update');
    Route::delete('/gallery/{album}', [GalleryController::class, 'destroy'])->name('gallery.destroy');
    Route::get('/gallery/{album}/toggle', [GalleryController::class, 'toggleStatus'])->name('gallery.toggle');
    Route::post('/gallery/{album}/upload', [GalleryController::class, 'uploadImages'])->name('gallery.upload');
    Route::post('/gallery/{album}/video', [GalleryController::class, 'addVideo'])->name('gallery.add-video');
    Route::delete('/gallery/images/{image}', [GalleryController::class, 'deleteImage'])->name('gallery.delete-image');
    Route::get('/gallery/images/{image}/featured', [GalleryController::class, 'toggleFeatured'])->name('gallery.toggle-featured');

    // Activities
    Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');
    Route::get('/activities/create', [ActivityController::class, 'create'])->name('activities.create');
    Route::post('/activities', [ActivityController::class, 'store'])->name('activities.store');
    Route::get('/activities/{activity}', [ActivityController::class, 'show'])->name('activities.show');
    Route::get('/activities/{activity}/edit', [ActivityController::class, 'edit'])->name('activities.edit');
    Route::put('/activities/{activity}', [ActivityController::class, 'update'])->name('activities.update');
    Route::post('/activities/{activity}/status', [ActivityController::class, 'updateStatus'])->name('activities.status');
    Route::delete('/activities/{activity}', [ActivityController::class, 'destroy'])->name('activities.destroy');

    // CMS
    Route::get('/cms', [CmsController::class, 'index'])->name('cms.index');
    Route::get('/cms/create', [CmsController::class, 'create'])->name('cms.create');
    Route::post('/cms', [CmsController::class, 'store'])->name('cms.store');
    Route::get('/cms/{page}/edit', [CmsController::class, 'edit'])->name('cms.edit');
    Route::put('/cms/{page}', [CmsController::class, 'update'])->name('cms.update');
    Route::delete('/cms/{page}', [CmsController::class, 'destroy'])->name('cms.destroy');
    Route::post('/cms/{page}/quick-edit', [CmsController::class, 'quickEdit'])->name('cms.quick-edit');

    // Documents
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/create', [DocumentController::class, 'create'])->name('documents.create');
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::get('/documents/{document}/verify', [DocumentController::class, 'verify'])->name('documents.verify');
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    Route::post('/members/{member}/documents', [DocumentController::class, 'store'])->name('documents.store');

    // Notifications
    Route::get('/notifications', [MemberNotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/create', [MemberNotificationController::class, 'create'])->name('notifications.create');
    Route::post('/notifications', [MemberNotificationController::class, 'store'])->name('notifications.store');
    Route::get('/notifications/{notification}/read', [MemberNotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::get('/notifications/mark-all-read', [MemberNotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::post('/notifications/bulk', [MemberNotificationController::class, 'sendBulk'])->name('notifications.bulk');
    Route::delete('/notifications/{notification}', [MemberNotificationController::class, 'destroy'])->name('notifications.destroy');

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/clear-cache', [SettingsController::class, 'clearCache'])->name('settings.clear-cache');
    Route::get('/settings/activity-logs', [SettingsController::class, 'activityLogs'])->name('settings.activity-logs');

    // Audit Logs
    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
    Route::get('/audit-logs/{auditLog}', [AuditLogController::class, 'show'])->name('audit-logs.show');
    Route::delete('/audit-logs/{auditLog}', [AuditLogController::class, 'destroy'])->name('audit-logs.destroy');
    Route::get('/audit-logs/export', [AuditLogController::class, 'export'])->name('audit-logs.export');

    // QR Codes
    Route::get('/members/{member}/qr-code', [QRVerificationController::class, 'generateMemberQR'])->name('members.qr-code');
    Route::get('/members/{member}/qr-code/download', [QRVerificationController::class, 'downloadMemberQR'])->name('members.qr-code.download');
    Route::get('/payments/{payment}/qr-code', [QRVerificationController::class, 'generatePaymentQR'])->name('payments.qr-code');
    Route::get('/payments/{payment}/qr-code/download', [QRVerificationController::class, 'downloadPaymentQR'])->name('payments.qr-code.download');
});

// Member Portal Routes
Route::prefix('portal')->name('member.')->middleware('auth')->group(function () {
    Route::get('/dashboard', [MemberPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [MemberPortalController::class, 'profile'])->name('profile');
    Route::put('/profile', [MemberPortalController::class, 'profileUpdate'])->name('profile.update');
    Route::get('/change-password', [MemberPortalController::class, 'showChangePassword'])->name('change-password');
    Route::put('/change-password', [MemberPortalController::class, 'updatePassword'])->name('password.update');
    Route::get('/card', [MemberPortalController::class, 'memberCard'])->name('card');
    Route::get('/card/download', [MemberPortalController::class, 'downloadCard'])->name('card.download');
    Route::get('/payments', [MemberPortalController::class, 'payments'])->name('payments');
    Route::get('/contributions', [MemberPortalController::class, 'contributions'])->name('contributions');
    Route::get('/emergency-collections', [MemberPortalController::class, 'emergencyCollections'])->name('emergency-collections');
    Route::get('/notices', [MemberPortalController::class, 'notices'])->name('notices');
    Route::get('/donations', [MemberPortalController::class, 'donations'])->name('donations');
});

// Online Payment Routes
Route::prefix('payment')->name('payment.')->middleware('auth')->group(function () {
    Route::post('/checkout', [OnlinePaymentController::class, 'checkout'])->name('checkout');
    Route::get('/success', [OnlinePaymentController::class, 'success'])->name('success');
    Route::get('/cancel', [OnlinePaymentController::class, 'cancel'])->name('cancel');
});

// Public Receipt Verification
Route::get('/verify/receipt/{receipt_no}', [ReceiptController::class, 'verify'])->name('receipt.verify');

// Public Donation Routes
Route::get('/donate', [PublicDonationController::class, 'index'])->name('donate');
Route::post('/donate', [PublicDonationController::class, 'store'])->name('donation.store');
Route::get('/donate/success/{donation}', [PublicDonationController::class, 'paymentSuccess'])->name('donation.payment.success');
Route::get('/donate/cancel/{donation}', [PublicDonationController::class, 'paymentCancel'])->name('donation.payment.cancel');
