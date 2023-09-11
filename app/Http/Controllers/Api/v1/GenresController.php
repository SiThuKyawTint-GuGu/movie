<?php

namespace App\Http\Controllers\Api\v1;

use App\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\genres\UpdateGenresRequest;
use App\Http\Resources\Api\v1\genres\GenresResource;
use App\Models\Genre;

class GenresController extends Controller
{
    use ApiResponser;


    /**
     * @OA\POST(
     *     path="/api/v1/genres",
     *     tags={"Genres"},
     *     summary="Genres Create",
     *     description="Create Genres",
     *     security={{"bearer_token":{}}},
     *     @OA\RequestBody(
     *         description="Genres objects",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data", 
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="name",
     *                     description="name",
     *                     type="string",
     *                     example="Action"
     *                 ),
     *                 @OA\Property(
     *                     property="image",
     *                     description="image",
     *                     type="file",
     *                     example="image"
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input",
     *         @OA\JsonContent()
     *     )
     * )
     */


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
