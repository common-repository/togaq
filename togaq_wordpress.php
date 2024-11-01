<?php
/*
Plugin Name:  Toga-Q for WordPress
Plugin URI: http://www.vjcatkick.com/?page_id=5930
Description: Top Banner Sharing Project, Toga-Q for WordPress. This automatically changes your top banner (image at top of your blog) from our over 1,000 beautifull banner images stock.
Version: 0.1.3
Author: V.J.Catkick
Author URI: http://www.vjcatkick.com/
*/

/*
License: GPL
Compatibility: WordPress 2.6 with Widget-plugin.

Installation:
Place the widget_single_photo folder in your /wp-content/plugins/ directory
and activate through the administration panel, and then go to the widget panel and
drag it to where you would like to have it!
*/

/*  Copyright V.J.Catkick - http://www.vjcatkick.com/

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/* Changelog
* Jan 11 2009 - v0.1.0
- Initial release
* Jan 13 2009 - v0.1.1
- added: option file, adjust sidebar widgets, random logic change, hourly added
* Jan 15 2009 - v0.1.2
- widget: creator link had been fixed
* Jan 15 2009 - v0.1.3
- fixed togaq_get_image_fullpath() - when load error
*/

//load_plugin_textdomain('togaq');
$plugin_dir = basename(dirname(__FILE__));
load_plugin_textdomain( 'togaq', 'wp-content/plugins/'. $plugin_dir, $plugin_dir );

function togaq_init() {
	add_action( 'admin_menu', 'togaq_config_page');
} /* togaq_init() */
add_action('init', 'togaq_init');

function togaq_config_page() {
	if ( function_exists('add_options_page') )
		add_options_page( 'Toga-Q Configuration', 'Toga-Q',  8, 'togaq_options_page', 'togaq_conf' );
} /* togaq_config_page */

function togaq_conf() {
	$options = $newoptions = get_option('togaq_options');

	if ( $_POST["togaq_options_submit"] ) {
		$newoptions['togaq_options_off_top'] = (int) $_POST["togaq_options_off_top"];
		$newoptions['togaq_options_off_left'] = (int) $_POST["togaq_options_off_left"];
		$newoptions['togaq_options_height'] = (int) $_POST["togaq_options_height"];
		$newoptions['togaq_options_path_title'] = $_POST["togaq_options_path_title"];
		$newoptions['togaq_options_use_text_title'] = (int) $_POST["togaq_options_use_text_title"];

		$newoptions['togaq_options_site_timing'] = (int) $_POST["togaq_options_site_timing"];
		$newoptions['togaq_options_site_order'] = (int) $_POST["togaq_options_site_order"];
		$newoptions['togaq_options_img_order'] = (int) $_POST["togaq_options_img_order"];
	} /* if */
	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option('togaq_options', $options);
	} /* if */

	// those are default value
	if ( !$options['togaq_options_height'] ) $options['togaq_options_height'] = 100;


	// for option page
	$togaq_options_off_top = (int)$options['togaq_options_off_top'];
	$togaq_options_off_left = (int)$options['togaq_options_off_left'];
	$togaq_options_height = (int)$options['togaq_options_height'];
	$togaq_options_path_title = $options['togaq_options_path_title'];
	$togaq_options_use_text_title = (int)$options['togaq_options_use_text_title'];

	$togaq_options_site_timing = (int)$options['togaq_options_site_timing'];
	$togaq_options_site_order = (int)$options['togaq_options_site_order'];
	$togaq_options_img_order = (int)$options['togaq_options_img_order'];


?>
<?php if ( !empty($_POST ) ) : ?>
<div id="message" class="updated fade"><p><strong><?php _e('Options saved.') ?></strong></p></div>
<?php endif; ?>
<div class="wrap"><div class='narrow' >
<h2><?php _e('Toga-Q Configuration','togaq'); ?></h2>
<form action="" method="post" id="akismet-conf" style="margin: auto; width: 400px; ">

	<h3><?php _e( 'Display Pattern','togaq'); ?></h3>
