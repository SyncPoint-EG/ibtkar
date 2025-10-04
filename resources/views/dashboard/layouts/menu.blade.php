{{--<li class=" nav-item"><a href="index.html"><i class="icon-home3"></i><span data-i18n="nav.dash.main" class="menu-title">Dashboard</span><span class="tag tag tag-primary tag-pill float-xs-right mr-2">2</span></a>--}}
{{--    <ul class="menu-content">--}}
{{--        <li><a href="index.html" data-i18n="nav.dash.main" class="menu-item">Dashboard</a>--}}
{{--        </li>--}}
{{--        <li><a href="dashboard-2.html" data-i18n="nav.dash.main" class="menu-item">Dashboard 2</a>--}}
{{--        </li>--}}
{{--    </ul>--}}
{{--</li>--}}
<li class=" nav-item"><a href="{{route('dashboard')}}"><i class="icon-home"></i><span data-i18n="nav.changelog.main" class="menu-title">Dashboard</span></a>
</li>

@can('view_reports')
    <li class=" nav-item has-sub"><a href="#"><i class="icon-file-text"></i><span data-i18n="nav.reports.main" class="menu-title">{{__('dashboard.reports.title')}}</span></a>
        <ul class="menu-content">
            <li><a href="{{ route('reports.students') }}" class="menu-item">{{__('dashboard.reports.students_report')}}</a></li>
            <li><a href="{{ route('reports.teachers') }}" class="menu-item">{{__('dashboard.reports.teachers_report')}}</a></li>
            <li><a href="{{ route('reports.payments') }}" class="menu-item">{{__('dashboard.reports.payments_report')}}</a></li>
            <li><a href="{{ route('reports.codes') }}" class="menu-item">{{__('dashboard.reports.codes_report')}}</a></li>
        </ul>
    </li>
@endcan

@can('view_role')
    <li class=" nav-item"><a href="{{ route('roles.index') }}"><i class="icon-shield"></i><span data-i18n="nav.roles.main" class="menu-title">Roles</span></a>
    </li>
@endcan

{{--@can('view_permission')--}}
{{--    <li class=" nav-item"><a href="{{ route('permissions.index') }}"><i class="icon-key"></i><span data-i18n="nav.permissions.main" class="menu-title">Permissions</span></a>--}}
{{--    </li>--}}
{{--@endcan--}}

@can('view_user')
    <li class=" nav-item"><a href="{{ route('users.index') }}"><i class="icon-users"></i><span data-i18n="nav.users.main" class="menu-title">{{__('dashboard.user.title_plural')}}</span></a>
    </li>
@endcan

@can('view_governorate')
    <li class=" nav-item"><a href="{{ route('governorates.index') }}"><i class="icon-globe"></i><span data-i18n="nav.governorates.main" class="menu-title">{{__('dashboard.governorate.title_plural')}}</span></a>
    </li>
@endcan

@can('view_district')
    <li class=" nav-item"><a href="{{ route('districts.index') }}"><i class="icon-map-pin"></i><span data-i18n="nav.districts.main" class="menu-title">{{__('dashboard.district.title_plural')}}</span></a>
    </li>
@endcan

@can('view_center')
    <li class=" nav-item"><a href="{{ route('centers.index') }}"><i class="icon-office"></i><span data-i18n="nav.centers.main" class="menu-title">{{__('dashboard.center.title_plural')}}</span></a>
    </li>
@endcan

@can('view_stage')
    <li class=" nav-item"><a href="{{ route('stages.index') }}"><i class="icon-layers"></i><span data-i18n="nav.stages.main" class="menu-title">{{__('dashboard.stage.title_plural')}}</span></a>
    </li>
@endcan

@can('view_grade')
    <li class=" nav-item"><a href="{{ route('grades.index') }}"><i class="icon-star"></i><span data-i18n="nav.grades.main" class="menu-title">{{__('dashboard.grade.title_plural')}}</span></a>
    </li>
@endcan

@can('view_division')
    <li class=" nav-item"><a href="{{ route('divisions.index') }}"><i class="icon-grid"></i><span data-i18n="nav.divisions.main" class="menu-title">{{__('dashboard.division.title_plural')}}</span></a>
    </li>
@endcan

@can('view_student')
    <li class=" nav-item"><a href="{{ route('students.index') }}"><i class="icon-user"></i><span data-i18n="nav.students.main" class="menu-title">{{__('dashboard.student.title_plural')}}</span></a>
    </li>
@endcan

{{-- Remove duplicate student menu item --}}

@can('view_guardian')
    <li class=" nav-item"><a href="{{ route('guardians.index') }}"><i class="icon-user-check"></i><span data-i18n="nav.guardians.main" class="menu-title">{{__('dashboard.guardian.title_plural')}}</span></a>
    </li>
@endcan

