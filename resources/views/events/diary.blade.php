@extends('app')

@section('title', $title)

@section('stylesheets')
    @include('partials.tags.style', ['path' => 'partials/events'])
@endsection

@section('javascripts')
    @include('partials.tags.script', ['path' => 'partials/events'])
@endsection

@section('scripts')
    var $modal = $('#diaryDateModal');
    var $form = $modal.find('form');
    var $btns = $modal.find('button');
    $modal.find('#cancelDateModal').on('click', function () {
        $modal.modal('hide');
        $form.trigger('reset');
        clearModalForm($form);
    });
    $modal.find('#submitDateModal').on('click', function() {
        window.location = $(this).data('url')
                                 .replace('%year',$form.find('select[name="year"]').val())
                                 .replace('%month',$form.find('select[name="month"]').val());
    });
@endsection

@section('styles')
    @media (min-width: 992px) {
        #diary div.diary div.calendar div.cell:nth-of-type(7n+{{ (7 - $blank_before) }}) {
            border-right: 1px solid #444;
        }
        #diary div.diary div.calendar div.cell:nth-last-of-type(-n+{{ (7 - $blank_after) }}) {
            border-bottom: 1px solid #444;
        }
    }
@endsection

@section('content')
    <h1 class="page-header">@yield('title')</h1>
    <div id="diary">
        <div class="date-header">
            <a class="prev" href="{{ route('events.diary', ['year' => $date_prev->year, 'month' => $date_prev->month]) }}">
                <span class="fa fa-caret-left"></span>
            </a>
            <span class="month" data-toggle="modal" data-target="#diaryDateModal" title="Select month and year">{{ $date->format('F Y') }}</span>
            <a class="next" href="{{ route('events.diary', ['year' => $date_next->year, 'month' => $date_next->month]) }}">
                <span class="fa fa-caret-right"></span>
            </a>
        </div>
        <div class="diary">
            <div class="day-headers">
                <div class="cell">Mon</div>
                <div class="cell">Tue</div>
                <div class="cell">Wed</div>
                <div class="cell">Thu</div>
                <div class="cell">Fri</div>
                <div class="cell">Sat</div>
                <div class="cell">Sun</div>
            </div>
            <div class="calendar">
                @if($blank_before > 0)
                    <span class="cell blank" style="width: {{ $blank_before * 100 / 7 }}%"></span>
                @endif
                @for($i = 1; $i <= $date->daysInMonth; $i++)
                    <div class="cell day">
                        <span class="date">{{ $i }}</span>
                        @if(isset($calendar[$i]) && count($calendar[$i]) > 0)
                            <ul class="event-list">
                                @foreach($calendar[$i] as $event)
                                    <li class="event {{ $event->type_class }}"
                                        data-start="{{ $event->getEarliestStart(\Carbon\Carbon::createFromDate($date->year, $date->month, $i)) }}"
                                        data-end="{{ $event->getLatestEnd(\Carbon\Carbon::createFromDate($date->year, $date->month, $i)) }}"
                                        data-type="{{ $event->type_class }}">
                                        <a href="{{ route('events.view', $event->id) }}">{{ $event->name }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endfor
                @if($blank_after > 0)
                    <span class="cell blank" style="width: {{ $blank_after * 100 / 7 }}%"></span>
                @endif
            </div>
        </div>
    </div>
    @if(Auth::check() && Auth::user()->isMember())
        <div class="event-key">
            <h1>Key</h1>
            <ul class="event-list">
                @foreach(\App\Event::$Types as $i => $type)
                    <li class="event {{ \App\Event::$TypeClasses[$i] }}"><span>{{ $type }}</span></li>
                @endforeach
            </ul>
        </div>
        @if(Auth::user()->isAdmin())
            <a class="btn btn-success" href="{{ route('events.add') }}">
                <span class="fa fa-plus"></span>
                <span>Add an event to the diary</span>
            </a>
        @endif
    @endif
@endsection

@section('_test')
    <p>Hello!</p>
@endsection

@section('modal')
    @include('events.modal.diary_date')
@endsection