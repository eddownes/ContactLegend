#!/bin/bash

 export C2M_USERNAME=username
 export C2M_PASSWORD=password
 export C2M_URL=https://stage-soap.click2mail.com
 export C2M_PDF=postcard.pdf
 export C2M_DOC_TYPE="Postcard 4.25 x 6"
 export ADDRESS_LIST_LOCATION="NO LIST"
 export C2M_JOB_TEMPLATE=12823
 export C2M_DATA_TEMPLATE=459

phpunit tests/SubmitOrders.php