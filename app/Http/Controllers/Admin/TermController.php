<?php

namespace App\Http\Controllers\Admin;

use App\Models\Term;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TermController extends Controller
{
    public function index(){
        $term = Term::first();

        return view('admin.terms.index', compact('term'));
    }

    public function edit($id){
        $term =Term::find($id);

        return view('admin.terms.edit', compact('term'));

    }

    public function update(Request $request, Term $term){
        $request->validate([
            'content'=>'required',
        
        ]);

        $term->content = $request->input('content');
   
        $term->update();

        return to_route('admin.terms.index')->with('flash_message', "会社概要を編集しました。");
    }
}
