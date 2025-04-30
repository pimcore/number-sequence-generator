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

use Pimcore\Db;

class Generator
{
    protected const TABLE_NAME = 'bundle_number_sequence_generator_register';

    /**
     * Get the next number of a sequence, or the start value, if the first entry.
     *
     * @param string $register the register of the counter
     * @param int $startValue a start value, where a index has to begin
     * @param int $_trial internal param
     *
     * @return int > 0 or -1 on fatal error
     */
    public function getNext(string $register, $startValue = 1000, $_trial = 0): int
    {
        //transactional save see https://dev.mysql.com/doc/refman/5.7/en/innodb-locking-reads.html
        //the select last_insert_id() does not access any table. merely retrieves identifier information
        $db = Db::get();

        try {
            $db->beginTransaction();
            $sql = sprintf('SELECT counter from %s WHERE register = ? for update', self::TABLE_NAME);
            $currentNumber = $db->fetchOne($sql, [$register]);
            if ($currentNumber <= 0) {
                $insertSql = sprintf('INSERT  INTO %s (register,counter) VALUES (?,?)', self::TABLE_NAME);
                $db->executeQuery($insertSql, [$register, $startValue ]);
                $nextVal = $startValue;
            } else {
                $nextVal = $currentNumber + 1;
                $updateSql = sprintf('UPDATE %s SET counter = ? WHERE register=?', self::TABLE_NAME);
                $db->executeQuery($updateSql, [$nextVal, $register]);
            }
            $db->commit();

            return $nextVal;
        } catch (\Exception $e) {
            $db->rollback();
            if ($_trial < 3) {
                return self::getNext($register, $startValue, $_trial + 1); //try again
            }
        }

        return -1;
    }

    /**
     * Reset number, for instance to change to another sequence, etc.
     * NOT TRANSACTIONAL SAFE. MUST BE USED ONLY IN SPECIFIC CASES.
     *
     *
     */
    public function setCurrent($register, $value): int
    {
        $db = Db::get();
        $sql = sprintf('REPLACE  INTO %s (register,counter) VALUES (?,?)', self::TABLE_NAME);
        $db->executeQuery($sql, [$register, $value ]);

        return $value;
    }

    /**
     * Get the current number of a register for informational output.
     * Must not be used for incrementational purposes.
     *
     * @param string $register the register name
     *
     * @return int >= 0
     */
    public function getCurrent($register): int
    {
        $db = Db::get();
        $sql = sprintf('SELECT counter FROM %s WHERE register=?', self::TABLE_NAME);

        return (int)$db->fetchOne($sql, [$register]);
    }
}
