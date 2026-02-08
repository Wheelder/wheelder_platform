<?php
/**
 * Edu App Database — Thin redirect to central config
 * 
 * All database logic lives in pool/config/database.php.
 * This file exists only for backward compatibility with
 * existing require/include statements in the edu app.
 */
require_once dirname(dirname(dirname(__DIR__))) . '/pool/config/database.php';
