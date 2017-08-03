<?php

/**
 * Created by PhpStorm.
 * User: rutgerkirkels
 * Date: 01-08-17
 * Time: 20:04
 */
class EthosStatsGpu
{
    protected $id;
    protected $hash;
    protected $power;
    protected $rpm;
    protected $temp;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param mixed $hash
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
    }

    /**
     * @return mixed
     */
    public function getPower()
    {
        return $this->power;
    }

    /**
     * @param mixed $power
     */
    public function setPower($power)
    {
        $this->power = $power;
    }

    /**
     * @return mixed
     */
    public function getRpm()
    {
        return $this->rpm;
    }

    /**
     * @param mixed $rpm
     */
    public function setRpm($rpm)
    {
        $this->rpm = $rpm;
    }

    /**
     * @return mixed
     */
    public function getTemp()
    {
        return $this->temp;
    }

    /**
     * @param mixed $temp
     */
    public function setTemp($temp)
    {
        $this->temp = $temp;
    }


}