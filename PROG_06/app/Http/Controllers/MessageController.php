<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;

class MessageController extends Controller
{
    //
    public function message_form(Request $request){
        if ($request->has('id')) {
            $challenge = Message::find($request->query('id'));
        } else {
            $challenge = null;
        }

        return view('messages.popup_form', [
            "message" => $challenge
        ]);
    }


    public function create() {

    }


    public function update(){

    }


    public function delete(){

    }
}
