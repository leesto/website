<li>
    <div class='alert alert-{{ $level }}'>
        @if(isset($flashIcons[$level]))
            <span class="fa fa-{{ $flashIcons[$level] }}"></span>
        @endif
        <span>
        @if(!empty($title))
                <h1>{{ $title }}</h1>
            @endif
            <p>{!! str_replace(PHP_EOL, '</p><p>', $message) !!}</p>
    </span>
    </div>
</li>