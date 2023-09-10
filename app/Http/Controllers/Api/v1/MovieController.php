<?php

namespace App\Http\Controllers\Api\v1;

use App\Enums\MoviePublishStatusEnum;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\movies\StoreMovieRequest;

class MovieController extends Controller
{
    public function create(StoreMovieRequest $request){

        $data = $this->getRequestData($request,true);

        DB::beginTransaction();

        try {

            $movie = Movie::create($data);

            $movie->genres()->attach(json_decode($request->genre_ids));
            $movie->tags()->attach(json_decode($request->tag_ids));
            $movie->authors()->attach(json_decode($request->author_ids));

            DB::commit();
            return $this->successResponse(new MovieReource($movie));

        } catch (Exception $e) {

            DB::rollback();
            Log::info($e);
            return $this->errorResponse($e->getMessage(),500);

        }
    }

    public function update(StoreMovieRequest $request,$id){

        $movie = Movie::where('id',$id)->first();

        if(!$movie) return $this->errorResponse('No Data Found',204);

        $data = $this->getRequestData($request,false);

        DB::beginTransaction();

        try {

            $movie = $movie->update($data);

            $movie->genres()->sync(json_decode($request->genre_ids));
            $movie->tags()->sync(json_decode($request->tag_ids));
            $movie->authors()->sync(json_decode($request->author_ids));

            DB::commit();
            return $this->successResponse(new MovieReource($movie));

        } catch (Exception $e) {

            DB::rollback();
            Log::info($e);
            return $this->errorResponse($e->getMessage(),500);

        }
    }

    public function destroy($id){

        $movie = Movie::where('id',$id)->first();

        if(!$movie) return $this->errorResponse('No Data Found',204);

        DB::beginTransaction();

        try {

            $movie->genres()->detach();
            $movie->tags()->detach();
            $movie->authors()->detach();

            $movie->destroy();

            DB::commit();

            return $this->successResponse(new MovieReource($movie));

        } catch (Exception $e) {

            DB::rollback();
            Log::info($e);
            return $this->errorResponse($e->getMessage(),500);

        }
    }

    private function getRequestData($request,$create){
        $data = [
            'title'             => $request->title,
            'summary'           => $request->summary,
            'cover_image'       => $request->cover_image,
            'imdb_ratings'      => $request->imdb_ratings,
            'pdf_download_link' => $request->pdf_download_link,
            'publish_status'    => MoviePublishStatusEnum::cases($request->publish_status),
            'updated_at'        => now()
        ];
        if($create) $data['created_at'] = now();
        return $data;
    }
}
