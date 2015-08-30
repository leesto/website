<div id="userSkills">
    <div id="categoryAccordian">
        @foreach($skillCategories as $category)
            <div class="panel panel-default category">
                <h1 class="panel-heading">
                    <a data-toggle="collapse"
                       data-parent="#categoryAccordian"
                       href="#{{ $category->id ? "category_{$category->id}" : 'uncategorised' }}">{{ $category->name }}</a>
                </h1>

                <div class="panel-collapse collapse" id="{{ $category->id ? "category_{$category->id}" : 'uncategorised' }}">
                    <table class="table">
                        <tbody>
                            @if(count($category->skills) > 0)
                                @foreach($category->skills as $skill)
                                    <tr>
                                        <td class="skill-name">
                                            <a class="grey" href="{{ route('training.skills.view', $skill->id) }}">{{ $skill->name }}</a>
                                        </td>
                                        <td class="skill-level">
                                            @if($user->hasSkill($skill))
                                                {!! \App\TrainingSkill::getProficiencyHtml($user->getSkill($skill)->level) !!}
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
        @endforeach
    </div>
</div>