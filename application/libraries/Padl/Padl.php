<?php
/**
 * Padl Autoload Class
 * 
 * Project:   PHP Application Distribution License Class
 * File:      Padl.php
 *
 * Copyright (C) 2005 Oliver Lillie
 * Copyright (C) 2011 Rafael Goulart
 *
 * This program is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by  the Free
 * Software Foundation; either version 2 of the License, or (at your option)
 * any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 * or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License
 * for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @author  Oliver Lillie buggedcom <publicmail@buggedcom.co.uk>
 * @author  Rafael Goulart <rafaelgou@gmail.com>
 * @license GNU Lesser General Public License
 * @version Release: 1.0.0
 * @link    http://padl.rgou.net
 * @link    http://www.buggedcom.co.uk/
 * @link    http://www.phpclasses.org/browse/package/2298.html
 * @history---------------------------------------------
 * see CHANGELOG
 */
class Padl
{

    /**
     * The init path for autoload
     * @var string
     */
    static public $initPath;

    /**
     * Flag to check if is already intialized
     * @var boolean
     */
    static public $initialized = false;

    /**
     * Constructor
     *
     * set private to avoid directly instatiation to implement
     * but is not a Singleton Design Pattern
     **/
    private function __construct()
    {
    }

    /**
     * Configure autoloading using Padl.
     *
     * This is designed to play nicely with other autoloaders.
     *
     * @param string $initPath The init script to load when autoloading the first Padl class
     * 
     * @return void
     */
    public static function registerAutoload($initPath = null)
    {
        self::$initPath = $initPath;
        spl_autoload_register(array('Padl', 'autoload'));
    }

    /**
     * Internal autoloader for spl_autoload_register().
     *
     * @param string $class The class to load
     *
     * @return void
     */
    public static function autoload($class)
    {
        $path = dirname(__FILE__).'/'.str_replace('\\', '/', $class).'.php';
        if (!file_exists($path)) {
            return;
        }
        if (self::$initPath && !self::$initialized) {
            self::$initialized = true;
            require self::$initPath;
        }
        require_once $path;
    }

}	