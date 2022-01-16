<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class CrawlerController extends Controller
{
    public function index(Request $request) {
        $client = new Client();
        $response = $client->get('https://tipidpc.com/catalog.php?cat=4&sec=s&page=1');
        $responseBody = $response->getBody();

        $html = (string)$responseBody;
        $crawler = new Crawler($html);
        $items = $crawler->filter('#item-search-results > li');
        $itemsText = $items->text();

        $nodeValues = $items->each(function (Crawler $node, $i) {
            $title = $node->children('table h2')->first()->text();
            $price = $node->children('table h3')->first()->text();
            $price = floatval(str_replace('P', '', $price));
            $listUrl = $node->children('table h2 a')->first()->extract(['href'])[0];

            return [
                'title' => $title,
                'price' => $price,
                'url' => $listUrl
            ];
        });

        return ($nodeValues);
    }
}
