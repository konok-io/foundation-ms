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
