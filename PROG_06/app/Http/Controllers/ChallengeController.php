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

        $challenge['file_name'] = basename($challenge['file_url']);
        $file_extension = pathinfo($challenge['file_url'], PATHINFO_EXTENSION);
        $challenge['file_type'] = isset($file_types[$file_extension]) ? $file_types[$file_extension] : 'Unknown';
        $challenge['date'] = date('d/m/Y',strtotime($challenge['created_time']));


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


    public function create() {

    }


    public function update(){

    }


    public function delete(){

    }
}
