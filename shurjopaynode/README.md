# shurjoPay2 integration for nodejs sites

## Checkout, Verify

- Generate token for merchants
- perform checkout
- Check order status
- Verify order id

## Requirement
- nodejs >=13.0

## Install

```shell
# npm versions > 5
npm i shurjopay
```

## Docs/Usage

__Example integration use case scenario is expressCart(https://github.com/mrvautin/expressCart)__

Loading the module gets us a factory function, calling it instantiates the module.

```javascript
const sp_factory = require('shurjopay');
const shurjopay = sp_factory();

//  The minimum config/environment variables that your site need to maintain(config related to shurjopay)
const spConfig = {
    "client_id": "your merchant id",
    "client_secret": "your merchant password or secret token",
    "client_key_prefix": "sp",
    "currency": "BDT"
}

shurjopay.is_live();
shurjopay.configure_merchant(spConfig.client_id, spConfig.client_secret, spConfig.client_key_prefix, spConfig.currency);

// Now proceed with the rest of your route and controller logic. 
```

### The instance object provides methods and attributes that you need to use are:

- Payment operation methods: `checkout`, `verify`, `check_status`, `token_valid`.
- Configuration methods: `configure_merchant`, `is_live`
- Error handler methods: `gettoken_error_handler`, `checkout_error_handler`
- Callback handler methods: `checkout_callback`
- Module's own settings object: `sp.settings`. You may only check this for debug.

### In your route controllers, your workflow is (using three routes):

- At checkout->payment method, selecting shurjopay lands the buyer in _checkout_action_ route, where you initiate the transaction with cart details
- At first set the plugin environment to live by calling `shurjopay.is_live()` 
- Now configure the merchant info with `shurjopay.configure_merchant(merchant_username, merchant_password, merchant_key_prefix, merchant_default_currency)`
- Then set error handlers 
- Then call the checkout method with order data
```javascript
    // req: http request object
    // res: http response object
    shurjopay.gettoken_error_handler = function (error){ 
        req.session.message = `There was an error processing your payment. You have not been charged and can try again.${error}`;
        req.session.messageType = 'danger';
        console.log(error);
        res.redirect('/checkout/information');
    };
    shurjopay.checkout_error_handler = function (error){
        req.session.message = `There was an error processing your payment. You have not been charged and can try again.${error}`;
        req.session.messageType = 'danger';
        console.log(error);
        res.redirect('/checkout/information');
    };

    // now trigger the checkout
    shurjopay.checkout({
        amount: req.session.totalCartAmount,
        order_id: order_id,
        return_url: `${your_store_config.baseUrl}/shurjopay/checkout_return`,
        cancel_url: `${your_store_config.baseUrl}/shurjopay/checkout_cancel`,
        customer_name: `${req.session.customerFirstname} ${req.session.customerLastname}`,
        customer_address: req.session.customerAddress1,
        customer_phone: req.session.customerPhone,
        customer_city: req.session.customerState,
        customer_post_code: req.session.customerPostcode,
        client_ip: 'unknown'
    }, (response_data, checkout_url) => {
        // Now work with your session, cart, database insert and then redirect to checkout_url
    });
```

### A fully functional route controller is provided as example (expressjs-mongodb)

Check over the /examples folder.

### Some sample api response formats, for debug purpose

check the /docs folder.

<!--
## Contact

Minhajul Anwar; [resgef.com][resgef-url], Dhaka, Bangladesh.
<br>**Email:** [contact@resgef.com](mailto:contact@resgef.com)

## Questions or need help?

Come talk to us on the [GitHub discussion][gh-discussion]

## Social Media and links

[Twitter](https://twitter.com/intent/follow?original_referer=https%3A%2F%2Fgithub.com%2FMinhajulAnwar&screen_name=MinhajulAnwar) &nbsp;&nbsp;
[GitHub-Blog](https://minhajme.github.io/blog/) &nbsp;&nbsp;
-->
<br>[https://resgef.com][resgef-url] &nbsp;&nbsp;

[ff-introsite-gh-pages]: https://freightforward.github.io

[ff-doc-gh-pages]: https://freightforward.github.io/docs/

[gh-discussion]: https://github.com/minhajme/sp2nodejs/discussions

[dev-gh]: https://github.com/minhajme

[resgef-url]: https://resgef.com
