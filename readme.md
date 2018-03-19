# Checkout Pricing Simulator

[![Build Status](https://travis-ci.org/chalcedonyt/seek-intv.svg?branch=master)](https://travis-ci.org/chalcedonyt/seek-intv)

## Requirements and installation

* Standard Laravel 5.6 (PHP7.1)
* MySQL5.7 for JSON column type
```
composer install
php artisan migrate --seed #seeds the customers and pricing rules
npm install && npm run dev
php artisan serve
```

## Language/framework considerations

* NodeJS doesn't have built-in type-safety or proper class mechanics for an extensible rule system, hence PHP7
* I expect that with the patterns used, adding new rules or configuring values for existing ones will be easy.

## Patterns and structure

* Used standard Abstract/Interface/inheritance patterns to construct stackable rules (`App\Services\PricingRule`)
* Use of Factories to hydrate rules from a database
* A TransferObject is used to contain the final price as well as any rules applied
* A service provider to contain references to existing rules. (`App\Providers\PricingRuleProvider`)
* Unit tests focused on testing the accuracy of the pricing rules.

## Other notes

* Quick frontend in React to display pricing simulations.
* As we are just testing prices, there is no actual checkout saved at the end, though the prices are simulated.


