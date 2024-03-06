@extends('application')

@push('styles')
    <link href="{{ asset('css/assignments/assignment_detail.css') }}" rel="stylesheet">
@endpush

@section('content')

  <div class="container">
    <div class="wrapper">
      <div class="content">
        <div class="main_content">

          <div class="assignment_detail_container">
            <div class="assignment_detail">
              <div class="assignment_title">{{(isset($assignment) ? $assignment['title']: "")}}</div>
              <div class="assignment_info">{{ (isset($assignment) ? $assignment['name']: "")}} &bull; {{ (isset($assignment) ? $assignment['date'] : "")}}</div>
              <div class="assignment_description">{{ (isset($assignment) ? $assignment['description']: "")}}</div>
              <a class="file_info" href="{{ (isset($assignment) ? $assignment['file_url']: "")}}" download="{{ (isset($assignment) ? $assignment['file_name']: "")}}">
                <div class="file_name">{{ (isset($assignment) ? $assignment['file_name'] : "")}}</div>
                <div class="file_type">{{ (isset($assignment) ? $assignment['file_type'] : "")}}</div>
              </a>
            </div>
            {{--if($_SESSION['role'] !== 'teacher'): --}}
            <div class="submission_tab">
              <div class="submission_title">Submission</div>
              @if (isset($submission))
                <div class="submission_info" >
                  <a class="submission_detail" href="{{ $submission['file_url']}}" download="{{ $submission['file_name']}}">
                    <div class="file_name">{{ $submission['file_name']}}</div>
                    <div class="file_type">{{ $submission['file_type']}}</div>
                  </a>
                  <i class="material-icons submission_delete" data-id='{{ $submission['id']}}'>close</i>
                </div>
              @endif
              <div class="add_button"><i class="material-icons button">upload_file</i> &nbsp; {{ (isset($submission) ? "Change" : "Add")}} submission</div>
              <form action="" class="submission_form">
                <input type="file" name="submission_file" id="file_input">
                <input type="hidden" name="assignment_id" value='{{ (isset($assignment) ? $assignment['id']: "")}}'>
              </form>
            </div>
            {{-- @endif --}}
          </div>
          {{-- if(isset($submission_list) && $_SESSION['role'] === 'teacher')  --}}
            <div class="submission_list">
              <div class="list_title">Submissions</div>
              @foreach($submission_list as $row)
                <a class="submission_row" href="{{ $row['file_url']}}" download="{{ $row['file_name']}}">
                  <div class="submission_info">
                    <div class="submission_name">{{ $row['name']}}</div>
                    <div class="submission_detail">{{ $row['file_name']}} - {{$row['file_type']}} - {{$row['date']}}</div>
                  </div>
                </a>

              @endforeach
            </div>
          {{-- @endif --}}
        </div>
      </div>
    </div>
  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script>
    $(document).ready(function(){
      $('.add_button').click(function(){
        $('#file_input').click();
      });

      $('#file_input').change(function(){
        var formData = new FormData($(".submission_form")[0]);
        console.log(formData);
        $.ajax({
          url : './?controller=Submission&action=add_update',
          type: 'POST',
          data : formData,
          contentType: false,
          processData: false,
          success: function(response) {
            location.reload();
          },
          error: function(xhr, status, error) {
            console.error(xhr.responseText);
          }
        });
      });

      $('.submission_delete').click(function(){
        var submissionId = $(this).data('id');
        $.ajax({
          url : `./?controller=Submission&action=delete&id=${submissionId}`,
          type: 'GET',
          success: function(response) {
            location.reload();
          },
          error: function(xhr, status, error) {
            console.error(xhr.responseText);
          }
        });
      });
    });
  </script>

@endsection
