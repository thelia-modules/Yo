# YO

This module send a Yo when an order is created. You need to know your Api key and the username who will receive the Yo notification.

## Installation

### Manually

* Copy the module into ```<thelia_root>/local/modules/``` directory and be sure that the name of the module is Yo.
* Activate it in your thelia administration panel

### Composer

Add it in your main thelia composer.json file

```
composer require thelia/yo:~1.0
```

## Usage

In the configuration panel of this module, you can record your Api key and the username

## How to retrieve my Api Key

You need to connect to this url [http://dev.justyo.co/](http://dev.justyo.co/) and then copy your Api Key

## Client Api

This module comes with a small client Api for the Yo webservice, you can send a Yo in all your Thelia application.

### How to use it

```
$yo = new \Yo\Client('Your_api_key');

//send a Yo to user FOO
$yo->yo('FOO');

//send a Yo to all your contacts
$yo->yoAll();
```