<p>
	<?php _e('Creators','togaq'); ?><br />
	<?php _e('Timing: ','togaq'); ?> <input id="togaq_options_site_timing" name="togaq_options_site_timing" type="radio" value="0" <?php if( $togaq_options_site_timing == 0 ) echo 'checked';?>/><?php _e('Each time loading page','togaq'); ?>
	<input id="togaq_options_site_timing" name="togaq_options_site_timing" type="radio" value="1" <?php if( $togaq_options_site_timing == 1 ) echo 'checked';?>/><?php _e('Daily','togaq'); ?>
	<input id="togaq_options_site_timing" name="togaq_options_site_timing" type="radio" value="2" <?php if( $togaq_options_site_timing == 2 ) echo 'checked';?>/><?php _e('Hourly','togaq'); ?><br />
	<?php _e('Order: ','togaq'); ?> <input id="togaq_options_site_order" name="togaq_options_site_order" type="radio" value="0" <?php if( $togaq_options_site_order == 0 ) echo 'checked';?>/><?php _e('Random','togaq'); ?>
	<input id="togaq_options_site_order" name="togaq_options_site_order" type="radio" value="1" <?php if( $togaq_options_site_order == 1 ) echo 'checked';?>/><?php _e('Order','togaq'); ?><br />
<br />
	<?php _e('Banner Images','togaq'); ?><br />
	<?php _e('Order: ','togaq'); ?> <input id="togaq_options_img_order" name="togaq_options_img_order" type="radio" value="0" <?php if( $togaq_options_img_order == 0 ) echo 'checked';?>/><?php _e('Random','togaq'); ?>
	<input id="togaq_options_img_order" name="togaq_options_img_order" type="radio" value="1" <?php if( $togaq_options_img_order == 1 ) echo 'checked';?>/><?php _e('Order','togaq'); ?><br /><span style="font-size:0.8em;"><?php _e( '*if error detect while loading banner, program will automatically change creator.','togaq'); ?></span>
</p>

<hr />

	<h3><?php _e( 'Toga-Q Image Positioning','togaq'); ?></h3>
<p>
	<?php _e('Top offset:','togaq'); ?> <input style="width: 50px;" id="togaq_options_off_top" name="togaq_options_off_top" type="text" value="<?php echo $togaq_options_off_top; ?>" /><?php _e(' px', 'togaq'); ?><br />
	<?php _e('Left offset:','togaq'); ?> <input style="width: 50px;" id="togaq_options_off_left" name="togaq_options_off_left" type="text" value="<?php echo $togaq_options_off_left; ?>" /><?php _e(' px', 'togaq'); ?><br /><br />
	<?php _e('Height:','togaq'); ?> <input style="width: 50px;" id="togaq_options_height" name="togaq_options_height" type="text" value="<?php echo $togaq_options_height; ?>" /><?php _e(' px', 'togaq'); ?><br />
	<span style="font-size:0.8em;"><?php _e( '*original image height is 100px, so if you change, there is a possibility that the image gets rough.','togaq'); ?></span>
</p>

<hr />

	<h3><?php _e( 'Blog Title','togaq'); ?></h3>
<p>
	<input id="togaq_options_use_text_title" name="togaq_options_use_text_title" type="radio" value="1" <?php if( $togaq_options_use_text_title == 1 ) echo 'checked';?>/><?php _e('Use default text title and description','togaq'); ?><br />
	<input id="togaq_options_use_text_title" name="togaq_options_use_text_title" type="radio" value="0" <?php if( $togaq_options_use_text_title == 0 ) echo 'checked';?>/><?php _e('Or use image title (advanced)','togaq'); ?><br />
	<input id="togaq_options_use_text_title" name="togaq_options_use_text_title" type="radio" value="2" <?php if( $togaq_options_use_text_title == 2 ) echo 'checked';?>/><?php _e('Or use both text title over image title (advanced)','togaq'); ?><br />
	<span style="font-size:0.8em;"><?php _e( '*if you use image title, you must specify below where your image title is.','togaq'); ?></span><br /><br />


	<?php _e('Title image:','togaq'); ?> <input style="width: 300px;" id="togaq_options_path_title" name="togaq_options_path_title" type="text" value="<?php echo $togaq_options_path_title; ?>" /><br />
	<span style="font-size:0.8em;"><?php _e( '*enter pathname from theme folder, if your theme folder is "sample", this should be looks like: "sample/images/titleimage.png".','togaq'); ?></span>
