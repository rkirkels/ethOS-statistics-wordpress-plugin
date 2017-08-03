<?php
/**
 * Class EthosStatsRig
 * Holds all the mining rig data.
 */
class EthosStatsRig
{
    protected $name = null;
    protected $condition = null;
    protected $hash = 0.0;
    protected $totalGpus = 0;
    protected $activeGpus = 0;
    protected $gpus = [];
    protected $uptime = null;
    protected $miningTime = null;

    public function __construct($name = 'No name', $rigData) {
        $this->name = $name;
        $this->setCondition($rigData->condition);
        $this->setHash($rigData->hash);
        $this->setTotalGpus($rigData->gpus);
        $this->setActiveGpus($rigData->miner_instance);
        $this->setGpuData($rigData);
        $this->setUptime($rigData->uptime);
        $this->setMiningTime($rigData->miner_secs);
    }

    /**
     * @return null
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * @return null
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * @param null $condition
     */
    public function setCondition($condition)
    {
        $this->condition = $condition;
    }

    /**
     * @return int
     */
    public function getHash(): float
    {
        return $this->hash;
    }

    /**
     * @param int $hash
     */
    public function setHash(float $hash)
    {
        $this->hash = $hash;
    }

    /**
     * @return int
     */
    public function getTotalGpus(): int
    {
        return $this->totalGpus;
    }

    /**
     * @param int $totalGpus
     */
    public function setTotalGpus(int $totalGpus)
    {
        $this->totalGpus = $totalGpus;
    }

    /**
     * @return int
     */
    public function getActiveGpus(): int
    {
        return $this->activeGpus;
    }

    /**
     * @param int $activeGpus
     */
    public function setActiveGpus(int $activeGpus)
    {
        $this->activeGpus = $activeGpus;
    }

    /**
     * @return array
     */
    public function getGpus(): array
    {
        return $this->gpus;
    }

    protected function setGpuData($rigData) {
        $tempValues = [];
        $tempValuesRaw = explode(' ', $rigData->temp);
        foreach ($tempValuesRaw as $value) {
            $tempValues[] = floatval($value);
        }

        $hashValues = [];
        $hashValuesRaw = explode(' ',$rigData->miner_hashes);
        foreach ($hashValuesRaw as $value) {
            $hashValues[] = floatval($value);
        }

        $rpmValues = [];
        $rpmValuesRaw = explode(' ', $rigData->fanrpm);
        foreach ($rpmValuesRaw as $value) {
            $rpmValues[] = intval($value);
        }

        if (!empty($rigData->watts)) {
            $powerValues = [];
            $powerValuesRaw = explode(' ', $rigData->watts);
            foreach ($powerValuesRaw as $value) {
                $powerValues[] = $value;
            }
        }

        foreach ($hashValues as $gpuId => $values) {
            $gpu = new EthosStatsGpu();
            $gpu->setId($gpuId);
            $gpu->setHash($hashValues[$gpuId]);
            $gpu->setPower($powerValues[$gpuId]);
            $gpu->setRpm($rpmValues[$gpuId]);
            $gpu->setTemp($tempValues[$gpuId]);
            $this->gpus[] = $gpu;
        }
    }

    protected function setUptime($uptime) {
        $this->uptime = intval($uptime);
    }

    /**
     * @return null
     */
    public function getUptime()
    {
        $now = new DateTime();
        $bootTime = new DateTime();
        $bootTime->setTimestamp(time() - $this->uptime);
        $returnData = date_diff($bootTime, $now);
        return $returnData;
    }

    /**
     * @return null
     */
    public function getMiningTime()
    {
        return $this->miningTime;
    }

    /**
     * @param int $miningTime
     */
    protected function setMiningTime($miningTime)
    {
        $miningStart = new DateTime();
        $miningStart->setTimestamp(time() - $miningTime);
        $this->miningTime = date_diff(new DateTime(), $miningStart);
    }


}