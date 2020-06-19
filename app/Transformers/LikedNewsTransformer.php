<?php
namespace Noox\Transformers;

use Noox\Models\News;
use League\Fractal\TransformerAbstract;

class LikedNewsTransformer extends TransformerAbstract
{
    public function transform(News $news)
    {
        return [
            'news_id'   => (int) $news->id,
            'title'     => $news->title,
            'pubtime'   => $news->pubtime,
            'source'    => $news->source,
        ];
    }
}