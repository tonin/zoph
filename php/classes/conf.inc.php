<?php
/**
 * Via this class Zoph can read configurations from the database
 * the configurations themselves are stored in confItem objects
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
 * @package Zoph
 * @author Jeroen Roos
 */

require_once("database.inc.php");

/**
 * conf is the main object for access to Zoph's configuration
 * in the database
 */
class conf {

    /**
     * @var array Groups are one or more configuration objects that
     *            belong together;
     */
    private static $groups=array();
    
    /**
     * @var bool whether or not the configuration has been loaded from the db
     */
    private static $loaded=false;

    /**
     * Initialize object
     */
    public static function init() {
        if(!self::$loaded) {
            self::getDefault();
            self::loadFromDB();
        }
    }

    /**
     * Read configuration from database
     */
    public static function loadFromDB() {
        self::getDefault();
        $sql="SELECT conf_id, value FROM " . DB_PREFIX . "conf";

        $result=query($sql, "Cannot load configuration from database");

        while($row= fetch_row($result)) {
            $key=$row[0];
            $value=$row[1];
            $item=conf::getItemByName($key);
            $item->setValue($value);
        }
        self::$loaded=true;
        
    }

    /**
     * Read configuration from submitted form
     * @param array $_GET or $_POST variables
     * @todo: bug - when submit contains both GET and POST, only GET is loaded in $vars
     *        POST is needed, so in case there are GET vars, this will not work!
     */
    public static function loadFromRequestVars(array $vars) {
        self::getDefault();
        foreach($vars as $key=>$value) {
            if(substr($key,0,1) == "_") { continue; }
            $key=str_replace("_", ".", $key);
            try {
                $item=conf::getItemByName($key);
                $item->setValue($value);
                $item->update();
            } catch(ConfigurationException $e) { 
                log::msg("Configuration cannot be updated: " . $e->getMessage(), log::ERROR, log::CONFIG);
            }
        }
        self::$loaded=true;
    }


    /**
     * Get a configuration item by name
     * @param string Name of item to return
     * @return confItem Configuration item
     * @throws ConfigurationException
     */
    public static function getItemByName($name) {
        $name_arr=explode(".", $name);
        $group=array_shift($name_arr);
        if(isset(self::$groups[$group]) && isset(self::$groups[$group][$name])) {
            return self::$groups[$group][$name];
        } else {
            throw new ConfigurationException("Unknown configuration item " . $name);
        }
    }

    /**
     * Get the value of a configuration item
     * @param string Name of item to return
     * @return string Value of parameter
     */
    public static function get($key) {
        self::init();
        $item=conf::getItemByName($key);
        return $item->getValue();
            
    }

    /**
     * Get all configuration items (in groups)
     * @return array Array of group objects
     */
    public static function getAll() {
        self::init();
        return self::$groups;
    }

    /**
     * Create a new confGroup and add it to the list
     * @param string name
     * @param string description
     */
    public static function addGroup($name, $desc = "") {
        $group = new confGroup();

        $group->setName($name);
        $group->setDesc($desc);


        self::$groups[$name]=$group;
        return $group;
    }

