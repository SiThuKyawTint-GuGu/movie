<?php

namespace App\Http\Controllers\Api\v1;

use App\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\author\UpdateTagRequest;
use App\Http\Resources\Api\v1\tag\TagResource;
use App\Models\Tag;

class TagController extends Controller
{
    use ApiResponser;
    public function store(UpdateTagRequest $request)
    {
        $data = $this->getRequestData($request);
        $genres = Tag::create($data);
        return response()->json([
            'data' => new TagResource($genres),
            'success' => true,
        ], 200);
    }

    private function getRequestData($request)
    {
        return [
            'name'     => $request->name
        ];
    }
}
