<div class="col-sm-6 position">
    @if($role && Auth::check() && Auth::user()->can('admin'))
        <a class="btn btn-warning btn-sm edit-button"
           data-toggle="modal"
           data-target="#roleModal"
           data-url="{{ route('committee.edit') }}"
           data-method="edit"
           data-role-id="{{ $role->id }}"
           data-role-name="{{ $role->name }}"
           data-role-email="{{ $role->email }}"
           data-role-desc="{{ $role->description }}"
           data-role-user-id="{{ $role->user_id }}"
           data-role-order="{{ $role->order }}"
           title="Edit this role">
            <span class="fa fa-pencil"></span>
        </a>
    @endif
    <div class="title">{{ $role ? $role->name : '&nbsp;' }}</div>
    <div class="name">{{ $role ? $role->user->name : '&nbsp;' }}</div>
    <div class="picture">
        @if($role)
            <img class="img-rounded" src="{{ $role->user->getAvatarUrl() }}">
        @endif
    </div>
    <div class="email">
        @if($role)
            <span class="fa fa-envelope"></span>
            <a href="mailto:{{ $role->email }}">{{ $role->email }}</a>
        @endif
    </div>
    <div class="description">
        @if($role)
            {!! nl2br($role->description) !!}
        @endif
    </div>
</div>