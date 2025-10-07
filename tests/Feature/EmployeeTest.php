<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Company;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmployeeApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_employees(): void
    {
        $company = Company::factory()->create();
        Employee::factory()->count(3)->create(['company_id' => $company->id]);

        $response = $this->getJson("/api/companies/{$company->id}/employees");

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    public function test_can_show_single_employee(): void
    {
        $company = Company::factory()->create();
        $employee = Employee::factory()->create(['company_id' => $company->id]);

        $response = $this->getJson("/api/companies/{$company->id}/employees/{$employee->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => $employee->id]);
    }

    public function test_can_create_employee(): void
    {
        $company = Company::factory()->create();
        $data = Employee::factory()->make(['company_id' => $company->id])->toArray();

        $response = $this->postJson("/api/companies/{$company->id}/employees", $data);

        $response->assertStatus(201)
                 ->assertJsonFragment(['first_name' => $data['first_name']]);

        $this->assertDatabaseHas('employees', [
            'company_id' => $company->id,
            'first_name' => $data['first_name'],
        ]);
    }

    public function test_validation_errors_on_store(): void
    {
        $company = Company::factory()->create();

        $response = $this->postJson("/api/companies/{$company->id}/employees", []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['first_name', 'last_name', 'email']);
    }

    public function test_can_update_employee(): void
    {
        $company = Company::factory()->create();
        $employee = Employee::factory()->create(['company_id' => $company->id]);

        $data = ['first_name' => 'UpdatedFirstName'];

        $response = $this->putJson("/api/companies/{$company->id}/employees/{$employee->id}", $data);

        $response->assertStatus(200)
                 ->assertJsonFragment(['first_name' => 'UpdatedFirstName']);

        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'first_name' => 'UpdatedFirstName',
        ]);
    }

    public function test_can_delete_employee(): void
    {
        $company = Company::factory()->create();
        $employee = Employee::factory()->create(['company_id' => $company->id]);

        $response = $this->deleteJson("/api/companies/{$company->id}/employees/{$employee->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('employees', ['id' => $employee->id]);
    }

    public function test_scoped_binding_prevents_cross_company_access(): void
    {
        $company1 = Company::factory()->create();
        $company2 = Company::factory()->create();

        $employee = Employee::factory()->create(['company_id' => $company2->id]);

        // Attempt to access employee through a different company
        $response = $this->getJson("/api/companies/{$company1->id}/employees/{$employee->id}");

        $response->assertStatus(404);
    }

    public function test_phone_is_optional_on_store(): void
    {
        $company = Company::factory()->create();

        $data = Employee::factory()
            ->make(['company_id' => $company->id, 'phone' => null])
            ->toArray();

        $response = $this->postJson("/api/companies/{$company->id}/employees", $data);

        $response->assertStatus(201)
                ->assertJsonFragment([
                    'first_name' => $data['first_name'],
                    'phone' => null,
                ]);

        $this->assertDatabaseHas('employees', [
            'company_id' => $company->id,
            'first_name' => $data['first_name'],
            'phone' => null,
        ]);
    }
}
