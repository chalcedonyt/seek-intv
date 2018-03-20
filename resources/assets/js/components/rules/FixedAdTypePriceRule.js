const api = require('../../utils/api')
const React = require('react')
const Button = require('react-bootstrap/lib/Button')
const Col = require('react-bootstrap/lib/Col')
const FormControl = require('react-bootstrap/lib/FormControl')
const FormGroup = require('react-bootstrap/lib/FormGroup')
const Grid = require('react-bootstrap/lib/Grid')
const Row = require('react-bootstrap/lib/Row')

class FixedAdTypePriceRule extends React.Component {
  constructor(props) {
    super(props)
    this.state = {
      adTypes: null,
      selectedAdTypeId: '',
      fixedPrice: 0,
    }

    this.handleAdTypeChange = this.handleAdTypeChange.bind(this)
    this.handleChange = this.handleChange.bind(this)
    this.handleFixedPriceChange = this.handleFixedPriceChange.bind(this)
    this.handleSubmit = this.handleSubmit.bind(this)
  }

  componentDidMount() {
    this.setState({
      fixedPrice: this.props.settings.fixedPrice,
      selectedAdTypeId: parseInt(this.props.settings.adTypeId)
    }, () => {
      api.getAdTypes()
      .then(({ad_types: adTypes}) => {
        this.setState({
          adTypes
        })
      })
    })
  }

  getValidationState() {
    return isNaN(this.state.fixedPrice) || this.state.fixedPrice <= 0 ? 'warning': null
  }

  handleAdTypeChange(e) {
    this.setState({
      selectedAdTypeId: e.target.value
    }, this.handleChange)
  }

  handleChange() {
    const adType = this.state.adTypes.find((adType) => adType.id == this.state.selectedAdTypeId)
    this.props.onSuggestedDisplayName(`${adType.display_name}: Fixed price of $${this.state.fixedPrice}`)
  }

  handleFixedPriceChange(e) {
    this.setState({
      fixedPrice: e.target.value
    }, this.handleChange)
  }

  handleSubmit() {
    if (this.getValidationState() != null)
      return
    const params = {
      adTypeId: this.state.selectedAdTypeId,
      fixedPrice: this.state.fixedPrice,
    }
    this.props.onSubmit(params)
  }

  handleThresholdQtyChange(e) {
    this.setState({
      thresholdQty: e.target.value
    })
  }

  render() {
    return (
      <FormGroup controlId="formValidationWarning1" validationState={this.getValidationState()}>
        <Grid>
          <Row>
            <Col md={3} xs={3}>
              <strong>Apply for ad type:</strong>
            </Col>
            <Col md={8} xs={8}>
              <FormControl
                componentClass="select"
                value={this.state.selectedAdTypeId}
                onChange={this.handleAdTypeChange}>
              {this.state.adTypes && this.state.adTypes.map((adType, i) => (
                <option key={i} value={adType.id}>
                  {adType.display_name}
                </option>
              ))}
              </FormControl>
            </Col>
          </Row>
          <Row>
            <Col md={3} xs={3}>
              <strong>Fix price to ($):</strong>
            </Col>
            <Col md={8} xs={8}>
              <Row>
                <Col md={3} xs={3}>
                  <FormControl type="text" onChange={this.handleFixedPriceChange} value={this.state.fixedPrice}></FormControl>
                </Col>
              </Row>
            </Col>
          </Row>
          <Row>
            <Col md={4} mdOffset={8} xs={4} xsOffset={8}>
              <Button bsStyle='info' onClick={this.handleSubmit}>Apply</Button>
            </Col>
          </Row>
        </Grid>
      </FormGroup>
    )
  }
}

module.exports = FixedAdTypePriceRule