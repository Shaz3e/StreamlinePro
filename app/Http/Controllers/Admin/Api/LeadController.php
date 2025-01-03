<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LeadController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            // Validate the request data
            $validated = $request->validate([
                'name' => 'required|string|max:50',
                'company' => 'nullable|string|max:50',
                'country' => 'nullable|string|max:50',
                'email' => 'required|email|max:255',
                'phone' => ['required', 'string', 'min:9', 'max:16', 'regex:/^\d+$/'], // Only digits allowed
                'product' => 'nullable|string|max:255',
                'message' => 'nullable|string|max:1000',
            ], [
                'phone.min' => 'Please enter a valid phone number.',
                'phone.max' => 'Please enter a valid phone number.',
            ]);

            Lead::create($validated);

            return response()->json([
                'message' => 'We have received your information and will contact you shortly.',
            ]);
        } catch (ValidationException $e) {
            // Catch validation errors and return the first one as the message
            $firstErrorMessage = collect($e->errors())->flatten()->first();  // Get the first validation error message

            // Return validation errors
            return response()->json([
                'message' => $firstErrorMessage,  // Show the first error message in the response
                'errors' => $e->errors(),  // Include all the validation errors for reference
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something went wrong. Please try again.',
            ], 500);
        }
    }
}
