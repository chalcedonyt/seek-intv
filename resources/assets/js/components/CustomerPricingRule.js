const React = require('react')
const api = require('../utils/api')
import { Button, Table } from 'react-bootstrap'

export default class CustomerPricingRule extends React.Component {
  constructor(props) {
    super(props)
    this.state = {
      rules: null
    }
  }

  componentDidMount() {
    api.getCustomerPricingRule(this.props.match.params.ruleId)
    .then(({ }) => {
      this.setState({
        rules
      })
    })
  }

  render() {
    return (
      <div>

      </div>
    )
  }
}