# Number Sequence Generator Bundle

Generates continous numbers for example for order numbers or customer numbers.

```php
$generator = \Pimcore::getContainer()->get(\Pimcore\Bundle\NumberSequenceGeneratorBundle\Generator::class);

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
```