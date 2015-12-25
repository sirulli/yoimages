#YoImages

Better image handling capabilities for Wordpress.
All you need to handle your images in Wordpress in one plugin.

YoImages adds the following functional enhancements to the Wordpress admin interface:
- image cropping tools: [demo video](https://www.youtube.com/watch?v=nGkn7A8gA6M ""). No more images cropped wrong, you can choose now what to display and even replace the entire image for a specific crop size if the orginal image doesn't fit. Crop at a lower quality to speed up page loading. Create croppings in retina format too.
- image SEO hooks: [demo video](https://www.youtube.com/watch?v=ZMv4Pqp4HQA ""). Images are important for SEO but are never optimized enough. With YoImages you can automatically optimize images for Search Engines. No more alt tag missing or non informative titles or file names. Google can't see the image (yet) but, can read its attributes.
- free stock photos search: [demo video](https://www.youtube.com/watch?v=QH9uzQ2hE_c ""). Search and upload royalty free photos from the web directly into the Wordpress Admin interface.


##Image cropping tools

YoImages' cropping tools let you crop manually each cropping format that your theme defines: this feature gives you full control on how cropped versions of your images will look like.

You can choose to replace the source image for some specific formats.

From the image cropping interface you can change the image quality for each cropped format.

YoImages cropping is retina friendly: if you are using a retina plugin that uses the standard @2x as file naming convention when creating retina images from source (e.g. [WP Retina 2x](https://wordpress.org/plugins/wp-retina-2x/ "")) you can enable the retina friendly cropping option in YoImages' settings page and the manual crops will be created in retina format too.

##Image SEO hooks

YoImages' SEO hooks automate image metadata (title, alt and filename) filling on image upload and on post (or page) saving.

Each image SEO hook can be enabled or disabled individually and it works on any image that is child of a post or page such as the featured image and images or galleries added into the post WYSIWYG area.

You are free to define metadata values by using fixed texts and the following variables from the post/page that contains an image:
- parent post title
- parent post type
- parent post tags
- parent post categories
- parent post author username
- parent post author first name
- parent post author last name
- site name


####Adding your own custom hooks

YoImages' SEO hooks work on post saving or updating time and updates post's related images metadata.
The *yoimg_seo_images_to_update* filter allows to add other images to be considered, for example images linked to the post via custom fields.

This filter takes in input the array of the images' ids linked to a post and the post id itself.

The following example shows how YoImages plugin uses this filter to have the featured image metadata updated:

```php

function yoimg_imgseo_add_featured_image( $ids, $post_id ) {
	$post_thumbnail_id = get_post_thumbnail_id( $post_id );
	array_push( $ids, $post_thumbnail_id );
	return $ids;
}
add_filter('yoimg_seo_images_to_update', 'yoimg_imgseo_add_featured_image', 10, 2);

```


####Adding your own custom variables

To add a new variable you have to hook two filters:
- *yoimg_seo_expressions*
- *yoimg_supported_expressions*


*yoimg_seo_expressions* is the filter that allows variables substitutions into the string being associated with the image metadata.

This filter makes use of the parent post object and of the attachment image post.

The following example shows how this filter is used for the *\[title\]* variable: 

```php

function example_expression_title( $result, $attachment, $parent ) {
	if ( strpos( $result, '[title]' ) !== FALSE ) {
		$result = str_replace( '[title]', $parent->post_title, $result );
	}
	return $result;
}
add_filter('yoimg_seo_expressions', 'example_expression_title', 10, 3);

```

*yoimg_supported_expressions* is the filter that defines which variables expressions are supported.

This filter takes in input an array of already supported variables and adds new variables to this array.

The following example shows how to add support for the *\[title\]* variable:

```php

function example_supported_expressions( $supported_expressions ) {
	if ( ! $supported_expressions ) {
		$supported_expressions = array();
	}
	array_push( $supported_expressions, '[title]' );
	return $supported_expressions;
}
add_filter( 'yoimg_supported_expressions', 'example_supported_expressions', 10, 1 );

```


##Free stock photos search

YoImages' free stock photos search feature lets you perform a free term search directly from the Wordpress admin interface in the following databases:
- [splashbase.co](http://www.splashbase.co/ "")
- [unsplash.com](https://unsplash.com/ "")
The photos you select are uploaded into your Wordpress site and optimized with YoImages' crop and SEO tools.

Photos from splashbase.co and unsplash.com are hi-res and free to use, but we recommend checking copyright details for each photo you choose.


####Adding new free stock photos search providers

Implement and register a javascript client for the free stock photos search provider you want to add.
To do that, you can use this reference implementation: [yoimg-search-splashbase.js](https://github.com/sirulli/yoimages-search/blob/master/inc/js/providers/yoimg-search-splashbase.js "")

Then add your provider's javascript client implementation via the "yoimg_search_providers" filter: 

```php
function my_search_provider( $search_providers ) {
       array_push( $search_providers, array(
			'js' => MY_PLUGIN_URL . '/my-provider-client.js',
			'url' => 'http://my.provider.url/',
			'name' => 'MyProviderName'
	   ) );
       return $search_providers;
}
add_filter( 'yoimg_search_providers', 'my_search_provider' );
```



##Install YoImages from sources

YoImages is a modular Wordpress plugin built with [Composer](https://getcomposer.org/ "").

YoImages includes the following modules:

* [YoImages Commons] (https://github.com/sirulli/yoimages-commons "")
* [YoImages Crop] (https://github.com/sirulli/yoimages-crop "")
* [YoImages SEO] (https://github.com/sirulli/yoimages-seo "")
* [YoImages Search] (https://github.com/sirulli/yoimages-search "")


To install it from sources go to your Wordpress plugin directory via terminal and there:

```sh

git clone https://github.com/sirulli/yoimages.git
cd yoimages
curl -sS https://getcomposer.org/installer | php
php composer.phar install

```


To update your installed YoImages plugin from sources go to Wordpress plugin directory via terminal and there: 

```sh

cd yoimages
git pull
php composer.phar update

```

##Languages supported

Primary: English

Translations: Italian, German, Dutch, French, Polish

Translations are managed with [poeditor.com](https://poeditor.com/projects/view?id=25799 "").

##Future features

Future features to implement:
- simple built-in image editor (effects, editing, color optimization)
- image gallery templates


Feel free to report bugs or request new features [here](https://github.com/sirulli/yoimages/issues "").

##How to contribute

[http://sirulli.org/yoimages/#contribute](http://sirulli.org/yoimages/#contribute "")

##Credits

Thanks to Fengyuan Chen for his [jQuery Image Cropper](http://fengyuanchen.github.io/cropper/ "") plugin.

Thanks to [wp-fred](https://profiles.wordpress.org/wp-fred-1/ "") for the Dutch translations of the plugin.

Thanks to [Maxime Lafontaine](http://www.maximelafontaine.net/ "") for the French translations of the plugin.

Thanks to [Thomas Meyer](https://github.com/tmconnect/ "") for code contributions and fixes to the German translations.

Thanks to [Robert Vermeulen](https://github.com/robert388 "") for adding better support for metadata, support for WP-CLI commands and making YoImages compatible with Regenerate Thumbnails plugin.

Thanks to [Elliot Coad](https://github.com/ecoad "") for adding the firing of an action after cropping.

Thanks to [odie2](https://github.com/odie2/ "") for code contributions and for the Polish translations of the plugin.
