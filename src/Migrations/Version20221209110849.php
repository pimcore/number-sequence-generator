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
