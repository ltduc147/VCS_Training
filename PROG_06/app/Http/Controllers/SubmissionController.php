<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use Illuminate\Http\Request;
use App\Models\Submission;

class SubmissionController extends Controller
{
    //
    public function create_update(Request $request) {

        try {

            $request->validate([
                'assignment_id' => 'required',
                'student_id' => 'required',
                'submission_file' => 'required|file|mimes:txt,doc,docx,pdf,jpg,png'
            ]);

            $submission = Submission::where([
                'assignment_id' => $request->input('assignment_id') ,
                'student_id' =>  $request->input('student_id')
            ])->get();

            $file = $request->file('submission_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/submissions'), $filename);
            $request->merge(['file_url' => 'storage/submissions/' . $filename]);

            if (count($submission)) {
                // Delete the old file
                //$old_file_path = str_replace('storage/', storage_path('app/public/'), $submission[0]['file_url']);
                if (file_exists(public_path($submission[0]['file_url']))) {
                    unlink(public_path($submission[0]['file_url']));
                }
                $submission[0]->update($request->except(['submission_file' , '_token']));
            } else {
                Submission::create($request->except(['submission_file' , '_token']));

            }


            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
            return false;
        }

    }


    public function delete($id){

        try {

            $submission = Submission::findOrFail($id);
            //$old_file_path = str_replace('storage/', storage_path('app/public/'), $submission['file_url']);
            if (file_exists(public_path($submission['file_url']))) {
                unlink(public_path($submission['file_url']));
            }
            $submission->delete();

            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
            return false;
        }
    }
}
