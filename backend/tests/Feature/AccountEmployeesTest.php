<?php

use App\Filament\Account\Resources\EmployeeResource\Pages\CreateEmployee;
use App\Filament\Account\Resources\EmployeeResource\Pages\EditEmployee;
use App\Filament\Account\Resources\EmployeeResource\Pages\ListEmployees;
use App\Filament\Account\Resources\EmployeeResource\RelationManagers\WorkStreamsRelationManager;
use App\Filament\Account\Resources\WorkStreamResource\Pages\CreateWorkStream;
use App\Models\AccountService;
use App\Models\Employee;
use App\Models\Role;
use App\Models\User;
use App\Models\WorkStream;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    Filament::setCurrentPanel(Filament::getPanel('account'));
});

function makeWork(Employee $employee, string $status, float $cost): WorkStream
{
    return WorkStream::create([
        'title' => "Work {$status} {$cost}",
        'account_service_id' => AccountService::firstOrCreate(['name' => 'Research'])->id,
        'employee_id' => $employee->id,
        'status' => $status,
        'cost' => $cost,
    ]);
}

it('adds an employee with a salary', function () {
    $this->actingAs(User::factory()->admin()->create());

    Livewire::test(CreateEmployee::class)
        ->fillForm(['name' => 'Neha Singh', 'designation' => 'Research Writer', 'monthly_salary' => 18000])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Employee::sole())
        ->name->toBe('Neha Singh')
        ->and((float) Employee::sole()->monthly_salary)->toBe(18000.0);
});

it('assigns a work stream to an employee from the dropdown', function () {
    $this->actingAs(User::factory()->admin()->create());
    $employee = Employee::create(['name' => 'Amit', 'monthly_salary' => 20000]);
    $service = AccountService::firstOrCreate(['name' => 'IT']);

    Livewire::test(CreateWorkStream::class)
        ->assertFormFieldExists('employee_id')
        ->fillForm([
            'title' => 'Hospital website',
            'account_service_id' => $service->id,
            'employee_id' => $employee->id,
            'status' => 'in_progress',
            'cost' => 40000,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(WorkStream::sole()->employee->is($employee))->toBeTrue();
});

it('shows how much work each employee has done', function () {
    $this->actingAs(User::factory()->admin()->create());
    $employee = Employee::create(['name' => 'Amit', 'monthly_salary' => 20000]);
    makeWork($employee, 'completed', 30000);
    makeWork($employee, 'completed', 10000);
    makeWork($employee, 'in_progress', 5000);

    $row = Livewire::test(ListEmployees::class)
        ->assertCanSeeTableRecords([$employee])
        ->instance()->getFilteredTableQuery()->first();

    expect($row->work_streams_count)->toBe(3)
        ->and($row->completed_work_count)->toBe(2)
        ->and($row->active_work_count)->toBe(1)
        ->and((float) $row->work_streams_sum_cost)->toBe(45000.0);
});

it('lists the employee\'s assigned work on their page', function () {
    $admin = User::factory()->admin()->create();
    $this->actingAs($admin);
    $employee = Employee::create(['name' => 'Amit']);
    $mine = makeWork($employee, 'completed', 1000);
    $other = makeWork(Employee::create(['name' => 'Someone else']), 'pending', 2000);

    $this->get("/accounts/employees/{$employee->id}/edit")->assertOk();
    $this->get('/accounts/employees')->assertOk();
    $this->get('/accounts/work-streams/create')->assertOk();

    Livewire::test(WorkStreamsRelationManager::class, ['ownerRecord' => $employee, 'pageClass' => EditEmployee::class])
        ->assertCanSeeTableRecords([$mine])
        ->assertCanNotSeeTableRecords([$other]);
});

it('needs the employees permission', function () {
    $role = Role::create(['name' => 'Sales', 'permissions' => ['proposals' => ['view']]]);

    $this->actingAs(User::factory()->create(['role_id' => $role->id]))
        ->get('/accounts/employees')
        ->assertForbidden();
});
