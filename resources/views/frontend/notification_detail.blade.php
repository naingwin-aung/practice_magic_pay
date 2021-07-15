@extends('frontend.layouts.app')
@section('title', "Notification Detail")

@section('content')
    <div class="notification__detail">
        <div class="card shadow">
            <div class="card-body">
                <div class="text-center">
                    <img src="{{asset('img/notification.png')}}" alt="Notification" width="210px" height="210px">
                </div>
                <div class="text-center">
                    <h6>{{$notification->data['title']}}</h6>
                    <p class="mb-1 text-muted">{{$notification->data['message']}}</p>
                    <small class="mb-1">
                        {{$notification->created_at->toFormattedDateString()}}
                        {{$notification->created_at->format('h:i:s A')}}
                    </small>
                </div>
                <div class="text-center mt-3">
                    <a href="{{$notification->data['web_link']}}" class="btn btn-primary">Continue</a>
                </div>
            </div>
        </div>
    </div>
@endsection