</p>

	<h3><?php _e( 'Documents','togaq'); ?></h3>
<p>
	<?php _e( 'Plugin page: ','togaq'); ?><br />
	&nbsp;&bull;<a href="http://www.vjcatkick.com/?page_id=5930" target="_blank" >http://www.vjcatkick.com/?page_id=5930</a><br /><br />
	<?php _e( 'Toga-Q Documentation: (in Japanese)','togaq'); ?><br />
	&nbsp;&bull;<a href="http://t5blog.typepad.jp/TogaQ/" target="_blank" >http://t5blog.typepad.jp/TogaQ/</a><br />
</p>



	<p class="submit"><input type="submit" name="togaq_options_submit" value="<?php _e('Update options &raquo;','togaq'); ?>" /></p>
</form>
</div>
</div> <!-- wrap -->
<?php
} /* togaq_conf() */


// function togaq_put_headerimage( $tq_offset_top, $tq_offset_left, $tq_height, $tq_header_img_path_str, $use_default_title ) {


if ( !function_exists('_tq_get_script_file') ) :
function _tq_get_script_file( $isOriginal ) {
	if( $isOriginal === true ) {
		$tq_script_src = "http://www.remus.dti.ne.jp/~sugiyama/headpic/getheadbanner.js";
	}else if( strlen( $isOriginal ) > 0 ) {
		$tq_script_src = $isOriginal;
	}else{
		return( false );
	} /* if else if else */

	$filedata = false;
	$filedata = @file_get_contents( $tq_script_src );

	return( $filedata );
} /* _tq_get_script_file() */
endif;

if ( !function_exists('_tq_get_data_array') ) :
function _tq_get_data_array( $filedata ) {
		$spos = strpos( $filedata, 'Temporary Out Space' );		// change if need
		$filedata = substr( $filedata, 0, $spos );
		$filedata = substr( $filedata, strlen('var domArry = new Array(0);') );		// strip 1st line
		$filedata = ereg_replace( "domArry\[(.|..|...)\] = new Array\(", "" , $filedata );

		$safebelt = 0;
		$tq_data = array();
		$mykeys = array( 'src','num','sitename','siteurl' );
			while( ($spos = strpos( $filedata, ');' ) ) !== false ) {
			$tq_data[] = array_combine( $mykeys, explode( ",", substr( $filedata, 0, $spos ) ));
			$filedata = substr( $filedata, $spos+2 );
			if( ++$safebelt >= 150 ) break;		// just make sure
		} /* whilte */

		$site_posibilities = array();
		foreach( $tq_data as $t ) {
			$isFound = false;
			foreach( $site_posibilities as $s ) {
				if( strcmp( $s['src'], $t['src'] ) == 0 ) {
					$isFound = true;
					break;
				} /* if */
			} /* foreach */
			if( $isFound == false ) {
				$site_posibilities[] = $t;
			} /* if */
		} /* foreach */
//		$tq_data = $site_posibilities;

		return( array( $tq_data, $site_posibilities ) );
} /* _tq_get_data_array() */
endif;

