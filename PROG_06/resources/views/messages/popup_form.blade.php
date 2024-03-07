<head>
  <link rel="stylesheet" href="{{ asset('css/messages/popup_form.css')}}"/>
</head>

<div class="popup">

  <div class="popup_content">
    <div class="header">
      <div class="title">Edit message</div>
      <div class="close"><span class="material-symbols-outlined">close_small</span></div>
    </div>
    <form class="record_form" enctype="multipart/form-data" method="post">
        @csrf
      <div class="input_item username">
        <label for="username">Message *</label>
        <textarea name="message_content" col='30', row='5'>{{ (isset($message) ? $message['message_content']: "") }}</textarea>
      </div>

      <div class="button">
        <div class="cancel_button">Cancel</div>
        <button type="submit" class="submit_button">OK</button>
      </div>
    </form>
  </div>
</div>
