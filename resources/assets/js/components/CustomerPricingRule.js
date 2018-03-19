const React = require('react')
const api = require('../utils/api')
import { Breadcrumb, Button, Col, FormControl, Grid, Panel, Row } from 'react-bootstrap'

export default class CustomerPricingRule extends React.Component {
  constructor(props) {
    super(props)
    this.state = {
      rule: null
    }
  }

  componentDidMount() {
    api.getCustomerPricingRule(this.props.match.params.ruleId)
    .then((rule) => {
      console.log(rule)
      this.setState({
        rule
      })
    })
  }

  render() {
    return (
      <div>
        <Breadcrumb>
          <Breadcrumb.Item href="/">Home</Breadcrumb.Item>
          <Breadcrumb.Item active>Rule: {this.state.rule && this.state.rule.display_name}</Breadcrumb.Item>
        </Breadcrumb>
        {this.state.rule &&
        <Panel>
          <h1>{this.state.rule.display_name}</h1>
          <Grid>
            <Row>
              <Col md={3} xs={3}>
                <strong>Base Rule:</strong>
              </Col>
              <Col md={3} xs={3}>
                <p>{this.state.rule.pricingRule.display_name}</p>
              </Col>
            </Row>
            <Row>
              <Col md={3} xs={3}>
                <strong>Settings:</strong>
              </Col>
              <Col md={3} xs={3}>
                <ul>
                  {Object.keys(this.state.rule.pricing_rule_settings).map((key, i) => (
                    <li key={i}>{key}: {this.state.rule.pricing_rule_settings[key]}</li>
                  ))}
                </ul>
              </Col>
            </Row>
          </Grid>
        </Panel>
      }
      </div>
    )
  }
}