<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric', // Alterado para 'numeric'
            'unit_of_measure' => 'required|in:unidades,litros,quilos,metros',
            'quantity' => 'required|integer|min:1',
            'inventories_id' => 'required|exists:inventories,id'
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'price' => $this->formatPrice($this->price),
        ]);
    }

    /**
     * Format the price to a valid float value.
     *
     * @param string $price
     * @return float
     */
    private function formatPrice($price)
    {
        return (float)str_replace(',', '.', str_replace('.', '', $price));
    }
}
