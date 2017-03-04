<?php

namespace App\Lib;

use App\Lib\Node;

/**
 * @author dungpv <dungpv@rikkeisoft.com>
 */
class DecisionTree
{
    public $data;
    public $label;
    public $tree;
    const PARENT_NODE_INIT = -1;

    public function __construct($data, $label)
    {
        $this->data = $data;
        $this->label = $label;
    }

    public function addNode($data)
    {
        
    }

}