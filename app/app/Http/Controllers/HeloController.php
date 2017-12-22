<?php
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
 
use App\Http\Requests;
use App\Http\Controllers\Controller;
 
class HeloController extends Controller
{
    public function getIndex()
    {
        return view('helo', ['message' => 'please type...']);
    }

    public function postIndex(Request $request)
    {
		if(isset($_POST['str']) === TRUE){
			$res = "you typed: " . (string)$_POST['str'];
		}
		else{
			$res = "you typed: ";
		}
        return view('helo', ['message' => $res]);
    }
}