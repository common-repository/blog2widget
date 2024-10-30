<?php
/*
Plugin Name: Blog 2 Widget
Plugin URI: http://www.smartlogix.co.in/
Description: The New way of sharing. Let your visitors take your site with them!. Advanced Recent Post Widget.
Version: 2.0
Author: Namith Jawahar
Author URI: http://www.smartlogix.co.in/
The New way of sharing. Let your visitors take your site with them!. Contains an Advanced Recent Post Widget. Controllable excerpt length, Custom Title, Templates; Usable on any website or blog.
*/

/*  Copyright 2011  NAMITH JAWAHAR  (website : http://www.smartlogix.co.in)

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
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
/*BEGIN WIDGET*/
if (isset($_GET["widget"])) {
	define('WP_USE_THEMES', false);
	require('../../../wp-blog-header.php');
	$options = get_option('blog2widget_options');
	echo "document.write(\"<div id='blog2widgetContainer'>\");";
		echo "document.write(\"<style type='text/css'>\");";
			echo "document.write(\"".str_replace(array("\r", "\n", '"'), array("", "", "'"), $options[blog2widget_custom_stylesheet])."\");";
		echo "document.write(\"</style>\");";
		echo "document.write(\"<div id='blog2widgetContainer'>\");";
		echo "document.write(\"<h2 id='blog2widgetTitle'>\");";
			echo "document.write(\"".str_replace('"', "'", $options[blog2widget_widget_title])."\");";
		echo "document.write(\"</h2>\");";
		echo "document.write(\"<ul id='blog2widgetContent' style='display:block;'>\");";
		$posts = get_posts('numberposts='.$options[blog2widget_posts_count].'&order=DESC');
		foreach($posts as $post) {
			echo "document.write(\"<li>\");";
				$excerpt = str_replace(array("\r", "\n", '"'), array("", "", "'"), substr(strip_tags($post->post_content, ''), 0, $options[blog2widget_excerpt_length]));
				$custom_template = str_replace(array("\r", "\n", '"'), array("", "", "'"), $options[blog2widget_custom_template]);
				preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
				$post_image = $matches[1][0];
				if(!$matches[1][0]) { $post_image = $options[blog2widget_default_post_image]; }

				$template_tags = array("%TITLE%", "%EXCERPT%", "%PERMALINK%", "%POSTIMAGE%", "%DATE%");
				$template_tag_values   = array($post->post_title, $excerpt, get_permalink($post->ID), $post_image, date('F j, Y', $post->post_date));
				echo "document.write(\"".str_replace($template_tags, $template_tag_values, $custom_template)."\");";
			echo "document.write(\"</li>\");";
		}
		echo "document.write(\"</ul>\");";
	echo "document.write(\"</div>\");";
} else {
/*END WIDGET*/

/*BEGIN ADMIN PAGE*/
add_action('admin_menu', 'blog2widget_add_menu');
function blog2widget_add_menu() {
	$page = add_options_page('Blog 2 Widget', 'Blog 2 Widget', 'manage_options', 'blog2widget', 'blog2widget_settings_page');
}

add_action('admin_init', 'blog2widget_admin_init');
function blog2widget_admin_init() {	
	register_setting('blog2widget_options', 'blog2widget_options', 'blog2widget_validate');
    add_settings_section('blog2widget_main', 'Settings for Foreign Websites', 'blog2widget_section_text', 'blog2widget');
	add_settings_field('blog2widget_widget_title', 'Widget Title', 'blog2widget_setting_widget_title', 'blog2widget', 'blog2widget_main');
	add_settings_field('blog2widget_posts_count', 'No of Posts', 'blog2widget_setting_posts_count', 'blog2widget', 'blog2widget_main');
	add_settings_field('blog2widget_excerpt_length', 'Excerpt Length', 'blog2widget_setting_excerpt_length', 'blog2widget', 'blog2widget_main');
	add_settings_field('blog2widget_custom_stylesheet', 'Custom Stylesheet', 'blog2widget_setting_custom_stylesheet', 'blog2widget', 'blog2widget_main');
	add_settings_field('blog2widget_custom_template', 'Custom Template', 'blog2widget_setting_custom_template', 'blog2widget', 'blog2widget_main');
	add_settings_field('blog2widget_default_post_image', 'Default Post Image', 'blog2widget_setting_default_post_image', 'blog2widget', 'blog2widget_main');
	add_settings_field('blog2widget_instruction', 'Embed Code', 'blog2widget_setting_instructions', 'blog2widget', 'blog2widget_main');
	
	add_settings_section('blog2widget_secondary', 'Settings for This Website', 'blog2widget_section_text', 'blog2widget-secondary');
	add_settings_field('blog2widget_replace_recentposts_widget', 'Advanced Recent Posts', 'blog2widget_setting_replace_recentposts_widget', 'blog2widget-secondary', 'blog2widget_secondary');
}

function blog2widget_settings_page() { ?>
    <div class="wrap">
		<h2>Blog 2 Widget : A New Way to Share!</h2>
		<?php blog2widget_show_support_options(); ?>
		<form method="post" action="options.php" name="blog2widget_form">
			<div id="poststuff" class="metabox-holder has-right-sidebar">
				<div id="side-info-column" class="inner-sidebar">
					<script type="text/javascript" src="http://www.wp-insert.smartlogix.co.in/wp-content/plugins/wp-adnetwork/wp-adnetwork.php?showad=1"></script>
					<p class="submit">
						<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" style="background: green !important; font-size: 26px !important; font-weight: bold; height: 60px; width: 80%;"/>
					</p>
					<p style="text-align: center; width: 85%">
						<b>A Wordpress Plugin By</b><br/><br/>
						<a href="http://www.smartlogix.co.in/"><img src="http://www.smartlogix.co.in/wp-content/themes/logix/images/logo.gif" width="100%"/></a>
					</p>
				</div>
				<div id="post-body" class="has-sidebar">				
					<div id="post-body-content" class="has-sidebar-content">
						<?php settings_fields('blog2widget_options'); ?>
						<?php do_settings_sections('blog2widget'); ?>
						<?php do_settings_sections('blog2widget-secondary'); ?>
					</div>
				</div>
				<br class="clear"/>			
			</div>	
		</form>
    </div>
<?php
}

function blog2widget_section_text() {
	return '';
}

function blog2widget_setting_widget_title() {
	$options = get_option('blog2widget_options');
	$value = 'Recent Posts';
	if($options[blog2widget_widget_title] != '') { $value = $options[blog2widget_widget_title]; }
	echo '<input type="text" id="blog2widget_widget_title" name="blog2widget_options[blog2widget_widget_title]" class="input" value="'.$value.'" style="width: 300px"/>';
}

function blog2widget_setting_posts_count() {
	$options = get_option('blog2widget_options');
	$value = '5';
	if($options[blog2widget_posts_count] != '') { $value = $options[blog2widget_posts_count]; }
	echo '<input type="text" id="blog2widget_posts_count" name="blog2widget_options[blog2widget_posts_count]" class="input" value="'.$value.'" style="width: 300px"/>';
}

function blog2widget_setting_excerpt_length() {
	$options = get_option('blog2widget_options');
	$value = '120';
	if($options[blog2widget_excerpt_length] != '') { $value = $options[blog2widget_excerpt_length]; }
	echo '<input type="text" id="blog2widget_excerpt_length" name="blog2widget_options[blog2widget_excerpt_length]" class="input" value="'.$value.'" style="width: 300px"/>';
}

function blog2widget_setting_custom_stylesheet() {
	$options = get_option('blog2widget_options');
	$value = '';
	if($options[blog2widget_custom_stylesheet] != '') { $value = $options[blog2widget_custom_stylesheet]; }
	echo '<textarea id="blog2widget_custom_stylesheet" name="blog2widget_options[blog2widget_custom_stylesheet]" class="input" style="width: 300px; height: 250px;">'.$value.'</textarea>';
}

function blog2widget_setting_custom_template() {
	$options = get_option('blog2widget_options');
	$value = '<a href="%PERMALINK%">%TITLE%</a><br/><p>%EXCERPT%</p>';
	if($options[blog2widget_custom_template] != '') { $value = $options[blog2widget_custom_template]; }
	echo '<textarea id="blog2widget_custom_template" name="blog2widget_options[blog2widget_custom_template]" class="input" style="width: 300px; height: 250px;">'.$value.'</textarea><br/><small>Accepted TAGS : <br/>%PERMALINK%, %TITLE%, %EXCERPT%, %POSTIMAGE%, %DATE%</small>';
}

function blog2widget_setting_replace_recentposts_widget() {
	$options = get_option('blog2widget_options');
	$value = '';
	if($options[blog2widget_replace_recentposts_widget]) { $value = ' checked="checked"'; }
	echo '<input type="checkbox" value="1" id="blog2widget_replace_recentposts_widget" name="blog2widget_options[blog2widget_replace_recentposts_widget]" class="input" '.$value.'/>';
}

function blog2widget_setting_default_post_image() {
	$options = get_option('blog2widget_options');
	$value = get_bloginfo('url').'/wp-content/plugins/blog2widget/images/blank.gif';
	if($options[blog2widget_default_post_image] != '') { $value = $options[blog2widget_default_post_image]; }
	echo '<input type="text" id="blog2widget_default_post_image" name="blog2widget_options[blog2widget_default_post_image]" class="input" value="'.$value.'" style="width: 300px"/>';
}

function blog2widget_setting_instructions() {
	echo '<textarea style="width: 300px; height: 75px; font-size: 10px;"><script src="'.get_bloginfo('url').'/wp-content/plugins/blog2widget/blog2widget.php?widget=1" type="text/javascript"></script></textarea><br/>';
	echo '<small>Copy the embed code above and paste it into your blog or<br/> webpage to show recent posts from this blog on your site.</small><br/><br/>';
}

function blog2widget_validate($input) {
	return $input;
}

function blog2widget_show_support_options() { ?>
<table class="form-table">
	<tr valign="bottom">
		<th scope="row">
			<small>
				<span style="color:#FF0000;"><b>Donate a few Dollars</b></span><br/>
				<span style="color:#008E04;">Support our FREE Plugins</span><br/>
				You Might Also Like <a target="_blank" href="http://wordpress.org/extend/plugins/wp-insert/">WP-INSERT</a>
			</small>
		</th>
		<td width="100px">
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
				<input type="hidden" name="cmd" value="_s-xclick">
				<input type="hidden" name="hosted_button_id" value="7834514">
				<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
				<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
			</form>
		</td>
		<td>
			<b>Think we have done a great job?</b><br/><a target="_blank" href="http://wordpress.org/extend/plugins/blog2widget/">Rate the plugin</a> or <a target="_blank" href="http://www.smartlogix.co.in/">Leave Us a Comment</a><br/>
			Let us match your blog to your website : <a target="_blank" href="http://www.smartlogix.co.in/request-a-free-quote/">Request a Quote</a>
		</td>
	</tr>
</table>
<?php
}
/*END ADMIN PAGE*/

/*BEGIN REPLACING DEFAULT RECENT POSTS WIDGET AND ACTIVATING THE NEW ONE*/
add_action('widgets_init', 'blog2widget_widgets_init', 20);
function blog2widget_widgets_init() {
	$options = get_option('blog2widget_options');
	if($options[blog2widget_replace_recentposts_widget]) {
		unregister_widget('WP_Widget_Recent_Posts');
		register_widget("Blog2Widget");
	}
}

class Blog2Widget extends WP_Widget {
    function Blog2Widget() {
        parent::WP_Widget(false, $name = 'Advanced Recent Posts');	
    }

    function widget($args, $instance) {		
        extract($args);
        $title = apply_filters('widget_title', $instance['title']);
		$no_posts = esc_attr($instance['no_posts']);
        ?>
		<?php echo $before_widget; ?>
			<?php if($title) { echo $before_title . $title . $after_title; } ?>
			<ul>
				<?php wp_get_archives('type=postbypost&limit='.$no_posts); ?>
			</ul>
			<div class="blog2widget_embed">
				<textarea style="width: 100%; height: 50px; font-size: 10px;"><script src='<?php bloginfo('url'); ?>/wp-content/plugins/blog2widget/blog2widget.php?widget=1' type='text/javascript'></script></textarea><br/>
				<small>Copy the embed code above and paste it into your blog or webpage to show recent posts from this blog on your site.</small>
			</div>
			<?php echo $after_widget; ?>
        <?php
    }

    function update($new_instance, $old_instance) {				
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['no_posts'] = strip_tags($new_instance['no_posts']);
        return $instance;
    }

    function form($instance) {				
        $title = esc_attr($instance['title']);
		$no_posts = esc_attr($instance['no_posts']);
        ?>
        <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php if($title == '') {echo "Recent Posts"; } else { echo $title; } ?>" />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('no_posts'); ?>"><?php _e('Number of Posts:'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('no_posts'); ?>" name="<?php echo $this->get_field_name('no_posts'); ?>" type="text" value="<?php if($no_posts == '') {echo "5"; } else { echo $no_posts; } ?>" />
        </p>
        <?php 
    }

}
}
/*END REPLACING DEFAULT RECENT POSTS WIDGET AND ACTIVATING THE NEW ONE*/
?>