if ( !function_exists('togaq_get_image_fullpath') ) :
function togaq_get_image_fullpath() {
	function _tq_get_image_fullpath() {
			// 0.1.1
		function tq_get_random( $startpos, $endpos, $oldnum ) {
			$safebelt = 0;
			do {
				$tmpv = floor( rand( $startpos, $endpos ) );
				if( ++$safebelt >= 10 ) break;
			} while( $tmpv == $oldnum );

			return( $tmpv );
		} /* tq_get_random() */

		$filedata = _tq_get_script_file( true );
		if( $filedata ) {
			$tq_datas = _tq_get_data_array( $filedata );
			$tq_data = $tq_datas[1];

			$options  = get_option('togaq_options');
			$togaq_options_site_timing = (int)$options['togaq_options_site_timing'];	// 0:each, 1:daily
			$togaq_options_site_order = (int)$options['togaq_options_site_order'];	// 0:random 1: order
			$togaq_options_img_order = (int)$options['togaq_options_img_order'];	// 0:random 1: order

			$togaq_options_lastimg_site = (int)$options['togaq_options_lastimg_site'];
			$togaq_options_lastimg_img = (int)$options['togaq_options_lastimg_img'];
			$togaq_options_lastimg_date = (int)$options['togaq_options_lastimg_date'];

			$renew_site = true;
			if( $togaq_options_site_timing >= 1 ) {
				$l_date = getdate( $togaq_options_lastimg_date );		// $l_date['mday'];
				$c_date = getdate();	//
				if( $togaq_options_site_timing == 1 ) {
					if( $l_date['mday'] == $c_date['mday'] ) {
						$renew_site = false;
						if( $togaq_options_lastimg_img === false ) $renew_site = true;		// $togaq_options_lastimg_img == false == error
					} /* if */
				}else if( $togaq_options_site_timing == 2 ) {			// 0.1.1 hourly change added
					if( $l_date['hours'] == $c_date['hours'] ) {
						$renew_site = false;
						if( $togaq_options_lastimg_img === false ) $renew_site = true;		// $togaq_options_lastimg_img == false == error
					} /* if */
				} /* else if */
			} /* if */

			// 0.1.1
			@include "togaq_options.php" ;

			if( $renew_site ) {
				if( $togaq_options_site_order == 1 ) {
					$sitenumber = $togaq_options_lastimg_site + 1;
					if( $sitenumber >= count( $tq_data ) ) $sitenumber = 0;
				}else{
					$sitenumber = tq_get_random( 0, count( $tq_data ) - 1, $sitenumber );
//					$sitenumber = floor( rand( 0, count( $tq_data ) - 1 ) );		// site random
				} /* if else */
			}else{
				$sitenumber = $togaq_options_lastimg_site;
			} /* if else */

			$sitemax = $tq_data[ $sitenumber ]['num'];

			if( $togaq_options_img_order == 1 ) {
				$imagenumber = $togaq_options_lastimg_img + 1;
				if( $imagenumber >= $sitemax ) $imagenumber = 0;
			}else{
				$imagenumber = tq_get_random( 0, $sitemax - 1, $imagenumber );
//				$imagenumber = floor( rand( 0, $sitemax - 1 )  );		// image random
			} /* if else */

			$options['togaq_options_lastimg_site'] = $sitenumber;
			$options['togaq_options_lastimg_img'] = $imagenumber;
			// 0.1.1
			$options['togaq_options_lastimg_sitename'] = str_replace( '"', '', $tq_data[ $sitenumber ]['sitename'] );
			$options['togaq_options_lastimg_siteurl'] = str_replace( '"', '', $tq_data[ $sitenumber ]['siteurl'] );
			//
			$options['togaq_options_lastimg_date'] = time();
			update_option('togaq_options', $options);

			if( $imagenumber < 10 ) { $imagenumber = '00' . $imagenumber; }
			else if( $imagenumber < 100 ) { $imagenumber = '0' . $imagenumber; }
			$filename = $imagenumber . '.jpg';

			$filefullpath = str_replace( '"', "", $tq_data[ $sitenumber ]['src'] ) . $filename;
			$filefullpath = str_replace( array( "\r\n", "\r", "\n" ), '', $filefullpath );

			return( $filefullpath );
		}else{
			// read error logic here if needed
			return( $filedata );
		} /* if else */
	} /* _tq_get_image_fullpath() */
	
	$safebelt = 0;
	$retv = false;
//	do {	// removed 0.1.3
//		if( ++$safebelt > 5 ) { break; }
		$retv = _tq_get_image_fullpath();

		$fHandle = @fopen( $retv, "rb" );
		if( $fHandle == false ) {
			$retv = false;
		}else{
			fclose( $fHandle );
		} /* if else */

//	} while( $retv == false );	// removed 0.1.3

	if( $retv == false ) {
		$retv = "http://my.reset.jp/~gds/nat/headpics/000.jpg";		//"http://www.vjcatkick.com/headpic/error.jpg";
		$options  = get_option('togaq_options');
		$options['togaq_options_lastimg_img'] = false;
		update_option('togaq_options', $options);
	} /* if */

	return( $retv );
} /* togaq_get_image_fullpath() */
endif;

