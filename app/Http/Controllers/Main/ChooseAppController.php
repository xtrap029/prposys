<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\AppExternal;

class ChooseAppController extends Controller {

    public function index() {
        return view('pages.main.index')->with([
            'app_externals' => AppExternal::orderBy('id', 'asc')->get()
        ]);
    }
}
