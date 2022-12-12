<?php

namespace Pimcore\Bundle\NumberSequenceGeneratorBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Pimcore\Bundle\NumberSequenceGeneratorBundle\RandomGenerator;
use Pimcore\Migrations\Migration\AbstractPimcoreMigration;
use Pimcore\Model\Tool\SettingsStore;

/**
 * Checking if tables already exist
 */
class Version20221209110849 extends AbstractPimcoreMigration
{

    public function doesSqlMigrations(): bool
    {
        return false;
    }

    public function up(Schema $schema): void
    {

        $result1 = \Pimcore\Db::get()->fetchAll('SHOW TABLES LIKE "bundle_number_sequence_generator_register"');
        $result2 = \Pimcore\Db::get()->fetchAll('SHOW TABLES LIKE "' . RandomGenerator::TABLE_NAME . '"');

        $installed = !empty($result1) && !empty($result2);

        SettingsStore::set('BUNDLE_INSTALLED__Pimcore\\NumberSequenceGeneratorBundle\\NumberSequenceGeneratorBundle', $installed, 'bool', 'pimcore');

    }

    public function down(Schema $schema): void
    {
    }


}
