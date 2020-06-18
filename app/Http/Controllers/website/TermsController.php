<?php

namespace App\Http\Controllers\website;

use App\Staticpage;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TermsController extends Controller
{
    public function index($language)
    {
        if ($language == 'en')
            $term_column = 'discription';
        else if ($language == 'zh-hk')
            $term_column = 'discription1';
        else if ($language == 'zh-cn')
            $term_column = 'discription2';
        else
            abort(404);

        $data['terms'] = Staticpage::count() > 0 ? Staticpage::first()->{$term_column} : "";

        return view('website.terms.index', $data);
    }
}
