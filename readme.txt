=== List Last Changes ===
Contributors: rolandbaer
Tags: last changes, pages
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=PRW4QXZ3DHWL6&lc=GB&item_name=List%20Last%20Changes%20Plugin&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donateCC_LG_global%2egif%3aNonHosted
Requires at least: 3.0.0
Tested up to: 4.9.1
License: GPLv2 or later
Stable tag: 0.5.0

Shows a list of the last changes in the widget area.

== Description ==

Shows a list of the last changes in the widget area. This can include changed pages and/or posts (configurable)

Additional features include:

* Select the number of entries in the list
* Define pages to be exluded

= Exclude Page or Post =
To exclude a page or post from being listed in the widget do the following steps:

1. Open the page or post you want to exclude for editing.
2. Open the "Screen Options" (on the top right of the page)
3. Ensure that "Custom Fields" is checked (see Screenshot #3)
4. In the "Custom Fields" further down the screen add the following custom field (see  Screenshot #4):
 * name: list_last_changes_ignore
 * value: true

To include it again set the value to false or remove the custom field completely.

== Changelog ==

= 0.5.0 =

* Added support for also showing recent changed posts (deactivated by default)
* It's also possible to exclude posts from being listed in the widget.

= 0.4.1 =

* Bugfix for excluding hierarchical pages 

= 0.4.0 =

* Added possibility to exclude pages listed in the widget.
* Tested with Wordpress 4.2

= 0.3.2 =

* Tested with Wordpress 4.0

= 0.3.1 =

* Changed Plugin link directly to official Web Page

= 0.3.0 =

* Changed to class
* Added configuration dialog for number of entries and title

= 0.2.0 =

* First usage on own website

== Installation ==

1. Upload the `list-last-changes` folder to your `/wp-content/plugins/` directory
2. Activate the plugin using the `Plugins` menu in WordPress
3. Add Last Changes Widget to your page
4. Adjust the Last Changes Options as you prefer them. 

== Screenshots ==

1. Configuration of the Widget
2. Output of the Widget
3. Enable custom fields on the page
4. Add this custom field on a page to exclude it from being listed in the widget.

== Frequently Asked Questions ==

= Where are your Frequently Asked Questions? Why aren't they here? =

Because no questions were asked.

== Upgrade Notice ==

* 0.5.0 Added support for also showing recent changed posts
* 0.4.1 Bugfix for excluding hierarchical pages 
* 0.4.0 Added possibility to exclude pages listed in the widget.
* 0.3.2 Tested with Wordpress 4.0
* 0.3.1 Changed Plugin Link
* 0.3.0 First official release.
