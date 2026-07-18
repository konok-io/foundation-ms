<?php

namespace Tests\Feature;

use App\Models\IncomeCategory;
use App\Models\ExpenseCategory;
use App\Models\Income;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountingTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_income_list()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('incomes.view');

        $response = $this->actingAs($user)->get('/admin/accounting/incomes');
        $response->assertStatus(200);
    }

    public function test_can_create_income()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('incomes.create');
        
        $category = IncomeCategory::factory()->create();

        $data = [
            'category_id' => $category->id,
            'amount' => 500,
            'payment_method' => 'cash',
            'date' => date('Y-m-d'),
            'received_from' => 'Test Donor',
        ];

        $response = $this->actingAs($user)->post('/admin/accounting/incomes', $data);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('incomes', ['amount' => 500]);
    }

    public function test_can_view_expense_list()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('expenses.view');

        $response = $this->actingAs($user)->get('/admin/accounting/expenses');
        $response->assertStatus(200);
    }

    public function test_can_create_expense()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('expenses.create');
        
        $category = ExpenseCategory::factory()->create();

        $data = [
            'category_id' => $category->id,
            'amount' => 200,
            'payment_method' => 'cash',
            'date' => date('Y-m-d'),
            'payee_name' => 'Test Payee',
        ];

        $response = $this->actingAs($user)->post('/admin/accounting/expenses', $data);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('expenses', ['amount' => 200]);
    }

    public function test_can_view_ledger()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('ledger.view');

        $response = $this->actingAs($user)->get('/admin/accounting/ledger');
        $response->assertStatus(200);
    }

    public function test_can_view_income_statement()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('reports.view');

        $response = $this->actingAs($user)->get('/admin/accounting/reports/income-statement');
        $response->assertStatus(200);
    }

    public function test_can_manage_income_categories()
    {
        $user = User::factory()->create();
        $user->givePermissionTo(['income_categories.view', 'income_categories.create']);

        $response = $this->actingAs($user)->get('/admin/accounting/income-categories');
        $response->assertStatus(200);
    }

    public function test_can_manage_expense_categories()
    {
        $user = User::factory()->create();
        $user->givePermissionTo(['expense_categories.view', 'expense_categories.create']);

        $response = $this->actingAs($user)->get('/admin/accounting/expense-categories');
        $response->assertStatus(200);
    }

    public function test_income_voucher_number_generation()
    {
        $category = IncomeCategory::factory()->create();
        
        $income1 = Income::factory()->create(['category_id' => $category->id]);
        $income2 = Income::factory()->create(['category_id' => $category->id]);

        $this->assertNotEquals($income1->voucher_no, $income2->voucher_no);
    }

    public function test_expense_voucher_number_generation()
    {
        $category = ExpenseCategory::factory()->create();
        
        $expense1 = Expense::factory()->create(['category_id' => $category->id]);
        $expense2 = Expense::factory()->create(['category_id' => $category->id]);

        $this->assertNotEquals($expense1->voucher_no, $expense2->voucher_no);
    }
}
