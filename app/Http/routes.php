<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Password reset link request routes...
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');

// Password reset routes...
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');

//Amphur
Route::get('amphur/read/{provinceid}', 'SystemDatas\AmphurController@read');
//District
Route::get('district/read/{amphurid}', 'SystemDatas\DistrictController@read');
//Zipcode
Route::get('zipcode/read/{districtid}', 'SystemDatas\ZipcodeController@read');

//Employee
Route::get('employee', 'EmployeeController@index');
Route::get('employee/read', 'EmployeeController@read');
Route::post('employee/update', 'EmployeeController@update');
Route::post('employee/check_username', 'EmployeeController@check_username');
Route::post('employee/check_email', 'EmployeeController@check_email');

//EmployeePermission
Route::get('employeepermission/read', 'EmployeePermissionController@read');
Route::post('employeepermission/update', 'EmployeePermissionController@update');

//Branch
Route::get('branch', 'Settings\BranchController@index');
Route::get('branch/read', 'Settings\BranchController@read');
Route::post('branch/update', 'Settings\BranchController@update');
Route::get('branch/readSelectlistForDisplayInGrid', 'Settings\BranchController@readSelectlistForDisplayInGrid');
Route::post('branch/check_headquarter', 'Settings\BranchController@check_headquarter');
Route::post('branch/check_keyslot', 'Settings\BranchController@check_keyslot');

//Department
Route::get('department', 'Settings\DepartmentController@index');
Route::get('department/read', 'Settings\DepartmentController@read');
Route::post('department/update', 'Settings\DepartmentController@update');

//Team
Route::get('team', 'Settings\TeamController@index');
Route::get('team/read', 'Settings\TeamController@read');
Route::post('team/update', 'Settings\TeamController@update');

//CarBrand
Route::get('carbrand', 'Settings\CarBrandController@index');
Route::get('carbrand/read', 'Settings\CarBrandController@read');
Route::post('carbrand/update', 'Settings\CarBrandController@update');
Route::get('carbrand/getmodelbyid/{id}', 'Settings\CarBrandController@getmodelbyid');

//Cartype
Route::get('cartype', 'Settings\CarTypeController@index');
Route::get('cartype/read', 'Settings\CarTypeController@read');
Route::post('cartype/update', 'Settings\CarTypeController@update');

//Carmodel
Route::get('carmodel', 'Settings\CarModelController@index');
Route::get('carmodel/read', 'Settings\CarModelController@read');
Route::post('carmodel/update', 'Settings\CarModelController@update');
Route::get('carmodel/getsubmodelandcolorbyid/{id}/{registrationtype}', 'Settings\CarModelController@getsubmodelandcolorbyid');
Route::get('carmodel/getregistrationcost/{id}/{registrationtype}', 'Settings\CarModelController@getregistrationcost');

//Carsubmodel
Route::get('carsubmodel/read', 'Settings\CarSubModelController@read');
Route::post('carsubmodel/update', 'Settings\CarSubModelController@update');
Route::get('carsubmodel/readSelectlist/{carmodelid}', 'Settings\CarSubModelController@readSelectlist');

//Color
Route::get('color', 'Settings\ColorController@index');
Route::get('color/read', 'Settings\ColorController@read');
Route::post('color/update', 'Settings\ColorController@update');

//CarmodelColor
Route::get('carmodelcolor/read', 'Settings\CarModelColorController@read');
Route::post('carmodelcolor/update', 'Settings\CarModelColorController@update');
Route::get('carmodelcolor/readSelectlist/{carmodelid}', 'Settings\CarModelColorController@readSelectlist');
Route::post('carmodelcolor/check_color', 'Settings\CarModelColorController@check_color');

//Bank
Route::get('bank', 'Settings\BankController@index');
Route::get('bank/read', 'Settings\BankController@read');
Route::post('bank/update', 'Settings\BankController@update');

//InsuranceCompany
Route::get('insurancecompany', 'Settings\InsuranceCompanyController@index');
Route::get('insurancecompany/read', 'Settings\InsuranceCompanyController@read');
Route::post('insurancecompany/update', 'Settings\InsuranceCompanyController@update');

//FinaceCompany
Route::get('finacecompany', 'Settings\FinaceCompanyController@index');
Route::get('finacecompany/read', 'Settings\FinaceCompanyController@read');
Route::post('finacecompany/update', 'Settings\FinaceCompanyController@update');

//Customer
Route::get('customer', 'CustomerController@index');
Route::get('customer/read', 'CustomerController@read');
Route::post('customer/update', 'CustomerController@update');
Route::get('customer/readSelectlistForDisplayInGrid', 'CustomerController@readSelectlistForDisplayInGrid');
Route::get('customer/getbyid/{id}', 'CustomerController@getbyid');

//CustomerExpectation
Route::get('customerexpectation/read', 'CustomerExpectationController@read');
Route::post('customerexpectation/update', 'CustomerExpectationController@update');

//Car
Route::get('car', 'CarController@index');
Route::get('car/read', 'CarController@read');
Route::post('car/update', 'CarController@update');
Route::post('car/upload', 'CarController@upload');
Route::get('car/readSelectlistForDisplayInGrid', 'CarController@readSelectlistForDisplayInGrid');

