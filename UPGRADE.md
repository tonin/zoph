Zoph 0.9.2 to 0.9.3
===================
* If you want to upgrade from an older version, first follow the instructions to upgrade to 0.9.2. It is not necessary to install older versions first, you can just install the current version and follow the upgrade instructions below.

Copy files
----------

Copy the contents of the php directory, including all subdirs, into your webroot.

cp -a php/* /var/www/html/zoph

Database changes
----------------
* Execute zoph-update-0.9.3.sql:

    mysql -u zoph_admin -p zoph < sql/zoph_update-0.9.3.sql

Changes this script makes:

* Resize the password field to allow store bigger hashes
* Add fields to the user table to allow for new access rights
* Add 'created by' fields to the albums, categories, places, people and circles tables

Zoph 0.9.1 to 0.9.2
===================
* If you want to upgrade from an older version, first follow the instructions to upgrade to 0.9.1. It is not necessary to install older versions first, you can just install the current version and follow the upgrade instructions below.

Copy files
----------
Copy the contents of the php directory, including all subdirs, into your webroot. 

     cp -a php/* /var/www/html/zoph

Database changes
----------------
* Execute zoph-update-0.9.2.sql:

    mysql -u zoph_admin -p zoph < sql/zoph_update-0.9.2.sql

Changes this script makes:

* Add previously missing 'random' sortorder to preferences
* Resize Last IP address field so IPv6 addresses can be stored
* Database changes for 'circles' feature
* Create a VIEW on the database to speed up queries for non-admin users


Zoph 0.9 to 0.9.1
=================
* You can use these instructions as well to upgrade from v0.8.4 or 0.9pre1 or 0.9pre2
* If you want to upgrade from an older version, first follow the instructions to upgrade to 0.9. It is not necessary to install older versions first, you can just install the current version and follow the upgrade instructions below.

Copy files
----------
Copy the contents of the php directory, including all subdirs, into your webroot and copy the lang directory into the webroot as well.

     cp -a php/* /var/www/html/zoph
     cp -a lang /var/www/html/zoph

Database changes
----------------
* Execute zoph-update-0.9.1.sql:

    mysql -u zoph_admin -p zoph < sql/zoph_update-0.9.1.sql

Changes this script makes:

* Adding a database table to store configuration
* Removing a field from preferences table for a removed preference
* Removing the rating field from the photos table
* Adding a view on the photos and rating table to get the average rating
* Adding an index on the rating table

Migrating the configuration
---------------------------
As of Zoph 0.9.1, configuration is mainly controlled from the GUI and no longer from config.inc.php. This means that you either must migrate your configuration or make all configuration changes by hand.
   1. Log in to your Zoph installation with an admin user.
      * You may get some errors about because Zoph cannot find your photos in the default location, don't worry, we'll fix that next.
   2. Copy migrate_config.php from the contrib directory into your Zoph directory
   3. In your browser, replace zoph.php with migrate_config.php
   4. Zoph will try to migrate your config to the new, database-based configuration.
   5. Delete migrate_config.php
   6. There are a few configuration-items left in config.inc.php, in most cases you'll want to leave those on default, so instead of 
      manually removing all the no longer existing configuration-items, you may as well just overwrite your config file with the one included with Zoph.

Removed Configuration options
-----------------------------
Since the configuration is now controlled from the Web GUI, most of the configuration options are now deprecated:
* ZOPH_TITLE
* MAX_CRUMBS
* MAX_DAYS_PAST
* ZOPH_URL
* ZOPH_SECURE_URL
* DEFAULT_TABLE_WIDTH
* CSS_SHEET
* ICONSET
* LANG_DIR
* DEFAULT_LANG
* DEFAULT_ORDER
* DEFAULT_DIRECTION
* $VALIDATOR
* FORCE_SSL
* FORCE_SSL_LOGIN
* THUMB_SIZE
* MID_SIZE
* THUMB_PREFIX
* MID_PREFIX
* MIXED_THUMBNAILS
* THUMB_EXTENSION
* ALLOW_ROTATIONS
* ROTATE_CMD
* BACKUP_ORIGINAL
* BACKUP_PREFIX
* IMAGE_DIR
* CLI_USER
* IMPORT
* UPLOAD
* IMPORT_DIR
* IMPORT_PARALLEL
* MAGIC_FILE
* TAR_CMD
* UNZIP_CMD
* UNGZ_CMD
* UNBZ_CMD
* MAX_UPLOAD
* USE_DATED_DIRS
* HIER_DATED_DIRS
* DIR_MODE
* FILE_MODE
* JAVASCRIPT
* EMAIL_PHOTOS
* WATERMARKING
* WATERMARK
* WM_POSX
* WM_POSY
* WM_TRANS
* ALLOW_COMMENTS
* AUTOCOMPLETE
* DOWNLOAD
* MAPS
* GOOGLE_KEY
* CAMERA_TZ
* DATE_FORMAT
* TIME_FORMAT
* GUESS_TZ
* SHARE
* SHARE_SALT_FULL
* SHARE_SALT_MID

Configuration options in config.inc.php
---------------------------------------
There are only a few options left that can be set in `config.inc.php`. Normally you will not need to change them:
* VERSION: Controls the version displayed in Zoph's GUI, only change this if you're a Zoph developper.
* RELEASEDATE: Controls the release date displayed in Zoph's GUI, only change this if you're a Zoph developper.
* INI_FILE: Location of zoph.ini
* THUMB_SIZE: Size of thumbnail files (you shouldn't change this)
* MID_SIZE: Size of mid size files (you shouldn't change this)
* THUMB_PREFIX: Size of thumbnail files (you shouldn't change this)
* MID_PREFIX: Size of mid size files (you shouldn't change this)
* LOG_ALWAYS: Control debugging level
* LOG_SEVERITY: Control debugging level
* LOG_SUBJECT: Control debugging level


Zoph 0.8 to 0.9
===============

* If you want to upgrade from a version older then 0.8, first follow the instructions to upgrade to 0.8. It is not necessary to install older versions first, you can just install the current version and follow the upgrade instructions below.

* If you want to upgrade from 0.8.4, you just need to copy the files, no database changes are needed
* You can also follow these instructions to go to 0.9preX
* You can also follow these instruction if you are on one of the maintenance releases of 0.8 (0.8.0.x)

* If you are on one of the feature releases for 0.8 (0.8.x), except 0.8.4, you will need to edit zoph_update-0.9.sql to comment any changes you have already made on your system.

Copy the contents of the php directory, including all subdirs, into your webroot and copy the lang directory into the webroot as well. You should make a backup copy of config.inc.php to prevent overwriting it.

     cp config.inc.php config.local.php
     cp -a php/* /var/www/html/zoph
     cp -a lang /var/www/html/zoph

Copy cli/zoph into /bin (or another directory in your $PATH):
     cp cli/zoph /bin

Copy zoph.1.gz into your man 1 directory (usually /usr/share/man/man1) and zoph.ini.5.gz into man 5 (usually /usr/share/man/man5):
     cp cli/zoph.1.gz /usr/share/man/man1
     cp cli/zoph.ini.5.gz /usr/share/man/man5

Database changes
================

Don't forget to edit the sql script if you are running on 0.8.x.

Zoph 0.9 requires a manual upgrade to the database, this is described in http://en.wikibooks.org/wiki/Zoph/Upgrading/Changing_your_database_to_UTF-8. If you are on 0.8.1 or later, you should already have made this change.

Execute zoph-update-0.9.sql:
     mysql -u zoph_admin -p zoph < sql/zoph_update-0.9.sql

Change zoph into zophutf8 if you are working on the temporary database.

Changes this script makes:

* Remove the people_slots setting from the user preferences table (0.8.4)
* Add a hash to the photos table (0.8.4)
* Add a setting to control whether or not the user is allowed to use the sharing feature in the users table (0.8.4)
* Added tables and preferences for geotagging support (0.8.3).
* Make the language field in the prefs table longer so languages like en-ca can be stored (0.8.1)

Configuration updates
=====================

In Zoph 0.8.2, .zophrc and a part of config.inc.php were replaced by zoph.ini. You can use zoph.ini.example in the cli dir as an example. (see http://en.wikibooks.org/wiki/Zoph/Configuration) for details):

New options
-----------

* LOG_ALWAYS 
    Control how much debug information is showed for all subjects. (0.8.1)
* LOG SEVERITY 
    Configure how much debug information is showed, for the subjects defined in LOG_SUBJECT (0.8.1)
* LOG_SUBJECT 
    Configure on which subject you would like to see logging. 0.8.1)
* CLI_USER 
    User id that the CLI client uses to connect to Zoph. Must be admin. Change this into '0' to let Zoph lookup the user from the Unix user that is running Zoph. (0.8.2)
* IMPORT
    Enable ('1') or disable ('0') webimport (0.8.2)
* UPLOAD
    Enable ('1') or disable ('0') uploading photos through the browser (0.8.2)
* IMPORT_DIR
    Directory, relative to IMAGE_DIR, that will store uploaded photos until they have been imported in Zoph. (0.8.2)
* IMPORT_PARALLEL
    Number of photos to resize concurrently. (0.8.2)
* MAGIC_FILE
    MIME Magic file. Zoph needs this to determine the file type of an imported file. (0.8.2)
* FILE_MODE
    File permissions for files imported in Zoph. (0.8.2)
* UNGZ_CMD
    Command to be used to decompress .gz files. (0.8.2)
* UNBZ_CMD
    Command to be used to decompress .bzip files. (0.8.2)
* SHARE 
    Enable the possibility to share a photo by using a URL that can be used without logging in to Zoph. Once enabled, you can determine per user whether or not this user is allowed to see these URLs. (0.8.4)
* SHARE_SALT_FULL 
    When using the SHARE feature, Zoph uses a hash to identify a photo. Because you do not want people who have access to you full size photos (via Zoph or otherwise) to be able to generate these hashes, you should give Zoph a secret salt so only authorized users of your Zoph installation can generate them. This one is used for fullsize photos (0.8.4)
* SHARE_SALT_FULL 
    When using the SHARE feature, Zoph uses a hash to identify a photo. Because you do not want people who have access to you full size photos (via Zoph or otherwise) to be able to generate these hashes, you should give Zoph a secret salt so only authorized users of your Zoph installation can generate them. This one is used for midsize photos (0.8.4)

Removed options
---------------

The following configuration options no longer exist, you should remove them from you config.inc.php:

* DB_HOST 
    Moved to zoph.ini (0.8.2)
* DB_NAME 
    Moved to zoph.ini (0.8.2)
* DB_USER 
    Moved to zoph.ini (0.8.2)
* DB_PASS 
    Moved to zoph.ini (0.8.2)
* CLIENT_WEB_IMPORT 
    Replaced by UPLOAD (0.8.2)
* SERVER_WEB_IMPORT 
    Replaced by IMPORT (0.8.2)
* DEFAULT_DESTINATION_PATH 
    Due to introduction of IMPORT_DIR no longer necessary (0.8.2)
* SHOW_DESTINATION_PATH 
    Due to introduction of IMPORT_DIR no longer necessary (0.8.2)
* REMOVE_ARCHIVE 
    As of Zoph 0.8.2, Zoph always removes an archive after a successful decompress (0.8.2)
* IMPORT_MOVE 
    Due to introduction of IMPORT_DIR, Zoph always moves files (0.8.2)
* IMPORT_UMASK 
    Replaced by FILE_MODE (0.8.2)
* USE_IMAGE_SERVICE 
    The Image Service is now always on. If you were previously using define('USE_IMAGE_SERVICE', 0), you should move your images out of your webroot, and update IMAGE_DIR accordingly. (0.8.4)
* WEB_IMAGE_DIR 
    This was only needed when USE_IMAGE_SERVICE was enabled. (0.8.4)
* MAX_PEOPLE_SLOTS 
    The people slots feature, that allowed multiple 'add people' dropdowns on the edit photo and bulk edit photo pages has been replaced by a Javascript that automatically adds a new dropdown whenever a new person is added, allowing a virtually unlimited amount of people to be added in one edit. (0.8.4)

For upgrade instruction for older releases, please see http://en.wikibooks.org/wiki/Zoph/Upgrading/Archive