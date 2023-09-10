<?php

namespace App\Http\Controllers\Api\v1;

use App\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\author\UpdateGenresRequest;
use App\Http\Resources\Api\v1\genres\GenresResource;
use App\Models\Genre;

class GenresController extends Controller
{
    use ApiResponser;
    public function store(UpdateGenresRequest $request)
    {
        $data = $this->getRequestData($request);
        $genres = Genre::create($data);
        return response()->json([
            'data' => new GenresResource($genres),
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
