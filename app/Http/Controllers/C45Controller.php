<?php

namespace App\Http\Controllers;

use App\Models\DatasetTelur;
use App\Services\C45Service;
use Illuminate\Http\Request;

class C45Controller extends Controller
{
    protected C45Service $c45Service;

    public function __construct(C45Service $c45Service)
    {
        $this->c45Service = $c45Service;
    }

    public function index()
    {
        $dataset = DatasetTelur::all();
        $details = $this->c45Service->getCalculationDetails($dataset);

        return view('c45.index', compact('details'));
    }
}
