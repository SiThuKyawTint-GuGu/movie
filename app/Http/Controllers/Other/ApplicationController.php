<?php
namespace App\Http\Controllers\Other;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Storage;
use League\MimeTypeDetection\FinfoMimeTypeDetector;

class ApplicationController extends Controller {
    public function file($filename) {
        try {
            $contents = Storage::get($filename);
            $fileSize = Storage::size($filename);
            $detector = new FinfoMimeTypeDetector();
            $mimeType = $detector->detectMimeType($filename, $contents);
            return response()->make(
                $contents,
                200,
                [
                    'Content-Type'   => $mimeType,
                    'Pragma'         => 'public',
                    'Expires'        => '-1',
                    'Cache-Control'  => 'max-age=604800',
                    'Content-Length' => $fileSize,
                ]
            );
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function zubtitle($filename) {
        try {
            $contents = Storage::get($filename);
            $fileSize = Storage::size($filename);
            $detector = new FinfoMimeTypeDetector();
            $mimeType = $detector->detectMimeType($filename, $contents);
            return response()->make(
                $contents,
                200,
                [
                    'Content-Type'   => $mimeType,
                    'Pragma'         => 'public',
                    'Expires'        => '-1',
                    'Cache-Control'  => 'max-age=604800',
                    'Content-Length' => $fileSize,
                ]
            );
        } catch (Exception $e) {
            throw $e;
        }
    }
}
