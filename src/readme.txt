=== List Last Changes ===
Contributors: rbaer, osthafen
Tags: last changes, widget, shortcode, block editor
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=PRW4QXZ3DHWL6&lc=GB&item_name=List%20Last%20Changes%20Plugin&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donateCC_LG_global%2egif%3aNonHosted
Requires at least: 4.6.0
Tested up to: 6.6
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Stable tag: 1.2.0

Shows a list of the last changes of a WordPress site.

== Description ==

Shows a list of the last changes in the widget area, via shortcode or in the gutenberg block editor.
This can include changed pages and/or posts (configurable).

Additional features include:

* Select the number of entries in the list
* Define pages to be excluded
* Show the author or the last editor of the page/post
* Select and order by date modified or date published

= Exclude page or post =
To exclude a page or post from being listed in the widget do the following steps:

1. Open the page or post you want to exclude for editing.
2. Open the "Screen Options" (on the top right of the page)
3. Ensure that "Custom Fields" is checked (see Screenshot #3)
4. In the "Custom Fields" further down the screen add the following custom field (see Screenshot #4):
 * name: list_last_changes_ignore
 * value: true

To include it again set the value to false or remove the custom field completely.

= Block Editor (Gutenberg) =
The block can be found in Widgets -> List Last Changes.
The block can be configured in the block settings (panel on the right side)

In difference to the widget, with the block no title is written. If a title is needed it has to be defined in an own block in front of the List Last Changes block.

= Shortcode =
To show the list of the last changes via shortcode use the following syntax:

    [list_last_changes number='7' showpages='true' showposts='true' showauthor='false' usedatepublished='true' template='{title} {change_date} {author}' /]

The attribute 'number' defines the number of entries shown.
With the attributes 'showpages' and 'showposts' changed pages and/or posts are included.
To show also the author set the attribute 'showauthor' to true (deprecated, use template mechanism instead).
With the attribute 'usedatepublished' set to 'true' the date when the page or post was first published, if set to 'false' (or not set at all) the date the page or post was modified is used.
The 'template' attribute defines the content of the entries. If the 'template' attribute is defined the attribute 'showauthor' is ignored.

In difference to the widget, with the shortcode no title is written. If a title is needed it has to be defined by hand in front of the shortcode.

= Templates =

In the template string the following fields can be used: {title}, {change_date}, {published_date}, {author} and {editor}.
{title} : the title of the page or post with a link to it
{change_date} or {change_date[format]} : the date the page or post was changed ("modified date")
{published_date} or {published_date[format]} : the date the page or post was published ("post date")
{author} : the author of the page or post
{editor} : the last editor of the page or post

With the optional [format] the date format of change_date and published_date can be defined. The date format is in the php date formatting.
Without definition the wordpress system date format is used.

Sample templates:
{title} {change_date} : the default template
{title} {change_date[Y-m-d H:i]} : like the default template but with date and time defined by the given format
{title} {change_date} {author} : behaves as in versions before 0.9 when show author was enabled
{change_date} : shows only the change_date, can be used with number = 1 as last modified date of a WordPress Site

= How can I report security bugs? =

You can report security bugs through the Patchstack Vulnerability Disclosure Program. The Patchstack team help validate, triage and handle any security vulnerabilities. [Report a security vulnerability.](https://patchstack.com/database/vdp/list-last-changes)

Or you send them directly to [mailto:info@rolandbaer.ch](mailto:info@rolandbaer.ch).

== Changelog ==

= 1.2.0 =

*Release date: October 02, 2024*

* fields {change_date} and {published_date} with optional date format

= 1.1.2 =

*Release date: September 13, 2024*

* fix for wrong user name of editor field under some circumstances

= 1.1.1 =

*Release date: July 13, 2024*

* Small quality fixes (from static code analyzer)

= 1.1.0 =

*Release date: April 23, 2024*

* Configuration to select and order the pages and/or posts by date modified or date published
* new field {published_date} in the template string to show the date the page or post was published ("post date")
* new field {editor} in the template string to show the last editor of the page or post

= 1.0.5 =

*Release date: November 12, 2023*

* Bugfix for limit the ignored pages or posts to the number of posts per page (regression of 1.0.2).

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

