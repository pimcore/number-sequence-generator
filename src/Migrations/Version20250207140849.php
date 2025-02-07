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

namespace Pimcore\Bundle\NumberSequenceGeneratorBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Pimcore\Bundle\NumberSequenceGeneratorBundle\RandomGenerator;
use Pimcore\Model\Tool\SettingsStore;

/**
 * Checking if tables already exist
 */
class Version20250207140849 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $numberSequenceBundleState = SettingsStore::get(
            'BUNDLE_INSTALLED__Pimcore\\Bundle\\NumberSequenceGeneratorBundle\\NumberSequenceGeneratorBundle',
            'pimcore'
        );

        if ($numberSequenceBundleState instanceof SettingsStore) {
            SettingsStore::delete('BUNDLE_INSTALLED__Pimcore\\Bundle\\NumberSequenceGeneratorBundle\\NumberSequenceGeneratorBundle');
            SettingsStore::set(
                'BUNDLE_INSTALLED__Pimcore\\Bundle\\NumberSequenceGeneratorBundle\\PimcoreNumberSequenceGeneratorBundle',
                true,
                'bool',
                'pimcore'
            );
        }
    }

    public function down(Schema $schema): void
    {
    }
}
