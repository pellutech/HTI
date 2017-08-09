<?php

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

Route::get('/', function () {
    return view('login');
});
Route::get('dashboard', 'DashboardController@showdashboard');
Route::get('configurations/newinstitution', 'InstituitionController@newinstituitiion');
Route::get('configurations/institutions', 'InstituitionController@showinstitutions');
Route::get('configurations/districts', 'ConfigurationController@showdistricts');
Route::get('configurations/testdb', 'ConfigurationController@testdb');
Route::get('configurations/regiondistricts', 'ConfigurationController@showRegionDistricts');
Route::get('configurations/departments', 'ConfigurationController@showdepartments');
Route::get('configurations/gradetypes', 'ConfigurationController@showGradeTypes');
Route::get('configurations/institutiontypes', 'ConfigurationController@showInstitutionTypes');
Route::get('staff/new/{code}', 'StaffController@showprincipal');
Route::get('staff/new', 'StaffController@showstaff');
Route::get('staff/all', 'StaffController@showallstaff');
Route::get('account/usergroups', 'AccountController@showUserGroups');
Route::get('account/assignroles', 'AccountController@showrolesandpermissions');
Route::get('account/users', 'AccountController@showusers');
Route::get('/logout', function(Request $request) {
        //Uncomment to see the logs record
        //\Log::info("Session before: ".print_r($request->session()->all(), true));
         Session::flush();
        //Uncomment to see the logs record
        //\Log::info("Session after: ".print_r($request->session()->all(), true));
        return redirect('/');
    });




//apis
Route::get('configurations/getdistricts', 'ConfigurationController@getDistricts');
Route::delete('configurations/district/{id}', 'ConfigurationController@deleteDistrict');
Route::put('configurations/district', 'ConfigurationController@updateDistrict');
Route::post('configurations/district', 'ConfigurationController@saveDistrict');
Route::get('configurations/getregions', 'ConfigurationController@getRegions');
Route::get('configurations/getunassigneddistricts', 'ConfigurationController@getUnAssignedDistricts');
Route::post('configurations/regiondistrict', 'ConfigurationController@saveRegionDistricts');
Route::get('configurations/regiondistrict', 'ConfigurationController@getRegionDistricts');
Route::delete('configurations/regiondistrict/{id}', 'ConfigurationController@deleteRegionDistrict');
Route::get('configurations/getdepartments', 'ConfigurationController@getDepartments');
Route::post('configurations/department', 'ConfigurationController@saveDepartment');
Route::put('configurations/department', 'ConfigurationController@updateDepartment');
Route::delete('configurations/department/{id}', 'ConfigurationController@deleteDepartment');
Route::get('configurations/getinstituitionstypes', 'ConfigurationController@getInstituitionsTypes');
Route::post('configurations/instituitiontype', 'ConfigurationController@saveInstituitionType');
Route::put('configurations/instituitiontype', 'ConfigurationController@updateInstituitionType');
Route::delete('configurations/instituitiontype/{id}', 'ConfigurationController@deleteInstituitionType');
Route::get('configurations/getgradetypes', 'ConfigurationController@getGradeTypes');
Route::post('configurations/gradetype', 'ConfigurationController@saveGradeType');
Route::put('configurations/gradetype', 'ConfigurationController@updateGradeType');
Route::delete('configurations/gradetype/{id}', 'ConfigurationController@deleteGradeType');
Route::get('configurations/getinstitutions', 'ConfigurationController@getInstituitions');
Route::get('configurations/institution/{id}', 'ConfigurationController@getInstituitionDetail');
Route::get('configurations/getdistrictsonregioncode/{code}', 'ConfigurationController@getDistrictsOnRegion');
Route::post('configurations/instituite', 'ConfigurationController@saveInstitute');
Route::post('staff/savestaffinfo', 'StaffController@saveStaff');
Route::delete('configurations/institution/{id}', 'ConfigurationController@deleteInstituition');
Route::get('staff/getstaff', 'StaffController@getStaff');
Route::delete('staff/deletestaff/{id}', 'StaffController@deleteStaff');
Route::get('account/getusergroups', 'AccountController@getUserGroups');
Route::delete('account/usergroup/{id}', 'AccountController@deleteUserGroup');
Route::put('account/usergroup', 'AccountController@updateUserGroup');
Route::post('account/usergroup', 'AccountController@saveUserGroup');
Route::get('account/permissions', 'AccountController@getPermissions');
Route::get('account/retreivepermissions/{id}', 'AccountController@getGroupPermissions');
Route::post('account/assigngrouproles', 'AccountController@assignGroupRoles');
Route::get('account/getusers', 'AccountController@getUsers');
Route::get('account/user/{id}', 'AccountController@getUserInfo');
Route::put('account/userinfo', 'AccountController@updateUserInfo');
Route::post('login/authenticateuser', 'LoginController@authenticateUser');
Route::post('login/updatepassword', 'LoginController@updatePassword');

//savestaffinfo





Route::get('posts/{something}', function () {
    //
})->middleware(['down.for.maintenance']);

Route::get('role', [
    'middleware' => 'Role:editor',
    'uses' => 'TestController@index',
]);



Route::put('configurations/institution/{id}', function ($id) {
    $response = array();
    $update = App\Instituition::find($id);
    $update->active = '0';
    $update->save();
    $response['success'] = 0;
    return GuzzleHttp\json_encode($response);
});






Route::get('configurations/getdepartments', function () {

    return App\Department::where('active', 0)
                    ->get();
});



//InstituitionController
////institutions
//Route::get('newuser', function () {
//    return view('login');
//});