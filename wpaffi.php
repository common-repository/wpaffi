<?php
/*
Plugin Name: wpAFFI
Plugin URI: http://playground.ebiene.de/263/wpaffi-wordpress-plugin-fuer-affiliate-links/
Description: wpAFFI is a very simple method to mask your affiliate links.
Author: Sergej M&uuml;ller
Version: 0.3
Author URI: http://www.wpSEO.org
*/


if (!function_exists('is_admin')) {
header('Status: 403 Forbidden');
header('HTTP/1.1 403 Forbidden');
exit();
}
if (function_exists('add_shortcode')) {
add_action(
'template_redirect',
create_function(
'',
'if (preg_match("/\/outgoing\/([0-9]{1})\/(.*)\.html(\/?)$/", $_SERVER["REQUEST_URI"], $matches)) {
if (php_sapi_name() != "cgi-fcgi") {
status_header(302);
}
header(
"Location: ". sprintf(
"http://%s%s",
$matches[1] ? "www." : "",
str_rot13(html_entity_decode(urldecode(urldecode($matches[2]))))
)
);
exit();
}'
)
);
add_shortcode(
'wpaffi',
create_function(
'$attr',
'return sprintf(
"%s/outgoing/%s/%s.html",
get_bloginfo("home"),
preg_match("#^htt(.{1,2})://www\n.#", $attr["link"]) ? 1 : 0,
urlencode(urlencode(str_rot13(preg_replace("#^(?:http://|www\.)#", "", $attr["link"]))))
);'
)
);
}