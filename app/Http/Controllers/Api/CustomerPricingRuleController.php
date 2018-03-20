<?php

namespace App\Http\Controllers\Api;

use App\Models\CustomerPricingRule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Validator;

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
        $data = fractal()->includeCustomer()
        ->includePricingRule()
        ->item($customerPricingRule, new \App\Transformers\CustomerPricingRuleTransformer)
        ->toArray();

        return response()->json($data);
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
        $factoryClass = app('PricingRuleFactories')[$customerPricingRule->pricingRule->provider_alias];
        $settingData = json_decode($customerPricingRule->pricing_rule_settings, $assoc = true);
        $inputValidation = \Validator::make($request->all(), [
            'display_name' => 'string|required|min:5'
        ]);

        $rule = $factoryClass::fromArray($settingData);
        $ruleValidation = $rule->getValidation($request->input('settings'));
        if ($inputValidation->fails() || $ruleValidation->fails()) {
            $errorMsgs = collect($ruleValidation->errors())
            ->concat(collect($inputValidation->errors()))
            ->flatten()
            ->implode("\r\n");
            return response()->json([
                'error' => $errorMsgs
            ], 422);
        }
        $customerPricingRule->pricing_rule_settings = json_encode($request->input('settings'));
        $customerPricingRule->display_name = $request->input('display_name');
        $customerPricingRule->save();

        return $this->show($customerPricingRule);
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
