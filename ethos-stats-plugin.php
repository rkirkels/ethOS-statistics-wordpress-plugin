<?php
/**
 * Plugin Name: ethOS Statistics
 * Plugin URI: https://rutger.kirkels.nl
 * Author: Rutger Kirkels
 * Version: 1.0
 * Description: Plugin for displaying the stats of your ethOS mining rig.
 */


/**
 * Include all neccessary classes
 */
include_once __DIR__ . DIRECTORY_SEPARATOR . "lib" . DIRECTORY_SEPARATOR . "EthosStatsWidget.php";
include_once __DIR__ . DIRECTORY_SEPARATOR . "lib" . DIRECTORY_SEPARATOR . "EthosStatsPanel.php";
include_once __DIR__ . DIRECTORY_SEPARATOR . "lib" . DIRECTORY_SEPARATOR . "EthosStatsRig.php";
include_once __DIR__ . DIRECTORY_SEPARATOR . "lib" . DIRECTORY_SEPARATOR . "EthosStatsGpu.php";

/*
 * Add actions
 */
add_action('widgets_init', function() {
    register_widget('EthosStatsWidget');
});
add_action('admin_menu', 'ethos_stats_settings');
add_action('admin_init', 'ethos_stats_settings_init');
add_action('wp_enqueue_scripts', 'ethos_stats_plugin_styles');

/*
 * Load translations
 */
load_plugin_textdomain('ethosstats', false, dirname(plugin_basename(__FILE__)) . '/languages/');

function ethos_stats_settings() {
    add_options_page('ethOS Statistics', 'ethOS Statistics', 'manage_options', 'ethos-stats-settings', 'ethos_stats_settings_page');
    }


function ethos_stats_settings_page() {
?>
    <div class="wrap">
        <h1>ethOS Statistics <?php echo __('settings', 'ethosstats'); ?></h1>
        <form method="post" action="options.php">
            <?php settings_fields('ethos-stats-settings'); ?>
            <?php do_settings_sections('ethos_stats_settings_page');?>
            <?php submit_button(); ?>
        </form>
    </div>
<?php
}

function ethos_stats_settings_init() {
    add_settings_section('ethos_stats_main_section', ucfirst(__('default', 'ethosstats')) . ' ' . __('settings', 'ethosstats'), 'ethos_stats_settings_setup', 'ethos_stats_settings_page');

    add_settings_field('ethos-stats-panel-id', 'ethOS ' . __('panel','ethosstats') . ' ID:', 'ethos_stats_textbox', 'ethos_stats_settings_page', 'ethos_stats_main_section', ['name' => 'PanelId', 'maxlength' => 6]);
    register_setting('ethos-stats-settings','ethos_stats_options');
}

function ethos_stats_plugin_styles() {
    wp_enqueue_style('EthosStatsStyles', plugins_url('style.css', __FILE__));
    wp_enqueue_style('EthosStatsStyles-FontAwesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
}

function ethos_stats_plugin_scripts() {
//    wp_enqueue_script('EthosStatsScripts', );
}

function ethos_stats_settings_setup() {
    echo __('These default settings are used to connect to your panel', 'ethosstats') . '.';
}

function ethos_stats_textbox($args) {
    extract($args);
    $optionArray = (array)get_option('ethos_stats_options');
    $current_value = $optionArray[$name];
    echo '<input type="text" name="ethos_stats_options[' . $name . ']" value="' . $current_value . '"';
    echo (isset($maxlength)) ? ' maxlength="' . $maxlength . '"' : '' ;
    echo '/>';
}
?>