@if(Session::has('flash.alerts'))
    @foreach(Session::pull('flash.alerts') as $alert)
        @include('partials.flash.message', [
            'level' => $alert['level'],
            'title' => $alert['title'],
            'message' => $alert['message']
        ])
    @endforeach
@endif