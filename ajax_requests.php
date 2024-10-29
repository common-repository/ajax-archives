<?php
/*
Author: Miki
Version: 1.0
Author URI: http://www.miki.cat/

    Copyright 2009  Miquel Fontanals  (email : miki@miki.cat)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
require_once('../../../wp-blog-header.php');

// Check request came from valid source here
// TO DO!

// read submitted information

$ajaxar_month = intval($_GET['ajaxar_month']);
$ajaxar_year = intval($_GET['ajaxar_year']);
$ajaxar_div_id = $_GET['ajaxar_div_id'];

if ($ajaxar_year < 0 && $ajaxar_month < 0) {
	die("alert('ajaxar plugin - Bad Request')");
}

if ($ajaxar_month > 0 && $ajaxar_year > 0) {
	$post_list = str_replace ( "'", "\'", ajaxar_get_posts($ajaxar_year, $ajaxar_month));
	die("document.getElementById('$ajaxar_div_id').innerHTML = '$post_list';");
}

if ($ajaxar_year > 0) {
	$month_list = str_replace ( "'", "\'", ajaxar_get_months($ajaxar_year));
	die("document.getElementById('$ajaxar_div_id').innerHTML = '$month_list';");
}

// Not used, I know...
if( $error ) {
   die( "alert('$error')" );
} 

die();

?>
