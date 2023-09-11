<?php

namespace App\Http\Controllers\Api\v1;

use Exception;
use App\Models\Movie;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\v1\movies\MovieResource;
use App\Http\Requests\Api\v1\movies\StoreMovieRequest;
use App\Http\Requests\Api\v1\movies\UpdateMovieRequest;
use App\Http\Resources\Api\v1\movies\MovieDetailsResource;
use App\Http\Resources\Api\v1\movies\MovieListResource;

class MovieController extends Controller
{
    use ApiResponser;

    /**
     * @OA\GET(
     *     path="/api/v1/movies/{id}",
     *     tags={"Movie"},
     *     summary="Get Detail",
     *     description="Get Detail",
     *     security={{"bearer_token":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="movie id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
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
    public function details($id){
        $movie = Movie::with('genres', 'tags', 'authors')->where('id', $id)->first();
        if (!$movie) return $this->errorResponse('No Data Found', 204);
        return $this->successResponse(new MovieDetailsResource($movie));
    }


    /**
     * @OA\GET(
     *     path="/api/v1/movies/related-movies/{id}",
     *     tags={"Movie"},
     *     summary="Get relatedMovie",
     *     description="Get relatedMovie",
     *     security={{"bearer_token":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="movie id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
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

    public function relatedMovie($id){
        $movie = Movie::with('genres','tags','authors')->where('id',$id)->first();
        if (!$movie) return $this->errorResponse('No Data Found', 204);
        $relatedGenres = $movie->genres?->pluck('id')->toArray() ?? [];
        $relatedTags = $movie->tags?->pluck('id')->toArray() ?? [];
        $relatedAuthors = $movie->autho?->pluck('id')->toArray() ?? [];

        $realatedMoives = Movie::where(function ($query) use ($relatedGenres) {
            $query->whereHas('genres', function ($query) use ($relatedGenres) {
                $query->whereIn('genres.id', $relatedGenres);
            });
        })->orWhere(function ($query) use ($relatedTags) {
            $query->whereHas('tags', function ($query) use ($relatedTags) {
                $query->whereIn('tags.id', $relatedTags);
            });
        })->orWhere(function ($query) use ($relatedAuthors) {
            $query->whereHas('authors', function ($query) use ($relatedAuthors) {
                $query->whereIn('authors.id', $relatedAuthors);
            });
        })->orWhere('imdb_ratings',$movie->imdb_ratings)->orderBy('id','desc')->limit(7)->get();

        return $this->successResponse($realatedMoives);
    }

    /**
     * @OA\GET(
     *     path="/api/v1/movies",
     *     tags={"Movie"},
     *     summary="Get MovieList",
     *     description="Get MovieList",
     *     security={{"bearer_token":{}}},
     *        @OA\Parameter(
     *         name="page",
     *         in="path",
     *         description="page",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *        @OA\Parameter(
     *         name="limit",
     *         in="path",
     *         description="limit",
     *         @OA\Schema(
     *             type="integer"
     *         )
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

    public function index(Request $request)
    {
        $request->validate([
            'page'         => 'required|numeric',
            'limit'        => 'required|numeric',
            'search_key'   => 'string',
            'genre_id'     => 'exists:genres,id',
            'sort_type'    => 'in:desc,asc',
            'sort_by'      => 'in:id,title,release_date,imdb_rating,position,popularity,updated_at',
        ]);

        $query = Movie::query();

        if (isset($request->search_key)) {
            $query = $this->search($request, $query);
        }


        if (isset($request->sort_by) && isset($request->sort_type)) {
            $query = $query->orderBy($request->sort_by, $request->sort_type);
        } else {
            $query = $query->orderBy('id', 'desc');
        }

        $results = $query->paginate($request->limit);

        $totalPages = ceil($results->total() / $request->limit);

        if (!$results->total())
            return $this->errorResponse('No Movie Found', 204);

        return response()->json([
            'total'         => $results->total(),
            'can_load_more' => $results->total() == 0 || $request->page >= $totalPages ? false : true,
            'data'          =>MovieListResource::collection($results),
        ], 200);
    }


    /**
     * @OA\POST(
     *     path="/api/v1/movies",
     *     tags={"Movie"},
     *     summary="Movie Create",
     *     description="Create Movie",
     *     security={{"bearer_token":{}}},
     *     @OA\RequestBody(
     *         description="Movie objects",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data", 
     *            @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="title",
     *                     description="title",
     *                     type="string",
     *                     example="love"
     *                 ),
     *                 @OA\Property(
     *                     property="summary",
     *                     description="summary",
     *                     type="string",
     *                     example="about fight"
     *                 ),
     *                 @OA\Property(
     *                     property="cover_image",
     *                     description="cover_image",
     *                     type="file",
     *                     example="image"
     *                 ),
     *                  @OA\Property(
     *                     property="imdb_ratings",
     *                     description="imdb_ratings",
     *                     type="string",
     *                     example="9"
     *                 ),
     *                  @OA\Property(
     *                     property="publish_status",
     *                     description="publish_status",
     *                     type="string",
     *                     example="1"
     *                 ),
     *                  @OA\Property(
     *                     property="pdf_download_link",
     *                     description="pdf_download_link",
     *                     type="string",
     *                     example="www.google.com"
     *                 ),
     *                  @OA\Property(
     *                     property="genre_ids",
     *                     description="genre_ids",
     *                     type="string",
     *                     example="[1,2]"
     *                 ),
     *                  @OA\Property(
     *                     property="tag_ids",
     *                     description="tag_ids",
     *                     type="string",
     *                     example="[1,2]"
     *                 ),
     *                   @OA\Property(
     *                     property="author_ids",
     *                     description="author_ids",
     *                     type="string",
     *                     example="[1]"
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

    public function store(StoreMovieRequest $request)
    {

        $data = $this->getRequestData($request, true);

        DB::beginTransaction();

        try {

            $movie = Movie::create($data);
            $movie->genres()->attach([1, 2]);
            $movie->tags()->attach(json_decode($request->tag_ids));
            $movie->authors()->attach(json_decode($request->author_ids));

            DB::commit();
            return $this->successResponse(new MovieResource($movie));
        } catch (Exception $e) {

            DB::rollback();
            Log::info($e);
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * @OA\PUT(
     *     path="/api/v1/movies/{id}",
     *     tags={"Movie"},
     *     summary="Movie Update",
     *     description="Update Movie",
     *     security={{"bearer_token":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="movie id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="Movie objects",
     *         required=true,
     *         @OA\MediaType(
     *         mediaType="application/json",
     *            @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="title",
     *                     description="title",
     *                     type="string",
     *                     example="love"
     *                 ),
     *                 @OA\Property(
     *                     property="summary",
     *                     description="summary",
     *                     type="string",
     *                     example="about fight"
     *                 ),
     *                 @OA\Property(
     *                     property="cover_image",
     *                     description="cover_image",
     *                     type="file",
     *                     example="image"
     *                 ),
     *                  @OA\Property(
     *                     property="imdb_ratings",
     *                     description="imdb_ratings",
     *                     type="string",
     *                     example="9"
     *                 ),
     *                  @OA\Property(
     *                     property="publish_status",
     *                     description="publish_status",
     *                     type="string",
     *                     example="1"
     *                 ),
     *                  @OA\Property(
     *                     property="pdf_download_link",
     *                     description="pdf_download_link",
     *                     type="string",
     *                     example="www.google.com"
     *                 ),
     *                  @OA\Property(
     *                     property="genre_ids",
     *                     description="genre_ids",
     *                     type="string",
     *                     example="[1,2]"
     *                 ),
     *                  @OA\Property(
     *                     property="tag_ids",
     *                     description="tag_ids",
     *                     type="string",
     *                     example="[1,2]"
     *                 ),
     *                   @OA\Property(
     *                     property="author_ids",
     *                     description="author_ids",
     *                     type="string",
     *                     example="[1]"
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

    public function update(UpdateMovieRequest $request, $id)
    {
        $movie = Movie::where('id', $id)->first();

        if (!$movie) return $this->errorResponse('No Data Found', 204);

        $data = $this->getRequestData($request, false);

        DB::beginTransaction();

        try {

            $movie->genres()->sync(json_decode($request->genre_ids));
            $movie->tags()->sync(json_decode($request->tag_ids));
            $movie->authors()->sync(json_decode($request->author_ids));

            $movie->update($data);

            DB::commit();
            return $this->successResponse(new MovieResource($movie->refresh()));
        } catch (Exception $e) {

            DB::rollback();
            Log::info($e);
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * @OA\DELETE(
     *     path="/api/v1/movies/{id}",
     *     tags={"Movie"},
     *     summary="Delete",
     *     description="Delete",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Movie id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *      security={{"bearer_token":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Successfully Deleted")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Level not found",
     *     @OA\JsonContent(
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */

