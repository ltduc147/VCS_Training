<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Challenge;
use App\Models\User;

class ChallengeController extends Controller
{
    //
    public function challenge_list(Request $request){

        $challenge_per_page = 10;
        $challenges = Challenge::get();

        if ($request->has('page')){
            $page = $request->query('page');
        } else {
            $page = 1;
        }

        $total_challenge = count($challenges);
        if ($page < 1) {
            $page = 1;
        } elseif ($page > ceil($total_challenge / $challenge_per_page)){
            $page = ceil($total_challenge / $challenge_per_page);
        }

        $offset = ($page - 1) * $challenge_per_page;

        $challenges = Challenge::offset($offset)
                        ->limit($challenge_per_page)
                        ->get();

        //dd($assignments);
        for($i = 0; $i < count($challenges); $i++){
                $user = User::find($challenges[$i]['teacher_id']);
                $challenges[$i]['name'] = $user['full_name'];
        }

        return view('challenges.challenge_list', [
            'challenges' => $challenges,
            'page' => $page,
            'num_page' => ceil($total_challenge / $challenge_per_page)
        ]);
    }

    public function challenge_detail($id){
        $challenge = Challenge::find($id);

        if (isset($challenge)){
            $user = User::find($challenge['teacher_id']);
            $challenge['name'] = $user['full_name'];
        }

        $file_types = array(
            'txt' => 'Text'
        );

        $challenge['file_name'] = substr(basename($challenge['file_url']), strpos(basename($challenge['file_url']), '_') + 1);
        $file_extension = pathinfo($challenge['file_url'], PATHINFO_EXTENSION);
        $challenge['file_type'] = isset($file_types[$file_extension]) ? $file_types[$file_extension] : 'Unknown';
        $challenge['date'] = date('d/m/Y',strtotime($challenge['created_at']));


        return view('challenges.challenge_detail' ,[
            "challenge" => $challenge
        ]);
    }

    public function challenge_form(Request $request){
        if ($request->has('id')) {
            $challenge = Challenge::find($request->query('id'));
        } else {
            $challenge = null;
        }

        return view('challenges.popup_form', [
            "challenge" => $challenge
        ]);
    }

    public function answer(Request $request, $id){
        try {
            $request->validate([
                'answer' => 'required'
            ]);

            $challenge = Challenge::find($id);
            $filename = pathinfo($challenge['file_url'], PATHINFO_FILENAME);
            $answer = substr($filename, strpos($filename, '_') + 1);
            if ($answer === $request->input('answer')) {
                //print_r(asset($challenge['file_url']));
                return file_get_contents(str_replace('storage/', storage_path('app/public/'), $challenge['file_url']));
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return $e->getMessage();
            return false;
        }
    }


    public function create(Request $request) {

        try {

            $request->validate([
                'title' => 'required',
                'hint' => 'required',
                'teacher_id' => 'required',
                'challenge_file' => 'required|file|mimes:txt'
            ]);

            $file = $request->file('challenge_file');
            $filename = time() . '_' . $file->getClientOriginalName();

            $file->storeAs('public/challenges', $filename, '');
            $request->merge(['file_url' => 'storage/challenges/' . $filename]);
            Challenge::create($request->except(['challenge_file' , '_token']));

            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
            return false;
        }

    }


    public function update(Request $request, $id){

        try {

            $request->validate([
                'title' => 'required',
                'hint' => 'required',
                'teacher_id' => 'nullable',
                'challenge_file' => 'nullable|file|mimes:txt'
            ]);

            $challenge = Challenge::findOrFail($id);

            if ($request->has('challenge_file')){
                $file = $request->file('challenge_file');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/challenges', $filename, '');
                $request->merge(['file_url' => 'storage/challenges/' . $filename]);

                // Delete the old file
                $old_file_path = str_replace('storage/', storage_path('app/public/'), $challenge['file_url']);
                if (file_exists($old_file_path)) {
                    unlink($old_file_path);
                }
            }

            $challenge->update($request->except(['challenge_file' , '_token']));

            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
            return false;
        }
    }


    public function delete($id){

        try {

            $challenge = Challenge::findOrFail($id);
            $old_file_path = str_replace('storage/', storage_path('app/public/'), $challenge['file_url']);
            if (file_exists($old_file_path)) {
                unlink($old_file_path);
            }
            $challenge->delete();

            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
            return false;
        }
    }
}
