@extends('layouts.app')

@section('content')
<form action="/new", method="POST">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="ms-Grid-col ms-u-mdPush1 ms-u-md9 ms-u-lgPush1 ms-u-lg6">
        <p class="ms-font-xl">
            By using this application, you can register an event in the calendar.
        </p>
        <div class="ms-TextField">
           <p class="ms-font-xl"> Event Title <input id="event-title" class="ms-TextField-field" name="event_title" value="" placeholder="New event name"></p>
		  <p class="ms-font-xl"> Event Start DayTime</p>

		  <?php $today = date('Y-m-d');
		  $time = date('H:i:s'); ?>

		   <input class="ms-TextField-field" name="start_date" placeholder="{{$today}}" value="{{$today}}">
		   <input class="ms-TextField-field" name="start_time" placeholder="{{$time}}" value="{{$time}}">

		   <p class="ms-font-xl"> Event End DayTime</p>
		   <input class="ms-TextField-field" name="end_date" placeholder="{{$today}}" value="{{$today}}">
		   <input class="ms-TextField-field" name="end_time" placeholder="{{$time}}" value="{{$time}}">

		   <div class="ms-TextField ms-TextField--multiline">
				<p class="ms-font-xl">Description</p>
					<textarea class="ms-TextField-field" name="event_description" placeholder="Input text" value=""></textarea>
		   </div>

		   <div class="ms-TextField ms-TextField--multiline">
				<p class="ms-font-xl">Participant list</p>
					<textarea class="ms-TextField-field" name="participant" placeholder="xxx@yyy.zz&#13;xxx@yyy.zz" value=""></textarea>
		   </div>

		   <div class="ms-TextField ms-TextField--multiline">
				<p class="ms-font-xl">Mail text</p>
					<textarea class="ms-TextField-field" name="mail_text" rows="10" placeholder="" value=""></textarea>
		   </div>
        </div>

        <button id="event-submit" class="ms-Button">
        <span class="ms-Button-label">Event Creation</span>
        </button>
        @if ($status == "success")
        <p class="ms-font-m ms-fontColor-green">
            Successfully sent an email!<br>
			{{$debag}}
        </p>
        @endif
    </div>
</form>
@endsection