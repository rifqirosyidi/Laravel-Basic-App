<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index() {
    	$title = 'Hello, Welcome To Laravel Everyone';
    	return view('pages.index')->with('title', $title);
    }

    public function about(){
    	$title = "About Us Page, Check this Out";
    	return view('pages.about')->with('title', $title);
    }

    public function service(){
    	$data = array(
    			'title' => 'Service Page HomeP Page',
    			'services' => ['Web Programming', 'SQL', 'Android']
		);

    	return view('pages.service')->with($data);
    }
}
