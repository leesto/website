@if(Session::has('flash.alerts'))
    @foreach(Session::pull('flash.alerts') as $alert)
        <li>
        @include('partials.flash.message', [
            'level' => $alert['level'],
            'title' => $alert['title'],
            'message' => $alert['message']
        ])
        </li>
    @endforeach
@endif