<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmployeeController;

Route::apiResource('companies', CompanyController::class);

Route::apiResource('companies.employees', EmployeeController::class)
    ->scoped();

