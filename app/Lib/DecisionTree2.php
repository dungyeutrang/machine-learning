<?php

namespace App\Lib;

/**
 * @author dungpv <dungpv@rikkeisoft.com>
 */
class DecisionTree2
{
    const PARENT_ROOT_VALUE = -1;
    public $data;
    public $tree;
    public $numberFields = 0;
    public $entropy = 0;
    public $listEntropy = array();


    public function __construct($data)
    {
        $this->data = $data;
        $this->numberFields = count($data[0]) - 1;
    }

    public function getNumberRecord()
    {
        return count($this->data);
    }


    public function caculateEntropy()
    {
        $listClass = array();
        $listAttributes = array();
        $i = 0;
        foreach ($this->data as $index => $dt) {
            if (isset($listClass[$dt[$this->numberFields]])) {
                $listClass[$dt[$this->numberFields]] += 1;
            } else {
                $listClass[$dt[$this->numberFields]] = 1;
            }
            $i++;
        }

        $total = $this->numberFields - 1;
        for ($index = 0; $index <= $total; $index++) {
            foreach ($this->data as $dt) {
                if (isset($listAttributes[$index][$dt[$index]]['number'])) {
                    $listAttributes[$index][$dt[$index]]['number'] += 1;
                } else {
                    $listAttributes[$index][$dt[$index]]['number'] = 1;
                }
                if (!isset($listAttributes[$index][$dt[$index]]['class'])) {
                    $listAttributes[$index][$dt[$index]]['class'] = array();
                }
                if (isset($listAttributes[$index][$dt[$index]]['class'][$dt[$this->numberFields]])) {
                    $listAttributes[$index][$dt[$index]]['class'][$dt[$this->numberFields]] += 1;
                } else {
                    $listAttributes[$index][$dt[$index]]['class'][$dt[$this->numberFields]] = 1;
                }
            }
        }
        foreach ($listClass as $val) {
            $this->entropy += ($val / $i) * (-1) * log(($val / $i)) / log(2);
        }

        $listValueEntropy = array();
        foreach ($listAttributes as $attrs) {
            $entropy = $this->entropy;
            foreach ($attrs as $att) {
                $cale = ($att['number'] / $i);
                $entro = 0;
                foreach ($att['class'] as $classTrain) {
                    $entro += ($classTrain / $att['number']) * (-1) * log(($classTrain / $att['number'])) / log(2);
                }
                $entropyField = $cale * $entro;
                $entropy -= $entropyField;
            }
            array_push($listValueEntropy, $entropy);
        }
        arsort($listValueEntropy);
        $this->listEntropy = $listValueEntropy;
        return $listValueEntropy;
    }

    function get_values_for_keys($mapping, $keys)
    {
        foreach ($keys as $key) {
            $output_arr[] = $mapping[$key];
        }
        return $output_arr;
    }

    /**
     * build tree
     */
    public function buildTree()
    {
        $this->addNode(array(), self::PARENT_ROOT_VALUE);
        $listEntropy = $this->caculateEntropy();
        array_push($listEntropy,1); // field class to end
        $listArraySelected = array();
        foreach ($listEntropy as $i => $entropy) {
            array_push($listArraySelected, $i);
            foreach ($this->data as $index => $dt) {
                $this->addNode($this->get_values_for_keys($dt, $listArraySelected), $dt[$i]);
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
            $listEntropy = $this->listEntropy;
            $exampleNew = array();
            foreach ($listEntropy as $index => $entro) {
                array_push($exampleNew,$example[$index]);
            }
            return $this->search($this->tree, $exampleNew);
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