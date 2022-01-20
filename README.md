Php - Laravel - Equivalent of https://github.com/mikeyriver2/gpuCrawler
<br /> Purpose: To Test the difference in response time of laravel vs django.
<br /> Function: Scrape GPU listings from tipidpc
```
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
```
Laravel response time for 1 page ~ 350ms (2x the time of django). Most likely due to laravel performing more parsing in responses and inputs, which at makes data handling easier (for me at least)
