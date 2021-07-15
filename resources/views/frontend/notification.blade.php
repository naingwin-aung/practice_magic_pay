@extends('frontend.layouts.app')
@section('title', "Notification")

@section('content')
    <div class="notification">
        <h6 class="ml-2 font-weight-bold">Notification</h6>
        <div class="infinite-scroll">
            @foreach ($notifications as $notification)
                <a href="{{route('notification.detail', $notification->id)}}">
                    <div class="card shadow mb-2">
                        <div class="card-body py-3">
                            <h6><i class="fas fa-bell @if(is_null($notification->read_at)) text-danger @endif"></i> {{Illuminate\Support\Str::limit($notification->data['title'], 40)}}</h6>
                            <p class="mb-1">{{Illuminate\Support\Str::limit($notification->data['message'], 100)}}</p>
                            <small class="text-muted mb-1">
                                {{$notification->created_at->diffForHumans()}} -
                                {{$notification->created_at->toFormattedDateString()}} -
                                {{$notification->created_at->format('h:i:s A')}}
                            </small>
                        </div>
                    </div>
                </a>
            @endforeach
            {{$notifications->links()}}  
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $('ul.pagination').hide();
        $(function() {
            $('.infinite-scroll').jscroll({
                autoTrigger: true,
                loadingHtml: '<div class="text-primary">Loading......</div>',
                padding: 0,
                nextSelector: '.pagination li.active + li a',
                contentSelector: 'div.infinite-scroll',
                callback: function() {
                    $('ul.pagination').remove();
                }
            });
        })
    </script>
@endsection