<?php

namespace App\Http\Requests\Api\v1\movies;

use App\Rules\ValidateTagIds;
use App\Rules\ValidateGenreIds;
use App\Rules\ValidateAuthorIds;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMovieRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title'             => 'required|string|max:255',
            'summary'           => 'required|string',
            'imdb_ratings'      => 'required|string',
            'publish_status'    => ['required'],
            'genre_ids'         => ['required', 'json', new ValidateGenreIds()],
            'tag_ids'           => ['required', 'json', new ValidateTagIds()],
            'author_ids'        => ['required', 'json', new ValidateAuthorIds()],
        ];
    }
}
