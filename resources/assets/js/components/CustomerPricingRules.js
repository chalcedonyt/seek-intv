const React = require('react')
const api = require('../utils/api')
import { Button, Table } from 'react-bootstrap'

export default class CustomerPricingRules extends React.Component {
  constructor(props) {
    super(props)
    this.state = {
      rules: null
    }
  }

  componentDidMount() {
    api.getCustomerPricingRules()
    .then(({ customer_pricing_rules: rules}) => {
      this.setState({
        rules
      })
    })
  }

  render() {
    return (
      <div>
        <h1>Customer Pricing Rules</h1>
        <Table>
          <thead>
            <tr>
              <th>Customer</th>
              <th>Rule</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
          {this.state.rules && this.state.rules.map((rule, i) => (
            <tr key={i}>
              <td>{rule.customer.name}</td>
              <td>{rule.display_name}</td>
              <td>
                <Button bsStyle='info' href={`/customer-pricing-rule/${rule.id}`}>Edit</Button>
              </td>
            </tr>
          ))}
          </tbody>
        </Table>
      </div>
    )
  }
}