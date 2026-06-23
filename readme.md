# SERVINGLIBRARY.ORG

## INSTALLATION

### php composer
1. move to `static/php/`
2. run `composer update`

### .env
1. create .env at the root directory
2. set its permission to 700 and the owner:group to the web user. e.g., www-data for nginx
3. add the following variables and their values to the file
- `PAYPAL_ALLOWED_ORIGIN`  
the url of the website. e.g., `https://servinglibrary.org`
- `PAYPAL_CLIENT_ID_LIVE_US`, `PAYPAL_CLIENT_SECRET_LIVE_US`, `PAYPAL_CLIENT_ID_LIVE_EU`, `PAYPAL_CLIENT_SECRET_LIVE_EU`  
the paypal credentials. they can be retrieved in "apps & credentials" of the developer dashboards. for imformation: https://developer.paypal.com/api/rest/


