@section('modal.header', '<h1>Add a Quote</h1>')
@section('modal.content')
    {!! Form::open() !!}
    {{-- Text field 'culprit' --}}
    <div class="form-group">
        <div class="input-group">
            <span class="input-group-addon"><span class="fa fa-user"></span></span>
            {!! Form::text('culprit', null, ['class' => 'form-control', 'placeholder' => 'Who said it?']) !!}
        </div>
    </div>

    {{-- Text field for the date --}}
    <div class="form-group">
        <div class="input-group">
            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
            {!! Form::text('date', null, ['class' => 'form-control', 'id' => 'newQuoteDate', 'placeholder' => 'When did they say it?']) !!}
        </div>
    </div>

    {{-- Textarea for the quote --}}
    <div class="form-group">
        <div class="input-group textarea">
            <span class="input-group-addon"><span class="fa fa-quote-left"></span></span>
            {!! Form::textarea('quote', null, ['class' => 'form-control', 'placeholder' => 'What was said?', 'rows' => 5]) !!}
        </div>
    </div>
    {!! Form::close() !!}
@endsection

@section('modal.footer')
    <button class="btn btn-success" id="addQuoteModal">
        <span class="fa fa-plus"></span>
        <span>Add quote</span>
    </button>
    <button class="btn btn-danger" id="cancelQuoteModal">
        <span class="fa fa-undo"></span>
        <span>Cancel</span>
    </button>
@endsection

@include('partials.modal.small', ['id' => 'newQuoteModal'])