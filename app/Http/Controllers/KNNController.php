<?php

namespace App\Http\Controllers;

use App\Lib\KNearestNeighbors;

class KNNController extends Controller
{
    public function index()
    {
        $samples = [[1, 3], [1, 4], [2, 4], [3, 1], [4, 1], [4, 2]];
        $labels = ['a', 'a', 'a', 'b', 'b', 'b'];
        $classifier = new KNearestNeighbors($samples,$labels);
        echo "Result predict is:".$classifier->predict([1, 2]);
    }
}
