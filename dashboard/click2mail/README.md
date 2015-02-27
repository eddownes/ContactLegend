php-mol-pro-api
===============

PHP example of using the Click2Mail MOLPRO SOAP API.

To run the test you will need to have PHP 5.2 or later and [PHPUnit](https://github.com/sebastianbergmann/phpunit/).

In order to run this test you will first need an account with API access at Click2Mail.com.

You can find the documentation here...

[Mol Pro SOAP API](https://developers.click2mail.com/soap-api/molpro/getting-started)

The logic beginning on line 100 of SubmitOrders.php opens an address list and parses its contents. Please make sure the location of the list is set (full path) in run.sh. If you do not want to use a list, and would like to hardcode the data,
enter "NO LIST" for the list path, and the parsing logic will be skipped. If using a list, be sure to always have the correct mapping details.

If not using a list and hardcoding the addresses, follow the example on line 96.

Make sure to review the run.sh and update your username and password before running the test.

To run the test...

bash run.sh

This test will run through all of the steps need to submit a mailing using the API and submit an order.