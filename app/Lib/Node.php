<?php
/**
 * Created by PhpStorm.
 * User: dungict
 * Date: 10/03/2017
 * Time: 09:07
 */

namespace App\Lib;


class Node
{
    public $label;
    public $value;
    public $parentLabel;
    public $parentValue;
    public $leftChild;
    public $rightSib;
    public $parent;

    public function __construct($label, $value, $parentLabel, $parentValue,$leftChild,$rightSib)
    {
        $this->label = $label;
        $this->value = $value;
        $this->parentLabel = $parentLabel;
        $this->parentValue = $parentValue;
        $this->leftChild = $leftChild;
        $this->rightSib = $rightSib;
    }
}