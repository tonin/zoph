<?php
/**
 * Time class, extension of the standard PHP DateTime class
 * Changes the __construct() to validate the timezone.
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
 * @author Jeroen Roos
 * @package Zoph
 */

/**
 * Time class, extension of the standard PHP DateTime class
 */
class Time extends DateTime {
    /**
     * Create Time object
     * @param string Date and time
     * @param string Timezone
     * @return Time time object
     */
    function __construct($datetime, $tz=null) {
        try {
            if(TimeZone::validate($tz->getName())) {
                parent::__construct($datetime,$tz);
            } else {
                parent::__construct($datetime);
            }
       } catch (Exception $e){
            echo "<b>Invalid time</b><br>";
            log::msg("<pre>" . $e->getMessage() . "</pre>", log::DEBUG, log::GENERAL);
       }
    }
}



?>