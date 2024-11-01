=== Twitter It! ===
Contributors: Roman Allenstein
Donate link: http://roman-allenstein.de/wordpress
Tags: twitter, post twitter, twitter post, twitterit, twitter it, twitter this
Requires at least: 2.7.0
Tested up to: 3.2.1
Stable tag: 5

Lets the user twitter your blogpost easy - with short-url support.

== Description ==

EN: With this plugin an user has the ability to twitter a article that he is your site. This plugin is easy to install highly customizable. Caching of short-urls included!

DE: Mit diesem Plugin kann ein Benutzer &uuml;ber Twitter mitteilen, welchen Artikel er gerade auf deinem Blog liest.

== Changelog ==
**V4.2** - Added option to clear the cache and refresh all short-urls.

**V4.1** - Fixed file_get_contents() error, if this function is disabled from your hoster.

**V4** - Added bit.ly shortening, added more images, relaunched admin-interface

**V3.2** - Fixed CSS-Error

**V3** - Added some customizing features like prefix and custom css via style-tag. Added ability to mark the link as "nofollow".

**V2** - Added TinyURL-Caching, added Icons.

**V1** - First release. Just generating a twitter-link.

Plugin by <a href="http://roman-allenstein.de">Roman Allenstein</a>.

== Installation ==

EN: Using this plugin is very easy. Just install it and put 

<pre>&lt;?php if ( function_exists('twitter_it') ) : twitter_it($post->ID); endif; ?&gt;</pre>

in your template next to your posting into "the loop". For further informations see the examples.


DE: Die Benutzung des Plugins ist sehr einfach. Einfach installieren und und folgenden Code in das Template einf&uuml;gen. 

<pre>&lt;?php if ( function_exists('twitter_it') ) : twitter_it($post->ID); endif; ?&gt;</pre>

F&uuml;r weitere Anwendungsbeispiele unter "Examples" nachschauen.



1. Upload the whole plugin folder to your /wp-content/plugins/ folder.

2. Go to the Plugins page and activate the plugin.

3. Use the Options page to set the customizing-options like prefix.

4. Integrate the template-tag into your template.
<pre>&lt;?php if ( function_exists('twitter_it') ) : twitter_it($post->ID); endif; ?&gt;</pre>

== Upgrade Notice ==

== License ==

This file is part of Twitter It!

Twitter It! is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

Twitter It! is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with Theme Test Drive. If not, see <http://www.gnu.org/licenses/>.

== Screenshots ==

1. Twitter It! Options-Panel

2. Twitter It! in action.

== Frequently Asked Questions ==

= How do I correctly use this plugin? =

Go to Admin Panel, Options, Twitter It!
Set up the plugin options.
Integrate the template-tag into your template.

= Can I suggest an feature for the plugin? =

Of course, visit <a href="http://roman-allenstein.de/wordpress/">Twitter It! Home Page</a> or <a href="http://www.macbookbilliger.de">macbook billiger</a>

= I love your work, are you available for hire? =

Yes I am, visit and contact me via <a href="http://roman-allenstein.de/">Roman Allenstein</a>.