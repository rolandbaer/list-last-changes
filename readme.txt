=== List Last Changes ===
Contributors: rbaer, osthafen
Tags: last changes, pages, posts, widget, shortcode, block editor
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=PRW4QXZ3DHWL6&lc=GB&item_name=List%20Last%20Changes%20Plugin&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donateCC_LG_global%2egif%3aNonHosted
Requires at least: 4.6.0
Tested up to: 6.4
Requires PHP: 7.0
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Stable tag: 1.0.4

Shows a list of the last changes of a WordPress site.

== Description ==

Shows a list of the last changes in the widget area, via shortcode or in the gutenberg block editor.
This can include changed pages and/or posts (configurable).

Additional features include:

* Select the number of entries in the list
* Define pages to be excluded
* Show the author of the page/post

= Exclude page or post =
To exclude a page or post from being listed in the widget do the following steps:

1. Open the page or post you want to exclude for editing.
2. Open the "Screen Options" (on the top right of the page)
3. Ensure that "Custom Fields" is checked (see Screenshot #3)
4. In the "Custom Fields" further down the screen add the following custom field (see  Screenshot #4):
 * name: list_last_changes_ignore
 * value: true

To include it again set the value to false or remove the custom field completely.

= Block Editor (Gutenberg) =
The block can be found in Widgets -> List Last Changes.
The block can be configured in the block settings (panel on the right side)

In difference to the widget, with the block no title is written. If a title is needed it has to be defined in an own block in front of the List Last Changes block.

= Shortcode =
To show the list of the last changes via shortcode use the following syntax:

    [list_last_changes number='7' showpages='true' showposts='true' showauthor='false' template='{title} {change_date} {author}' /]

The attribute 'number' defines the number of entries shown.
With the attributes 'showpages' and 'showposts' changed pages and/or posts are included.
To show also the author set the attribute 'showauthor' to true (deprecated, use template mechanism instead).
The 'template' attribute defines the content of the entries. If the 'template' attribute is defined the attribute 'showauthor' is ignored.

In difference to the widget, with the shortcode no title is written. If a title is needed it has to be defined by hand in front of the shortcode.

= Templates =

In the template string the following fields can be used: {title}, {change_date} and {author}.
{title} : the title of the page or post with a link to it
{change_date} : the date the page or post was changed
{author} : the author of the page or post

Sample templates:
{title} {change_date} : the default template
{title} {change_date} {author} : behaves as in versions before 0.9 when show author was enabled
{change_date} : shows only the change_date, can be used with number = 1 as last modified date of a WordPress Site

== Changelog ==

= 1.0.4 =

*Release date: November 11, 2023*

* Bugfixx for block editor support: adapted to changes in the block editor handling

= 1.0.3 =

*Release date: November 08, 2023*

* Bugfix for 1.0.2: removed refactorings (type information) that occured errors with some older php versions

= 1.0.2 =

*Release date: November 07, 2023*

* Bugfix for interference with other plugins. Uses now the method WP_Query to get the posts and pages to ignore

= 1.0.1 =

*Release date: September 05, 2022*

* Bugfix for exclude only up to five posts 

= 1.0.0 =

*Release date: December 09, 2020*

* Introducing template mechanism in the block editor 
* The configuration field 'show author' in the block editor is replaced by the template mechanism
* The template mechanism is now "feature complete"

= Older releases =
see [additional changelog.txt file](https://plugins.svn.wordpress.org/list-last-changes/trunk/changelog.txt)

== Screenshots ==

1. Configuration of the widget
2. Output of the widget
3. Enable custom fields on the page
4. Add this custom field on a page to exclude it from being listed in the widget.

== Frequently Asked Questions ==

= Where are your Frequently Asked Questions? Why aren't they here? =

Because no questions were asked.

