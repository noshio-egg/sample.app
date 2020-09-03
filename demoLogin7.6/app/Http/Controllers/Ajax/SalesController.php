<?php
namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SalesController extends Controller
{

	public function index(Request $request)
	{
		$data = \App\Sale::select('company_name', 'amount')->where('year', $request->year)->get();
		return $data;
	}

	public function years()
	{
		$years = \App\Sale::select('year')->groupBy('year')->pluck('year');
		return $years;
	}
}
