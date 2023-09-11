<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Movie;
use App\Models\Comment;
use App\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\v1\genres\GenresResource;
use App\Http\Resources\Api\v1\comment\CommentResource;
use App\Http\Requests\Api\v1\author\UpdateGenresRequest;
use App\Http\Requests\Api\v1\comment\UpdateCommentRequest;

class CommentController extends Controller
{
    use ApiResponser;

    /**
     * @OA\POST(
     *     path="/api/v1/comments",
     *     tags={"Comment"},
     *     summary="Comment Create",
     *     description="Create Comment",
     *     security={{"bearer_token":{}}},
     *     @OA\RequestBody(
     *         description="Comment objects",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *            @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="movie_id",
     *                     description="movie_id",
     *                     type="integer",
     *                     example="1"
     *                 ),
     *                  @OA\Property(
     *                     property="comment",
     *                     description="comment",
     *                     type="string",
     *                     example="good"
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

    public function store(UpdateCommentRequest $request)
    {
        $user = $request->user();
        $data = $this->getRequestData($request);
        $data['user_id'] = $request->user()->id;
        $data['email'] = $user ? $user->email : null;
        $comment = Comment::create($data);
        return response()->json([
            'data' => new CommentResource($comment),
            'success' => true,
        ], 200);
    }

    /**
     * @OA\GET(
     *     path="/api/v1/comments/list",
     *     tags={"Comment"},
     *     summary="Get Comment",
     *     description="Get Comment",
     *     security={{"bearer_token":{}}},
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

    public function list(){
        $list = Movie::with(['comment.user'])->get();
        return response()->json([
            'data' =>  CommentResource::collection($list),
            'success' => true,
        ], 200);
    }

    private function getRequestData($request)
    {
        return [
            'movie_id'     => $request->movie_id,
            'comment'     => $request->comment,
        ];
    }
}
