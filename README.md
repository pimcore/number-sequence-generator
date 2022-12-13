# Number Sequence Generator Bundle

## Continues numbers

Generates continous numbers for example for order numbers or customer numbers.

```php
public function exampleAction(Pimcore\Bundle\NumberSequenceGeneratorBundle\Generator $generator) {
    /*
    * Generates the next order number (increments current order number by 1)
    * If no order number was generated before it will start with 10000
    */
    $next = $generator->getNext('ordernumber', 10000);

    /*
    * Receive the current order number without incrementing the counter.
    */
    $current = $generator->getCurrent('ordernumber');

    /*
    * Sets the order number to 35017 in the database.
    */
    $generator->setCurrent('ordernumber', 35017);
}
```
## Random numbers (either numeric or alphanumeric)

Generates unique random numbers.

```php
public function __construct(Generator $generator)
{
    $this->generator = $generator;
}

public function generateCode()
{
    $code = $this->generator->generateCode("vouchercode", \Pimcore\Bundle\NumberSequenceGeneratorBundle\RandomGenerator::ALPHANUMERIC, 32);
}
```

## Upgrade to Pimcore XI
- Update to latest (allowed) bundle version in Pimcore X and execute all migrations.
- Then update to Pimcore XI.
