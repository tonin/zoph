<?php
/**
 * Create test database from XML-MySQL Dump
 *
 * This file is part of Zoph.
 *
 * Zoph is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * Zoph is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with Zoph; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @package ZophUnitTest
 * @author Jeroen Roos
 */
use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;

/**
 * Create test database from XML-MySQL Dump
 * @package ZophUnitTest
 * @author Jeroen Roos
 */
abstract class ZophDatabaseTestCase extends TestCase {
    use TestCaseTrait;

    static private $pdo = null;
    private $conn = null;

    final public function getConnection() {
        if ($this->conn === null) {
            if (self::$pdo == null) {
                self::$pdo = new PDO("mysql:dbname=" . DB_NAME . ";host=" . DB_HOST,
                    DB_USER, DB_PASS);
            }
            $this->conn = $this->createDefaultDBConnection(self::$pdo, DB_NAME);
        }
        return $this->conn;
    }

    final public function getDataSet() {
        return $this->createMySQLXMLDataSet('UnitTests/db.xml');
    }
}
?>
