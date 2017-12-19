<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


 

/* Account registration */
Route::post('/accounts/register', ['uses' => 'AccountsController@createAccount']);

/* Password reset */
Route::post('/password/request-recovery', 'Auth\ForgotPasswordController@getResetToken');
Route::post('/password/reset', 'Auth\ResetPasswordController@reset');

/* Tenant requests */
Route::middleware(['auth:api','switchDBConnection'])->group(function(){
    Route::get('/user-permissions', "UserController@getUserPermissions");
    Route::get('/navigation-menu', "UserController@getNavigationMenu");
    Route::get('/user/get-account', 'AccountsController@getUserAccountId');
    Route::get('/get-accounts', 'TestController@getAccounts');
    Route::post('/create-user', ['uses' => 'AccountsController@createUser']);
    Route::post('/admin/images/upload/{parentType}/{parentId}', ['uses' => 'FilesController@uploadImage']);
    Route::get('/get-product-list', ["uses" => "PosController@getProductList"]);
    Route::get('/load-order', ["uses" => "PosController@loadOrder"]);
    Route::post('/add-order-line', ["uses" => "PosController@addOrderLine"]); 
    Route::post('/update-order-line', ["uses" => "PosController@updateOrderLine"]); 
    Route::get('/remove-order-line/{orderLineId}', ["uses" => "PosController@removeOrderLine"]); 
    Route::post('/discard-order', ["uses" => "PosController@discardOrder"]);
    Route::post('/park-order', ["uses" => "PosController@parkOrder"]);
    Route::get('/reopen-order/{orderId}', ["uses" => "PosController@reopenOrder"]);
    
    Route::get('/get-relations', ["uses" => "PosController@getRelations"]);
    Route::post('/add-relation-to-order', ["uses" => "PosController@addRelationToOrder"]);
    Route::post('/remove-relation-from-order', ["uses" => "PosController@removeRelationFromOrder"]);
    Route::get('/load-relation', ["uses" => "PosController@loadRelation"]);      
    Route::post("/order/pay", ["uses" => "PaymentsController@payOrder"]);
    Route::post("/order/register-paid-order",["uses" => "PaymentsController@registerPaidOrder"]);
    
});    
Route::get('/get-resource-list/{resourceType}',[
    "middleware" => ['auth:api','switchDBConnection', 'checkReadPermission'],
    "uses" => "ResourcesController@getResourceList"]
);
Route::get('/get-resource/{resourceType}/{resourceId}',[
    "middleware" => ['auth:api','switchDBConnection','checkReadPermission'],
    "uses" => "ResourcesController@getResource"]
);
Route::get('/get-ecommerce-resource/{resourceType}/{resourceId}',[
    "middleware" => ['auth:api','switchDBConnection','checkEcommerceReadPermission'],
    "uses" => "ResourcesController@getEcommerceResource"]
);
Route::post('/save-resource/{resourceType}',[
    "middleware" => ['auth:api','switchDBConnection','checkSavePermission'],
    "uses" => "ResourcesController@saveResource"
]);
Route::post('/save-compound-resource/{resourceType}',[
    "middleware" => ['auth:api','switchDBConnection','checkSavePermission'],
    "uses" => "ResourcesController@saveCompoundResource"
]);
Route::post('/save-ecommerce-resource/{resourceType}',[
    "middleware" => ['auth:api','switchDBConnection','checkEcommerceSavePermission'],
    "uses" => "ResourcesController@saveEcommerceResource"
]);
Route::post('/delete-resource/{resourceType}',[
    "middleware" => ['auth:api','switchDBConnection','checkDeletePermission'],
    "uses" => "ResourcesController@deleteResource"
]);
Route::get('/get-resource-dropdown-options/{resourceType}', [
    "middleware" => ['auth:api','switchDBConnection','checkReadPermission'],
    'uses' => 'ResourcesController@getDropdownOptions'
]);
Route::post('/sort-resource-list/{resourceType}', [
    "middleware" => ['auth:api','switchDBConnection','checkReadPermission'],
    'uses' => 'ResourcesController@sortList'        
]);
