const React = require('react')
const api = require('../utils/api')
import { Alert, Breadcrumb, Button, ButtonGroup, Col, Glyphicon, Grid, ListGroup, ListGroupItem, Panel, Row } from 'react-bootstrap'

export default class CustomerPricingRules extends React.Component {
  constructor(props) {
    super(props)
    this.state = {
      adTypes: null,
      checkoutItems: [],
      customers: null,
      selectedCustomer: null,
      simulatedPrice: null
    }
    this.handleCustomerSelect = this.handleCustomerSelect.bind(this)
    this.handleItemCheckout = this.handleItemCheckout.bind(this)
    this.handleItemReset = this.handleItemReset.bind(this)
    this.simulateCheckoutPrices = this.simulateCheckoutPrices.bind(this)
  }

  componentDidMount() {
    api.getCustomers()
    .then(({ customers}) => {
      this.setState({
        customers
      })
    })

    api.getAdTypes()
    .then(({ ad_types: adTypes}) => {
      this.setState({
        adTypes
      })
    })
  }

  handleCustomerSelect(selectedCustomer) {
    this.setState({
      selectedCustomer,
      checkoutItems: []
    })
  }

  handleItemCheckout(adType) {
    this.setState({
      checkoutItems: this.state.checkoutItems.concat(adType)
    }, () => {
      this.simulateCheckoutPrices()
    })
  }

  handleItemReset() {
    this.setState({
      checkoutItems: [],
      simulatedPrice: null
    })
  }

  simulateCheckoutPrices() {
    const items = this.state.checkoutItems.map((adType) => {
      return {ad_type_id: adType.id}
    })
    api.simulateCheckoutPrices(this.state.selectedCustomer.id, items)
    .then(({price: simulatedPrice}) => {
      this.setState({
        simulatedPrice
      })
    })
  }

  render() {
    return (
      <div>
        <Breadcrumb>
          <Breadcrumb.Item href="/">Home</Breadcrumb.Item>
          <Breadcrumb.Item active>Checkout Simulator</Breadcrumb.Item>
        </Breadcrumb>
        <h1>Checkout Simulator</h1>

        <Row>
          <Col md={6}>
            <Panel>
              <Panel.Heading>
                Choose a customer to simulate
              </Panel.Heading>
              <Panel.Body>
                <ListGroup>
                  {this.state.customers && this.state.customers.map((customer, i) => (
                    <li className='list-group-item' key={i}>
                      <Row>
                        <Col md={5} xs={5}>
                          <h4 className='list-group-item-heading'>{customer.name}</h4>
                        </Col>
                        <Col mdOffset={10} xsOffset={9}>
                          <Button bsStyle='info' onClick={(e) => this.handleCustomerSelect(customer)}>
                            Simulate <Glyphicon glyph='chevron-right' />
                          </Button>
                        </Col>
                      </Row>
                      <ul>
                        {Array.isArray(customer.pricingRules) && customer.pricingRules.map((rule, i) => (
                          <li key={i}>
                            <Button
                              bsStyle='link'
                              href={`/customer-pricing-rule/${rule.id}`}>
                              {rule.display_name}
                            </Button>
                          </li>
                        ))}
                      </ul>
                    </li>
                  ))}
                </ListGroup>
              </Panel.Body>
            </Panel>
          </Col>
          <Col md={6}>
          {this.state.selectedCustomer && (
            <Panel>
              <Panel.Heading>
                Simulate a checkout for {this.state.selectedCustomer.name}
              </Panel.Heading>
              <Panel.Body>
                <h3>Select an SKU to scan:</h3>
                <ButtonGroup bsSize='large'>
                  {Array.isArray(this.state.adTypes) && this.state.adTypes.map((adType, i) => (
                    <Button
                      key={i}
                      bsStyle='success'
                      onClick={(e) => this.handleItemCheckout(adType)}>
                      <Glyphicon glyph='plus' />
                      {adType.display_name}
                    </Button>
                  ))}
                </ButtonGroup>
                <Panel>
                  <Panel.Heading>
                    {this.state.checkoutItems.length == 0
                    ? 'Select an ad SKU to checkout above'
                    :
                      <p>
                        {`${this.state.checkoutItems.length} ad(s) in checkout`}
                        <Button bsStyle='link' onClick={this.handleItemReset}>Reset</Button>
                      </p>
                    }
                  </Panel.Heading>
                  <Panel.Body>
                    <ListGroup>
                      {this.state.checkoutItems.map((item, i) => (
                        <li className='list-group-item' key={i}>
                          {item.display_name}
                        </li>
                      ))}
                    </ListGroup>
                  </Panel.Body>
                  {this.state.simulatedPrice && (
                    <Panel.Footer>
                      <Alert bsStyle="success">
                        Total : <strong>${this.state.simulatedPrice}</strong>
                      </Alert>
                    </Panel.Footer>
                  )}
                </Panel>
              </Panel.Body>
            </Panel>
          )}
          </Col>
        </Row>
      </div>
    )
  }
}