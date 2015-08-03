@section('modal.header', '<h1>Add a Committee Position</h1>')
@section('modal.content')
    {!! Form::open() !!}
    {{-- Text field for the name --}}
    <div class="form-group">
        <div class="input-group">
            <span class="input-group-addon"><span class="fa fa-user"></span></span>
            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'What is the role called?']) !!}
        </div>
    </div>

    {{-- Text field for the email --}}
    <div class="form-group">
        <div class="input-group">
            <span class="input-group-addon"><span class="fa fa-at"></span></span>
            {!! Form::input('email', 'email', null, ['class' => 'form-control', 'placeholder' => 'What is the role\'s email address?']) !!}
        </div>
    </div>

    {{-- Textarea for the description --}}
    <div class="form-group">
        <div class="input-group textarea">
            <span class="input-group-addon"><span class="fa fa-quote-left"></span></span>
            {!! Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => 'Describe the role', 'rows' => 5]) !!}
        </div>
    </div>

    {{-- Select field for the user --}}
    <div class="form-group">
        {!! Form::label('user_id', 'Assign user:') !!}
        {!! Form::select('user_id', $users, null, ['class' => 'form-control']) !!}
    </div>

    {{-- Select field for order --}}
    <div class="form-group">
        {!! Form::label('order', 'Add this role:') !!}
        {!! Form::select('order', $order, null, ['class' => 'form-control']) !!}
    </div>

    {{-- Hidden input for the id --}}
    {!! Form::input('hidden', 'id', null) !!}
    {!! Form::close() !!}
@endsection

@section('modal.footer')
    <button class="btn btn-success" id="modalSubmit">
        <span class="fa fa-plus"></span>
        <span>Add role</span>
    </button>
    <button class="btn btn-danger" id="modalCancel">
        <span class="fa fa-undo"></span>
        <span>Cancel</span>
    </button>
@endsection

@include('partials.modal.small', ['id' => 'roleModal'])