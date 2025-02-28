# Installation of the Number Sequence Generator Bundle

## Bundle Installation

To install the Number Sequence Generator Bundle, follow the three steps below:

1) Install the required dependencies:

```bash
composer require pimcore/number-sequence-generator
```

2) Make sure the bundle is enabled in the `config/bundles.php` file. The following lines should be added:

```php
use Pimcore\Bundle\NumberSequenceGeneratorBundle;
// ...
return [
    // ...
    NumberSequenceGeneratorBundle::class => ['all' => true],
    // ...
];  
```

3) Install the bundle:

```bash
bin/console pimcore:bundle:install PimcoreNumberSequenceGeneratorBundle
```