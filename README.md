bitshares/drupal7-plugin
=======================
# About
	
+ Bitshares payments for Drupal 7.
	
# System Requirements

+ Drupal 7 with Ubercart 3
+ PHP 5+

# Installation

1. Copy these files into your Drupal root directory
2. Copy Bitshares Checkout (https://github.com/sidhujag/bitsharescheckout) files into your Drupal root directory, overwrite any existing files.

# Configuration

1. Upload files to the root directory of your magento installation.<br />
2. Under Administration > Modules, verify that the Bitshares module is enabled
  under the Ubercart - payment section.<br />
3. Under Store > Configuration > Payment Methods, enable the Bitshares payment
  method.<br />
4. Fill out config.php with appropriate information and configure Bitshares Checkout<br />
    - See the readme at https://github.com/sidhujag/bitsharescheckout



Usage
-----
When a shopper chooses the Bitshares payment method, they will be redirected to Bitshares Checkout where they will pay an invoice.  Bitshares Checkout will then notify your system that the order was paid for.  The customer will be redirected back to your store.  


# Support

## Plugin Support

* [Github Issues](https://github.com/sidhujag/bitshares-drupal7/issues)
  * Open an Issue if you are having issues with this plugin

## Ubercart Support

* [Homepage](http://www.ubercart.org/)
* [Documentation](http://www.ubercart.org/docs)
* [Forums](http://www.ubercart.org/forum)

# Contribute

To contribute to this project, please fork and submit a pull request.

# License

The MIT License (MIT)

Copyright (c) 2011-2015 Bitshares

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
