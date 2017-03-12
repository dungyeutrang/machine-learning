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
class Node2
{
    public $value;
    public $child;
    public $path;

    public function __construct($path, $value)
    {
        $this->value = $value;
        $this->path = $path;
        $this->child = array();
    }
}