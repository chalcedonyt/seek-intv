@extends('shared.master')

@section('title', 'Checkout Simulation')
@section('scripts')
<div id="checkout-simulator"></div>
<script src="{{mix('js/checkout-simulator.js')}}"></script>
@endsection