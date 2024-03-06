@extends('application')

@push('styles')
    <link href="{{ asset('css/assignments/assignment_list.css') }}" rel="stylesheet">
@endpush

@section('content')

  <div class="container">
    <div class="wrapper">
      <div class="content">
        <div class="top_bar">
          <div class="page_title">ASSIGNMENT</div>
            {{-- @if ($_SESSION['role'] === 'teacher'):?> --}}
            <button class="add_record">
              <i class="material-icons button">assignment_add</i> &nbsp;
              Add assignment
            </button>
            {{-- @endif;?> --}}
        </div>
        <div class="main_content">
          <div class="page">
            <div class="assignment_list_container">
              @if (isset($assignments))
              @foreach($assignments as $assignment)
                <div class="assignment_row">
                  <a class="assignment_content" href="/assignment/{{$assignment['id']}}">
                    <i class="material-symbols-outlined icon">assignment</i>
                    <div class="assignment_detail">
                      <div class="assignment_title">{{$assignment['title']}}</div>
                      <div class="assignment_info">{{$assignment['name']}}</div>
                    </div>
                  </a>
                  <div class="assignment_action">
                    {{-- <?php if ($assignment['teacher_id'] === $_SESSION['id']):?> --}}
                      <i class='material-icons icon_edit' data-id='{{$assignment['id']}}'>border_color</i>
                      <i class='material-icons icon_delete' data-id='{{$assignment['id']}}'>&#xe872;</i>
                    {{-- <?php endif; ?> --}}
                  </div>
                </div>
              @endforeach
              @endif
            </div>
            <div class="assignment_form"></div>
            <div class="paglink">
                <a class='link_button first {{$page <= 1 ? "disable" : ""}}' href='/assignments?page=1'>FIRST</a>

                <a class='link_button pre {{$page <= 1 ? "disable" : ""}}' href='/assignments?page={{$page - 1}}'>PREVIOUS</a>

                @for ($i = 1; $i <= $num_page; $i++)
                    @if ($i == $page)
                        <a class='isset link' href = '/assignments?page={{$i}}'>{{$i}}</a>
                    @else
                        <a class='link' href = '/assignments?page={{$i}}'>{{$i}}</a>
                    @endif
                @endfor
                <a class='link_button next {{($page >= $num_page) ? "disable" : ""}}' href='/assignments?page={{$page +1}}'>NEXT</a>
                <a class='link_button last {{($page >= $num_page) ? "disable" : ""}}' href='/assignments?page={{$num_page}}'>LAST</a>
            </div>


          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script>
    $(document).ready(function(){

      $('.add_record').click(function(){
        // Send AJAX request to viewForm endpoint
        $.ajax({
            url: '/assignment/form',
            type: 'GET',
            success: function(response) {
                // Display the edit form
                $('.assignment_form').html(response);

                $('.close, .cancel_button').click(function(){
                  $('.assignment_form').html('');
                  $('.submit_button').off();
                });

                $('.submit_button').click(function(){
                  event.preventDefault();
                  var formData = new FormData($(".record_form")[0]);
                  $.ajax({
                    url : '/assignment/create',
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
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
      });

      function handleEditClick(assignmentId){
          // Send AJAX request to showEditForm endpoint

          $.ajax({
              url: `/assignment/form?id=${assignmentId}`,
              type: 'GET',
              success: function(response) {
                  // Display the edit form
                  $('.assignment_form').html(response);

                  // Hide the edit form
                  $('.close, .cancel_button').click(function(){
                    location.reload();
                  });

                  $('.submit_button').click(function(){
                    // Submit form and reload new data
                    event.preventDefault();
                    var formData = new FormData($(".record_form")[0]);
                    $.ajax({
                      url : `/assignment/update/${assignmentId}`,
                      type: 'POST',
                      data : formData,
                      contentType: false,
                      processData: false,
                      success: function(response) {

                      },
                      error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        // Handle error
                      }
                    });

                    handleEditClick(assignmentId);
                  });

              },
              error: function(xhr, status, error) {
                  console.error(xhr.responseText);
                  // Handle error
              }
          });
        }

        $('.icon_edit').click(function(){
          var assignmentId = $(this).data('id');
          handleEditClick(assignmentId);
        });

        // Handle delete function
        $('.icon_delete').click(function(){
            var assignmentId = $(this).data('id');
            // Send AJAX request to viewForm endpoint
            $.ajax({
                url : `/assignment/delete/${assignmentId}`,
                type: 'GET',
                success: function(response) {
                    // Display the edit form
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
