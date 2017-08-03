<?php

/**
 * Class EthosStatsPanel
 * Connects to the ethOS panel API and retrieves all available data. If cache time is set, the data is cached for the
 * set cache time.
 *
 * @author Rutger Kirkels <rutger@kirkels.nl>
 */
class EthosStatsPanel
{
    private $panelId = null;
    private $rigs = [];
    private $totalHash = 0;
    protected $rigsActive = 0;
    protected $cacheTime = null;

    public function __construct($panelId = null, $cacheTime = null) {
        $this->cacheTime = intval($cacheTime);
        $this->panelId = $panelId;
        $this->getPanelData();
    }

    /**
     * Retrieves the panel data and stores it in the cache.
     */
    protected function getPanelData() {
        if (get_transient( 'ethos_stats' ) === false) {
            set_transient( 'ethos_stats', $this->retrievePanelData(), $this->cacheTime );
        }
        $panelData = get_transient( 'ethos_stats' );
        $this->totalHash = $panelData->total_hash;
        foreach(get_object_vars($panelData->rigs) as $name => $rigData) {
            $newRig = new EthosStatsRig($name, $rigData);
            $this->rigs[] = $newRig;

        }
    }

    /**
     * @return int Number of active rigs
     */
    public function getActiveRigsCount() {
        $this->rigsActive = 0;
        if (count($this->rigs > 0)) {
            foreach ($this->rigs as $rig) {
                if ($rig->getCondition() == 'mining' || $rig->getCondition() == 'throttle') {
                    $this->rigsActive += 1;
                }
            }
            return $this->rigsActive;
        }
        return $this->rigsActive;

    }

    public function getRigs() {
        return $this->rigs;
    }

    protected function retrievePanelData() {
        // Set the timeout
        $ctx = stream_context_create(array('http'=>
            [
                'timeout' => 5,
            ]
        ));
        $panelData = file_get_contents('http://' . $this->panelId . '.ethosdistro.com/?json=yes', false, $ctx);

        // If the data couldn't be retrieved or the data is empty, FALSE is returned.
        if (!$panelData || $panelData === '') {
            return false;
        }

        // Check if the panel data is JSON. If not, FALSE is returned.
        if (!json_decode($panelData)) {
            return false;
        }
        return json_decode($panelData);
    }
    /**
     * @return float
     */
    public function getTotalHash(): float
    {
        return $this->totalHash;
    }


}