@can('view_subject')
    <li class=" nav-item"><a href="{{ route('subjects.index') }}"><i class="icon-book"></i><span data-i18n="nav.subjects.main" class="menu-title">{{__('dashboard.subject.title_plural')}}</span></a>
    </li>
@endcan

@can('view_teacher')
    <li class=" nav-item"><a href="{{ route('teachers.index') }}"><i class="icon-user-tie"></i><span data-i18n="nav.teachers.main" class="menu-title">{{__('dashboard.teacher.title_plural')}}</span></a>
    </li>
@endcan

@can('view_educationtype')
    <li class=" nav-item"><a href="{{ route('education-types.index') }}"><i class="icon-graduation-cap"></i><span data-i18n="nav.education-types.main" class="menu-title">{{__('dashboard.education_type.title_plural')}}</span></a>
    </li>
@endcan

@can('view_semister')
    <li class=" nav-item"><a href="{{ route('semisters.index') }}"><i class="icon-calendar"></i><span data-i18n="nav.semisters.main" class="menu-title">{{__('dashboard.semister.title_plural')}}</span></a>
    </li>
@endcan

@can('view_course')
    <li class=" nav-item"><a href="{{ route('courses.index') }}"><i class="icon-folder"></i><span data-i18n="nav.courses.main" class="menu-title">{{__('dashboard.course.title_plural')}}</span></a>
    </li>
@endcan

@can('view_chapter')
    <li class=" nav-item"><a href="{{ route('chapters.index') }}"><i class="icon-bookmark"></i><span data-i18n="nav.chapters.main" class="menu-title">{{__('dashboard.chapter.title_plural')}}</span></a>
    </li>
@endcan

@can('view_lesson')
    <li class=" nav-item"><a href="{{ route('lessons.teachers') }}"><i class="icon-play-circle"></i><span data-i18n="nav.lessons.main" class="menu-title">{{__('dashboard.lesson.title_plural')}}</span></a>
    </li>
@endcan

@can('view_exam')
    <li class=" nav-item"><a href="{{ route('exams.index') }}"><i class="icon-clipboard"></i><span data-i18n="nav.exams.main" class="menu-title">{{__('dashboard.exam.title_plural')}}</span></a>
    </li>
@endcan

@can('view_homework')
    <li class=" nav-item"><a href="{{ route('homework.index') }}"><i class="icon-pencil-square"></i><span data-i18n="nav.homework.main" class="menu-title">{{__('dashboard.homework.title_plural')}}</span></a>
    </li>
@endcan

@can('view_center_exam')
    <li class=" nav-item"><a href="{{ route('center-exams.index') }}"><i class="icon-file-text"></i><span data-i18n="nav.center_exams.main" class="menu-title">Center Exams</span></a>
    </li>
@endcan
@can('update_settings')
    <li class=" nav-item"><a href="{{ route('settings.bulkEdit') }}"><i class="icon-cog"></i><span data-i18n="nav.homework.main" class="menu-title">{{__('dashboard.settings.title')}}</span></a>
    </li>
@endcan

@can('edit_action_points')
    <li class=" nav-item"><a href="{{ route('action-points.edit') }}"><i class="icon-trophy"></i><span data-i18n="nav.action-points.main" class="menu-title">{{ trans('dashboard.action_points.title') }}</span></a>
    </li>
@endcan

@can('edit_reward_points')
    <li class=" nav-item"><a href="{{ route('reward-points.edit') }}"><i class="icon-gift"></i><span data-i18n="nav.reward-points.main" class="menu-title">{{ trans('dashboard.reward_points.title') }}</span></a>
    </li>
@endcan

@can('edit_luck_wheel')
    <li class=" nav-item"><a href="{{ route('luck-wheel.edit') }}"><i class="icon-disc"></i><span data-i18n="nav.luck-wheel.main" class="menu-title">{{ trans('dashboard.luck_wheel.title') }}</span></a>
    </li>
@endcan

@can('view_banner')
<li class=" nav-item"><a href="{{ route('banners.index') }}"><i class="icon-image"></i><span data-i18n="nav.banners.main" class="menu-title">{{__('dashboard.banner.title_plural')}}</span></a>
</li>
@endcan
@can('view_code')
<li class=" nav-item"><a href="{{ route('codes.index') }}"><i class="icon-ticket"></i><span data-i18n="nav.codes.main" class="menu-title">{{__('dashboard.code.title_plural')}}</span></a>
</li>
@endcan
@can('view_payment_approval')
<li class=" nav-item"><a href="{{ route('payment_approvals.index') }}"><i class="icon-credit-card"></i><span data-i18n="nav.payment_approvals.main" class="menu-title">{{__('dashboard.payment_approval.title_plural')}}</span></a>
</li>
@endcan
@can('view_charge_approval')
<li class=" nav-item"><a href="{{ route('charge_approvals.index') }}"><i class="icon-money"></i><span data-i18n="nav.charge_approvals.main" class="menu-title">{{__('dashboard.charge_approval.title_plural')}}</span></a>
</li>
@endcan

