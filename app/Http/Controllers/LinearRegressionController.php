<?php

namespace App\Http\Controllers;

use App\Lib\LinearRegression;

class LinearRegressionController extends Controller
{

    public function index()
    {
        $input = [[1, 3], [1, 4], [2, 4], [3, 1], [4, 1], [4, 2]];
        $output = [5, 6, 7, 5, 6, 7];
        $linear = new LinearRegression($input, $output);
        $linear->train();
//        $linear->predict([1,2]);
    }
}
