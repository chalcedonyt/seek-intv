<?php

namespace App\Http\Controllers\Api;

use App\Models\CustomerPricingRule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerPricingRuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rules = CustomerPricingRule::with('customer', 'pricingRule')->get();

        $data = fractal()->includeCustomer()
        ->includePricingRule()
        ->collection($rules, new \App\Transformers\CustomerPricingRuleTransformer, 'customer_pricing_rules')
        ->toArray();

        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CustomerPricingRule  $customerPricingRule
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerPricingRule $customerPricingRule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CustomerPricingRule  $customerPricingRule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomerPricingRule $customerPricingRule)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CustomerPricingRule  $customerPricingRule
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerPricingRule $customerPricingRule)
    {
        //
    }
}
