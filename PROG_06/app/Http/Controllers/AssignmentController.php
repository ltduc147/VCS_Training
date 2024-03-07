<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Assignment;
use App\Models\User;
use App\Models\Submission;

class AssignmentController extends Controller
{
    //
    public function assignment_list(Request $request){

        $assignment_per_page = 10;
        $assignments = Assignment::get();

        if ($request->has('page')){
            $page = $request->query('page');
        } else {
            $page = 1;
        }

        $total_assignment = count($assignments);
        if ($page < 1) {
            $page = 1;
        } elseif ($page > ceil($total_assignment / $assignment_per_page)){
            $page = ceil($total_assignment / $assignment_per_page);
        }

        $offset = ($page - 1) * $assignment_per_page;

        $assignments = Assignment::offset($offset)
                        ->limit($assignment_per_page)
                        ->get();

        //dd($assignments);
        for($i = 0; $i < count($assignments); $i++){
                $user = User::find($assignments[$i]['teacher_id']);
                $assignments[$i]['name'] = $user['full_name'];
        }

        return view('assignments.assignment_list', [
            'assignments' => $assignments,
            'page' => $page,
            'num_page' => ceil($total_assignment / $assignment_per_page)
        ]);
    }

    public function assignment_detail($id){

        $assignment = Assignment::find($id);

        if (isset($assignment)){
            $user = User::find($assignment['teacher_id']);
            $assignment['name'] = $user['full_name'];
        }

        $file_types = array(
            'txt' => 'Text',
            'doc' => 'Word',
            'docx' => 'Word',
            'pdf' => 'PDF',
            'jpg' => 'JPEG Image',
            'png' => 'PNG Image'
        );

        $filename = basename($assignment['file_url']);
        $assignment['file_name'] = substr($filename, strpos($filename, '_') + 1);
        $file_extension = pathinfo($assignment['file_url'], PATHINFO_EXTENSION);
        $assignment['file_type'] = isset($file_types[$file_extension]) ? $file_types[$file_extension] : 'Unknown';
        $assignment['date'] = date('d/m/Y',strtotime($assignment['created_at']));

        $submission = Submission::where([
            'assignment_id' => $id,
            'student_id' => session('user')['id']
        ])->get();

        if (count($submission)) {
            $filename = basename($submission[0]['file_url']);
            $submission[0]['file_name'] = substr($filename, strpos($filename, '_') + 1);
            $file_extension = pathinfo($submission[0]['file_url'], PATHINFO_EXTENSION);
            $submission[0]['file_type'] = isset($file_types[$file_extension]) ? $file_types[$file_extension] : 'Unknown';
        }

        $submission_list = Submission::where('assignment_id', $id)->get();
        if (isset($submission_list)) {
            for($i = 0; $i < count($submission_list); $i++){
                $user = User::find($submission_list[$i]['student_id']);
                $submission_list[$i]['name'] = $user['full_name'];

                $filename = basename($submission_list[$i]['file_url']);
                $submission_list[$i]['file_name'] = substr($filename, strpos($filename, '_') + 1);
                $file_extension = pathinfo($submission_list[$i]['file_url'], PATHINFO_EXTENSION);
                $submission_list[$i]['file_type'] = isset($file_types[$file_extension]) ? $file_types[$file_extension] : 'Unknown';
                $submission_list[$i]['date'] = date('d/m/Y',strtotime($submission_list[$i]['created_at']));
            }
        }

        return view('assignments.assignment_detail' ,[
            "assignment" => $assignment,
            "submission" => (count($submission) ? $submission[0] : null),
            "submission_list" => $submission_list
        ]);

    }

    public function assignment_form(Request $request){
        if ($request->has('id')) {
            $assignment = Assignment::find($request->query('id'));
        } else {
            $assignment = null;
        }

        return view('assignments.popup_form', [
            "assignment" => $assignment
        ]);
    }


    public function create(Request $request) {

        try {

            $request->validate([
                'title' => 'required',
                'description' => 'required',
                'teacher_id' => 'required',
                'assignment_file' => 'required|file|mimes:txt,doc,docx,pdf,jpg,png'
            ]);

            $file = $request->file('assignment_file');
            $filename = time() . '_' . $file->getClientOriginalName();

            $file->storeAs('public/assignments', $filename, '');
            $request->merge(['file_url' => 'storage/assignments/' . $filename]);
            Assignment::create($request->except(['assignment_file' , '_token']));

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
                'description' => 'required',
                'teacher_id' => 'nullable',
                'assignment_file' => 'nullable|file|mimes:txt,doc,docx,pdf,jpg,png'
            ]);

            $assignment = Assignment::findOrFail($id);

            if ($request->has('assignment_file')){
                $file = $request->file('assignment_file');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/assignments', $filename, '');
                $request->merge(['file_url' => 'storage/assignments/' . $filename]);

                // Delete the old file
                $old_file_path = str_replace('storage/', storage_path('app/public/'), $assignment['file_url']);
                if (file_exists($old_file_path)) {
                    unlink($old_file_path);
                }
            }

            $assignment->update($request->except(['assignment_file' , '_token']));

            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
            return false;
        }
    }


    public function delete($id){

        try {

            $assignment = Assignment::findOrFail($id);
            $old_file_path = str_replace('storage/', storage_path('app/public/'), $assignment['file_url']);
            if (file_exists($old_file_path)) {
                unlink($old_file_path);
            }
            $assignment->delete();

            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
            return false;
        }
    }
}
