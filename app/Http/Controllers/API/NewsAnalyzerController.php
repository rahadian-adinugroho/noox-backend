<?php

namespace Noox\Http\Controllers\API;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class NewsAnalyzerController extends BaseController
{
    /**
     * Guzzle HTTP client.
     * @var GuzzleHttp\Client
     */
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'http://192.168.43.30:10000/',
            'timeout'  => 10.0,
            ]);
    }

    /**
     * Analyze news.
     * Submit a news article
     * @param  \Noox\Http\Requests\ArticleAnalysisRequest $request [description]
     * @return [type]                                              [description]
     */
    public function analyze(\Noox\Http\Requests\ArticleAnalysisRequest $request)
    {
        $promise = $this->client
        ->post('analyze', [
            'form_params' => [
            'src'     => parse_url( $request->input('src') , PHP_URL_HOST ),
            'title'   => $request->input('title'),
            'article' => $request->input('article'),
            ]
            ]);

        $promise->then(
            function (ResponseInterface $res) {
                $res = json_decode($res->getBody());
                return response()->json($res);
            },
            function (RequestException $e) {
                return $this->response->errorInternal('Article analysis service unavailable.');
            }
        );
    }
}