    public function destroy($id)
    {
        $movie = Movie::where('id', $id)->first();

        if (!$movie) return $this->errorResponse('No Data Found', 204);

        DB::beginTransaction();

        try {

            $movie->genres()->detach();
            $movie->tags()->detach();
            $movie->authors()->detach();

            $movie->delete();

            DB::commit();

            return $this->successResponse("deleted Successfully");
        } catch (Exception $e) {

            DB::rollback();
            Log::info($e);
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    private function getRequestData($request, $create)
    {
        $data = [
            'title'             => $request->title,
            'summary'           => $request->summary,
            'cover_image'       => $request->cover_image,
            'imdb_ratings'      => $request->imdb_ratings,
            'pdf_download_link' => $request->pdf_download_link,
            'publish_status'    => $request->publish_status,
            'updated_at'        => now()
        ];
        if ($create) {
            $data['created_at'] = now();
            if ($request->hasFile('cover_image')) {
                $data['cover_image'] = $request->file('cover_image')->store('cover_images');
            } else {
                $data['cover_image'] = $request->cover_image;
            }
            if ($request->hasFile('pdf')) {
                $data['pdf_download_link'] = $request->file('pdf_download_link')->store('pdfs');
            } else {
                $data['pdf_download_link'] = $request->pdf;
            }
        };
        return $data;
    }
}
