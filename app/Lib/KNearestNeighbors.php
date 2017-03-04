<?php

namespace App\Lib;

/**
 * @author dungpv <dungpv@rikkeisoft.com>
 */
class KNearestNeighbors
{
    public $dataTrain;
    public $label;
    public $k;

    function __construct($dataTrain, $label, $k = 3)
    {
        $this->dataTrain = $dataTrain;
        $this->label = $label;
        $this->k = $k;
    }

    /**
     * predict result
     *
     * @param $example
     * @return mixed
     */
    function predict($example)
    {
        $listDistance = array();
        foreach ($this->dataTrain as $index => $itemTrain) {
            $listDistance[$index] = $this->distance($itemTrain, $example);
        }
        arsort($listDistance);
        $listNeighBor = array_slice($listDistance, 0, $this->k, true);
        $listLabel = array();
        foreach ($listNeighBor as $index => $neighbor) {
            if (isset($listLabel[$this->label[$index]])) {
                ++$listLabel[$this->label[$index]];
            } else {
                $listLabel[$this->label[$index]] = 1;
            }
        }

        arsort($listLabel);
        reset($listLabel);
        return key($listLabel);
    }

    /**
     * caculate distance 2 vector
     *
     * @param $v1
     * @param $v2
     * @return float
     */
    public function distance($v1, $v2)
    {
        $distance = 0;
        foreach ($v1 as $index => $value) {
            $ordinary = ($value / (1 + $value) - $v2[$index] / (1 + $v2[$index])) ** 2;
            $distance += $ordinary;
        }
        return sqrt((float)$distance);
    }
}