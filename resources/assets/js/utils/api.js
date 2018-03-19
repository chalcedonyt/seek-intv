const __BASE_API_URL = '/api';
const axios = require('axios')
const QueryString = require('query-string')

module.exports = {
  getCustomerPricingRules: () => {
    const encodedURI = window.encodeURI(`${__BASE_API_URL}/customer-pricing-rules`);
    return axios.get(encodedURI)
      .then(function (response) {
        return response.data;
      });
    },

    getCustomerPricingRule: (ruleId) => {
      const encodedURI = window.encodeURI(`${__BASE_API_URL}/customer-pricing-rule/${ruleId}`);
      return axios.get(encodedURI)
        .then(function (response) {
          return response.data;
        });
    },

    getCustomers: () => {
      const encodedURI = window.encodeURI(`${__BASE_API_URL}/customers`);
      return axios.get(encodedURI)
        .then(function (response) {
          return response.data;
        });
    },

    getAdTypes: () => {
      const encodedURI = window.encodeURI(`${__BASE_API_URL}/ad-types`);
      return axios.get(encodedURI)
        .then(function (response) {
          return response.data;
        });
    }
}