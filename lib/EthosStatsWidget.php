<?php

/**
 * Class EthosStatsWidget
 * Generates a plugin for Wordpress that displayes the statistics of an EthOS mining farm. The total hashrate is
 * displayed with statistics per mining rig.
 *
 * @author Rutger Kirkels <rutger@kirkels.nl>
 */
class EthosStatsWidget extends WP_Widget {

    public function __construct()
    {
        $widgetOptions = [
            'description' => __('Display statistics of your ethOS mining rigs', 'ethosstats')
        ];
        parent::__construct('EthosStatsWidget', $name = __('EthOS Statistics', 'ethosstats'), $widgetOptions);
    }

    /**
     * @param $args
     * @param $instance
     */
    public function widget($args, $instance) {
        echo '<script src="https://use.fontawesome.com/d94b032d9e.js"></script>';
        echo '<h1 class="widget-title">' . $instance['title'] . '</h1>';
        echo '<div class="ethos-stats-widget-container">';

        // Get the panel data
        $panelData = new EthosStatsPanel($instance['panelId'], $instance['cacheTime']);

        if ($panelData->getActiveRigsCount() === 0) {
            echo '<div class="totals not-mining">' . __('There are currently no rigs mining', 'ethosstats') .'</div>';
        }
        else {
            if ($instance['showPanelTotals']) {
                if ($panelData->getActiveRigsCount() > 1) {
                    $rigs = __('rigs', 'ethosstats');
                }
                else {
                    $rigs = __('rig', 'ethosstats');
                }
                echo '<div class="totals">' . __('Total hashrate', 'ethosstats')  . '<h1>' . number_format_i18n( $panelData->getTotalHash(), 2) . ' MH/s</h1>' . __('Produced by', 'ethosstats') . ' ' . $panelData->getActiveRigsCount() . ' ' . $rigs . '</div>';
            }

        }

        foreach ($panelData->getRigs() as $rig) {
            if ($rig->getCondition() == 'mining' || $rig->getCondition() == 'throttle') {
                ?>
                <div class="ethos-stats-widget-data">
                    <div id="miner-<?php echo $rig->getName(); ?>" class="rig">
                        <?php echo ucfirst(__('rig', 'ethosstats')) . ' ID: <b>' . $rig->getName(); ?></b>
                        <table class="ethos-stats-rig-data">

                            <tr>
                                <th style="text-align: center">
                                    <?php echo ucfirst(__('hashrate', 'ethosstats')); ?>
                                </th>
                                <th style="text-align: center">
                                    <?php echo ucfirst(__('miners', 'ethosstats')); ?>
                                </th>
                            </tr>
                            <tr>
                                <td style="text-align: center">
                                    <span class="hashrate"><?php echo number_format_i18n($rig->getHash(),2); ?> MH/s</span>
                                </td>
                                <td style="text-align: center">
                                    <span class='minercount'><?php echo $rig->getActiveGpus(); ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">&nbsp;</td>
                            </tr>
                            <tr>
                                <th style="text-align: center">
                                    <?php echo ucfirst(__('uptime', 'ethosstats')); ?>
                                </th>
                                <th style="text-align: center">
                                    <?php echo ucfirst(__('mine time', 'ethosstats')); ?>
                                </th>
                            </tr>
                            <tr>
                                <td style="text-align: center">
                                    <?php
                                    $uptime = [];

                                    if ($rig->getUptime()->d > 0) {
                                        $uptimePart = $rig->getUptime()->d . ' ';
                                        $uptimePart .= ($rig->getUptime()->d > 1) ? __('days', 'ethosstats') : __('day', 'ethosstats');
                                        $uptime[] = $uptimePart;
                                    }

                                    if ($rig->getUptime()->h > 0) {
                                        $uptimePart = $rig->getUptime()->h . ' ';
                                        $uptimePart .= ($rig->getUptime()->h > 1) ? __('hours', 'ethosstats') : __('hour', 'ethosstats');
                                        $uptime[] = $uptimePart;
                                    }

                                    if ($rig->getUptime()->i > 0) {
                                        $uptimePart = $rig->getUptime()->i . ' ';
                                        $uptimePart .= ($rig->getUptime()->i > 1) ? __('minutes', 'ethosstats') : __('minute', 'ethosstats');
                                        $uptime[] = $uptimePart;
                                    }

                                    echo implode('<br/>', $uptime);
                                    ?>
                                </td>
                                <td style="text-align: center">
                                    <?php
                                    $miningTime = [];

                                    if ($rig->getMiningTime()->d > 0) {
                                        $miningTimePart = $rig->getMiningTime()->d . ' ';
                                        $miningTimePart .= ($rig->getMiningTime()->d > 1) ? __('days', 'ethosstats') : __('day', 'ethosstats');
                                        $miningTime[] = $miningTimePart;
                                    }

                                    if ($rig->getMiningTime()->h > 0) {
                                        $miningTimePart = $rig->getMiningTime()->h . ' ';
                                        $miningTimePart .= ($rig->getMiningTime()->h > 1) ? __('hours', 'ethosstats') : __('hour', 'ethosstats');
                                        $miningTime[] = $miningTimePart;
                                    }

                                    if ($rig->getMiningTime()->i > 0) {
                                        $miningTimePart = $rig->getMiningTime()->i . ' ';
                                        $miningTimePart .= ($rig->getMiningTime()->i > 1) ? __('minutes', 'ethosstats') : __('minute', 'ethosstats');
                                        $miningTime[] = $miningTimePart;
                                    }

                                    echo implode('<br/>', $miningTime);
                                    ?>
                                </td>
                            </tr>
                        </table>

                        <table style="margin: 0">
                            <tr>
                                <td>
                                    <table class="ethos-stats-gpu-data">
                                        <?php foreach ($rig->getGpus() as $gpu) { ?>
                                            <tr>
                                                <td><i class="fa fa-microchip"
                                                       aria-hidden="true"></i> <?php echo $gpu->getId() + 1; ?></td>
                                                <td><i class="fa fa-calculator"
                                                       aria-hidden="true"></i> <?php echo number_format_i18n( $gpu->getHash(), 2 ); ?> MH/s
                                                </td>
                                                <td><i class="fa fa-thermometer-three-quarters" aria-hidden="true"></i>
                                                    <?php echo $gpu->getTemp(); ?>&deg;
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <?php
            }
        }
        echo '</div>';
    }

    /**
     * @param $instance
     */
    public function form($instance) {
        $panelId = '';
        if (get_option('ethos_stats_options')) {
            $options = get_option('ethos_stats_options');

            $panelId = $options['PanelId'];
        }
        if (isset($instance['panelId'])) {
            $panelId = $instance['panelId'];
        }

        $title = __('EthOS Statistics');
        if (isset($instance['title'])) {
            $title = $instance['title'];
        }

        $showPanelTotals = true;
        if (isset($instance['showPanelTotals'])) {
            $showPanelTotals = $instance['showPanelTotals'];
        }

        $cacheTime = 60;
        if (isset($instance['cacheTime'])) {
            $cacheTime = intval($instance['cacheTime']);
        }

        echo '<p></p><label for="">' . ucfirst(__('title', 'ethosstats')) . ':</label><br/>';
        echo '<input type="text" value="' . $title . '" name="' . $this->get_field_name('title') . '" id="' . $this->get_field_id('title') . '"></p>';
        echo '<p><label for="">' . ucfirst(__('panel', 'ethosstats')) . ' ID: </label><br/>';
        echo '<input type="text" value="' . $panelId . '" name="' . $this->get_field_name('panelId') . '" id="' . $this->get_field_id('panelId') . '" maxlength="6" size="6">.ethosdistro.com</p>';
        echo '<input type="checkbox" name="' . $this->get_field_name('showPanelTotals') . '" id="' . $this->get_field_id('showPanelTotals') . '"';
        echo  ($showPanelTotals === true) ? ' checked' : '';
        echo  '>';
        echo '<label for="">' . __('Show panel totals', 'ethosstats') . '</label><br/>';
        echo '<p><label for="">' . ucfirst(__('cache time', 'ethosstats')) . '</label><br/>';
        $cacheTimes = [
                1   => 'Disable caching',
                60  => '1 ' . __('minute', 'ethosstats'),
                300  => '5 ' . __('minutes', 'ethosstats'),
                900  => '15 ' . __('minutes', 'ethosstats')
        ];
        echo '<select name="' . $this->get_field_name('cacheTime') . '" id="' . $this->get_field_id('cacheTime') . '">"';
        foreach ($cacheTimes as $cacheTimeValue => $label) {
            echo '<option value="' . $cacheTimeValue . '"';
            echo ($cacheTimeValue === $cacheTime) ? ' selected' : '';
            echo '>' . $label . '</option>';
        }
        echo '</select>';

    }

    /**
     * @param $new_instance
     * @param $old_instance
     * @return mixed
     */
    public function update($new_instance, $old_instance) {
        $instance['panelId'] = $new_instance['panelId'];
        $instance['title'] = $new_instance['title'];

        if ($new_instance['showPanelTotals'] === 'on') {
            $instance['showPanelTotals'] = true;
        }
        else {
            $instance['showPanelTotals'] = false;
        }

        $instance['cacheTime'] = $new_instance['cacheTime'];
        return $instance;
    }
}
?>