if ( !function_exists('togaq_get_blank_gif_path') ) :
function togaq_get_blank_gif_path() {
	return get_option('siteurl') . '/wp-content/plugins/togaq/blank.gif';
} /* togaq_get_blank_gif_path */
endif;

	$options = get_option('togaq_options');


if ( !function_exists('togaq_put_headerimage') ) :
function togaq_put_headerimage() {
	$options = get_option('togaq_options');
	$togaq_options_off_top = (int)$options['togaq_options_off_top'];
	$togaq_options_off_left = (int)$options['togaq_options_off_left'];
	$togaq_options_height = (int)$options['togaq_options_height'];
	$togaq_options_path_title = $options['togaq_options_path_title'];
	$togaq_options_use_text_title = (int)$options['togaq_options_use_text_title'];
	$togaq_options_text_titlebox_style = '';

	$tq_offset_top = $togaq_options_off_top;
	$tq_offset_left = $togaq_options_off_left;
	$tq_height = $togaq_options_height;
	$tq_header_img_path_str = $togaq_options_path_title;

	$_theme_path = get_bloginfo( 'template_directory' );
	$_theme_path = substr( $_theme_path, 0, strrpos( $_theme_path , 'default' ) );
	$tq_header_img_path_str = $_theme_path . $tq_header_img_path_str;

	$output = '';
	$output .= '<div id="headerimg" style="position:relative; overflow:hidden;" >';
	$output .= '<img src="' . togaq_get_image_fullpath() . '" height="' . $tq_height . 'px" style="position:absolute; top:' . $tq_offset_top . 'px; left:' . $tq_offset_left . 'px;" />';
	if( $togaq_options_use_text_title == 0 || $togaq_options_use_text_title == 2 ) {
		$output .= '<script type="text/javascript">';
		$output .= 'var blankgif = "' . togaq_get_blank_gif_path() . '";';
		$output .= 'var headtitle = "' . $tq_header_img_path_str . '";';
		$output .= 'var d = document.createElement( \'div\' );';
		$output .= 'if(d && d.runtimeStyle && blankgif) {';
		$output .= 'document.write(	\'<img src=\' + blankgif + \' border="0" hspace="0" width="1" height="' . $tq_height . '" style="filter:progid:DXImageTransform.Microsoft.AlphaImageLoader( src=\' + headtitle + \',sizingmethod=image); position:absolute; top:0px; left:0px;" />\');';
		$output .= '}else{';
		$output .= 'document.write(\'<img src=\' + headtitle + \' border="0" hspace="0" style="position:absolute; top:0px; left:0px;" />\');}';
		$output .= '</script>';
	} /* if */
	if( $togaq_options_use_text_title == 1 || $togaq_options_use_text_title  == 2 ) {
		$output .= '<div class="tq_text_titlebox" style="position:absolute; top:0px; left:0px; width:100%;' . $togaq_options_text_titlebox_style . '" >';
		$output .= '<h1><a href="' . get_option('home') . '">' . get_bloginfo( 'name' ) . '</a></h1>';		// bloginfo('name')
		$output .= '<div class="description">' . get_bloginfo( 'description' ) . '</div>';	 // bloginfo('description')
		$output .= '</div>';
	} /* if */

	$output .= '<a href="' . get_option('home') . '">';
	$output .= '<img src="' . togaq_get_blank_gif_path() . '" border="0" style="position:absolute; top:0px; left:0px; width: 100%; height:100%;"/>';
	$output .= '</a>';

	$output .= '</div>';

	echo( $output );
} /* togaq_put_headerimage() */
endif;







