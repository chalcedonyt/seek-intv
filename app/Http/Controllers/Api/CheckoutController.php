<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Services\CheckoutItemCollection\CheckoutItemCollection;
use App\Services\CheckoutItemCollection\CheckoutItemCollectionFactory;

use App\Models\Checkout;
use App\Models\CheckoutItem;

class CheckoutController extends Controller
{
    protected $factory;

    public function __construct(CheckoutItemCollectionFactory $factory)
    {
        $this->factory = $factory;
    }

    public function simulate(Request $request)
    {
        $customer = \App\Models\Customer::find($request->input('customer_id'));

        $itemCollection = $this->factory::createForCustomer($customer);
        foreach ($request->input('items') as $adType) {
            $checkoutItem = factory(CheckoutItem::class)->make([
                'ad_type_id' => $adType['ad_type_id'],
            ]);
            $itemCollection->addItem($checkoutItem);
        }
        $resolvedPrice = $itemCollection->resolve();

        $data = fractal()->item($resolvedPrice, new \App\Transformers\ResolvedPriceTransformer)->toArray();
        return response()->json($data);
    }
}
