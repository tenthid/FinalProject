<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class StoreProductRequest extends FormRequest
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
            'product_name' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'brand_id' => 'required|integer|exists:brands,id',
            'category_id' => 'required|integer|exists:categories,id',
        ];
    }

    public function messages() {
        return [
            'product_name.required' => 'wajib ada, harus berupa teks string, panjang maksimal adalah 50 karakter',
            'price.required' => 'wajib ada',
            'price.numeric' => 'Harga harus berupa angka.',
            'stock.required' => 'wajib ada',
            'stock.integer' => 'Stok harus berupa bilangan bulat.',
            'brand_id.required' => 'wajib ada',
            'brand_id.exists' => 'Id brand tidak ditemukan',
            'brand_id.integer' => 'Id dari brand harus berupa bilangan bulat.',
            'category_id.required' => 'wajib ada',
            'category_id.integer' => 'Id dari category harus berupa bilangan bulat.',
            'category_id.exists' => 'Id categories tidak ditemukan',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'status' => 'error',
            'errors' => $validator->errors(),
        ], 422);

        // return response()->json($response);

        throw new ValidationException($validator, $response);
    }
}
