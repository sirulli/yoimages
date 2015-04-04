<?php

/**
 * Plugin Name: YoImages
 * Plugin URI: http://sirulli.org/yoimages/
 * Description: Better image handling capabilities for Wordpress: image cropping tools and image SEO with automated metadata filling.
 * Version: 0.0.7
 * Author: Sirulli
 * Author URI: http://sirulli.org/
 * License: GPL2
 * Text Domain: yoimg
**/

/**
 * Copyright 2014-2015 Sirulli (email : team@sirulli.org)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */
if (! defined ( 'ABSPATH' )) {
	die ( 'No script kiddies please!' );
}

define ( 'YOIMG_PATH', dirname ( __FILE__ ) );
require_once (YOIMG_PATH . '/vendor/sirulli/yoimages-commons/inc/init.php');
yoimg_register_module( 'yoimages-crop', YOIMG_PATH, true );
yoimg_register_module( 'yoimages-seo', YOIMG_PATH, true );
