<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ImageCropService
{


    private $client;
    private $api_token;

    private $headers;

    public function __construct()
    {

        $this->client = new Client(['base_uri' => 'https://api.tinify.com']);
        $this->api_token = base64_encode(env('API_KEY_TINIFI'));
        $this->headers = ['Authorization' => 'Basic '.$this->api_token, 'Content-Type' => 'application/json', 'Host' => 'api.tinify.com'];
    }


    private function send(UploadedFile $photo)
    {

        $body = $photo->get();

        $request = new Request('POST', '/shrink', $this->headers, $body);
        $promise = $this->client->send($request);

        return $promise;
    }


    private function getUriForSave($promise): string
    {
        $location = $promise->getHeader('Location');
        $uri_for_save = str_replace('https://api.tinify.com', '', $location[0]);

        return $uri_for_save;
    }



    public function save(UploadedFile $photo)
    {
        $body = ['resize' =>
            [
                'method' => 'cover',
                'width' => 70,
                'height' => 70,
            ]
        ];
        $request = new Request('GET', $this->getUriForSave($this->send($photo)), $this->headers, json_encode($body));
        $promise = $this->client->send($request);
        $filename = Str::random();
        $file = file_put_contents('../storage/app/public/images/'.$filename.'.jpg', $promise->getBody()->getContents());

        return 'images/'.$filename.'.jpg';
    }

}
