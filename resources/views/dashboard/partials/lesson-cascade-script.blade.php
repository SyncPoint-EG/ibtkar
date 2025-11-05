@once
    <script>
        (function (global, $) {
            if (!$) {
                return;
            }

            function isFunction(value) {
                return typeof value === 'function';
            }

            function showToast(message) {
                if (!message) {
                    return;
                }

                if (global.toastr && isFunction(global.toastr.error)) {
                    global.toastr.error(message);
                } else {
                    console.error(message);
                    if (global.alert) {
                        global.alert(message);
                    }
                }
            }

            global.initLessonCascade = function (options) {
                if (!options) {
                    return;
                }

                const $stage = $(options.stageSelector);
                const $grade = $(options.gradeSelector);
                const $course = $(options.courseSelector);
                const $chapter = $(options.chapterSelector);
                const $lesson = $(options.lessonSelector);

                if (!$stage.length || !$grade.length || !$course.length || !$chapter.length || !$lesson.length) {
                    return;
                }

                const placeholder = options.placeholder || 'Select';
                const routes = options.routes || {};
                const messages = options.messages || {};
                const prefill = options.prefill || {};

                const onLessonChange = isFunction(options.onLessonChange)
                    ? options.onLessonChange
                    : function () {};

                const onCourseChange = isFunction(options.onCourseChange)
                    ? options.onCourseChange
                    : function () {};

                const courseQueryParams = isFunction(options.courseQueryParams)
                    ? options.courseQueryParams
                    : function () {
                        return {};
                    };

                function populateSelect($select, items, selectedId) {
                    const normalizedSelected = selectedId !== undefined && selectedId !== null
                        ? String(selectedId)
                        : null;

                    $select.empty().append(`<option value="">${placeholder}</option>`);

                    if (Array.isArray(items)) {
                        items.forEach(function (item) {
                            const value = item && typeof item === 'object'
                                ? (item.id ?? item.value ?? item.key)
                                : item;

                            if (value === undefined || value === null) {
                                return;
                            }

                            const label = item && typeof item === 'object'
                                ? (item.name ?? item.title ?? item.label ?? value)
                                : value;

                            const isSelected = normalizedSelected !== null && String(value) === normalizedSelected
                                ? ' selected'
                                : '';

                            $select.append(`<option value="${value}"${isSelected}>${label}</option>`);
                        });
                    }

                    $select.prop('disabled', false);
                }

                function resetSelect($select) {
                    $select.empty().append(`<option value="">${placeholder}</option>`);
                    $select.prop('disabled', true);
                }

                function buildRoute(template, replacement) {
                    if (!template || replacement === undefined || replacement === null) {
                        return null;
                    }

                    return template
                        .replace('__stage__', replacement)
                        .replace('__grade__', replacement)
                        .replace('__course__', replacement)
                        .replace('__chapter__', replacement)
                        .replace('__lesson__', replacement)
                        .replace('__id__', replacement);
                }

                function loadStages(selectedId) {
                    if (!routes.stages) {
                        populateSelect($stage, [], selectedId);
                        return $.Deferred().resolve().promise();
                    }

                    resetSelect($stage);

                    return $.get(routes.stages)
                        .done(function (data) {
                            populateSelect($stage, data, selectedId);
                        })
                        .fail(function () {
                            showToast(messages.stagesError || 'Failed to load stages.');
                        });
                }

                function loadGrades(stageId, selectedId) {
                    if (!routes.grades || !stageId) {
                        resetSelect($grade);
                        return $.Deferred().resolve().promise();
                    }

                    resetSelect($grade);

                    const url = buildRoute(routes.grades, stageId) || routes.grades;

                    return $.get(url)
                        .done(function (data) {
                            populateSelect($grade, data, selectedId);
                        })
                        .fail(function () {
                            showToast(messages.gradesError || 'Failed to load grades.');
                        });
                }

                function loadCourses(stageId, gradeId, selectedId) {
                    if (!routes.courses || !stageId || !gradeId) {
                        resetSelect($course);
                        return $.Deferred().resolve().promise();
                    }

                    resetSelect($course);

                    const params = Object.assign({}, courseQueryParams() || {}, {
                        stage_id: stageId,
                        grade_id: gradeId,
                    });

                    return $.get(routes.courses, params)
                        .done(function (data) {
                            populateSelect($course, data, selectedId);
                            if (selectedId) {
                                onCourseChange(selectedId);
                            }
                        })
                        .fail(function () {
                            showToast(messages.coursesError || 'Failed to load courses.');
                        });
                }

                function loadChapters(courseId, selectedId) {
                    if (!routes.chapters || !courseId) {
                        resetSelect($chapter);
                        return $.Deferred().resolve().promise();
                    }

                    resetSelect($chapter);

                    const url = buildRoute(routes.chapters, courseId) || routes.chapters;

                    return $.get(url)
                        .done(function (data) {
                            populateSelect($chapter, data, selectedId);
                        })
                        .fail(function () {
                            showToast(messages.chaptersError || 'Failed to load chapters.');
                        });
                }

                function loadLessons(chapterId, selectedId) {
                    if (!routes.lessons || !chapterId) {
                        resetSelect($lesson);
                        return $.Deferred().resolve().promise();
                    }

                    resetSelect($lesson);

                    const url = buildRoute(routes.lessons, chapterId) || routes.lessons;

                    return $.get(url)
                        .done(function (data) {
                            populateSelect($lesson, data, selectedId);
                            if (selectedId) {
                                onLessonChange(selectedId);
                            }
                        })
                        .fail(function () {
                            showToast(messages.lessonsError || 'Failed to load lessons.');
                        });
                }

                function clearLessonSelection() {
                    onLessonChange('');
                }

                function clearCourseSelection() {
                    onCourseChange('');
                }

                $stage.on('change', function () {
                    const stageId = $(this).val();
                    resetSelect($grade);
                    resetSelect($course);
                    resetSelect($chapter);
                    resetSelect($lesson);
                    clearCourseSelection();
                    clearLessonSelection();

                    if (!stageId) {
                        return;
                    }

                    loadGrades(stageId);
                });

                $grade.on('change', function () {
                    const gradeId = $(this).val();
                    const stageId = $stage.val();
                    resetSelect($course);
                    resetSelect($chapter);
                    resetSelect($lesson);
                    clearCourseSelection();
                    clearLessonSelection();

                    if (!stageId || !gradeId) {
                        return;
                    }

                    loadCourses(stageId, gradeId);
                });

                $course.on('change', function () {
                    const courseId = $(this).val();
                    resetSelect($chapter);
                    resetSelect($lesson);
                    clearLessonSelection();
                    onCourseChange(courseId || '');

                    if (!courseId) {
                        return;
                    }

                    loadChapters(courseId);
                });

                $chapter.on('change', function () {
                    const chapterId = $(this).val();
                    resetSelect($lesson);
                    clearLessonSelection();

                    if (!chapterId) {
                        return;
                    }

                    loadLessons(chapterId);
                });

                $lesson.on('change', function () {
                    onLessonChange($(this).val() || '');
                });

                resetSelect($grade);
                resetSelect($course);
                resetSelect($chapter);
                resetSelect($lesson);

                loadStages(prefill.stage_id).done(function () {
                    const stageId = prefill.stage_id;
                    const gradeId = prefill.grade_id;
                    const courseId = prefill.course_id;
                    const chapterId = prefill.chapter_id;
                    const lessonId = prefill.lesson_id;

                    if (!stageId) {
                        return;
                    }

                    loadGrades(stageId, gradeId).done(function () {
                        if (!gradeId) {
                            return;
                        }

                        loadCourses(stageId, gradeId, courseId).done(function () {
                            if (!courseId) {
                                return;
                            }

                            loadChapters(courseId, chapterId).done(function () {
                                if (!chapterId) {
                                    return;
                                }

                                loadLessons(chapterId, lessonId);
                            });
                        });
                    });
                });
            };
        })(window, window.jQuery);
    </script>
@endonce
