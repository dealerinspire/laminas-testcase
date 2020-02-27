# Dealer Inspire Apigility Test Support

Add the Satis repository to your `composer.json`:

```json
{
  "repositories": [
    {
      "type": "composer",
      "url": "https://dealerinspire:<the usual password>@composer.infra.dealerinspire.com/"    
    }
  ]
}
```
Be sure to replace the password above with...you know...the usual password.

Require this package:

```
composer require --dev dealerinspire/dealerinspire-apigility-testcase
```
