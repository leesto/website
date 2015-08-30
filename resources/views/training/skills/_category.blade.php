<div class="panel panel-default category">
    <h1 class="panel-heading">
        <a data-toggle="collapse" href="#{{ $category->id ? "category_{$category->id}" : 'uncategorised' }}">{{ $category->name }}</a>
        @if($activeUser->isAdmin() && $category->id)
            <div class="btn-group btn-group-sm">
                <button class="btn btn-warning btn-sm"
                        data-toggle="modal"
                        data-target="#modal"
                        data-modal-class="modal-sm"
                        data-modal-template="new_category"
                        data-modal-title="Edit Category"
                        data-form-data="{{ json_encode(['name' => $category->name]) }}"
                        data-form-action="{{ route('training.category.update', $category->id) }}"
                        type="button"
                        title="Edit this category">
                    <span class="fa fa-pencil"></span>
                </button>
                <button class="btn btn-danger btn-sm"
                        data-submit-ajax="{{ route('training.category.delete', $category->id) }}"
                        data-submit-confirm="Are you sure you want to delete this category? Any skills will become 'uncategorised'"
                        type="button"
                        title="Delete this category">
                    <span class="fa fa-remove"></span>
                </button>
            </div>
        @endif
    </h1>

    <div class="panel-collapse collapse{{ count($category->skills) > 0 ? ' in' : '' }}" id="{{ $category->id ? "category_{$category->id}" : 'uncategorised' }}">
        <table class="table table-striped">
            <tbody>
                @if(count($category->skills) > 0)
                    @foreach($category->skills as $skill)
                        <tr>
                            <td class="skill-name">
                                <a class="grey" href="{{ route('training.skills.view', $skill->id) }}">{{ $skill->name }}</a>
                            </td>
                            <td class="skill-buttons">
                                @if(Auth::check() && Auth::user()->isAdmin())
                                    <div class="btn-group">
                                        <button class="btn btn-danger btn-sm"
                                                data-submit-ajax="{{ route('training.skills.delete', $skill->id) }}"
                                                data-submit-confirm="Are you sure you want to delete this skill?"
                                                data-success-url="{{ Request::url() }}"
                                                type="button">
                                            <span class="fa fa-remove"></span>
                                        </button>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="2"><em>&ndash; there aren't any skills in this category &ndash;</em></td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>