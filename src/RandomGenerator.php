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

namespace Pimcore\Bundle\NumberSequenceGeneratorBundle;

use Pimcore\Db;
use Symfony\Component\Lock\LockFactory;

class RandomGenerator
{
    /**
     * @var LockFactory
     */
    private $lockFactory;

    /**
     *  key for lock table
     */
    const LOCK_KEY = 'number_sequence_generator';
    /**
     * numeric code
     */
    const NUMERIC = 'numeric';

    /**
     * alphanumeric code
     */
    const ALPHANUMERIC = 'alphanumeric';

    /**
     * table name
     */
    const TABLE_NAME = 'bundle_number_sequence_generator_randomregister';

    public function __construct(LockFactory $lockFactory)
    {
        $this->lockFactory = $lockFactory;
    }

    /**
     * @param $range
     * @param string $codeType
     * @param null $length
     * @param string $characterSet character set for alphanumeric codes
     *
     * @return bool|int|string
     */
    public function generateCode(
        $range,
        $codeType = self::NUMERIC,
        $length = null,
        $characterSet = '123456789abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ'
    ) {
        switch ($codeType) {
            case self::NUMERIC:
                return $this->generateNumericCode($range);
            case self::ALPHANUMERIC:
                return $this->generateAlphanumericCode($range, $length, $characterSet);
            default:
                throw new \Exception("Code Type $codeType not supported.");
        }
    }

    /**
     * @param $range
     *
     * @return int
     */
    private function generateNumericCode($range)
    {
        $lock = $this->lockFactory->createLock(self::LOCK_KEY);
        $lock->acquire(true);
        $db = Db::get();
        $code = $db->fetchOne('SELECT `code` FROM '.self::TABLE_NAME.' WHERE `range` = ?', [$range]);

        if ($code) {
            $code++;
            $updateData = ['code' => $code];
            $criteriaData = ['range' => $range];

            // keep compatible with pimcore 10.5 // TODO: Remove if pimcore 10 support is dropped
            if (!class_exists('\Pimcore\Db\Connection')) {
                $updateData = Db\Helper::quoteDataIdentifiers($db, $updateData);
                $criteriaData = Db\Helper::quoteDataIdentifiers($db, $criteriaData);
            }
            $db->update(self::TABLE_NAME, $updateData, $criteriaData);
        } else {
            $code = 1;
            // keep compatible with pimcore 10.5 // TODO: Remove if pimcore 10 support is dropped
            $insertData = ['code' => $code, 'range' => $range];
            if (!class_exists('\Pimcore\Db\Connection')) {
                $insertData = Db\Helper::quoteDataIdentifiers($db, $insertData);
            }

            $db->insert(self::TABLE_NAME, $insertData);
        }
        $lock->release();

        return $code;
    }

    /**
     * @param $range
     * @param $length
     *
     * @return string
     */
    private function generateAlphanumericCode($range, $length, $characterSet)
    {
        if ($length && $length > 50) {
            throw new \Exception('maximum code length is 50');
        }

        $lock = $this->lockFactory->createLock(self::LOCK_KEY);
        $lock->acquire(true);
        $result = true;
        $db = Db::get();

        while ($result) {
            $code = substr(str_shuffle($characterSet), 0, $length);
            $result = $db->fetchOne(
                'SELECT * FROM '.self::TABLE_NAME.' WHERE `range` = ? AND `code` = ?',
                [$range, $code]
            );
        }

        // keep compatible with pimcore 10.5 // TODO: Remove if pimcore 10 support is dropped
        $insertData = ['code' => $code, 'range' => $range];
        if (!class_exists('\Pimcore\Db\Connection')) {
            $insertData = Db\Helper::quoteDataIdentifiers($db, $insertData);
        }
        $db->insert(self::TABLE_NAME, $insertData);

        $lock->release();

        return $code;
    }

    /**
     * @param $range
     */
    public function resetCodeGenerator($range)
    {
        $db = Db::get();
        $db->executeQuery('DELETE FROM '.self::TABLE_NAME.' WHERE `range` = ?', [$range]);
    }
}
