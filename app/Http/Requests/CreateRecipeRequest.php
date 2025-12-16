<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateRecipeRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'title' => 'required|string|max:255',
      'description' => 'nullable|string',
      'ingredients' => 'required|array|min:1',
      'ingredients.*' => 'required|string',
      'instructions' => 'required|string',
    ];
  }

  /**
   * Get custom error messages for validation rules.
   *
   * @return array<string, string>
   */
  public function messages(): array
  {
    return [
      'title.required' => 'The title field is required.',
      'title.max' => 'The title must not exceed 255 characters.',
      'ingredients.required' => 'The ingredients field is required.',
      'ingredients.array' => 'The ingredients must be an array.',
      'ingredients.min' => 'At least one ingredient is required.',
      'ingredients.*.required' => 'Each ingredient must not be empty.',
      'ingredients.*.string' => 'Each ingredient must be a string.',
      'instructions.required' => 'The instructions field is required.',
    ];
  }

  /**
   * Handle a failed validation attempt.
   */
  protected function failedValidation(Validator $validator): void
  {
    throw new HttpResponseException(
      response()->json([
        'status' => 'error',
        'message' => 'Validation failed',
        'errors' => $validator->errors(),
      ], 422)
    );
  }
}
