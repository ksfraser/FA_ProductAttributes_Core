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

$autoload = __DIR__ . "/../vendor/autoload.php";
if (is_file($autoload)) {
    require_once $autoload;
}

page(_("Product Attributes Administration"));

display_notification(_("FA_ProductAttributes core module is active and ready to use."));

// Use FA hooks system to get admin links from related modules
$admin_links = [];
if (function_exists('apply_filters')) {
    $admin_links = apply_filters('product_attributes_admin_links', $admin_links);
}

// Display related module admin links if any found
if (!empty($admin_links)) {
    display_notification(_("Related modules:"));
    echo "<ul>";
    foreach ($admin_links as $link) {
        $name = isset($link['name']) ? $link['name'] : _('Unknown Module');
        $url = isset($link['url']) ? $link['url'] : '#';
        $description = isset($link['description']) ? $link['description'] : '';
        echo "<li><a href='" . $url . "'>" . $name . "</a>" . (!empty($description) ? " - " . $description : "") . "</li>";
    }
    echo "</ul>";
}

end_page();
