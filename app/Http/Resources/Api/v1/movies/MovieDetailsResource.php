<?php

namespace App\Http\Resources\Api\v1\movies;

use App\Http\Resources\Api\v1\author\AuthorResource;
use App\Http\Resources\Api\v1\genres\GenresResource;
use App\Http\Resources\Api\v1\tag\TagResource;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'               => $this->id,
            'title'            => $this->title,
            'summary'          => $this->summary,
            'cover_image'      =>$this->cover_image,
            'imdb_ratings'     => $this->imdb_ratings,
            'pdf_download_link'=> $this->pdf_download_link,
            'publish_status'   => $this->publish_status,
            'comment'          => $this->comment,
            'genres'           => GenresResource::collection($this->genres),
            'authors'          => AuthorResource::collection($this->authors),
            'tags'             => TagResource::collection($this->tags),
            'created_at'       => $this->created_at,
            'updated_at'       => $this->updated_at,
        ];
    }
}
