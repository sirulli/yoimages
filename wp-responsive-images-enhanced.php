<?php

/**
 * Plugin Name: WP Responsive Images Enhanced
 * Plugin URI: https://github.com/fagia/wp-responsive-images-enhanced
 * Description: Adds support for showing and managing responsive images.
 * Version: 0.0.1
 * Author: Matteo Cajani
 * Author URI: http://fagia.martjanplanet.com
 * License: GPL2
**/

/**
 * Copyright 2014 Matteo Cajani (email : matteo.cajani@gmail.com)
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
**/

if (! defined ( 'ABSPATH' )) {
	die ( 'No script kiddies please!' );
}

define ( 'WPRIE_PATH', dirname ( __FILE__ ) . '/' );
define ( 'WPRIE_URL', plugins_url ( basename ( dirname ( __FILE__ ) ) ) . '/' );
define ( 'WPRIE_EDIT_IMAGE_ACTION', 'wprie-edit-thumbnails' );

require_once (WPRIE_PATH . 'inc/utils.php');
require_once (WPRIE_PATH . 'inc/extend-admin-media.php');
require_once (WPRIE_PATH . 'inc/extend-admin-post.php');
