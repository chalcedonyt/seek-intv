
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
import CheckoutSimulator from './components/CheckoutSimulator'

ReactDOM.render(
    <BrowserRouter>
      <div>
        <Route exact path='/checkout-simulator' component={CheckoutSimulator} />
      </div>
    </BrowserRouter>
, document.getElementById('checkout-simulator'));