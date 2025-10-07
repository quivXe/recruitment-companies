<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Employee;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;

class EmployeeController extends Controller
{
    public function index(Company $company)
    {
        return $company->employees;
    }

    public function store(StoreEmployeeRequest $request, Company $company)
    {
        return $company->employees()->create($request->validated());
    }

    public function show(Company $company, Employee $employee)
    {
        return $employee;
    }

    public function update(UpdateEmployeeRequest $request, Company $company, Employee $employee)
    {
        $employee->update($request->validated());
        return $employee;
    }

    public function destroy(Company $company, Employee $employee)
    {
        $employee->delete();
        return response()->noContent();
    }
}
