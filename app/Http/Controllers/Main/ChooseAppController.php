<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;

class ChooseAppController extends Controller {

    public function index() {

        return view('pages.main.index')->with([
            
        ]);
    }
}
