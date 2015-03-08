=== YoImages ===
Contributors: ferrbea, fagia
Donate link: sirulli.org/yoimages
Tags: images, image, SEO, enhancement, crop, tool
Requires at least: 4.0
Tested up to: 4.1.1
Stable tag: 0.0.6
License: GPL2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Better image handling capabilities for Wordpress. YoImages adds functional enhancements to the Wordpress admin interface: image cropping and image SEO

== Description ==

Better image handling capabilities for Wordpress.
All you need to handle your images in Wordpress in one plugin.
YoImages adds functional enhancements to the Wordpress admin interface:

* *Image cropping tools:* no more images cropped wrong, you can choose now what to display and even replace the entire image for a specific crop size if the orginal image doesn't fit. Crop at a lower quality to speed up page loading. Create croppings in retina format too.
* *Image SEO hooks:* images are important for SEO but are never optimized enough. With YoImages you can automatically optimize images for Search Engines. No more alt tag missing or non informative titles or file names. Google can't see the image (yet) but, can read its attributes.

[youtube https://www.youtube.com/watch?v=6SAC_QD1CCU]



= Image cropping tools =

YoImages' cropping tools let you crop manually each cropping format that your theme defines: this feature gives you full control on how cropped versions of your images will look like.
You can choose to replace the source image for some specific formats.
From the image cropping interface you can change the image quality for each cropped format.
YoImages cropping is retina friendly: if you are using a retina plugin that uses the standard @2x as file naming convention when creating retina images from source (e.g. [WP Retina 2x](https://wordpress.org/plugins/wp-retina-2x/ "")) you can enable the retina friendly cropping option in YoImages' settings page and the manual crops will be created in retina format too.

= Image SEO hooks =

YoImages' SEO hooks automate image metadata (title, alt and filename) filling on image upload and on post (or page) saving.
Each image SEO hook can be enabled or disabled individually and it works on any image that is child of a post or page such as the featured image and images or galleries added into the post WYSIWYG area.
You are free to define metadata values by using fixed texts and the following variables from the post/page that contains an image:

* parent post title
* parent post type
* parent post tags
* parent post categories
* parent post author username
* parent post author first name
* parent post author last name
* site name


*Note:* you can add your own [custom hooks](https://github.com/sirulli/yoimages#adding-your-own-custom-hooks) and your own [custom variables](https://github.com/sirulli/yoimages#adding-your-own-custom-variables).

= Languages supported =

* Primary: English
* Translations: Italian, German, Dutch, French

Translations are managed with [poeditor.com](https://poeditor.com/projects/view?id=25799 "").


= Future features =

Future features to implement:

* direct search of royalty free images
* simple built-in image editor (effects, editing, color optimization)
* image gallery templates
* ...


Feel free to report bugs or request new features [here](https://github.com/sirulli/yoimages/issues).

= How to contribute =

[http://sirulli.org/yoimages/#contribute](http://sirulli.org/yoimages/#contribute)

= Credits =

Thanks to Fengyuan Chen for his [jQuery Image Cropper](http://fengyuanchen.github.io/cropper/) plugin.
Thanks to [wp-fred](https://profiles.wordpress.org/wp-fred-1/) for the Dutch translations of the plugin.
Thanks to [Maxime Lafontaine](http://www.maximelafontaine.net/) for the French translations of the plugin.


= Source code =

YoImages source code is hosted on [GitHub](https://github.com/sirulli/yoimages).

== Installation ==

1. Install YoImages either via the WordPress.org plugin directory, or by uploading the files to your server.
2. Activate the plugin through the 'Plugins' menu in WordPress


== Screenshots ==

1. custom crop 
2. choose crop quality
3. custom metadata value on post
4. custom metadata values even on gallery creation

== Changelog ==

= 0.0.6 =
* Bugfix: avoiding undefined index notice while saving settings and avoiding "crop retina is smaller" warning when retina crop isn't enabled

= 0.0.5 =
* French translations, thanks to [Maxime Lafontaine](http://www.maximelafontaine.net/)
* Tested up to Wordpress 4.1.1 

= 0.0.4 =
* Added SEO expressions related to the post author metadata: username, first name and last name
* Minor fixes on UX and on default translations

= 0.0.3 =
* Support for retina cropping: integration with the [WP Retina 2x plugin](https://wordpress.org/plugins/wp-retina-2x/)
* Bugfix: avoid undefined index error notice

= 0.0.2 =
* Dutch translations, thanks to [wp-fred](https://profiles.wordpress.org/wp-fred-1/)
* Bugfix: SEO expressions replacement in any languange (not only in the language currently enabled)

= 0.0.1 =
* initial version
