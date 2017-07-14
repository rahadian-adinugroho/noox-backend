<?php

namespace Noox\Http\Controllers\API;

use GuzzleHttp\Client;
use Noox\Exceptions\NewsAnalyzerException;
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
        if (is_null(config('noox.analyzer_base_url'))) {
            throw NewsAnalyzerException::analyzerUrlNotSet();
        }
        $this->client = new Client([
            'base_uri' => config('noox.analyzer_base_url'),
            'timeout'  => 20,
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
        if (! $article = $request->input('article')) {
            if (! $article = $this->getArticleFromUrl($request->input('src'))) {
                return $this->response->error("The supplied url is not supported yet.", 503);
            }
        }
        try {
            $response = $this->client
            ->post('analyze', [
            'form_params' => [
            'src'     => $request->input('src') ? parse_url( $request->input('src') , PHP_URL_HOST ) : ' ',
            'title'   => $request->input('title') ?: ' ',
            'article' => $article,
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

    /**
     * Extract article from given url.
     * 
     * @param  str $url
     * @return str
     */
    protected function getArticleFromUrl($url)
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            \Log::critical('NewsAnalyzerController: Article extraction from URL are not available in Windows environment.');
            return null;
        }
        $shDir = config('noox.news_extractor_dir');
        $shRes = explode("\n", shell_exec("cd {$shDir} && python3 nooxcrawler.py '{$url}'"));
        if (! $article = json_decode($shRes[0])) {
            return null;
        }
        return strip_tags($article->content);
    }
}
