<?php

/**
 * This source file is available under the terms of the
 * Pimcore Open Core License (POCL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 *  @copyright  Copyright (c) Pimcore GmbH (https://www.pimcore.com)
 *  @license    Pimcore Open Core License (POCL)
 */

namespace Pimcore\Bundle\NumberSequenceGeneratorBundle;

use Pimcore\Bundle\NumberSequenceGeneratorBundle\DependencyInjection\NumberSequenceGeneratorExtension;
use Pimcore\Extension\Bundle\AbstractPimcoreBundle;
use Pimcore\Extension\Bundle\Traits\PackageVersionTrait;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

class NumberSequenceGeneratorBundle extends AbstractPimcoreBundle
{
    use PackageVersionTrait;

    protected function getComposerPackageName(): string
    {
        return 'pimcore/number-sequence-generator';
    }

    public function getContainerExtension(): ExtensionInterface
    {
        return new NumberSequenceGeneratorExtension();
    }

    public function getInstaller(): Installer
    {
        return $this->container->get(Installer::class);
    }
}
