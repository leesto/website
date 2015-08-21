@extends('contact.shared')

@section('title', 'Feedback')

@section('tab')
    <p>We always strive to provide a professional service to all of our clients but we know that there is room for improvement. If you have recently been involved with an event at which we assisted and have feedback (either positive or negative) we would really appreciate it if you could fill out the form below.</p>
    <p>All feedback is anonymous and will only be used to improve the quality of the services we provide.</p>
    {!! Form::open(['style' => 'max-width:600px']) !!}
        @include('partials.form.summary-errors')

        <!-- Text field for 'event' -->
        <div class="form-group @include('partials.form.error-class', ['name' => 'event'])">
            {!! Form::label('event', 'Event Name:', ['class' => 'control-label']) !!}
            {!! Form::text('event', null, ['class' => 'form-control']) !!}
            @include('partials.form.input-error', ['name' => 'event'])
        </div>

        <!-- Textarea for 'feedback' -->
        <div class="form-group @include('partials.form.error-class', ['name' => 'feedback'])">
            {!! Form::label('feedback', 'Your Feedback:', ['class' => 'control-label']) !!}
            {!! Form::textarea('feedback', null, ['class' => 'form-control', 'rows' => 6]) !!}
            @include('partials.form.input-error', ['name' => 'feedback'])
        </div>

        <div class="form-group @include('partials.form.error-class', ['name' => 'g-recaptcha-response'])">
            {!! Recaptcha::render() !!}
            @include('partials.form.input-error', ['name' => 'g-recaptcha-response'])
        </div>

        <div class="form-group">
            <button class="btn btn-success" disable-submit="Sending feedback ..." type="submit">
                <span class="fa fa-send"></span>
                <span>Send Feedback</span>
            </button>
            <a class="btn btn-danger" href="{{ route('home') }}">
                <span class="fa fa-undo"></span>
                <span>Cancel</span>
            </a>
        </div>
    {!! Form::close() !!}
@endsection