=== Ensemble ===
Contributors: DrewAPicture
Tags: color guard, sports, teams, percussion, circuit
Donate link: https://wordpressfoundation.org/donate/
Requires at least: 4.9.6
Tested up to: 5.0
Requires PHP: 7.0
Stable tag: 1.0.1
License: GPLv2

Easily manage the data for a color guard circuit (or similar sport/activity organization) with WordPress.

== Description ==
Ensemble is a game-changing solution for running a color guard or other alternative sports circuit using WordPress. Finally, all of the data you need in one place, and it’s all tied together: venues, contests, seasons, units, unit directors, and more.

Note: Ensemble serves only as the data layer. There are a wide variety of rich connections between the data, which can be managed via the WordPress admin, but this plugin serves only to create an entry point for tying all of that data together.

A sister theme is in the works to bring all of this to the front end in the near future.

= Contests =

Contests are central to how Ensemble works. They’re tied to venues and seasons, which are in-turn tied to everything else.

= Venue Management =

Being able to manage canonical venue data is what ultiamtely allows for so much work to be done once instead of once every season.

= Competing Units =

Units are the heart and soul of any sports activity, and Ensemble has designed them to be super easy to tie in to everything.

= Unit Directors =

People management is important too – this first version of Ensemble has a Unit Director component baked in from the start.

= Season & Time Management =

Season creation with start and end dates means no more confusion about which contests are current and which have already passed.

= What's Next? =

Integrations will be coming soon in the form of sister theme for the front-end and support for popular calendar & eCommerce solutions.

== Frequently Asked Questions ==
Q: Where is the front end of this thing?
A: A sister theme is currently in development, which will bring Ensemble to the front end soon.

Q: I activated the plugin, but I don't see a menu anywhere, what gives?
A: It's possible your site doesn't meet the minimum requirements. Check the Plugins screen to see if any partial activation warnings are listed.

Q: Where can I request a feature or report a bug?
A: You\'re welcome to submit an issue on the [Github repository](https://github.com/DrewAPicture/ensemble/issues).

== Screenshots ==
1. Welcome screen.
2. Venue management.
3. Adding a new season.
4. Assigning units to a new director.
5. Adding a new unit.
6. Contests overview.

== Changelog ==

= 1.1.0 =

* New: Introduced an Instructor component and management screens to live alongside Directors
* New: Added an 'External' label to contests with an external URL
* New: Added two new contest types: Regional Competition and World Championships
* Improved: Contests now default to published instead of private
* Improved: Directors are now listed alphabetically by name
* Fixed: Pagination controls were not displaying on all screens
* Fixed: Notices were sometimes unnecessarily persisting across screens
* Under the hood: Added full test coverage was added for all core components

= 1.0.1 =

Minor bug fixes:

* Added missing translator notes for strings with specifiers in `Ensemble_Check_Requirements`
* Updated the default venue type to School, reordered the choices more logically
* Fixed some typos in the welcome message shown when adding the first Unit Director
* Added logic to remove all created terms and term meta on plugin uninstall

= 1.0.0 =

* First release.

== Upgrade Notice ==

* Nothing to see here (yet!)
