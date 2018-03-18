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
      <Table>
        {this.state.rules && this.state.rules.map((rule, i) => (
          <tr>
            <td>{rule.customer.name}</td>
            <td>{rule.display_name}</td>
            <td><Button href={`/customer-pricing-rule/${rule.id}`}>Edit</Button></td>
          </tr>
        ))}
      </Table>
    )
  }
}