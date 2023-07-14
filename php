<?php

class GifAvatars extends WP_Plugin {

  public function __construct() {
    parent::__construct();
    // Load the plugin textdomain.
    load_plugin_textdomain('gif-avatars', false, dirname(__FILE__) . '/languages');
    // Add the plugin actions and filters.
    add_action('admin_init', array($this, 'admin_init'));
    add_filter('get_avatar', array($this, 'get_avatar'), 10, 5);
  }

  public function admin_init() {
    // Add a new setting to the avatar section.
    add_settings_field('gif_avatars_enabled',
      __('Enable GIF Avatars', 'gif-avatars'),
      array($this, 'settings_field_gif_avatars_enabled'),
      'avatar',
      'avatar_options'
    );
  }

  public function settings_field_gif_avatars_enabled() {
    // Get the current setting value.
    $enabled = get_option('gif_avatars_enabled');
    // Output the settings field.
    ?>
    <input type="checkbox" name="gif_avatars_enabled" id="gif_avatars_enabled" value="1" <?php checked($enabled, 1); ?> />
    <label for="gif_avatars_enabled"><?php _e('Enable GIF Avatars', 'gif-avatars'); ?></label>
    <?php
  }

  public function get_avatar($avatar, $id_or_email, $size, $default, $alt) {
    // Only get GIF avatars if the setting is enabled.
    if (!get_option('gif_avatars_enabled')) {
      return $avatar;
    }
    // Get the avatar URL.
    $avatar_url = get_avatar_url($id_or_email, $size, $default, $alt);
    // Check if the avatar URL is a GIF.
    if (strpos($avatar_url, '.gif') !== false) {
      // Return the GIF avatar.
      return $avatar_url;
    } else {
      // Return the default avatar.
      return $default;
    }
  }
}

// Register the plugin.
function register_gif_avatars() {
  new GifAvatars();
}

// Add the plugin to the WordPress hook.
add_action('plugins_loaded', 'register_gif_avatars');
