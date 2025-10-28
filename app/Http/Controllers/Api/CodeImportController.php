<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Imports\UpdateCodesPriceImport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class CodeImportController extends Controller
{
    /**
     * Handle an uploaded Excel file to update code prices.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv'],
        ]);

        $file = $validated['file'];

        if (! $file->isValid()) {
            throw ValidationException::withMessages([
                'file' => 'Uploaded file is not valid.',
            ]);
        }

        $import = new UpdateCodesPriceImport(70.0);

        try {
            Excel::import($import, $file);
        } catch (\Throwable $exception) {
            throw ValidationException::withMessages([
                'file' => 'Unable to read the provided file.',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Code prices updated successfully.',
            'updated' => $import->getUpdatedCount(),
        ]);
    }
}
