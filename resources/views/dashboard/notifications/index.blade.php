@extends('dashboard.layouts.master')

@section('content')
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-8 col-12 mb-2">
                    <h3 class="content-header-title">إرسال إشعار جديد</h3>
                    <p class="text-muted">اختر الفئة المستهدفة ثم اكتب عنوان ورسالة الإشعار.</p>
                </div>
            </div>
            <div class="content-body">
                <section id="send-notification">
                    <div class="row">
                        <div class="col-12">
                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif

                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">بيانات الإشعار</h4>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('notifications.store') }}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="recipient_type">إرسال إلى</label>
                                                    <select name="recipient_type" id="recipient_type" class="form-control" required>
                                                        <option value="students" @selected(old('recipient_type', 'students') === 'students')>الطلاب</option>
                                                        <option value="guardians" @selected(old('recipient_type', 'students') === 'guardians')>أولياء الأمور</option>
                                                        <option value="both" @selected(old('recipient_type', 'students') === 'both')>الطلاب وأولياء الأمور</option>
                                                    </select>
                                                    <small class="text-muted">في حالة اختيار "الطلاب وأولياء الأمور" سيتم الإرسال للطرفين.</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="stage_ids">المراحل الدراسية</label>
                                                    <select name="stage_ids[]" id="stage_ids" class="form-control select2" multiple data-placeholder="اختر المراحل">
                                                        @foreach($stages as $stage)
                                                            <option value="{{ $stage->id }}" @selected(collect(old('stage_ids', []))->contains($stage->id))>{{ $stage->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <small class="text-muted">اترك القائمة فارغة للإرسال إلى جميع المراحل.</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="grade_ids">الصفوف</label>
                                                    <select name="grade_ids[]" id="grade_ids" class="form-control select2" multiple data-placeholder="اختر الصفوف">
                                                        @foreach($grades as $grade)
                                                            <option value="{{ $grade->id }}" @selected(collect(old('grade_ids', []))->contains($grade->id))>{{ $grade->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <small class="text-muted">اترك القائمة فارغة للإرسال إلى جميع الصفوف.</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="division_ids">الشُعب</label>
                                                    <select name="division_ids[]" id="division_ids" class="form-control select2" multiple data-placeholder="اختر الشُعب">
                                                        @foreach($divisions as $division)
                                                            <option value="{{ $division->id }}" @selected(collect(old('division_ids', []))->contains($division->id))>{{ $division->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <small class="text-muted">اترك القائمة فارغة للإرسال إلى جميع الشُعب.</small>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="title">عنوان الإشعار</label>
                                                    <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="body">نص الإشعار</label>
                                                    <textarea name="body" id="body" rows="4" class="form-control" required>{{ old('body') }}</textarea>
                                                    <small class="text-muted">اكتب الرسالة التي ستظهر للمستخدمين في التطبيق.</small>
                                                </div>
                                            </div>
                                            <div class="col-12 d-flex justify-content-end">
                                                <button type="submit" class="btn btn-primary">
                                                    إرسال الإشعار
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')
    <script>
        $(document).ready(function () {
            $('.select2').select2({
                width: '100%',
                placeholder: function(){
                    return $(this).data('placeholder');
                },
                allowClear: true
            });
        });
    </script>
@endsection
