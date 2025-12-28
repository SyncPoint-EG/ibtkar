<?php

namespace App\Services;

use App\Http\Requests\PaymentRequest;
use App\Models\Chapter;
use App\Models\Code;
use App\Models\Course;
use App\Models\GradePlan;
use App\Models\Lesson;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Watch;
use App\Traits\GamificationTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class StudentPaymentService
{
    use GamificationTrait;

    public function __construct(private readonly WhatsappNotificationService $whatsappNotificationService)
    {
    }

    public function create(PaymentRequest $request, Student $student): JsonResponse
    {
        $gradePlan = null;

        if ($request->plan_type) {
            $gradePlan = GradePlan::query()
                ->where('stage_id', $student->stage_id)
                ->where('grade_id', $student->grade_id)
                ->where('is_active', true)
                ->first();

            if (! $gradePlan) {
                return response()->json([
                    'status' => false,
                    'message' => 'لم يتم إعداد خطة لهذه المرحلة والصف بعد.',
                ], 404);
            }
        }

        if (in_array($request->payment_method, ['instapay', 'wallet'])) {
            $existing_payment = Payment::where('student_id', $student->id)->where(function ($query) use ($request, $gradePlan) {
                if ($request->lesson_id) {
                    $query->where('lesson_id', $request->lesson_id);
                } elseif ($request->chapter_id) {
                    $query->where('chapter_id', $request->chapter_id);
                } elseif ($request->course_id) {
                    $query->where('course_id', $request->course_id);
                } elseif ($request->plan_type && $gradePlan) {
                    $query->where('plan_type', $request->plan_type)
                        ->where('grade_plan_id', $gradePlan->id);
                }
            })->whereIn('payment_method', ['instapay', 'wallet'])->where('payment_status', Payment::PAYMENT_STATUS['pending'])->first();

            if ($existing_payment) {
                return response()->json([
                    'status' => true,
                    'message' => 'تمت عملية الدفع بنجاح و في انتظار موافقة الادمن ',
                ], 201);
            }
        }

        try {
            DB::beginTransaction();
            $points = null;
            $amount = $this->getAmount($request, $gradePlan);

            if ($amount === null) {
                DB::rollBack();

                return response()->json([
                    'status' => false,
                    'message' => 'لم يتم العثور على السعر المطلوب.',
                ], 404);
            }

            $validated = $request->validated();
            $validated['student_id'] = $student->id;
            $validated['amount'] = $amount;
            $validated['total_amount'] = $amount;

            if ($gradePlan) {
                $validated['grade_plan_id'] = $gradePlan->id;
                $validated['plan_type'] = $request->plan_type ?? GradePlan::TYPE_GENERAL;
                $validated['stage_id'] = $student->stage_id;
                $validated['grade_id'] = $student->grade_id;
            }

            $purchaseType = $this->resolvePurchaseType($request);
            $code = null;

            if ($request->payment_method == 'code') {
                $code = Code::where('code', $request->payment_code)->first();

                if (! $code) {
                    DB::rollBack();

                    return response()->json([
                        'success' => false,
                        'message' => 'Code not found',
                    ]);
                }

                if ($code->for === 'charge' || $code->for !== $purchaseType) {
                    DB::rollBack();

                    return response()->json([
                        'success' => false,
                        'message' => 'Code is not applicable',
                    ]);
                }

                if ($code->price != $amount) {
                    DB::rollBack();

                    return response()->json([
                        'status' => false,
                        'message' => 'قيمة الكود لا تتناسب مع السعر المطلوب',
                    ]);
                }

                if ($code->number_of_uses >= 1 || ($code->expires_at != null && $code->expires_at < now())) {
                    DB::rollBack();

                    return response()->json([
                        'status' => false,
                        'message' => 'الكود مستخدم من قبل',
                    ]);
                }
            }

            if ($request->course_id) {
                $course = Course::find($request->course_id);
                if (! $course) {
                    DB::rollBack();

                    return response()->json([
                        'status' => false,
                        'message' => 'Course not found',
                    ], 404);
                }

                $points = $this->givePoints($student, 'purchase_course');
                $chapterIds = $course->chapters->pluck('id')->toArray();
                $lessonIds = Lesson::whereIn('chapter_id', $chapterIds)->pluck('id')->toArray();
                Watch::where('student_id', $student->id)->whereIn('lesson_id', $lessonIds)->update(['count' => 3]);
            }

            if ($request->chapter_id) {
                $lessonIds = Lesson::where('chapter_id', $request->chapter_id)->pluck('id')->toArray();
                $chapter = Chapter::find($request->chapter_id);
                if (! $chapter) {
                    DB::rollBack();

                    return response()->json([
                        'status' => false,
                        'message' => 'Chapter not found',
                    ], 404);
                }

                $points = $this->givePoints($student, 'purchase_chapter');
                Watch::where('student_id', $student->id)->whereIn('lesson_id', $lessonIds)->update(['count' => 3]);
            }

            if ($request->lesson_id) {
                $lesson = Lesson::find($request->lesson_id);
                if (! $lesson) {
                    DB::rollBack();

                    return response()->json([
                        'status' => false,
                        'message' => 'Lesson not found',
                    ], 404);
                }

                $points = $this->givePoints($student, 'purchase_lesson');

                Watch::where('student_id', $student->id)->where('lesson_id', $request->lesson_id)->update(['count' => 3]);
            }

            if ($code) {
                $code->increment('number_of_uses');
                $code->save();
            }

            if (in_array($request->payment_method, ['instapay', 'wallet'])) {
                $validated['payment_status'] = Payment::PAYMENT_STATUS['pending'];
            } else {
                $validated['payment_status'] = Payment::PAYMENT_STATUS['approved'];
            }

            if ($request->payment_method == 'ibtkar_wallet') {
                if ($student->wallet < $amount) {
                    DB::rollBack();

                    return response()->json([
                        'status' => false,
                        'message' => 'لا يوجد لديك رصيد كافي في المحفظة للشراء',
                    ]);
                }
                $student->wallet -= $amount;
                $student->save();
            }

            $payment = Payment::create($validated);
            DB::commit();

            if ($payment->payment_status === Payment::PAYMENT_STATUS['approved']) {
                $this->whatsappNotificationService->sendLessonPurchaseNotification($payment);
            }

            if (in_array($request->payment_method, ['code', 'ibtkar_wallet'])) {
                return response()->json([
                    'status' => true,
                    'message' => 'تمت عملية الشراء بنجاح',
                    'rewarded_points' => $points,
                ]);
            } elseif (in_array($request->payment_method, ['instapay', 'wallet'])) {
                return response()->json([
                    'status' => true,
                    'message' => 'تمت عملية الشراء بنجاح و في انتظار موافقة الادمن',
                    'rewarded_points' => $points,
                ], 201);
            }
        } catch (\Exception $exception) {
            DB::rollBack();

            return response()->json(['status' => false, 'message' => $exception->getMessage()]);
        }

        return response()->json([
            'status' => true,
            'message' => 'تمت عملية الشراء بنجاح',
        ]);
    }

    private function getAmount(PaymentRequest $request, ?GradePlan $gradePlan): ?float
    {
        $amount = null;

        if ($request->plan_type && $gradePlan) {
            $amount = $gradePlan->general_plan_price;
        } elseif ($request->course_id) {
            $amount = Course::find($request->course_id)?->price;
        } elseif ($request->chapter_id) {
            $amount = Chapter::find($request->chapter_id)?->price;
        } elseif ($request->lesson_id) {
            $amount = Lesson::find($request->lesson_id)?->price;
        }

        return $amount;
    }

    private function resolvePurchaseType(PaymentRequest $request): ?string
    {
        if ($request->plan_type) {
            return 'grade_plan';
        }

        if ($request->lesson_id) {
            return 'lesson';
        }

        if ($request->chapter_id) {
            return 'chapter';
        }

        if ($request->course_id) {
            return 'course';
        }

        return null;
    }
}
