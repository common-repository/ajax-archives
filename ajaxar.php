<?php
/*
Plugin Name: Ajax Archives
Plugin URI: http://www.miki.cat/index.php/ajax-archives/
Description: Display your archives in collapsible mode using ajax and jQuery. Each subnode is loaded via ajax as per user request, so not whole archive is loaded each time your archives page is displayed. If you have performance problems or server issues with other archive plugins, try this one and enjoy.
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
function ajaxar_get_years() {
	global $wpdb;
	
	$output = ajaxar_js();
	$output .= '<ul class="ajaxar_years">';
	$yearscount = $wpdb->get_results("SELECT DISTINCT YEAR(post_date) as year, count(ID) as pcount FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' GROUP BY year ORDER BY post_date DESC");
	foreach($yearscount as $yearc) {
		$output .= '<li class="ajaxar_year_item"><a href="#" onclick="ajaxar_show('.$yearc->year.');return false;">'.$yearc->year.' ('.$yearc->pcount.' '.__(ajaxar_format_posts_count($yearc->pcount)).')</a>';
		$output .= '<div id="ajaxar-'.$yearc->year.'" style="display:none"></div></li>';				
	}
	$output .= '</ul>';
	return $output;
}

function ajaxar_get_months($ayear) {
	global $wpdb, $month;
	
	$output = '<ul class="ajaxar_months">';
	$monthcount = $wpdb->get_results("SELECT DISTINCT MONTH(post_date) as month, count(ID) as pcount FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' AND YEAR(post_date) = $ayear GROUP BY month ORDER BY post_date DESC");
	foreach($monthcount as $monthc) {
		$output .= '<li class="ajaxar_month_item"><a href="#" onclick="ajaxar_show('.$ayear.', '.$monthc->month.');return false;">'.$month[zeroise($monthc->month, 2)].' ('.$monthc->pcount.' '.__(ajaxar_format_posts_count($monthc->pcount)).')</a>';
		$output .= '<div id="ajaxar-'.$ayear.'-'.$monthc->month.'" style="display:none;"></div></li>';				
	}
	$output .= '</ul>';
	return $output;
}

function ajaxar_get_posts($ayear, $amonth) {
	global $wpdb;
	
	$output = '<ul class="ajaxar_posts">';
	$monthposts = $wpdb->get_results("SELECT p.*, (select count(*) from $wpdb->comments c where p.ID = c.comment_post_ID AND c.comment_approved = '1') as xcount FROM $wpdb->posts p WHERE p.post_status = 'publish' AND p.post_type = 'post' AND YEAR(p.post_date) = $ayear AND MONTH(p.post_date) = $amonth GROUP BY p.ID ORDER BY p.post_date DESC");
	foreach($monthposts as $apost) {
		$output .= '<li class="ajaxar_post_item"><strong>'.mysql2date(get_option('date_format'), $apost->post_date).'</strong> - <a href="'.get_permalink($apost->ID).'" rel="bookmark" title="'.__('Permanent Link to').' '.$apost->post_title.'">'.$apost->post_title.'</a> ('.$apost->xcount.' '.__(ajaxar_format_comments_count($apost->xcount)).')</li>';
	}
	$output .= '</ul>';
	return $output;
}

function ajaxar_format_comments_count($count) {
	$c = (int) $count;
	if ($c == 0) {
		return 'Comments';
	} else if ($c == 1) {
		// Comment isn't translated in all languages, modify here the text for only one comment
		return 'Comment';
	}
	return 'Comments';
}

function ajaxar_format_posts_count($count) {
	$c = (int) $count;
	if ($c == 0) {
		return 'Posts';
	} else if ($c == 1) {
		// Post isn't translated in all languages, modify here the text for only one post
		return 'Post';
	}
	return 'Posts';
}

function ajaxar_js() {
	wp_print_scripts( array( 'sack' ));

	$output .= '<script type="text/javascript">'."\n";
	$output .= '//<![CDATA['."\n";

	$output .= 'function ajaxar_show (year, month) {';
	$output .=   'var elementId = month ? "ajaxar-"+year+"-"+month : "ajaxar-"+year ;';
	$output .=   'var element = document.getElementById(elementId);';
	$output .=   'if (!element) {';
	$output .=     'alert(\'Ajax Archives plugin not found in this page\');';
	$output .=     'return;';
	$output .=   '}';
	$output .=   'if (element.style.display == \'none\') {';
	$output .=     'element.innerHTML = \'<img src="'. get_option ( 'home' ) .'/wp-admin/images/loading.gif" alt="Loading..."/>\';';
	$output .=     'jQuery(\'#\' + elementId).slideToggle(\'slow\');';
	$output .=     'var mysack = new sack("'. get_option ( 'home' ) .'/wp-content/plugins/ajax-archives/ajax_requests.php" );';
	$output .=     'mysack.execute = 1;';
	$output .=     'mysack.method = \'GET\';';
	$output .=     'mysack.setVar( "ajaxar_year", year );';
	$output .=     'mysack.setVar( "ajaxar_month", month );';
	$output .=     'mysack.setVar( "ajaxar_div_id", elementId);';
	$output .=     'mysack.onError = function() { alert(\'Ajax error on ajaxar_show\' ); };';
	$output .=     'mysack.runAJAX();';
	$output .=   '} else {';
	$output .=     'jQuery(\'#\' + elementId).slideToggle(\'slow\');';
	$output .=   '}';
	$output .=   'return true;';
	$output .= '}'."\n";

	$output .= '//]]>'."\n";
	$output .= '</script>'."\n";

	return $output;
}

function ajaxar_snippet ($content) {
	if (eregi ('\[ajaxar\]', $content)) {
		return eregi_replace ('\[ajaxar\]', ajaxar_get_years(), $content);
	}
	return $content;
}

add_action ('the_content', 'ajaxar_snippet');
?>