    /**
     * Returns the default configuration
     * This is used to define all configurable items in Zoph
     */
    private static function getDefault() {
        $interface = self::addGroup("interface", "Zoph interface settings");

        $int_title = new confItemString();
        $int_title->setName("interface.title");
        $int_title->setLabel("Title");
        $int_title->setDesc("The title for the application. This is what appears on the home page and in the browser's title bar.");
        $int_title->setDefault("Zoph");
        $int_title->setRegex("^.*$");
        $interface[]=$int_title;

        $int_width = new confItemString(); 
        $int_width->setName("interface.width");
        $int_width->setLabel("Screen width");
        $int_width->setDesc("A number in pixels (\"px\") or percent (\"%\"), the latter is a percentage of the user's browser window width.");
        $int_width->setDefault("600px");
        $int_width->setRegex("^[0-9]+(px|%)$");
        $interface[]=$int_width;

        $int_css = new confItemString(); 
        $int_css->setName("interface.css");
        $int_css->setLabel("Style Sheet");
        $int_css->setDesc("The CSS file Zoph uses");
        $int_css->setDefault("css.php");
        $int_css->setRegex("^[A-Za-z0-9_\.]+$");
        $interface[]=$int_css;

        $int_share = new confItemBool();
        $int_share->setName("interface.share");
        $int_share->setLabel("Sharing");
        $int_share->setDesc("Sometimes, you may wish to share an image in Zoph without creating a user account for those who will be watching them. For example, in order to post a link to an image on a forum or website. When this option is enabled, you will see a 'share' tab next to a photo, where you will find a few ways to share a photo, such as a url and a HTML &lt;img&gt; tag. With this special url, it is possible to open a photo without logging in to Zoph. You can determine per user whether or not this user will see the tab and therefore the urls.");
        $int_share->setDefault(false);
        $interface[]=$int_share;

        $int_salt_full = new confItemSalt();
        $int_salt_full->setName("interface.share.salt.full");
        $int_salt_full->setLabel("Salt for sharing full size images");
        $int_salt_full->setDesc("When using the sharing feature, Zoph uses a hash to identify a photo. Because you do not want people who have access to you full size photos (via Zoph or otherwise) to be able to generate these hashes, you should give Zoph a secret salt so only authorized users of your Zoph installation can generate them. The salt for full size images (this one) must be different from the salt of mid size images (below), because this allows Zoph to distinguish between them. If a link to your Zoph installation is being abused (for example because someone whom you mailed a link has published it on a forum), you can modify the salt to make all hash-based links to your Zoph invalid.");
        $int_salt_full->setDefault("Change this");
        $interface[]=$int_salt_full;

        $int_salt_mid = new confItemSalt();
        $int_salt_mid->setName("interface.share.salt.mid");
        $int_salt_mid->setLabel("Salt for sharing mid size images");
        $int_salt_mid->setDesc("The salt for mid size images (this one) must be different from the salt of mid full images (above), because this allows Zoph to distinguish between them. If a link to your Zoph installation is being abused (for example because someone whom you mailed a link has published it on a forum), you can modify the salt to make all hash-based links to your Zoph invalid.");
        $int_salt_mid->setDefault("Modify this");
        $interface[]=$int_salt_mid;

        $int_autoc = new confItemBool();
        $int_autoc->setName("interface.autocomplete");
        $int_autoc->setLabel("Autocomplete");
        $int_autoc->setDesc("Use autocompletion for selection of albums, categories, places and people instead of standard HTML selectboxes. Can be individually switched off from user preferences.");
        $int_autoc->setDefault(true);
        $interface[]=$int_autoc;


        $path = self::addGroup("path", "File and directory locations");
        

        $path_images = new confItemString();
        $path_images->setName("path.images");
        $path_images->setLabel("Images directory");
        $path_images->setDesc("Location of the images on the filesystem. Absolute path, thus starting with a /");
        $path_images->setDefault("/data/images");
        $path_images->setRegex("^\/[A-Za-z0-9_\.\/]+$");
        $path_images->setTitle("Alphanumeric characters (A-Z, a-z and 0-9), forward slash (/), dot (.), and underscore (_). Must start with a /");
        $path[]=$path_images;

        $path_upload = new confItemString();
        $path_upload->setName("path.upload");
        $path_upload->setLabel("Upload dir");
        $path_upload->setDesc("Directory where uploaded files are stored and from where files are imported in Zoph. This is a directory under the images directorty (above). For example, if the images directory is set to /data/images and this is set to upload, photos will be uploaded to /data/images/upload.");
        $path_upload->setDefault("upload");
        $path_upload->setRegex("^[A-Za-z0-9_]+[A-Za-z0-9_\.\/]*$");
        $path_upload->setTitle("Alphanumeric characters (A-Z, a-z and 0-9), forward slash (/), dot (.), and underscore (_). Can not start with a dot or a slash");
        $path[]=$path_upload;
        
        $path_magic = new confItemString();
        $path_magic->setName("path.magic");
        $path_magic->setLabel("Magic file");
        $path_magic->setDesc("Zoph needs a MIME Magic file to be able to determine the filetype of an uploaded file. This is an important security measure, since it prevents users from uploading files other than images and archives. If left empty, PHP will use the built-in Magic file, if for some reason this does not work, you can specify the location of the MIME magic file. Where this file is located, depends on your distribution, /usr/share/misc/magic.mgc, /usr/share/misc/file/magic.mgc, /usr/share/file/magic are often used.");
        $path_magic->setDefault("");
        $path_magic->setRegex("^(\/[A-Za-z0-9_\.\/]+|)$");
        $path_magic->setTitle("Alphanumeric characters (A-Z, a-z and 0-9), forward slash (/), dot (.), and underscore (_). Must start with a /. Can be empty for PHP builtin magix file.");
        $path[]=$path_magic;

        $maps = self::addGroup("maps", "Mapping support");

        $maps_provider = new confItemSelect();
        $maps_provider->setName("maps.provider");
        $maps_provider->setDesc("Enable or disable mapping support and choose the mapping provider");
        $maps_provider->setLabel("Mapping provider");
        $maps_provider->addOption("", "Disabled");
        $maps_provider->addOption("google", "Google Maps");
        $maps_provider->addOption("googlev3", "Google Maps v3");
        $maps_provider->addOption("yahoo", "Yahoo maps");
        $maps_provider->addOption("cloudmade", "Cloudmade (OpenStreetMap)");
        $maps_provider->setDefault("");
        $maps[]=$maps_provider;
        
        $import = self::addGroup("import", "Importing and uploading photos");

        $import_enable = new confItemBool();
        $import_enable->setName("import.enable");
        $import_enable->setLabel("Import through webinterface");
        $import_enable->setDesc("Use this option to enable or disable importing using the webbrowser. With this option enabled, an admin user, or a user with import rights, can import files placed in the import directory (below) into Zoph. If you want users to be able to upload as well, you need to enable uploading as well.");
        $import_enable->setDefault(false);
        $import[]=$import_enable;

        $import_upload = new confItemBool();
        $import_upload->setName("import.upload");
        $import_upload->setLabel("Upload through webinterface");
        $import_upload->setDesc("Use this option to enable or disable uploading files. With this option enabled, an admin user, or a user with import rights, can upload files to the server running Zoph, they will be placed in the import directory (below). This option requires \"import through web interface\" (above) enabled.");
        $import_upload->setDefault(false);
        $import[]=$import_upload;


        $import_maxupload = new confItemString(); 
        $import_maxupload->setName("import.maxupload");
        $import_maxupload->setLabel("Maximum filesize");
        $import_maxupload->setDesc("Maximum size of uploaded file in bytes. You might also need to change upload_max_filesize, post_max_size and possibly max_execution_time and max_input_time in php.ini.");
        $import_maxupload->setRegex("^[0-9]+$");
        $import_maxupload->setDefault("10000000");
        $import[]=$import_maxupload;
        
        $import_parallel = new confItemString(); 
        $import_parallel->setName("import.parallel");
        $import_parallel->setLabel("Resize parallel");
        $import_parallel->setDesc("Photos will be resized to thumbnail and midsize images during import, this setting determines how many resize actions run in parallel. Can be set to any number. Don't change this, unless you have a fast server with multiple CPU's or cores.");
        $import_parallel->setRegex("^[0-9]+$");
        $import_parallel->setDefault("1");
        $import[]=$import_parallel;

        $import_rotate = new confItemBool();
        $import_rotate->setName("import.rotate");
        $import_rotate->setLabel("Rotate images");
        $import_rotate->setDesc("Automatically rotate imported images, requires jhead");
        $import_rotate->setDefault(false);
        $import[]=$import_rotate;

        $import_resize = new confItemSelect();
        $import_resize->setName("import.resize");
        $import_resize->setLabel("Resize method");
        $import_resize->setDesc("Determines how to resize an image during import. Resize can be about 3 times faster than resample, but the resized image has a lower quality.");
        $import_resize->addOption("resize", "Resize (lower quality / low CPU / fast)");
        $import_resize->addOption("resample", "Resample (high quality / high CPU / slow)");
        $import_resize->setDefault("resample");
        $import[]=$import_resize;

        $import_dated = new confItemBool();
        $import_dated->setName("import.dated");
        $import_dated->setLabel("Dated dirs");
        $import_dated->setDesc("Automatically place photos in dated dirs (\"2012.10.16/\") during import");
        $import_dated->setDefault(false);
        $import[]=$import_dated;

        $import_dated_hier = new confItemBool();
        $import_dated_hier->setName("import.dated.hier");
        $import_dated_hier->setLabel("Hierarchical dated dirs");
        $import_dated_hier->setDesc("Automatically place photos in a dated directory tree (\"2012/10/16/\") during import. Ignored unless \"Dated dirs\" is also enabled");
        $import_dated_hier->setDefault(false);
        $import[]=$import_dated_hier;

        $date = self::addGroup("date", "Date and time");

        $date_tz = new confItemSelect();
        $date_tz->setName("date.tz");
        $date_tz->setLabel("Timezone");
        $date_tz->setDesc("This setting determines the timezone to which your camera is set. Leave empty if you do not want to use this feature and always set your camera to the local timezone");

        $date_tz->addOptions(TimeZone::getTzArray());
        $date_tz->setDefault("");

        $date[]=$date_tz;
        
        $date_guesstz = new confItemBool();
        $date_guesstz->setName("date.guesstz");
        $date_guesstz->setLabel("Guess timezone");
        $date_guesstz->setDesc("If you have defined the precise location of a place (using the mapping feature), Zoph can 'guess' the timezone based on this location. It uses the Geonames project for this. This will, however, send information to their webserver, do not enable this feature if you're not comfortable with that.");
        $date_guesstz->setDefault(false);
        $date[]=$date_guesstz;

    }
}
