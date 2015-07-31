<!-- Text field for 'title' -->
<div class="form-group @include('partials.form.error-class', ['name' => 'title'])">
    {!! Form::label('title', 'Page Title:', ['class' => 'control-label']) !!}
    {!! Form::text('title', null, ['class' => 'form-control']) !!}
    @include('partials.form.input-error', ['name' => 'title'])
</div>

<!-- Text field for 'slug' -->
<div class="form-group @include('partials.form.error-class', ['name' => 'slug'])">
    {!! Form::label('slug', 'Page Slug:', ['class' => 'control-label']) !!}
    {!! Form::text('slug', null, ['class' => 'form-control']) !!}
    @include('partials.form.input-error', ['name' => 'slug'])
</div>

<!-- Textarea for 'content' -->
<div class="form-group @include('partials.form.error-class', ['name' => 'content'])">
    {!! Form::label('content', 'Content:', ['class' => 'control-label']) !!}
    {!! Form::textarea('content', null, ['class' => 'form-control tinymce']) !!}
    @include('partials.form.input-error', ['name' => 'content'])
</div>

<!-- Select field for 'published' -->
<div class="form-group @include('partials.form.error-class', ['name' => 'published'])">
    {!! Form::label('published', 'Published:', ['class' => 'control-label']) !!}
    {!! Form::select('published', [0 => 'No', 1 => 'Yes'], null, ['class' => 'form-control', 'id' => 'published']) !!}
    @include('partials.form.input-error', ['name' => 'published'])
</div>

<!-- Select field for 'user_id' -->
<div class="form-group @include('partials.form.error-class', ['name' => 'user_id'])">
    {!! Form::label('user_id', 'Author:', ['class' => 'control-label']) !!}
    {!! Form::select('user_id', $users, null, ['class' => 'form-control']) !!}
    @include('partials.form.input-error', ['name' => 'user_id'])
</div>