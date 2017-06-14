<?php

namespace Noox\Http\Controllers\API;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

/**
 * @resource News Analyzer
 *
 * Analyze news article.
 */
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
            'base_uri' => config('noox.analyzer_base_url'),
            'timeout'  => 10,
            ]);
    }

    /**
     * Analyze news.
     * Submit a news article to be analyzed. The timeout is 10 seconds.
     *
     * @param  \Noox\Http\Requests\ArticleAnalysisRequest $request [description]
     * @return \Illuminate\Http\Response
     */
    public function analyze(\Noox\Http\Requests\ArticleAnalysisRequest $request)
    {
        try {
            $response = $this->client
            ->post('analyze', [
            'form_params' => [
            'src'     => $request->input('src') ? parse_url( $request->input('src') , PHP_URL_HOST ) : ' ',
            'title'   => $request->input('title') ?: ' ',
            'article' => $request->input('article'),
            ]
            ]);

            $response = json_decode($response->getBody());
            return response()->json($response);
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            $this->response->error('Article analysis service unavailable.', 503);
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            $this->response->error('Article analysis service unavailable.', 503);
        }
    }
}
