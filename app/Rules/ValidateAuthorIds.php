<?php

namespace App\Rules;

use App\Models\Author;
use Illuminate\Contracts\Validation\Rule;

class ValidateAuthorIds implements Rule
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
        $genreIds = json_decode($value);
        if (!is_array($genreIds))
            return false;

        if(Author::whereIn('id', $genreIds)->count() !== count($genreIds))
            return false;

        return true;

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid Author ids in the JSON data.';

    }
}
