=== Neykane Viral List Posts ===
Contributors: neykane
Donate link: https://www.neykane.com/donate
Tags: list, post, list post, listicle, viral, neykane
Requires PHP: 7.4
Requires at least: 4.7
Tested up to: 5.8
Stable tag: 1.1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds a new "List Posts" Custom Post Type to help you create memorable, well-organized, and highly-shareable list posts.

== Description ==

Neykane Viral List Posts is designed with content creators in mind and handles all of the boring configuration so you can focus on producing rich, quality content for your readers.

https://vimeo.com/337669289

On the most basic level, a list post ( or a *[listicle](https://en.wikipedia.org/wiki/Listicle)* ) is just an article in list format.

Here's a few real world examples:

* [10 Recent Netflix Originals Worth Your Time](https://www.nytimes.com/2019/03/15/arts/television/netflix-originals.html)
* [7 Podcasts You Need to Be Listening To](https://www.ismailzai.com/blog/7-serial-fiction-podcasts)
* [5 Command Line Techniques to Be More Productive](https://www.ismailzai.com/blog/5-command-line-techniques-to-be-more-productive)

What will you create?

== Installation ==

1. Install the plugin from the WordPress Plugin Directory.
2. Activate the plugin through the **Plugins** menu in WordPress.
3. Create a new List Post through the new **Viral List Posts** menu in WordPress. All published List Posts can be viewed on their own, and/or, optionally embedded into a post or page of your choice using the automatically generated shortcode.

== Frequently Asked Questions ==

= How can I customize the look of list posts? =

You can use CSS to customize most aspects of the list posts. Here are the basic structures of each of the templates:

**Modern (vertical and horizontal)**

`<div class="card shadow neykane-viral-list-posts__item" id="list_posts_1">
	<img class="card-img-top neykane-viral-list-posts__item--img" src="http://example.com/my_amazing_image.jpg">
	<div class="card-body neykane-viral-list-posts__item--body">
	    <h5 class="card-title neykane-viral-list-posts__item--title">1. My awesome title</h5>
	    <div class="py-1 neykane-viral-list-posts__item--content">
	        My epic body text
	    </div>
	</div>
</div>`

= Can I use my own template? =

We plan to add support for user templates in an upcoming update.

= Will you be adding more templates? =

Yes, we plan to add several new and exciting templates soon.

== Screenshots ==

1. Neykane Viral List Posts allows you to create beautiful list posts with just a few clicks. The resulting articles (sometimes called listicles) can be viewed on their own or embedded into existing pages, posts, or custom post types.
2. Unlike other plugins, there's no reliance on arcane markup or tedious workflows -- everything is done using our modern, intuitive visual editor.

== Changelog ==

= 1.1.1 =
* Fix title escaping (thank you for the bug report https://wordpress.org/support/users/sapphire/!)

= 1.1.0 =
* Switch to WordPress code style.
* Linting, spellcheck, other cosmetics.
* Escaping and sanitization.
* Switch to Bootstrap 5.

= 1.0.2 =
* Verify support for WordPress 5.8.

= 1.0.1 =
* Fixed an issue where shortcodes were not being saved by the gutenberg editor.
* Fixed a cosmetic issue where trashing a list post generated an error message (despite this message, posts *were* being correctly trashed).
* Updated the README.txt file to note the correct plugin name and required PHP.
* Updated the FAQ.

= 1.0.0 =
* Initial release.

== Upgrade Notice ==

= 1.0.1 =
Upgrade to this release if you want to use shortcodes with the gutenberg editor.

= 1.0.0 =
All of our beta users should upgrade to this stable release.
