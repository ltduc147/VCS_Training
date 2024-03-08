<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
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
            $user = User::find($request->query('id'));
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

    public function avt_form(){
        return view('users.avatar_form');
    }

    public function update_profile(Request $request){
        try {

            $request->validate([
                'email' => 'nullable',
                'phone_number' => 'nullable',
                'avt' => 'nullable|file|mimes:jpg,png,gif,svg+xml,webp',

            ]);

            $user = User::findOrFail(session('user')['id']);

            if ($request->has('avt')){
                $file = $request->file('avt');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('storage/avatars'), $filename);
                $request->merge(['avt_url' => 'storage/avatars/' . $filename]);

                // Delete the old file
                //$old_file_path = str_replace('storage/', storage_path('app/public/'), );
                if (file_exists(public_path($user['avt_url']))) {
                    unlink(public_path($user['avt_url']));
                }
            } elseif ($request->has('avt_url')){
                // Delete the old file
                //$old_file_path = str_replace('storage/', storage_path('app/public/'), $user['avt_url']);
                if (file_exists(public_path($user['avt_url']))) {
                    unlink(public_path($user['avt_url']));
                }
            }

            $user->update($request->except(['avt' , '_token']));
            session(['user' => $user]);

            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
            return false;
        }
    }

    public function change_pass(Request $request){
        try {

            $request->validate([
                'old_pass' => 'required',
                'new_pass' => 'required',
                'confirm_pass' => 'required'
            ]);

            $user = User::findOrFail(session('user')['id']);
            if ($user['password'] === $request->input('old_pass')){
                $user->update(['password' => $request->input('new_pass')]);
                return true;
            }

            return false;
        } catch (\Exception $e) {
            return $e->getMessage();
            return false;
        }
    }

    public function create(Request $request) {

        try {

            $request->validate([
                'username' => 'required',
                'password' => 'required',
                'full_name' => 'required',
                'email' => 'required',
                'phone_number' => 'required'
            ]);

            User::create($request->except('_token'));

            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
            return false;
        }

    }


    public function update(Request $request, $id){

        try {

            $request->validate([
                'username' => 'required',
                'password' => 'required',
                'full_name' => 'required',
                'email' => 'nullable',
                'phone_number' => 'nullable'
            ]);

            $user = User::findOrFail($id);
            $user->update($request->except('_token'));

            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
            return false;
        }
    }


    public function delete($id){

        try {

            $user = User::findOrFail($id);
            $user->delete();

            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
            return false;
        }
    }
}