function widget_togaq_init() {
	if ( !function_exists('register_sidebar_widget') )
		return;

	function widget_togaq( $args ) {
		extract($args);

		$w_options = get_option('widget_togaq');
		$title = $w_options['widget_togaq_title'];

		$options = get_option('togaq_options');
		$togaq_options_site_timing = (int)$options['togaq_options_site_timing'];	// 0:each, 1:daily
		$togaq_options_lastimg_site = $options['togaq_options_lastimg_site'];
		$togaq_options_lastimg_sitename = $options['togaq_options_lastimg_sitename'];
		$togaq_options_lastimg_siteurl = $options['togaq_options_lastimg_siteurl'];

		$output = '<div id="widget_togaq"><ul>';

		// section main logic from here 

		$filedata = _tq_get_script_file( true );
		if( $filedata ) $tq_datas = _tq_get_data_array( $filedata );
		$tq_data = $tq_datas[1];

		$baseurl = get_option('siteurl') . '/wp-content/plugins/togaq/';
		$bannerfname = $baseurl . 'togaQ192.gif';
		$output .= '<div id="togaq_infobox" style="width:100%;" >';
		$output .= '<a href="http://t5blog.typepad.jp/TogaQ/" target="_blank" >';
		$output .= '<img src="' . $bannerfname . '" style="width:100%;" border="0" />';
		$output .= '</a>';

		if( $tq_data ) {
			$output .= '<div style="margin-top: 1em; text-align:center; " >';
			$output .= $togaq_options_site_timing ? __('Today\'s ','togaq') : __('Current ','togaq');
			$output .= __('Toga-Q creator is','togaq') . '</div>';
			$output .= '<div style="text-align:center; font-size: 1.2em; font-weight:bold;" >';
			// 0.1.1
			$tq_sitename = $togaq_options_lastimg_sitename;
			$tq_siteurl = $togaq_options_lastimg_siteurl;
			//
			$output .= '<a href="' . $tq_siteurl . '" target="_blank">' . $tq_sitename .  '</a>';
			$output .= '</div>';

			$output .= '<div style="margin-top:2px; border:1px solid #DDD; padding: 2px; text-align:right;" >';
			$output .= '<div style="text-align:center; margin-bottom: 2px;" >' . __('- Current Status -','togaq') . '</div>';
			$output .= '<span style="color: red; font-weight:bold; font-size:1.2em;" >' . count( $tq_datas[0] ) . '</span>' . __(' creators','togaq') . '<br />';
			foreach( $tq_datas[0] as $t ) { $tq_total = $tq_total + $t[ 'num' ]; }
			$output .= __('Total ','togaq') . '<span style="color: red; font-weight:bold; font-size:1.2em;" >' . $tq_total . '</span>' . __(' images','togaq') . '<br />';
			$output .= '</div>';

		} /* if */


		$output .= '</div>';		// togaq_infobox
		// These lines generate the output
		$output .= '</ul></div>';

		echo $before_widget . $before_title . $title . $after_title;
		echo $output;
		echo $after_widget;
	} /* widget_togaq() */

	function widget_togaq_control() {
		$options = $newoptions = get_option('widget_togaq');
		if ( $_POST["widget_togaq_submit"] ) {
			$newoptions['widget_togaq_title'] = strip_tags(stripslashes($_POST["widget_togaq_title"]));
		}
		if ( $options != $newoptions ) {
			$options = $newoptions;
			update_option('widget_togaq', $options);
		}

		$title = htmlspecialchars($options['widget_togaq_title'], ENT_QUOTES);
?>

	    <?php _e('Title:'); ?> <input style="width: 170px;" id="widget_togaq_title" name="widget_togaq_title" type="text" value="<?php echo $title; ?>" /><br />

  	    <input type="hidden" id="template_src_submit" name="widget_togaq_submit" value="1" />

<?php
	} /* widget_togaq_control() */

	register_sidebar_widget('Toga-Q', 'widget_togaq');
	register_widget_control('Toga-Q', 'widget_togaq_control' );
} /* widget_togaq_init() */
add_action('plugins_loaded', 'widget_togaq_init');

?>