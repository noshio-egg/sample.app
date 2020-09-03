<?php
namespace App\Http\Controllers;

class SalesController extends Controller
{

	public function index()
	{
		return view('sale.index');
	}

	public function detail()
	{
		return view('sale.detail');
	}
}
