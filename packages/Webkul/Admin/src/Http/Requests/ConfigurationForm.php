<?php

namespace Webkul\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Webkul\Core\Rules\Decimal;
use Webkul\Core\Rules\PhoneNumber;
use Webkul\Core\Rules\PostCode;

class ConfigurationForm extends FormRequest
{
    /**
     * Determine if the Configuration is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = collect(request()->input('keys', []))->mapWithKeys(function ($item) {
            $data = json_decode($item, true);

            return collect($data['fields'])->mapWithKeys(function ($field) use ($data) {
                $key = "{$data['key']}.{$field['name']}";

                // Check delete key exist in the request
                if (! $this->has("{$key}.delete")) {
                    // Special validation for gateway_charge field
                    if ($field['name'] === 'gateway_charge') {
                        return [$key => 'nullable|numeric|min:0|max:100'];
                    }

                    return [$key => $this->getValidationRules($field['validation'] ?? 'nullable')];
                }

                return [];
            })->toArray();
        })->toArray();

        return $rules;
    }

    /**
     * Transform validation rules into an array and map custom validation rules
     *
     * @param  string|array  $validation
     * @return array
     */
    protected function getValidationRules($validation)
    {
        $validations = is_array($validation) ? $validation : explode('|', $validation);

        return array_map(function ($rule) {
            return match ($rule) {
                'phone'    => new PhoneNumber,
                'postcode' => new PostCode,
                'decimal'  => new Decimal,
                default    => $rule,
            };
        }, $validations);
    }
}
