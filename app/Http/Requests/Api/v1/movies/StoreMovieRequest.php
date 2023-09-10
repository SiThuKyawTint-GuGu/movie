<?php

namespace App\Http\Requests\Api\v1\movies;

use App\Rules\ValidateTagIds;
use App\Rules\ValidateGenreIds;
use App\Rules\ValidateAuthorIds;
use App\Enums\MoviePublishStatusEnum;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;

class StoreMovieRequest extends FormRequest
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
            'cover_image'       => 'required|image',
            'imdb_ratings'      => 'required|string',
            'pdf_download_link' => 'required|string|max:255',
            'publish_status'    => ['required',new Enum(MoviePublishStatusEnum::class)],
            'genre_ids'         => ['required','json',new ValidateGenreIds()],
            'tag_ids'           => ['required','json',new ValidateTagIds()],
            'author_ids'        => ['required','json',new ValidateAuthorIds()],
        ];
    }
}
