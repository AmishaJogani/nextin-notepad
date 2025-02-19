<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Str;

class TestController extends Controller
{
    //
    public function show(Request $request,$key){

        $note = Note::updateOrCreate(['key'=>$key ]);
        return view('notepad',['note'=>$note]);
    }

    public function save(Request $request)
    {

        $note = Note::updateOrCreate(['key'=> $request->key], ['content' => $request->content]);
        if($note){
            return response()->json(['success' => true]);

        }else{
            return response()->json(['success' => false]);

        }
    }

   
}
