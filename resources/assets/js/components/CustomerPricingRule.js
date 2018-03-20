const React = require('react')
const api = require('../utils/api')
import { Breadcrumb, Button, Col, FormControl, Grid, Panel, Row } from 'react-bootstrap'

export default class CustomerPricingRule extends React.Component {
  constructor(props) {
    super(props)
    this.state = {
      rule: null
    }
    this.buildRuleComponent = this.buildRuleComponent.bind(this)
    this.handleUpdate = this.handleUpdate.bind(this)
    this.loadRule = this.loadRule.bind(this)
  }

  componentDidMount() {
    this.loadRule()
  }

  buildRuleComponent(pricingRule) {
    switch (pricingRule.provider_alias) {
      case 'x_for_the_price_of_y':
        return require('./rules/XForThePriceOfYRule')
      case 'fixed_for_ad_type':
        return require('./rules/FixedAdTypePriceRule')
      default:
        return require('./rules/FixedAdTypePriceWithMinQtyRule')
    }
  }

  handleUpdate(params) {
    api.updateCustomerPricingRule(this.props.match.params.ruleId, params)
    .then((data) => {
      this.loadRule()
    }, ({error}) => {
      alert(error);
    })
  }

  loadRule() {
    api.getCustomerPricingRule(this.props.match.params.ruleId)
    .then((rule) => {
      this.setState({
        rule
      })
    })
  }

  render() {
    if (this.state.rule && this.state.rule.pricingRule ) {
      var RuleComponent = this.buildRuleComponent(this.state.rule.pricingRule)
    }

    return (
      <div>
        <Breadcrumb>
          <Breadcrumb.Item href="/">Home</Breadcrumb.Item>
          <Breadcrumb.Item href="/customer-pricing-rules">Customer Pricing Rules</Breadcrumb.Item>
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
          </Grid>
          <RuleComponent
            settings={this.state.rule.pricing_rule_settings}
            onSubmit={this.handleUpdate}
          />
        </Panel>
      }
      </div>
    )
  }
}