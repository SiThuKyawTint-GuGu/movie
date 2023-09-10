<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Author;
use App\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\v1\author\AuthorResource;
use App\Http\Requests\Api\v1\author\UpdateAuthorRequest;

class AuthorController extends Controller
{
    use ApiResponser;
    public function store(UpdateAuthorRequest $request)
    {
        $data = $this->getRequestData($request);
        $author = Author::create($data);
        return response()->json([
            'data' => new AuthorResource($author),
            'success' => true,
        ], 200);
    }

    private function getRequestData($request)
    {
        return [
            'name'     => $request->name,
            'image'    => $request->file('image') ? $request->file('image')->store('author/images') : null,
        ];
    }
}
