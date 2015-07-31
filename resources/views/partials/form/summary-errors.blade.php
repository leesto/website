@if($errors->default->has('summary'))
    <div class="alert alert-warning form-error">
        <span class="fa fa-exclamation"></span>
        <span>{{ $errors->default->first('summary') }}</span>
    </div>
@endif