//Pricelist
Route::get('pricelist', 'Settings\PricelistController@index');
Route::get('pricelist/read', 'Settings\PricelistController@read');
Route::post('pricelist/update', 'Settings\PricelistController@update');
Route::get('pricelist/readSelectlistForDisplayInGrid', 'Settings\PricelistController@readSelectlistForDisplayInGrid');
Route::get('pricelist/getprice/{carsubmodelid}/{date}', 'Settings\PricelistController@getprice');

//Giveaway
Route::get('giveaway', 'Settings\GiveawayController@index');
Route::get('giveaway/read', 'Settings\GiveawayController@read');
Route::post('giveaway/update', 'Settings\GiveawayController@update');
Route::post('giveaway/check_saleprice', 'Settings\GiveawayController@check_saleprice');

//CarPreemption
Route::get('carpreemption', 'CarPreemptionController@index');
Route::get('carpreemption/read', 'CarPreemptionController@read');
Route::post('carpreemption/update', 'CarPreemptionController@update');

Route::get('carpreemption/newcarpreemption', 'CarPreemptionController@newcarpreemption');
Route::get('carpreemption/edit/{id}', 'CarPreemptionController@edit');
Route::get('carpreemption/view/{id}', 'CarPreemptionController@view');
Route::post('carpreemption/save', 'CarPreemptionController@save');

//RedLabel
Route::get('redlabel', 'Settings\RedLabelController@index');
Route::get('redlabel/read', 'Settings\RedLabelController@read');
Route::post('redlabel/update', 'Settings\RedLabelController@update');

//InterestRateType
Route::get('interestratetype', 'Settings\InterestRateTypeController@index');
Route::get('interestratetype/read', 'Settings\InterestRateTypeController@read');
Route::post('interestratetype/update', 'Settings\InterestRateTypeController@update');
Route::get('interestratetype/readSelectlist/{finacecompanyid}', 'Settings\InterestRateTypeController@readSelectlist');

//CommissionFinace
Route::get('commissionfinace', 'Settings\CommissionFinaceController@index');
Route::get('commissionfinace/read', 'Settings\CommissionFinaceController@read');
Route::post('commissionfinace/update', 'Settings\CommissionFinaceController@update');
Route::get('commissionfinace/readSelectlistForDisplayInGrid', 'Settings\CommissionFinaceController@readSelectlistForDisplayInGrid');

//CommissionFinaceInterest
Route::get('commissionfinaceinterest/read', 'Settings\CommissionFinaceInterestController@read');
Route::post('commissionfinaceinterest/update', 'Settings\CommissionFinaceInterestController@update');
Route::get('commissionfinaceinterest/readSelectlist/{commissionfinaceid}', 'Settings\CommissionFinaceInterestController@readSelectlist');
Route::post('commissionfinaceinterest/check_dup_down', 'Settings\CommissionFinaceInterestController@check_dup_down');

//CommissionFinaceCom
Route::get('commissionfinacecom/read', 'Settings\CommissionFinaceComController@read');
Route::post('commissionfinacecom/update', 'Settings\CommissionFinaceComController@update');
Route::get('commissionfinacecom/readSelectlist/{commissionfinaceid}', 'Settings\CommissionFinaceComController@readSelectlist');
Route::post('commissionfinacecom/check_dup_interestcalculation', 'Settings\CommissionFinaceComController@check_dup_interestcalculation');

//CommissionFinaceCar
Route::get('commissionfinacecar/read', 'Settings\CommissionFinaceCarController@read');
Route::post('commissionfinacecar/update', 'Settings\CommissionFinaceCarController@update');
Route::get('commissionfinacecar/readSelectlist/{commissionfinaceid}', 'Settings\CommissionFinaceCarController@readSelectlist');
Route::get('commissionfinacecar/readSelectlistForDisplayInGrid', 'Settings\CommissionFinaceCarController@readSelectlistForDisplayInGrid');
Route::post('commissionfinacecar/check_dup_carsubmodel', 'Settings\CommissionFinaceCarController@check_dup_carsubmodel');

//CommissionExtra
Route::get('commissionextra', 'Settings\CommissionExtraController@index');
Route::get('commissionextra/read', 'Settings\CommissionExtraController@read');
Route::post('commissionextra/update', 'Settings\CommissionExtraController@update');
Route::get('commissionextra/readSelectlistForDisplayInGrid', 'Settings\CommissionExtraController@readSelectlistForDisplayInGrid');

//CommissionPA
Route::get('commissionpa', 'Settings\CommissionPAController@index');
Route::get('commissionpa/read', 'Settings\CommissionPAController@read');
Route::post('commissionpa/update', 'Settings\CommissionPAController@update');

//CarPayment
Route::get('carpayment', 'CarPaymentController@index');
Route::get('carpayment/read', 'CarPaymentController@read');
Route::post('carpayment/update', 'CarPaymentController@update');

//CarCancelPreemption
Route::get('carcancelpreemption', 'CarCancelPreemptionController@index');
Route::get('carcancelpreemption/read', 'CarCancelPreemptionController@read');
Route::post('carcancelpreemption/update', 'CarCancelPreemptionController@update');

Route::get('/', 'HomeController@index');

Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
