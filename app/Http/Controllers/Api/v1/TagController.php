<?php

namespace App\Http\Controllers\Api\v1;

use App\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\tag\UpdateTagRequest;
use App\Http\Resources\Api\v1\tag\TagResource;
use App\Models\Tag;

class TagController extends Controller
{
    use ApiResponser;
    /**
     * @OA\POST(
     *     path="/api/v1/tags",
     *     tags={"Tags"},
     *     summary="Tag Create",
     *     description="Create Tag",
     *     security={{"bearer_token":{}}},
     *     @OA\RequestBody(
     *         description="Tags objects",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *            @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="name",
     *                     description="name",
     *                     type="string",
     *                     example="love"
     *                 ),
     *
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
    public function store(UpdateTagRequest $request)
    {
        $data = $this->getRequestData($request);
        $tags = Tag::create($data);
        return response()->json([
            'data' => new TagResource($tags),
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
