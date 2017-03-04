<?php

namespace App\Http\Controllers;

use App\Lib\LinearRegression;

class LinearRegressionController extends Controller
{

    public function index()
    {
        $input = [[1,1],[2,1],[2,3],[4,5],[3,4],[5,2],[1,3],[3,2],[4,6]];
        $output = [5,7,11,19,15,15,9,11,21];
        $linear = new LinearRegression($input, $output);
        $linear->train();
        $linear->predict(array([1,2]));
    }
}
