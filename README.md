# Ethereum Rates
This PHP script allows you to create your own Ethereum ticker & calculator website. Supporting 79 currencies with the ability to change the default currency, website color scheme and more.

## Features
- Supporting 79 currencies
- API updates every 60 seconds
- JSON Data Cache
- Change the default currency/calculator (index page)
- Change the popular currencies (in the header)
- Individual currency pages
- Mobile Responsive Design
- Configurable title and description
- Configurable color scheme

## API Information
Ethereum Rates comes with a built in API. However, by default this API is set to disallow javascript cross-origin requests.

- api/v1/rates – Which will give you all the current rates
- api/v1/rates/{currency} – Which will give you the current rates for that currency (ex: api/v1/rates/usd)
- api/v1/calculate/{amount}/{currency} – Which will give you the current rate for x amount of ethereums (ex: api/v1/calculate/2/usd)

## Desktop
![Example Print1](https://raw.githubusercontent.com/s3spyd3r/Ethereum_Rates/master/Screens/screencapture-Desktop.png)

## Mobile
![Example Print2](https://raw.githubusercontent.com/s3spyd3r/Ethereum_Rates/master/Screens/screencapture-Mobile.png)
