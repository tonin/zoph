<?php
/**
 * Class that takes care of logging
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
 * This class takes care of logging and debug
 *
 * @author Jeroen Roos
 * @package Zoph
 */
class log {

    public static $stopOnFatal=true;

    public static $sev = array(
        60 => "Debug",
        50 => "Debug",
        40 => "Notification",
        30 => "Warning",
        20 => "Error",
        10 => "Fatal Error",
        5 => "Message",
        1 => "To File",
        0 => "None");

    const MOREDEBUG = 60;
    const DEBUG = 50;
    const NOTIFY = 40;
    const WARN = 30;
    const ERROR = 20;
    const FATAL = 10;
    const MSG = 5;
    const NONE = 0;
    const TOFILE = 1;

    const VARS = 1;
    const LANG = 2;
    const LOGIN = 4;
    const REDIRECT = 8;
    const IMPORT = 16;
    const GEOTAG = 32;
    const CONFIG = 64;
    const DB = 128;
    const SQL = 256;
    const XML = 512;
    const CONF = 1024;
    /* 2048, is free */
    const IMG = 4096;
    /* 8192, 16384 are free */
    const GENERAL = 32768;
    const ALL=65535;

    /**
     * Log a message
     * for now, only to the screen, but I may add file and database later;
     * @param string Message to be displayed
     * @param bigint Severity of the message, use the constants defined
     * @param bigint Subject of the message.
     * @param bool echo the message or return the contents
     */
    public static function msg($msg,
        $severity = self::NOTIFY, $subj = self::GENERAL, $print = true) {

        /**
         * There are 3 settings in config.ing.php that are important;
         * LOG_SEVERITY: Show log messages with a severity higher than this
         * LOG_SUBJECT:  Only show messages about this subject
         * LOG_ALWAYS:   Always show messages with a severity higher than this
         *               no matter what the subject is.
         */

        if (((LOG_SEVERITY >= $severity) && (LOG_SUBJECT & $subj)) ||
            (LOG_ALWAYS >= $severity)) {

            $dbt = debug_backtrace();
            $file = basename($dbt[0]["file"]);
            $line = $dbt[0]["line"];

            $msg="<b>" . static::$sev[$severity] . "</b>: " . $msg . " <tt>" . $file . "</tt>: " . $line . ".<br>\n";

            if ($print) {
                if (!defined("CLI")) {
                    echo $msg;
                } else {
                    $html=array("<b>", "</b>", "<strong>", "<strong>", "<br>", "<tt>", "</tt>");
                    $cli=array("\033[1m", "\033[0m", "\033[1m", "\033[0m", "\n", "", "");
                    echo str_replace($html, $cli, $msg);
                }
            } else {
                return $msg;
            }
        }

        if ($severity == static::FATAL && static::$stopOnFatal) {
            die("fatal error");
        }
    }
}
?>
