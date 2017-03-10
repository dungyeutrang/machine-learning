<?php
/**
 * Created by PhpStorm.
 * User: dungict
 * Date: 10/03/2017
 * Time: 09:06
 */

namespace App\Lib;


class DecisionTree
{
    public $targets = array();
    public $numberAttribute = 0;
    public $data = array();
    public $root = null;
    const ROOT_VALUE = -1;
    const ROOT_LABEL = -1;

    public function __construct($data)
    {
        $this->data = $data;
        $this->numberAttribute = count($data[0]) - 1;
    }

    public function buildTree()
    {
        $this->addNode(array(), self::ROOT_LABEL, self::ROOT_VALUE, '', '', null, null);
        for ($i = 0; $i <= ($this->numberAttribute); $i++) {
            foreach ($this->data as $index => $dt) {
                if ($i == 0) {
                    $this->addNode(array(self::ROOT_LABEL => SELF::ROOT_VALUE), $i, $dt[$i], self::ROOT_LABEL, SELF::ROOT_VALUE, null, null);
                } else {
                    $this->addNode(array_slice($dt, 0, $i), $i, $dt[$i], $i - 1, $dt[$i - 1], null, null);
                }

            }
        }

        return $this->root;
    }

    public function addNode($listParent, $label, $value, $parentLabel, $parentValue, $leftChild, $rightSib)
    {
        $node = new Node($label, $value, $parentLabel, $parentValue, $leftChild, $rightSib);
        if (!$this->root) {
            $this->root = $node;
            return;
        }
        $this->findParentAndAddNode($listParent, $node, $this->root);
    }

    public function findParentAndAddNode($listParent, $node, $nodeParent)
    {
        if ($nodeParent) {
            if ($nodeParent->label == $node->parentLabel) {
                $listLabelIdAndValue = array();
                $nodeCurrent = $nodeParent;
                while ($nodeCurrent) {
                    $listLabelIdAndValue[$nodeCurrent->label] = $nodeCurrent->value;
                    $nodeCurrent = $nodeCurrent->parent;
                }

                $isParent = false;
                if ($nodeParent->label == self::ROOT_LABEL) {
                    $isParent = true;
                } else {
                    foreach ($listParent as $index => $val) {
                        if ($listParent[$index] != $listLabelIdAndValue[$index]) {
                            $isParent = true;
                        }
                    }
                }

                if ($isParent) {
                    $node->parent = $nodeParent;
                    if ($nodeParent->leftChild) {

                        $leftChild = $nodeParent->leftChild;
                        if($leftChild->value == $node->value){
                            return true;
                        }
                        while ($leftChild->rightSib) {
                            $leftChild = $leftChild->rightSib;
                            if($leftChild->value == $node->value){
                                return true;
                            }
                        }
                        $leftChild->rightSib = $node;
                    } else {
                        $nodeParent->leftChild = $node;
                    }
                    return true;
                }
            } else {
                if (!$this->findParentAndAddNode($listParent, $node, $nodeParent->leftChild)) {
                    $this->findParentAndAddNode($listParent, $node, $nodeParent->rightSib);
                }
            }
        }

    }
}