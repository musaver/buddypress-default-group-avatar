<?php
/**
 * Plugin Name: BP Group Default Avatar
 * Plugin URI: http://vfowler.com/2012/02/buddypress-default-group-avatar/
 * Description: Adds a default group avatar to BuddyPress.
 * Version: 0.1.1
 * Author: Vernon Fowler
 * Author URI: http://buddypress.org/community/members/vernonfowler/
 */

add_action('admin_menu', 'BPDGA_plugin_menu');
add_action('admin_init', 'BPDGA_admin_settings');

/**
 * Adds an option submenu page under the BuddyPress menu
 */
function BPDGA_plugin_menu()
{
    //add_options_page('BuddyPress Group Avatar Settings Panel', 'BP Group Avatar', 9, 'buddypress-group-avatar', 'BPDGA_plugin_options');
    add_submenu_page('bp-general-settings', __('BP Group Default Avatar', 'buddypress-group-avatar'), __('BP Group Default Avatar', 'buddypress-group-avatar'), 'manage_options', 'group_avatar', 'BPDGA_plugin_options' );
}

/**
 * Display the main option's area
 * 
 * @global <type> $wpdb
 */

function BPDGA_plugin_options()
{
    global $wpdb;
?>
<div class="wrap">
    <h2>BuddyPress Group Default Avatar Settings</h2>
    <form method="post" action="options.php">
        <?php wp_nonce_field('update-options'); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Avatar URL</th>
                <td>
                    <input type="url" size="110" name="BPDGA_img_url" value="<?php echo get_option('BPDGA_img_url'); ?>" placeholder="<?php bloginfo('stylesheet_directory');?>/_inc/images/" />
                </td>
            </tr>
        </table>
        <input type="hidden" name="action" value="update" />
        <input type="hidden" name="page_options" value="BPDGA_img_url" />
        <p class="submit">
            <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
        </p>
    </form>
<?php
echo 'Your image preview:<br><img src="' . get_option('BPDGA_img_url') .'" />';
}

/**
 * Register the option's variable
 */
function BPDGA_admin_settings()
{
    register_setting('BPDGA_admin_options', 'BPDGA_img_url');
}

/**
 * The main function that changes to the BuddyPress
 */
function BPDGA_default_get_group_avatar($avatar) {
	global $bp, $groups_template;
	if( strpos($avatar,'group-avatars') ) {
		return $avatar;
	}
	else {
		$custom_avatar = get_option('BPDGA_img_url');
		if($bp->current_action == "" || $bp->current_action == "my-groups" || $bp->current_action == "invites" ) {
			if ( $bp->current_component == BP_FORUMS_SLUG ) {
				$width = "20";
				$height = "20";
			}
			else {
				$width = BP_AVATAR_THUMB_WIDTH;
				$height = BP_AVATAR_THUMB_HEIGHT;
			}
			return '<img width="'.$width.'" height="'.$height.'" src="'.$custom_avatar.'" class="avatar" alt="" />';
		}
		else
			return '<img width="'.BP_AVATAR_FULL_WIDTH.'" src="'.$custom_avatar.'" class="avatar" alt="' . attribute_escape( $groups_template->group->name ) . '" />';
	}

}

//add_filter( 'bp_get_group_avatar', 'BPDGA_default_get_group_avatar');
//add_filter( 'bp_get_the_topic_object_avatar', 'BPDGA_default_get_group_avatar');
//add_filter( 'bp_get_new_group_avatar', 'BPDGA_default_get_group_avatar');
add_filter( 'bp_group_gravatar_default', 'group_default');
function group_default($avatar)
{
	return get_option('BPDGA_img_url');
}