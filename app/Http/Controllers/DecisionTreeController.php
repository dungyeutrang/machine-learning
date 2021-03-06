<?php

namespace App\Http\Controllers;

use App\Lib\DecisionTree;

class DecisionTreeController extends Controller
{
    public function index()
    {
        $data = array();
        // read file data
        $f = fopen(storage_path('temp/data.csv'), 'r');
        while (!feof($f)) {
            array_push($data, fgetcsv($f));
        }
        fclose($f);
        $decision = new DecisionTree($data);
        $decision->buildTree();
        $examples = array('Rain','Cool','Normal','Weak');
        dd($decision->predict($examples));
    }
}
