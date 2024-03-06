@extends('application')

@push('styles')
    <link href="{{ asset('css/users/user_list.css') }}" rel="stylesheet">
@endpush

@section('content')

  <div class="container">
    <div class="wrapper">
      <div class="content">
        <div class="top_bar">
          <div class="page_title">LIST USER</div>
        </div>
        <div class="main_content">

          <div class="page">
            <div class="user_list_container">

              @if (isset($users))
                @foreach($users as $row)
                    <a class="user_row" href='/user/{{$row['id']}}'>
                        <img src='{{$row['avt_url']}}' alt="" class="user_avt">
                        <div class="user_detail">
                            <div class="user_fullname">{{$row['full_name']}}</div>
                            <div class="user_role">{{$row['role']}}</div>
                            <div class="user_email"><span class="material-icons">mail</span>{{$row['email']}}</div>
                        </div>
                    </a>
                @endforeach
              @endif
            </div>
            <div class="paglink">
                <a class='link_button first {{$page <= 1 ? "disable" : ""}}' href='/users?page=1'>FIRST</a>

                <a class='link_button pre {{$page <= 1 ? "disable" : ""}}' href='/users?page={{$page - 1}}'>PREVIOUS</a>

                @for ($i = 1; $i <= $num_page; $i++)
                    @if ($i == $page)
                        <a class='isset link' href = '/users?page={{$i}}'>{{$i}}</a>
                    @else
                        <a class='link' href = '/users?page={{$i}}'>{{$i}}</a>
                    @endif
                @endfor
                <a class='link_button next {{($page >= $num_page) ? "disable" : ""}}' href='/users?&page={{$page +1}}'>NEXT</a>
                <a class='link_button last {{($page >= $num_page) ? "disable" : ""}}' href='/users?page={{$num_page}}'>LAST</a>
            </div>


          </div>
        </div>
      </div>
    </div>
  </div>

@endsection
