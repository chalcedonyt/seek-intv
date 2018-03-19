
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes React and other helpers. It's a great starting point while
 * building robust, powerful web applications using React + Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh React component instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const React = require('react')
const ReactDOM = require('react-dom')
import {Route, BrowserRouter, Switch} from 'react-router-dom'
import {Button} from 'react-bootstrap'

ReactDOM.render(
  <BrowserRouter>
      <div>
        <h1>Customer Pricing Rules</h1>
        <Button href="/customer-pricing-rules">Pricing Rules</Button>
        <Button href="/checkout-simulator">Checkout Simulator</Button>
      </div>
    </BrowserRouter>
, document.getElementById('home'));