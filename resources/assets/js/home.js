
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
import {Button, Jumbotron} from 'react-bootstrap'

ReactDOM.render(
  <BrowserRouter>
      <Jumbotron>
        <h1>Customer Pricing Rules Simulator</h1>
        <p>
          Choose <em>Pricing Rules</em> to review and customize pricing rules.
        </p>
        <p>
          Choose <em>Checkout Simulator</em> to test the rules and check out items.
        </p>
        <Button href="/customer-pricing-rules" bsStyle='info'>Pricing Rules</Button>
        <Button href="/checkout-simulator" bsStyle='success'>Checkout Simulator</Button>
      </Jumbotron>
    </BrowserRouter>
, document.getElementById('home'));