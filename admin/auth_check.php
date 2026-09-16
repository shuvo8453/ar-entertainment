<?php

/**
 * AR Entertainment - Admin Authentication Check Middleware
 */

require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/config/db.php';
require_once dirname(__DIR__) . '/config/helpers.php';

if (!is_logged_in()) {
    set_flash('error', 'Please log in to access the AR Entertainment admin panel.');
    redirect('admin/login');
}
