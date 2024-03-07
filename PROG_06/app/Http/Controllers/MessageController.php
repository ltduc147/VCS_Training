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


    public function create(Request $request) {


        try {

            $request->validate([
                'sender_id' => 'required',
                'receiver_id' => 'required',
                'message_content' => 'required'
            ]);

            Message::create($request->except('_token'));

            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
            return false;
        }

    }


    public function update(Request $request, $id){

        $message = Message::find($id);
        if (session('user')['id'] === $message['sender_id']){
            try {

                $request->validate([
                    'sender_id' => 'nullable',
                    'receiver_id' => 'nullable',
                    'message_content' => 'required'
                ]);

                $message = Message::findOrFail($id);
                $message->update($request->except('_token'));

                return true;
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        } else {
            return false;
        }

    }


    public function delete($id){

        $message = Message::find($id);
        if (session('user')['id'] === $message['sender_id']){
            try {

                $message = Message::findOrFail($id);
                $message->delete();

                return true;
            } catch (\Exception $e) {
                return $e->getMessage();
                return false;
            }
        } else {
            return false;
        }
    }
}
