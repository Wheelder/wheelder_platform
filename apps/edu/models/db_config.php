<?php
/**
 * Edu App Database Configuration
 * 
 * Points to the central SQLite config in pool/config/ so that
 * both the auth system and edu app share the same database.
 */

// Include the central config class (which uses SQLite)
require_once dirname(dirname(dirname(__DIR__))) . '/pool/config/db_config.php';

// The config class is now available from pool/config/db_config.php
// No separate class needed here — edu models extend the same config → Database chain