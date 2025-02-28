<?php

/**
 * Pimcore
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Commercial License (PCL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 *  @copyright  Copyright (c) Pimcore GmbH (http://www.pimcore.org)
 *  @license    http://www.pimcore.org/license     GPLv3 and PCL
 */

namespace Pimcore\Bundle\NumberSequenceGeneratorBundle;

use Pimcore\Bundle\NumberSequenceGeneratorBundle\DependencyInjection\NumberSequenceGeneratorExtension;
use Pimcore\Extension\Bundle\AbstractPimcoreBundle;
use Pimcore\Extension\Bundle\Traits\PackageVersionTrait;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

class PimcoreNumberSequenceGeneratorBundle extends AbstractPimcoreBundle
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
