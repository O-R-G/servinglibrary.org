# Serving Library

A digital platform for managing collections and enabling online commerce.

## Installation

### PHP Dependencies

1. Navigate to `static/php/`
2. Run `composer update`

### Environment Configuration

1. Create a `.env` file at the root directory
2. Set permissions to `700` and owner/group to your web user (e.g., `www-data:www-data` for nginx)
3. Add the following environment variables:
   - `PAYPAL_ALLOWED_ORIGIN`: The website URL (e.g., `https://servinglibrary.org`)
   - `PAYPAL_CLIENT_ID_LIVE_US`, `PAYPAL_CLIENT_SECRET_LIVE_US`, `PAYPAL_CLIENT_ID_LIVE_EU`, `PAYPAL_CLIENT_SECRET_LIVE_EU`: PayPal credentials from your developer dashboard. See [PayPal API Documentation](https://developer.paypal.com/api/rest/)

### Cache Directory

1. Create a `.cache` folder at the root directory
2. Set permissions to `700` and owner/group to your web user (e.g., `www-data:www-data` for nginx)


