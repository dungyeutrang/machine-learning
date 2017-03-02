<?php


namespace App\Lib;

class LinearRegression
{
    public $input;
    public $output;
    public $listWeight = array();

    public function __construct($input, $output)
    {
        $this->input = $input;
        $this->output = $output;
    }

    public function train()
    {
        $listWeight = array();
        $listWeight[0] = 0;
        foreach ($this->input[0] as $index => $value) {
            $listWeight[$index + 1] = 0;
        }

    }
}