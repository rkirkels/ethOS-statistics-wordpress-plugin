<?php
/**
 * Created by PhpStorm.
 * User: rutgerkirkels
 * Date: 29-07-17
 * Time: 09:55
 */

function ethos_stats_settings() {
    ?>
    <div class="wrap"><h1>ethOS Statistics</h1></div>
    <form method="post" action="options.php">
        <?php
        settings_fields( 'ethos-stats-settings' );

        ?>
        <?php do_settings_sections( 'ethos-stats-settings' ); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Panel ID:</th>
                <td><input type="text" name="extra_post_info" value="<?php echo get_option( 'panelid' ); ?>"/>.ethosdistro.com</td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>
    <?php
}
?>
