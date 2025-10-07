<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;

class CompanyController extends Controller
{
    public function index()
    {
        return Company::with('employees')->get();
    }

    public function store(StoreCompanyRequest $request)
    {
        return Company::create($request->validated());
    }

    public function show(Company $company)
    {
        return $company->load('employees');
    }

    public function update(UpdateCompanyRequest $request, Company $company)
    {
        $company->update($request->validated());
        return $company;
    }

    public function destroy(Company $company)
    {
        $company->delete();
        return response()->noContent();
    }
}
