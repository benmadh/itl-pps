<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', [
    'uses' => 'HomeController@index',
    'as' => 'home',
    ]);

Route::group(['prefix' => LaravelLocalization::setLocale(),'middleware' => [ 'localeSessionRedirect', 'localizationRedirect' ]], function(){
    Route::group(['prefix' => 'admin','middleware' => 'auth','namespace' => 'Admin'],function(){
        Route::resource('dashboard', 'DashboardController'); 
        Route::resource('users', 'UsersController');
        Route::resource('permissions', 'PermissionsController'); 
        Route::resource('roles', 'RolesController');
        Route::resource('locations', 'LocationsController');
        Route::resource('companies', 'CompaniesController');
    });


    Route::group(['prefix' => 'general','middleware' => 'auth','namespace' => 'General'],function(){ 
        Route::resource('departments', 'DepartmentsController');        
        Route::resource('designations', 'DesignationsController');
        Route::resource('levels', 'LevelsController');
        Route::resource('substratecategories', 'SubstrateCatController');
        Route::resource('machinetypes', 'MachineTypeController');
        Route::resource('machinefeatures', 'MachineFeaturesController');
        Route::resource('units', 'UnitsController');
        Route::resource('wheeltypes', 'WheelTypesController');
        Route::resource('cylinders', 'CylindersController');
        Route::resource('machines', 'MachinesController');
        Route::resource('itemgroups', 'ItemGroupsController');
        Route::resource('items', 'ItemsController');
        Route::resource('chains', 'ChainsController');
        Route::resource('customergroups', 'CustomerGroupsController');
        Route::resource('customers', 'CustomersController');
        Route::resource('labeltypes', 'LabelTypesController');
        Route::resource('labelreferences', 'LabelReferencesController');
        Route::resource('employees', 'EmployeesController');
        Route::resource('ordtrcpln', 'OrdTrcPlnController');
        Route::resource('ordtrcpre', 'OrdTrcPreController');
        Route::resource('ordtrcplt', 'OrdTrcPltController');
        Route::resource('ordtrcprintstr', 'OrdTrcPrintStrController');
        Route::resource('ordtrccutstr', 'OrdTrcCutStrController');
        Route::resource('ordertracking', 'OrderTrackingController');
        Route::resource('sameasauto', 'SameasautoController');
        Route::resource('daytypes', 'DayTypesController');
        Route::resource('holidays', 'HolidaysController');
        Route::resource('mcholdreasons', 'McHoldReasonsController');
        Route::resource('holdmachines', 'HoldMachinesController');
    }); 

    
    //General Start
        Route::get('getMachineList/{department_id}','general\MachinesController@getMachineList');
        Route::post('general/holdmachines/search', 'general\HoldMachinesController@search')->name('holdmachines.search');
        Route::post('general/mcholdreasons/search', 'general\McHoldReasonsController@search')->name('mcholdreasons.search');
        Route::post('general/holidays/search', 'general\HolidaysController@search')->name('holidays.search');
        Route::post('general/daytypes/search', 'general\DayTypesController@search')->name('daytypes.search');
        Route::post('general/ordertracking/search', 'general\OrderTrackingController@search')->name('ordertracking.search');
        Route::get('getScanErrorList/{workorder_no}','general\OrdTrcPlnController@getScanErrorList');
        Route::get('getWoList/{workorder_no}','general\OrdTrcPlnController@getWoList');
        Route::post('general/departments/search', 'general\DepartmentsController@search')->name('departments.search');
        Route::post('general/designations/search', 'general\DesignationsController@search')->name('designations.search');
        Route::post('general/levels/search', 'general\LevelsController@search')->name('levels.search');
        Route::post('general/substratecategory/search', 'general\SubstrateCatController@search')->name('substratecategories.search');
        Route::post('general/machinetypes/search', 'general\MachineTypeController@search')->name('machinetypes.search');
        Route::post('general/machinefeatures/search', 'general\MachineFeaturesController@search')->name('machinefeatures.search');
        Route::post('general/units/search', 'general\UnitsController@search')->name('units.search');
        Route::post('general/wheeltypes/search', 'general\WheelTypesController@search')->name('wheeltypes.search');
        Route::post('general/cylinders/search', 'general\CylindersController@search')->name('cylinders.search');
        Route::post('general/machines/search', 'general\MachinesController@search')->name('machines.search');
        Route::post('general/itemgroups/search', 'general\ItemGroupsController@search')->name('itemgroups.search');
        Route::post('general/items/search', 'general\ItemsController@search')->name('items.search');
        Route::post('general/chains/search', 'general\ChainsController@search')->name('chains.search');
        Route::post('general/customergroups/search', 'general\CustomerGroupsController@search')->name('customergroups.search');
        Route::post('general/customers/search', 'general\CustomersController@search')->name('customers.search');
        Route::post('general/labeltypes/search', 'general\LabelTypesController@search')->name('labeltypes.search');
        Route::post('general/labelreferences/search', 'general\LabelReferencesController@search')->name('labelreferences.search');
        Route::get('general/labelreferences/edit_item_references/{id}','general\LabelReferencesController@edit_item_references')->name('labelreferences.edit_item_references');
        Route::get('getItemDetails/{item_id}','general\ItemsController@getItemDetails');
        Route::post('general/labelreferences/update_item_references', 'general\LabelReferencesController@update_item_references')->name('labelreferences.update_item_references');
        Route::get('getDayTypeList/{companies_id}','general\DayTypesController@getDayTypeList');
        Route::get('getLocationList/{company_id}','admin\LocationsController@getLocationList');
        Route::get('getEmpPhoto/{employee_id}','general\EmployeesController@getEmpPhoto');

        Route::get('general/sameasauto/edit_sameas_auto/{id}','general\SameasautoController@edit_sameas_auto')->name('sameasauto.edit_sameas_auto');
        Route::get('general/sameasauto/print_sameas_auto/{id}','general\SameasautoController@print_sameas_auto')->name('sameasauto.print_sameas_auto');
        Route::post('general/sameasupdate/update_sameas_auto', 'general\SameasautoController@update_sameas_auto')->name('sameasauto.update_sameas_auto'); 
        Route::post('general/sameasauto/search', 'general\SameasautoController@search')->name('sameasauto.search');
    //General End


    Route::group(['prefix' => 'screen','middleware' => 'auth','namespace' => 'Screen'],function(){ 
        Route::resource('scrratefile', 'ScrRateFileController'); 
        Route::resource('printingScreen', 'PrintingScreenController');
        Route::resource('partPrintingScreen', 'PartPrintingScreenController');
        Route::resource('cuttingScreen', 'CuttingScreenController');
        Route::resource('partCuttingScreen', 'PartCuttingScreenController');
        Route::resource('packingScreen', 'PackingScreenController');
        Route::resource('aqlScreen', 'AqlScreenController');
        Route::resource('dbScreen', 'DbScreenController');
        Route::resource('mrnScreen', 'MrnScreenController');  
    });  

    //Screen Start
        Route::get('screen/pflratefile/edit_machine_speed/{id}','screen\ScrRateFileController@edit_machine_speed')->name('scrratefile.edit_machine_speed');
        Route::post('screen/pflratefile/update_machine_speed', 'screen\ScrRateFileController@update_machine_speed')->name('scrratefile.update_machine_speed');
        Route::post('screen/pflratefile/search', 'screen\ScrRateFileController@search')->name('scrratefile.search'); 
        Route::get('getPrintingDetailsScreen','screen\PrintingScreenController@getPrintingDetailsScreen'); 
        Route::get('getCuttingDetailsScreen','screen\CuttingScreenController@getCuttingDetailsScreen');
        Route::get('getPackingDetailsScreen','screen\PackingScreenController@getPackingDetailsScreen');
        Route::get('getAqlDetailsScreen','screen\AqlScreenController@getAqlDetailsScreen');
        Route::post('screen/dashboard/chartDetails', 'screen\DbScreenController@chartDetails')->name('dbScreen.chartDetails'); 

        Route::post('screen/mrnscreen/search', 'screen\MrnScreenController@search')->name('mrnScreen.search');     
        Route::get('screen/mrnscreen/print_mrn/{id}','screen\MrnScreenController@print_mrn')->name('mrnScreen.print_mrn');   

    //Screen End
    
    Route::group(['prefix' => 'rotary','middleware' => 'auth','namespace' => 'Rotary'],function(){ 
        Route::resource('pflratefile', 'PflRateFileController'); 
        Route::resource('mrnRotary', 'MrnRotaryController');  
        Route::resource('dbRotary', 'DbRotaryController'); 
        Route::resource('printingRotary', 'PrintingRotaryController');
        Route::resource('partPrintingRotary', 'PartPrintingRotaryController');
        Route::resource('cuttingRotary', 'CuttingRotaryController');
        Route::resource('ordtrcprdrot', 'OrdTrcPrdRotController');
        Route::resource('partCuttingRotary', 'PartCuttingRotaryController');
        Route::resource('packingRotary', 'PackingRotaryController');
        Route::resource('aqlRotary', 'AqlRotaryController');
        Route::resource('processRotary', 'ProcessRotaryController');
        Route::resource('plnBoard', 'PlnBoardRotaryController');
        Route::resource('prdPlnRotary', 'PrdPlnRotaryController');
    });  

    //Rotary Start
        Route::post('rotary/productionplan/process', 'rotary\PrdPlnRotaryController@process')->name('prdPlnRotary.process');
        Route::get('plnBoard/change_wo_date/{dropDate}/{dropShift}/{dropMachine}/{planningboard_id}','rotary\PlnBoardRotaryController@change_wo_date');
        Route::get('getWoPlanListRotary/{workorderno}','rotary\PlnBoardRotaryController@getWoPlanListRotary');        
        Route::post('rotary/processRotary/process', 'rotary\ProcessRotaryController@process')->name('processRotary.process');
        Route::get('rotary/pflratefile/edit_machine_speed/{id}','rotary\PflRateFileController@edit_machine_speed')->name('pflratefile.edit_machine_speed');
        Route::post('rotary/pflratefile/update_machine_speed', 'rotary\PflRateFileController@update_machine_speed')->name('pflratefile.update_machine_speed');
        Route::post('rotary/pflratefile/search', 'rotary\PflRateFileController@search')->name('pflratefile.search');        
        Route::get('getWoDetais/{main_workorder_id}','rotary\MrnRotaryController@getWoDetais');
        Route::post('rotary/mrnrotary/search', 'rotary\MrnRotaryController@search')->name('mrnRotary.search');     
        Route::get('rotary/mrnrotary/print_mrn/{id}','rotary\MrnRotaryController@print_mrn')->name('mrnRotary.print_mrn');        
        Route::post('rotary/dashboard/chartDetails', 'rotary\DbRotaryController@chartDetails')->name('dbRotary.chartDetails');       
        Route::get('getSizeBrackDown/{scan_wo}','rotary\PrintingRotaryController@getSizeBrackDown');
        Route::get('getWoLst/{scan_wo}','rotary\PrintingRotaryController@getWoLst');
        Route::get('getPrintingDetailsRotary','rotary\PrintingRotaryController@getPrintingDetailsRotary');
        Route::get('getCuttingDetailsRotary','rotary\CuttingRotaryController@getCuttingDetailsRotary');
        Route::get('getPackingDetailsRotary','rotary\PackingRotaryController@getPackingDetailsRotary');
        Route::get('getAqlDetailsRotary','rotary\AqlRotaryController@getAqlDetailsRotary');

    //Rotary End


    // Authentication Routes...
        $this->get('admin/login', 'Auth\LoginController@showLoginForm')->name('login');
        $this->post('admin/login', 'Auth\LoginController@login');
        $this->post('admin/logout', 'Auth\LoginController@logout')->name('logout');
    // Password Reset Routes...
        $this->get('admin/password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.reset');
        $this->post('admin/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
        $this->get('admin/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset.token');
        $this->post('admin/password/reset', 'Auth\ResetPasswordController@reset');   
    //Import Excel File
        Route::get('admin/importexport/index', 'admin\ImportexportController@index')->name('importexport.index');     
        Route::get('downloadExcel/{type}', 'admin\ImportexportController@downloadExcel');
        Route::post('admin/importexport/importExcel', 'Admin\ImportexportController@importExcel')->name('importexport.importExcel');  
        Route::get('planning/importattendance/index', 'planning\ImportattendanceController@index')->name('importattendance.index');
    // Route::get('admin/importExport/importExcel', 'admin\MaatwebsiteDemoController@importExport');     
        Route::post('planning/importattendance/importExcel', 'planning\ImportattendanceController@importExcel')->name('importattendance.importExcel');
        Route::post('planning/pcucharts/importExcel', 'planning\PcuchartController@importExcel')->name('pcucharts.importExcel');
        Route::post('planning/holdwos/importExcel', 'planning\HoldwoController@importExcel')->name('holdwos.importExcel');   
    // Registration Routes...
        $this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
        $this->post('register', 'Auth\RegisterController@register');    
    // Change Password Routes...
        $this->get('change_password', 'Auth\ChangePasswordController@showChangePasswordForm')->name('auth.change_password');
        $this->post('change_password', 'Auth\ChangePasswordController@changePassword')->name('auth.change_password');   
   
        Route::post('admin/permissions/search', 'admin\PermissionsController@search')->name('permissions.search');
        Route::post('admin/roles/search', 'admin\RolesController@search')->name('roles.search');
        Route::post('admin/users/search', 'admin\UsersController@search')->name('users.search');
        Route::post('admin/locations/search', 'admin\LocationsController@search')->name('locations.search');
        Route::post('admin/companies/search', 'admin\CompaniesController@search')->name('companies.search');
   
});

Route::get('/home', 'HomeController@index');