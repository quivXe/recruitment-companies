<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompanyApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_companies(): void
    {
        Company::factory()->count(3)->create();

        $response = $this->getJson('/api/companies');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    public function test_can_show_a_single_company(): void
    {
        $company = Company::factory()->create();

        $response = $this->getJson("/api/companies/{$company->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => $company->id]);
    }

    public function test_can_create_a_company(): void
    {
        $data = Company::factory()->make()->toArray();

        $response = $this->postJson('/api/companies', $data);

        $response->assertStatus(201)
                 ->assertJsonFragment(['name' => $data['name']]);

        $this->assertDatabaseHas('companies', ['name' => $data['name']]);
    }

    public function test_can_update_a_company(): void
    {
        $company = Company::factory()->create();
        $data = ['name' => 'Updated Company Name'];

        $response = $this->putJson("/api/companies/{$company->id}", $data);

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Updated Company Name']);

        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'name' => 'Updated Company Name',
        ]);
    }

    public function test_can_delete_a_company(): void
    {
        $company = Company::factory()->create();

        $response = $this->deleteJson("/api/companies/{$company->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('companies', ['id' => $company->id]);
    }

    public function test_returns_validation_errors_when_storing_invalid_data(): void
    {
        $response = $this->postJson('/api/companies', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'nip', 'address', 'city', 'postal_code']);
    }
}
