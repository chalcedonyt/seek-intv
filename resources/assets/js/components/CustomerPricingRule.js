const React = require('react')
const api = require('../utils/api')
import { Breadcrumb, Button, Col, FormControl, Grid, Panel, Row } from 'react-bootstrap'

export default class CustomerPricingRule extends React.Component {
  constructor(props) {
    super(props)
    this.state = {
      rule: null,
      displayName: ''
    }
    this.buildRuleComponent = this.buildRuleComponent.bind(this)
    this.handleDisplayNameChange = this.handleDisplayNameChange.bind(this)
    this.handleDisplayNameSuggestion = this.handleDisplayNameSuggestion.bind(this)
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

  handleDisplayNameChange(e) {
    this.setState({
      displayName: e.target.value
    })
  }

  handleDisplayNameSuggestion(displayName) {
    this.setState({
      displayName
    })
  }

  handleUpdate(settings) {
    const params = {
      settings,
      display_name: this.state.displayName
    }
    api.updateCustomerPricingRule(this.props.match.params.ruleId, params)
    .then((data) => {
      this.loadRule()
      alert('Rule updated!')
    }, (error) => {
      alert(error.response.data.error);
    })
  }

  loadRule() {
    api.getCustomerPricingRule(this.props.match.params.ruleId)
    .then((rule) => {
      this.setState({
        rule,
        displayName: rule.display_name
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
                <strong>Customer:</strong>
              </Col>
              <Col md={5} xs={5}>
                <p>{this.state.rule.customer.name}</p>
              </Col>
            </Row>
            <Row>
              <Col md={3} xs={3}>
                <strong>Base Rule:</strong>
              </Col>
              <Col md={5} xs={5}>
                <p>{this.state.rule.pricingRule.display_name}</p>
              </Col>
            </Row>
            <Row>
              <Col md={3} xs={3}>
                <strong>Display rule as:</strong>
              </Col>
              <Col md={5} xs={5}>
                <FormControl type='text' onChange={this.handleDisplayNameChange} value={this.state.displayName} />
              </Col>
            </Row>
          </Grid>
          <RuleComponent
            settings={this.state.rule.pricing_rule_settings}
            onSuggestedDisplayName={this.handleDisplayNameSuggestion}
            onSubmit={this.handleUpdate}
          />
        </Panel>
      }
      </div>
    )
  }
}