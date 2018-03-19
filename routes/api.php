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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/checkout/simulate', 'Api\\CheckoutController@simulate');
Route::get('/ad-types', 'Api\\AdTypeController@index');
Route::get('/customers', 'Api\\CustomerController@index');
Route::get('/customer-pricing-rules', 'Api\\CustomerPricingRuleController@index');
Route::get('/customer-pricing-rule/{customerPricingRule}', 'Api\\CustomerPricingRuleController@show');
