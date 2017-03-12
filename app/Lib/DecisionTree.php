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
        $this->algId3($this->data, array());
        return $this->root;
    }

    public function algId3($traningSet, $listParent = array())
    {
        if (empty($traningSet)) {
            return;
        }
        $listEntropy = $this->getEntropy($traningSet, $listParent);
        array_push($listEntropy, 1); // add entropy for field class to end
        reset($listEntropy);
        $bestAttribute = key($listEntropy);

        $listValueAvaiableForAttribute = array();
        if (empty($listParent)) {
            $valueParent = self::ROOT_VALUE;
            $labelParent = self::ROOT_LABEL;
        } else {
            $valueParent = end($listParent);
            $labelParent = key($listParent);
        }

        foreach ($traningSet as $index => $row) {
            if (!isset($row[$bestAttribute])) {
                $bestAttribute = $this->numberAttribute;
            }
            $value = $row[$bestAttribute];
            if (!in_array($value, $listValueAvaiableForAttribute)) {
                array_push($listValueAvaiableForAttribute, $value);
                $this->addNode(array(self::ROOT_LABEL => self::ROOT_VALUE) + $listParent, $bestAttribute, $value, $labelParent, $valueParent, null, null);
                $this->algId3($this->cuttingArray($traningSet, $bestAttribute, $value), $listParent + array($bestAttribute => $value));
            }
        }
    }

    public function cuttingArray($data, $index, $value)
    {
        foreach ($data as $i => $dt) {
            if ($dt[$index] != $value) {
                unset($data[$i]);
            } else {
                unset($data[$i][$index]);
                if (!count($data[$i])) {
                    unset($data[$i]);
                }
            }
        }
        return $data;
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
                if ($nodeParent->label == self::ROOT_LABEL) {
                    $isParent = true;
                } else {
                    $isParent = true;
                    foreach ($listParent as $index => $val) {
                        if ($listParent[$index] != $listLabelIdAndValue[$index]) {
                            $isParent = false;
                            break;
                        }
                    }
                }

                if ($isParent) {
                    $node->parent = $nodeParent;
                    if ($nodeParent->leftChild) {
                        $leftChild = $nodeParent->leftChild;
                        if ($leftChild->value == $node->value) {
                            return true;
                        }
                        while ($leftChild->rightSib) {
                            $leftChild = $leftChild->rightSib;
                            if ($leftChild->value == $node->value) {
                                return true;
                            }
                        }
                        $leftChild->rightSib = $node;
                    } else {
                        $nodeParent->leftChild = $node;
                    }
                    return true;
                }
            }
            if (!$this->findParentAndAddNode($listParent, $node, $nodeParent->leftChild)) {
                $this->findParentAndAddNode($listParent, $node, $nodeParent->rightSib);
            }
        }
        return false;
    }

    /**
     * @param $examples
     * @param $listAttributesSelected
     * @return array
     */
    public function getEntropy($examples, $listAttributesSelected = array())
    {
        $listClass = array();
        $listAttributes = array();
        $i = 0; // number examples
        foreach ($examples as $index => $dt) {
            if (isset($listClass[$dt[$this->numberAttribute]])) {
                $listClass[$dt[$this->numberAttribute]] += 1;
            } else {
                $listClass[$dt[$this->numberAttribute]] = 1;
            }
            $i++;
        }
        $total = $this->numberAttribute - 1;

        for ($index = 0; $index <= $total; $index++) {
            if (isset($listAttributesSelected[$index])) {
                continue;
            }
            foreach ($examples as $dt) {
                if (isset($listAttributes[$index][$dt[$index]]['number'])) {
                    $listAttributes[$index][$dt[$index]]['number'] += 1;
                } else {
                    $listAttributes[$index][$dt[$index]]['number'] = 1;
                }
                if (!isset($listAttributes[$index][$dt[$index]]['class'])) {
                    $listAttributes[$index][$dt[$index]]['class'] = array();
                }
                if (isset($listAttributes[$index][$dt[$index]]['class'][$dt[$this->numberAttribute]])) {
                    $listAttributes[$index][$dt[$index]]['class'][$dt[$this->numberAttribute]] += 1;
                } else {
                    $listAttributes[$index][$dt[$index]]['class'][$dt[$this->numberAttribute]] = 1;
                }
            }
        }
        $entropyTotal = 0;
        foreach ($listClass as $val) {
            $entropyTotal += ($val / $i) * (-1) * log(($val / $i)) / log(2);
        }

        $listValueEntropy = array();
        foreach ($listAttributes as $index => $attr) {
            $entropy = $entropyTotal;
            foreach ($attr as $att) {
                $cale = ($att['number'] / $i);
                $entropyLocal = 0;
                foreach ($att['class'] as $classTrain) {
                    $entropyLocal += ($classTrain / $att['number']) * (-1) * log(($classTrain / $att['number'])) / log(2);
                }
                $entropyField = $cale * $entropyLocal;
                $entropy -= $entropyField;
            }
            $listValueEntropy[$index] = $entropy;
        }
        arsort($listValueEntropy);
        return $listValueEntropy;
    }


}