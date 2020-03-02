# Dealer Inspire Laminas Test Support

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
composer require --dev dealerinspire/laminas-testcase
```

##Deployment

Merge to master. Tag the commit. Update Satis.

##Testing

```
composer install
./vendor/bin/phpunit
```
