LCTV Badges
-----------

License: GPLv3  
Requires: PHP v5.4+  
Version: 0.0.7

#### SETUP

Setup constants are in lctv_badges_init.php

* LCTV_CLIENT_ID and LCTV_CLIENT_SECRET are set in the environment to keep
them private. This can be accomplished by placing the following in a
.htaccess file:

 * SetEnv LCTV_CLIENT_ID     "thisisyourclientid"
 * SetEnv LCTV_CLIENT_SECRET "thisisyourclientsecret"

* LCTVAPI_DATA_STORE_CLASS is the class of the data store to use for caching.
Options are LCTVAPIDataStoreFlatFiles or LCTVAPIDataStoreMySQL.

* LCTV_DATA_PATH is the path to the folder where API tokens and cache will be
stored if using the flat file data store. This folder must be writable by the server.
It would be a good idea to use a folder below the server's public html path, so
that cached user tokens aren't publicly accessible.

* LCTVAPI_CACHE_EXPIRES_IN is the amount of time in seconds before cached
data expires. Default is 300, or 5 minutes.

* LCTV_REDIRECT_URL is the url the API should return user authentication codes
to. Should be the url to authorize.php.

* LCTV_MASTER_USER is the master user account the API will use to make public
queries. In order for any API request to work it needs an authorized user.
You can authenticate this master account by visiting authorize.php.

* LCTVAPI_DB_NAME, LCTVAPI_DB_USER, LCTVAPI_DB_PASSWORD, LCTVAPI_DB_HOST are
database connection options if using the MySQL data store.

#### BADGE USAGE

Detailed badge instructions can be found by visiting index.php.