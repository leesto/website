@extends('app')

@section('title', 'Create Users')

@section('scripts')
    $('#modeTab').tabify();
    $('#modeTab').find('ul.nav > li').on('click', function() {
        var $this = $(this);
        $('input[name=mode]').val($this.data('mode'));
        $('#btnSubmit').find('span:last').text($this.data('btnText'));
    });
    $('#closeHelpModal').on('click', function() {
        $('#helpModal').modal('hide');
    });

    @if(Input::old('mode') == 'bulk')
        $('ul.nav > li[data-mode=bulk]').trigger('click');
    @endif
@endsection

@section('content')
    <h1 class="page-header">@yield('title')</h1>
    {!! Form::open(['route' => ['user.create.do'], 'style' => 'max-width:25em;']) !!}
        <div class="tabpanel" id="modeTab">
            <ul class="nav nav-tabs">
                <li class="active" data-mode="single" data-btn-text="Add User"><a href="#">Single User</a></li>
                <li data-mode="bulk" data-btn-text="Add Users"><a href="#">Multiple Users</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active">
                    <div class="form-group @if(Input::old('mode') == 'single') @include('partials.form.error-class', ['name' => 'name']) @endif">
                        {!! Form::label('name', 'Name:', ['class' => 'control-label']) !!}
                        {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Both their forename and surname']) !!}
                        @include('partials.form.input-error', ['name' => 'name'])
                    </div>
                    <div class="form-group @if(Input::old('mode') == 'single') @include('partials.form.error-class', ['name' => 'username']) @endif">
                        {!! Form::label('username', 'Username:', ['class' => 'control-label']) !!}
                        <div class="input-group">
                            {!! Form::text('username', null, ['class' => 'form-control', 'placeholder' => 'ab123']) !!}
                            <span class="input-group-addon">@bath.ac.uk</span>
                        </div>
                        @include('partials.form.input-error', ['name' => 'username'])
                    </div>
                </div>
                <div class="tab-pane">
                    {{ Input::get('mode') }}
                    <div class="form-group @if(Input::old('mode') == 'bulk') @include('partials.form.error-class', ['name' => 'users']) @endif">
                        {!! Form::textarea('users', null, ['class' => 'form-control resize-y', 'placeholder' => 'Fred Bloggs,fb123', 'rows' => 6]) !!}
                        @include('partials.form.input-error', ['name' => 'users'])
                    </div>
                </div>
            </div>
        </div>

        {{-- Field for the account type --}}
        <div class="form-group @include('partials.form.error-class', ['name' => 'type'])">
            {!! Form::label('type', 'Account Type:', ['class' => 'control-label']) !!}
            {!! Form::select('type', \App\User::$CreateAccountTypes, null, ['class' => 'form-control']) !!}
            @include('partials.form.input-error', ['name' => 'type'])
        </div>

        {{-- Buttons --}}
        <div class="form-group">
            <button class="btn btn-success" disable-submit="Adding user ..." id="btnSubmit" type="submit">
                <span class="fa fa-user-plus"></span>
                <span>Add User</span>
            </button>
            <a class="btn btn-danger" href="{{ route('user.index') }}">
                <span class="fa fa-undo"></span>
                <span>Cancel</span>
            </a>
            <a class="btn btn-primary" data-toggle="modal" data-target="#helpModal" href="#">
                <span class="fa fa-question-circle"></span>
                <span>Help</span>
            </a>
        </div>

        {{-- Add mode --}}
        {!! Form::input('hidden', 'mode', 'single') !!}
    {!! Form::close() !!}
@endsection

@section('modal')
    @section('modal.header', '<h1>Creating Users</h1>')
    @section('modal.content')
        <p>This form allows you to create new user accounts that can access the Backstage website.</p>
        <h3>Single vs Multiple</h3>
        <p>While the default form is pretty it isn't particularly practical for creating lots of users. If you need to create a lot of accounts "in bulk" then switch to "Multiple Users" mode.</p>
        <p>Simply enter each user's name and username into the textarea, separated by a new line, in the form <code>Forename Surname,username</code>.</p>
        <p>The user's email address will automatically be set to their bath.ac.uk email, using their username, and they will be given a randomly-generated password.</p>
        <h3>User Account Types</h3>
        <p>Both Single and Multiple User modes allow you to customise the type of account each user will be given.</p>
        <dl>
            <dt>Standard</dt>
            <dd>A normal Backstage member. These can access all the usual areas.</dd>
            <dt>Committee</dt>
            <dd>On top of all the functionality of "Standard" members, these also have access to the admin functions. These users aren't linked to the committee page.</dd>
            <dt>Associate</dt>
            <dd>Associates have the same permissions as "Standard" members - they just appear in various member lists as associates.</dd>
            <dt>SU Officer</dt>
            <dd>Members of the SU are restricted in what they can access - they are only given a very basic overview of our events diary.</dd>
        </dl>
    @endsection

    @section('modal.footer')
        <div class="text-center">
            <button class="btn btn-success" id="closeHelpModal">
                <span class="fa fa-thumbs-up"></span>
                <span>Got it!</span>
            </button>
        </div>
    @endsection

    @include('partials.modal.modal', ['class' => 'modal-md', 'id' => 'helpModal'])
@endsection