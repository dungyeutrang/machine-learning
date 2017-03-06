<?php

namespace App\Lib;

/**
 * @author dungpv <dungpv@rikkeisoft.com>
 */
class DecisionTree
{
    public $data;
    public $tree;
    const PARENT_ROOT_VALUE = -1;
    public $numberFields = 0;

    public function __construct($data)
    {
        $this->data = $data;
        $this->numberFields = count($data[0]) - 1;
    }

    /**
     * build tree
     */
    public function buildTree()
    {
        $this->addNode(array(), self::PARENT_ROOT_VALUE);
        for ($i = 0; $i <= $this->numberFields; $i++) {
            foreach ($this->data as $index => $dt) {
                $this->addNode(array_slice($dt, 0, $i + 1), $dt[$i]);
            }
        }
        return $this->tree;
    }

    /**
     * add node
     *
     * @param $path
     * @param $value
     * @return \App\Lib\Node
     */
    public function addNode($path, $value)
    {
        $node = new Node($path, $value);
        if (!$this->tree) {
            $this->tree = $node;
        } else {
            $this->addNewNode($node, $this->tree);
        }
        return $this->tree;
    }

    /**
     * add new node
     *
     * @param $node
     * @param $nodeParent
     * @return int
     */
    public function addNewNode($node, $nodeParent)
    {
        if (!isset($node->path[1])) { // case root
            $isExist = false;
            $this->insertChild($nodeParent, $node);
            return $isExist;
        } else {
            $listPathCurrent = $nodeParent->path;
            array_push($listPathCurrent, $node->value);
            if ($listPathCurrent == $node->path) {
                $this->insertChild($nodeParent, $node);
            }
            foreach ($nodeParent->child as $nodeChild) {
                $this->addNewNode($node, $nodeChild);
            }
        }
    }

    /**
     * insert child
     *
     * @param $nodeParent
     * @param $node
     * @return int
     */
    public function insertChild($nodeParent, $node)
    {
        $isExist = false;
        foreach ($nodeParent->child as $nodeChild) {
            if ($nodeChild->value == $node->value) {
                $isExist = true;
            }
        }
        if (!$isExist) {
            return array_push($nodeParent->child, $node);
        }
        return $nodeParent;
    }

    public function predict($example)
    {
        if (!$this->tree) {
            return 'Cannot predict';
        } else {
            return $this->search($this->tree, $example);
        }
    }

    public function search($node, $example)
    {
        if (isset($node->path[$this->numberFields])) {
            if ($example == array_slice($node->path, 0, $this->numberFields)) {
                return $node->value;
            }
        }
        foreach ($node->child as $nodeChild) {
            $result = $this->search($nodeChild, $example);
            if ($result) {
                return $result;
            }
        }
        return null;
    }
}