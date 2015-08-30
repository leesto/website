@extends('app')

@section('title', 'Training Skills')

@section('stylesheets')
    @include('partials.tags.style', ['path' => 'partials/training'])
@endsection

@section('scripts')
    $('.nav-stacked').tabify();
    $modal.on('show.bs.modal', function(event) {
        var btn = $modal.find('#submitCategory');
        var formAction = $(event.relatedTarget).data('formAction');
        btn.data('formAction', formAction);

        if(formAction.indexOf('update') > -1) {
            btn.find('span').eq(1).text('Update');
        }
    });
@endsection

@section('content')
    <h1 class="page-header">@yield('title')</h1>
    <div class="container-fluid" id="listSkills">
        @foreach($skillCategories as $category)
            @include('training.skills._category')
        @endforeach
        <div>
            <div>
                @if($activeUser->isMember())
                    <a class="btn btn-success"
                       data-form-action="{{ route('training.skills.propose') }}"
                       data-toggle="modal"
                       data-target="#modal"
                       data-modal-class="modal-sm"
                       data-modal-template="propose_skill"
                       data-modal-title="Propose Skill Level">
                        <span class="fa fa-plus"></span>
                        <span>Propose skill level</span>
                    </a>
                @endif
                <a class="btn btn-success"
                   data-form-action="{{ route('training.skills.award') }}"
                   data-toggle="modal"
                   data-target="#modal"
                   data-modal-class="modal-sm"
                   data-modal-template="award_skill"
                   data-modal-title="Award Skill">
                    <span class="fa fa-user-plus"></span>
                    <span>Award skill</span>
                </a>
                @if($activeUser->isAdmin())
                    <a class="btn btn-danger"
                       data-toggle="modal"
                       data-target="#modal"
                       data-modal-class="modal-sm"
                       data-modal-template="revoke_skill">
                        <span class="fa fa-user-times"></span>
                        <span>Revoke skill</span>
                    </a>
                @endif
            </div>
            @if($activeUser->isAdmin())
                <div>
                    <button class="btn btn-success"
                            data-toggle="modal"
                            data-target="#modal"
                            data-modal-class="modal-sm"
                            data-modal-template="new_category"
                            data-form-action="{{ route('training.category.add') }}"
                            type="button">
                    <span class="fa fa-plus"></span>
                    <span>Add category</span>
                    </button>
                    <a class="btn btn-success" href="{{ route('training.skills.add') }}">
                        <span class="fa fa-plus"></span>
                        <span>Add skill</span>
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('modal')
    @if(Auth::check() && Auth::user()->isAdmin())
        <div data-type="modal-template" data-id="new_category">
            <div class="modal-header">
                <h1>Add a Category</h1>
            </div>
            <div class="modal-body">
                {!! Form::open(['class' => 'form-horizontal']) !!}
                <div class="form-group">
                    {!! Form::label('name', 'Name:', ['class' => 'col-xs-3 control-label']) !!}
                    <div class="col-xs-9">
                        {!! Form::text('name', null, ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-3"></div>
                    <div class="col-xs-9">
                        <button class="btn btn-success" data-type="submit-modal" id="submitCategory" type="button">
                            <span class="fa fa-check"></span>
                            <span>Add category</span>
                        </button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    @endif
    @include('training.skills.modal.award')
    @include('training.skills.modal.propose')
    @include('training.skills.modal.revoke')
@endsection