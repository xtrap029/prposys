<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Changelog;

class ChangelogController extends Controller {

    public function index() {
        return view('pages.main.changelog')->with([
            'changelogs' => Changelog::orderBy('release_date', 'desc')->orderBy('id', 'desc')->get()
        ]);
    }
}
