<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ImageUrl implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        try {
            $headers = get_headers($value, 1);
            if (strpos($headers['Content-Type'], 'image/') !== false) {
                return true;
            }
            return false;
        }
        catch(\Throwable $e) {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be image url.';
    }
}
