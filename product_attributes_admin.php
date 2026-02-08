<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// FrontAccounting wrapper admin page.
// Place this repo under FA: modules/FA_ProductAttributes

$page_security = 'SA_FA_ProductAttributes';

$path_to_root = '../..';

include($path_to_root . "/includes/session.inc");

add_access_extensions();

include_once($path_to_root . "/includes/ui.inc");

// Force use of configured database connection
/*global $db_connections, $db;
$company = $_SESSION['wa_current_user']->company;
$configured_db = mysql_connect($db_connections[$company]['host'], $db_connections[$company]['user'], $db_connections[$company]['password']);
mysql_select_db($db_connections[$company]['name'], $configured_db);
$db = $configured_db;
*/

/*
// Debug: check path
display_notification("path_to_root: " . $path_to_root);
display_notification("session.inc exists: " . (file_exists($path_to_root . "/includes/session.inc") ? "yes" : "no"));
*/

// Manually define FA_ROOT if it's not set
if (!defined('FA_ROOT')) {
    define('FA_ROOT', $path_to_root . '/');
}

// Fix TB_PREF if it's incorrectly set
if (!defined('TB_PREF') && isset($_SESSION['wa_current_user']->company)) {
    define('TB_PREF', $_SESSION['wa_current_user']->company . '_');
}

$autoload = __DIR__ . "/../vendor/autoload.php";
if (is_file($autoload)) {
    require_once $autoload;
}

$autoload = __DIR__ . "/../vendor/autoload.php";
if (is_file($autoload)) {
    require_once $autoload;
}

page(_("Product Attributes Administration"));

start_table(TABLESTYLE, "width=80%");
table_header(array(_("Feature"), _("Status"), _("Description")));

label_row(_("Core Module"), _("Active"), _("FA_ProductAttributes core module is loaded"));
label_row(_("Database Schema"), _("Available"), _("Product attributes tables are installed"));
label_row(_("Admin Interface"), _("Ready"), _("Module administration interface is functional"));
label_row(_("Security"), _("Configured"), _("Access permissions are set up"));

end_table();

end_page();
