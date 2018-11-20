=== List Last Changes ===
Contributors: rbaer
Tags: last changes, pages, posts
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=PRW4QXZ3DHWL6&lc=GB&item_name=List%20Last%20Changes%20Plugin&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donateCC_LG_global%2egif%3aNonHosted
Requires at least: 4.6.0
Tested up to: 5.0.0
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Stable tag: 0.7.0

Shows a list of the last changes of a WordPress site.

== Description ==

Shows a list of the last changes in the widget area or via shortcode. This can include changed pages and/or posts (configurable).

Additional features include:

* Select the number of entries in the list
* Define pages to be exluded

= Exclude page or post =
To exclude a page or post from being listed in the widget do the following steps:

1. Open the page or post you want to exclude for editing.
2. Open the "Screen Options" (on the top right of the page)
3. Ensure that "Custom Fields" is checked (see Screenshot #3)
4. In the "Custom Fields" further down the screen add the following custom field (see  Screenshot #4):
 * name: list_last_changes_ignore
 * value: true

To include it again set the value to false or remove the custom field completely.

= Shortcode =
To show the list of the last changes via shortcode use the following syntax:

[list_last_changes number='7' showpages='true' showposts='true' /]

The Attribute 'number' defines the number of entries shown.
With the attributes 'showpages' and 'showposts' changed pages and/or posts are included.

In difference to the widget, with the sortcode no title is written. If a title is needed it has to be defined by hand in front of the shortcode.

== Changelog ==

= 0.7.0 =

*Release date: November 18, 2018*

* Added support for shortcode
* some refactorings

== Screenshots ==

1. Configuration of the widget
2. Output of the widget
3. Enable custom fields on the page
4. Add this custom field on a page to exclude it from being listed in the widget.

== Frequently Asked Questions ==

= Where are your Frequently Asked Questions? Why aren't they here? =

Because no questions were asked.

