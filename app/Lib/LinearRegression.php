<?php


namespace App\Lib;

/**
 * @author dungpv <dungpv@rikkeisoft.com>
 */
class LinearRegression
{
    public $input;
    public $output;
    public $alpha;
    public $listWeight = array();

    public function __construct($input, $output, $alpha = 0.01)
    {
        $this->input = $input;
        $this->output = $output;
        $this->alpha = $alpha;
    }

    public function train()
    {
        $listWeight = array();
        $listWeight[0] = 0;
        foreach ($this->input[0] as $index => $value) {
            $listWeight[$index + 1] = 0;
        }
        $converenge = true;
        $numberRecord = count($this->output);
        $listOldError = array();
        while ($converenge) {
            $listDelta = array();
            $listDelta[0] = 0;
            foreach ($this->input[0] as $index => $value) {
                $listDelta[$index + 1] = 0;
            }
            foreach ($this->input as $index => $item) {
                $realOutPut = 0;
                foreach ($listWeight as $i => $weight) {
                    if ($i == 0) {
                        $realOutPut += $weight;
                    } else {
                        $realOutPut += $item[$i - 1] * $weight;
                    }
                }
                foreach ($listWeight as $i => $weight) {
                    if ($i == 0) {
                        $listDelta[$i] += ($realOutPut - $this->output[$index]);
                    } else {
                        $listDelta[$i] += ($realOutPut - $this->output[$index]) * $item[$i - 1];
                    }
                }
            }
            $isStop = false;
            // stop when deltanew < delta old
            if(count($listOldError)){
                foreach ($listDelta as $index => $delta) {
                    if ($delta < $listOldError[$index]) {
                        $isStop = true;
                    }
                }
            }
            if($isStop){
                break;
            }
            foreach ($listWeight as $index => $value) {
                $listWeight[$index] -= (1 / $numberRecord) *  $this->alpha * $listDelta[$index];
            }
            $listOldError = $listDelta;
        }
        $this->listWeight = $listWeight;
    }

    public function predict($input){
        foreach ($input as $item) {
            $result = 0;
            foreach ($this->listWeight as $index => $weight) {
                if ($index == 0) {
                    $result = $weight;
                } else {
                    $result +=$weight*$item[$index-1];
                }
            }
            echo $result."\n";
        }
    }
}