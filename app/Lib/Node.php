<?php
/**
 * Created by PhpStorm.
 * User: dungict
 * Date: 04/03/2017
 * Time: 11:41
 */

namespace App\Lib;

/**
 * @author dungpv <dungpv@rikkeisoft.com>
 */
class Node
{
    public $type;
    public $label;
    public $parent;
    public $value;

    public function __construct($type, $label, $parent, $value)
    {
        $this->type = $type;
        $this->label = $label;
        $this->parent = $parent;
        $this->value = $value;
    }
}