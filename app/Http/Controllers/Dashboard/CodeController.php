<?php

namespace App\Http\Controllers\Dashboard;

use App\Exports\CodesExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\CodeRequest;
use App\Models\Code;
use App\Services\CodeService;
use App\Services\TeacherService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class CodeController extends Controller
{
    protected CodeService $codeService;

    protected TeacherService $teacherService;

    public function __construct(CodeService $codeService, TeacherService $teacherService)
    {
        $this->codeService = $codeService;
        $this->teacherService = $teacherService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return View|JsonResponse
     */
    public function index(Request $request)
    {
        $filters = $request->only(['teacher_id', 'expires_at', 'for', 'created_at_from', 'created_at_to', 'price', 'code', 'code_classification']);
        $codes = $this->codeService->search($filters, $request->get('per_page', 15), ['teacher', 'payment.student']);
        $teachers = $this->teacherService->getAll();
        $codeClassifications = $this->codeService->getUniqueCodeClassifications();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $codes,
                'message' => 'Codes retrieved successfully.',
            ]);
        }

        return view('dashboard.codes.index', compact('codes', 'teachers', 'filters', 'codeClassifications'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View|JsonResponse
     */
    public function create(Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Ready to create new Code.',
            ]);
        }

        $teachers = $this->teacherService->getAll();

        return view('dashboard.codes.create', compact('teachers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return RedirectResponse|JsonResponse
     */
    public function store(CodeRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $numberOfCodes = $request->input('number_of_codes', 1);

            if ($numberOfCodes > 1) {
                for ($i = 0; $i < $numberOfCodes; $i++) {
                    $validatedData['code'] = $this->codeService->generateUniqueCode();
                    $validatedData['number_of_uses'] = 0;
                    $this->codeService->create($validatedData);
                }
            } else {
                $validatedData['number_of_uses'] = 0;
                if (! $request->code) {
                    $validatedData['code'] = $this->codeService->generateUniqueCode();
                }
                $this->codeService->create($validatedData);
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Code(s) created successfully.',
                ], 201);
            }

            return redirect()->route('codes.index')
                ->with('success', 'Code(s) created successfully.');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating Code: '.$e->getMessage(),
                ], 500);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating Code: '.$e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @return View|JsonResponse
     */
    public function show(Code $code, Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $code,
                'message' => 'Code retrieved successfully.',
            ]);
        }

        return view('dashboard.codes.show', compact('code'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return View|JsonResponse
     */
    public function edit(Code $code, Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $code,
                'message' => 'Code ready for editing.',
            ]);
        }

        $teachers = $this->teacherService->getAll();

        return view('dashboard.codes.edit', compact('code', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return RedirectResponse|JsonResponse
     */
    public function update(CodeRequest $request, Code $code)
    {
        try {
            $validatedData = $request->validated();
            $validatedData['number_of_uses'] = 1;
            $updatedCode = $this->codeService->update($code, $validatedData);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $updatedCode,
                    'message' => 'Code updated successfully.',
                ]);
            }

            return redirect()->route('codes.index')
                ->with('success', 'Code updated successfully.');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating Code: '.$e->getMessage(),
                ], 500);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating Code: '.$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return RedirectResponse|JsonResponse
     */
    public function destroy(Code $code, Request $request)
    {
        try {
            $this->codeService->delete($code);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Code deleted successfully.',
                ]);
            }

            return redirect()->route('codes.index')
                ->with('success', 'Code deleted successfully.');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting Code: '.$e->getMessage(),
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Error deleting Code: '.$e->getMessage());
        }
    }

    /**
     * Get filtered/searched results
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $results = $this->codeService->search($request->all());

            return response()->json([
                'success' => true,
                'data' => $results,
                'message' => 'Search completed successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Search error: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Bulk delete resources
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        try {
            $ids = $request->input('ids', []);
            $deleted = $this->codeService->bulkDelete($ids);

            return response()->json([
                'success' => true,
                'message' => "Successfully deleted {$deleted} codes.",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bulk delete error: '.$e->getMessage(),
            ], 500);
        }
    }

    public function export(Request $request)
    {
        $filters = $request->only(['teacher_id', 'expires_at', 'for', 'created_at_from', 'created_at_to', 'code', 'code_classification']);

        return Excel::download(new CodesExport($filters), 'codes.xlsx');
    }
}
