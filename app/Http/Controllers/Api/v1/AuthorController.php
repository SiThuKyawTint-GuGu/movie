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

    /**
     * @OA\POST(
     *     path="/api/v1/authors",
     *     tags={"Author"},
     *     summary="Author Create",
     *     description="Create Author",
     *     security={{"bearer_token":{}}},
     *     @OA\RequestBody(
     *         description="Author objects",
     *         required=true,
     *         @OA\MediaType(
     *            mediaType="multipart/form-data", 
     *            @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="name",
     *                     description="name",
     *                     type="string",
     *                     example="love"
     *                 ),
     *                 @OA\Property(
     *                     property="image",
     *                     description="image",
     *                     type="file",
     *                     example="image"
     *                 ),
     *             )
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *   @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input",
     *   @OA\JsonContent()
     *     )
     * )
     */


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
