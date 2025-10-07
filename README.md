# REST API CRUD APP

A simple REST API built with Laravel for managing companies and their employees.

*API endpoints:*
```
  GET|HEAD        api/companies ........................................................................ companies.index › CompanyController@index
  POST            api/companies ........................................................................ companies.store › CompanyController@store
  GET|HEAD        api/companies/{company} ................................................................ companies.show › CompanyController@show
  PUT|PATCH       api/companies/{company} ............................................................ companies.update › CompanyController@update
  DELETE          api/companies/{company} .......................................................... companies.destroy › CompanyController@destroy
  GET|HEAD        api/companies/{company}/employees ......................................... companies.employees.index › EmployeeController@index
  POST            api/companies/{company}/employees ......................................... companies.employees.store › EmployeeController@store
  GET|HEAD        api/companies/{company}/employees/{employee} ................................ companies.employees.show › EmployeeController@show
  PUT|PATCH       api/companies/{company}/employees/{employee} ............................ companies.employees.update › EmployeeController@update
  DELETE          api/companies/{company}/employees/{employee} .......................... companies.employees.destroy › EmployeeController@destroy
 ```
