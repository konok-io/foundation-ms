<?php

/**
 * This file runs BEFORE Laravel is bootstrapped.
 * Use it to set early configuration.
 */

// We can't use config() here as Laravel isn't loaded yet
// So we set environment variables that will be used
putenv('PERMISSION_CACHE_STORE=array');
$_ENV['PERMISSION_CACHE_STORE'] = 'array';
$_SERVER['PERMISSION_CACHE_STORE'] = 'array';

// The actual config will be set in the ServiceProvider
// This file just ensures the environment is ready
