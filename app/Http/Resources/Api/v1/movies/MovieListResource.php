<?php

namespace App\Http\Resources\Api\v1\movies;

use Illuminate\Http\Resources\Json\JsonResource;

class MovieListResource extends JsonResource
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
        'id'               => $this->id ,
        'title'            => $this->title,
        'summary'          => $this->summary,
        'cover_image'      => $this->cover_image,
        'imdb_ratings'     => $this->imdb_ratings,
        'pdf_download_link'=> $this->pdf_download_link,
        'publish_status'   => $this->publish_status,
        'comment'          => $this->comment,
        'created_at'       => $this->created_at,
        'updated_at'       => $this->updated_at,
        ];
    }
}
