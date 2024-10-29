=== Ajax Archives  ===
Contributors: aposai
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=5153048
Tags: archive,archives,jquery,ajax
Requires at least: 2.3
Tested up to: 2.7
Stable tag: 1.0

Display your archives in collapsible mode using ajax and jQuery.

== Description ==

Display your archives in collapsible mode using ajax and jQuery.

Each subnode is loaded via ajax as per user request, so not whole archive is loaded each time your archives page is displayed. If you have performance problems or server issues with other archive plugins, try this one and enjoy.

It also displays the post count for each year and for each month and the comment count for each post. And uses your current wordpress date_format to format the dates.

== Installation ==

1. Upload `ajax-archives` entire folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Now you can use `<?php ajaxar_get_years ();?>` template tag to show the archive on your template.
1. Or you can put `[ajaxar]` under a page or post content that will be replaced by the archive.

You can use following css classes to style the list:
.ajaxar_years `ul` for years
.ajaxar_months `ul` for months
.ajaxar_posts `ul` for posts
.ajaxar_year_item `li` year item
.ajaxar_month_item `li` month item
.ajaxar_post_item `li` post item

== Frequently Asked Questions ==

= Is this plugin compatible with wp-cache or other cache plugins? =

It should be compatible because of ajax. But I don't tested it!

