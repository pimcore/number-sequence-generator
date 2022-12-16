<?php

/**
 * Pimcore
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Enterprise License (PEL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 *  @copyright  Copyright (c) Pimcore GmbH (http://www.pimcore.org)
 *  @license    http://www.pimcore.org/license     GPLv3 and PEL
 */

namespace Pimcore\Bundle\NumberSequenceGeneratorBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Pimcore\Bundle\NumberSequenceGeneratorBundle\RandomGenerator;
use Pimcore\Model\Tool\SettingsStore;

/**
 * Checking if tables already exist
 */
class Version20221209110849 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $result1 = \Pimcore\Db::get()->fetchOne('SHOW TABLES LIKE "bundle_number_sequence_generator_register"');
        $result2 = \Pimcore\Db::get()->fetchOne('SHOW TABLES LIKE "' . RandomGenerator::TABLE_NAME . '"');

        $installed = !empty($result1) && !empty($result2);

        if ($installed) {
            SettingsStore::set('BUNDLE_INSTALLED__Pimcore\\NumberSequenceGeneratorBundle\\NumberSequenceGeneratorBundle', $installed, 'bool', 'pimcore');
        }
    }

    public function down(Schema $schema): void
    {
    }
}
