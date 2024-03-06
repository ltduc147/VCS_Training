<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;

class UserController extends Controller
{
    //

    public function student_management(Request $request){

        $student_per_page = 10;
        $students = User::where('role' , 'student')->get();

        if ($request->has('page')){
            $page = $request->query('page');
        } else {
            $page = 1;
        }

        $total_student = count($students);
        if ($page < 1) {
            $page = 1;
        } elseif ($page > ceil($total_student / $student_per_page)){
            $page = ceil($total_student / $student_per_page);
        }

        $offset = ($page - 1) * $student_per_page;

        $students = User::where('role' , 'student')
                        ->offset($offset)
                        ->limit($student_per_page)
                        ->get();

        return view('users.student_management', [
            'students' => $students,
            'page' => $page,
            'num_page' => ceil($total_student / $student_per_page)
        ]);
    }

    public function user_list(Request $request){
        $user_per_page = 3;
        $users = User::get();

        if ($request->has('page')){
            $page = $request->query('page');
        } else {
            $page = 1;
        }

        $total_user = count($users);
        if ($page < 1) {
            $page = 1;
        } elseif ($page > ceil($total_user / $user_per_page)){
            $page = ceil($total_user / $user_per_page);
        }

        $offset = ($page - 1) * $user_per_page;

        $users = User::offset($offset)
                        ->limit($user_per_page)
                        ->get();

        return view('users.user_list', [
            'users' => $users,
            'page' => $page,
            'num_page' => ceil($total_user / $user_per_page)
        ]);

    }

    public function user_form(Request $request){

        if ($request->has('id')) {
            $user = $user = User::find($request->query('id'));
        } else {
            $user = null;
        }

        return view('users.popup_form', [
            "user" => $user
        ]);
    }

    public function profile($id){

        $main_user = User::find($id);
        $messages = Message::where('receiver_id', $id)->get();

        for($i = 0; $i < count($messages); $i++){
            $user = User::find($messages[$i]['sender_id']);
            $messages[$i]['name'] = $user['full_name'];
        }


        return view('users.profile' ,[
            'user' => $main_user,
            "tab" => "info",
            "messages" => $messages

        ]);
    }

    public function update_profile(){

    }

    public function change_pass(){

    }

    public function create() {

    }


    public function update(){

    }


    public function delete(){

    }
}
