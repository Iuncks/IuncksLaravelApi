<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DemoController extends Controller
{
    public function index() {
        $array = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com'
            ],
            [
                'name' => 'Mark Doe',
                'email' => 'mark@exemple.com'
            ]
        ];

        return response()->json([
            'menssage' => '2 Users found',
            'data' => $array,
            'status' => true
        ], 200);
    }
}