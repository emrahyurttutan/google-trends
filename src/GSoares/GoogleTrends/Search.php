<?php
namespace GSoares\GoogleTrends;

use GSoares\GoogleTrends\Dto\SearchResultDto;
use GSoares\GoogleTrends\Dto\TermDto;
use Guzzle\Http\Client;
use Guzzle\Http\Exception\BadResponseException;

/**
 * @author Gabriel Felipe Soares <gabrielfs7@gmail.com>
 * @package GSoares\GoogleTrends
 *
 * @uthor Emrah YURTTUTAN <emrah@yurttutan.net>
 *
 */
class Search
{

    const RISING_QUERIES = 'RISING_QUERIES_0_0';
    const TOP_QUERIES = 'TOP_QUERIES_0_0';
    const SEARCH_URL = 'http://www.google.com/trends/fetchComponent';
    const TRENDS_URL = 'http://www.google.com/trends/explore';
    const HOT_TREND_URL = 'http://www.google.com/trends/hottrends/atom/hourly';
    const KEYWORD_SUGGEST_URL = 'http://www.google.com/complete/search?output=toolbar';
    /**
     * @var string
     */
    private $location;

    /**
     * @var string
     */
    private $language;

    /**
     * @var string
     */
    private $category;

    /**
     * @var string
     */
    private $query;

    /**
     * @var string
     */
    private $cid;

    /**
     * @var Client
     */
    private $guzzleClient;

    /**
     * @var \DateTime
     */
    private $monthInterval;

    /**
     * @var \DateTime
     */
    private $lastDays;
	
	/**
     * @var \array
     */
    private $proxyList = array(
		/*'88.245.138.180',
		'78.186.215.196',
		'178.245.194.42',
		'176.53.3.250',
		'176.237.20.141',
		'31.145.83.206',
		'213.74.244.75',
		'31.145.83.198',
		'91.93.174.185	',
		'78.187.113.170',
		'95.0.178.5',
		'141.196.67.212',*/
        '165.234.102.177:8080',
        '112.91.208.78:9999',
        '120.52.73.173:8080',
        '124.133.230.254:80',
        '97.77.104.22:80',
        '61.162.223.41:9797',
        '223.27.194.66:8080',
        '120.52.73.173:80',
        '112.74.114.68:82',
        '202.106.16.36:3128',
        '97.77.104.22:3128',
        '218.191.247.51:8380',
        '112.91.208.78:9999',
        '115.127.22.149:8080',
        '109.196.127.35:8888',
        '182.23.83.6:8080',
        '162.8.230.7:11180',
        '202.166.195.113:8080',
        '58.9.99.41:3128',
        '167.114.224.6:80',
        '123.84.13.240:8118'

	);

    public function __construct(Client $guzzleClient = null)
    {
        $this->query = [];

        $this->setLanguage('tr-TR')
            ->setLocation('tr')
            ->searchTopQueries()
            ->setMonthInterval((new \DateTime('now'))->modify('-12 months'), new \DateTime('now'));
		$proxySec = $this->proxyList[rand(0,(count($this->proxyList)-1))];
		
		$this->guzzleClient = $guzzleClient ?: new Client(self::SEARCH_URL,array(
            'config' => [
                'curl' => [
                    'CURLOPT_PROXY' => $proxySec ,
                    'CURLOPT_HTTPPROXYTUNNEL' => 1,
                    'CURLOPT_FOLLOWLOCATION' => 1,
                    'CURLOPT_HEADER' => 1
                ],
            ]
        ));
    }

    /**
     * @param $initialMonth
     * @param $finalMonth
     * @return $this
     */
    public function setMonthInterval(\DateTime $initialMonth, \DateTime $finalMonth)
    {
        if ($initialMonth->format('Ym') === $finalMonth->format('Ym')) {
            $this->monthInterval = $initialMonth->format('m/Y');
        }

        if ($initialMonth->format('Ym') !== $finalMonth->format('Ym')) {
            $monthsDifference = ($initialMonth->format('m') - $finalMonth->format('m')) * -1;
            $yearsDifference = ($initialMonth->format('Y') - $finalMonth->format('Y')) * 12;

            $this->monthInterval = $initialMonth->format('m/Y') . '+' . (($yearsDifference - $monthsDifference) * -1) . 'm';
        }

        return $this;
    }

    /**
     * @param $lastDays
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setLastDays($lastDays)
    {
        if (!in_array($lastDays, $allowedDays = [7, 30, 90, 365])) {
            throw new \InvalidArgumentException(
                'Allowed days: ' . implode(', ', $allowedDays) .
                '. Supplied: ' . strval($lastDays)
            );
        }

        if ($lastDays == 7) {
            $this->lastDays = 'today+' . intval($lastDays) . '-d';
        }

        if ($lastDays != 7) {
            $this->lastDays = 'today+' . ceil(bcdiv($lastDays, 30)) . '-m';
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function searchRisingQueries()
    {
        $this->cid = self::RISING_QUERIES;

        return $this;
    }

    /**
     * @return $this
     */
    public function searchTopQueries()
    {
        $this->cid = self::TOP_QUERIES;

        return $this;
    }

    /**
     * @param $language
     * @return $this
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @param $category
     * @return $this
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @param $location
     * @return $this
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @param $word
     * @return $this
     */
    public function addWord($word)
    {
        $this->query[$word] = $word;

        return $this;
    }

    /**
     * @return SearchResultDto
     * @throws \Guzzle\Http\Exception\BadResponseException
     */
    public function search()
    {
        $searchUrl = $this->prepareSearchUrl();
		
		$proxySec = $this->proxyList[rand(0,(count($this->proxyList)-1))];
		
        $response = $this->guzzleClient
            ->setBaseUrl($this->prepareSearchUrl(),array(
                'config' => [
                    'curl' => [
                        'CURLOPT_PROXY' => $proxySec ,
                        'CURLOPT_HTTPPROXYTUNNEL' => 1,
                        'CURLOPT_FOLLOWLOCATION' => 1,
                        'CURLOPT_HEADER' => 1
                    ],
                ]
            ))
            ->get()
            ->send();

        $responseBody = substr($response->getBody(true), 62, -2);
        //print_r($response);
        if (!$responseDecoded = json_decode($responseBody)) {
            throw new BadResponseException(
                'Status code ' . $response->getStatusCode() .
                ': ' . strip_tags($response->getBody(true))
            );
        }

        if ($responseDecoded->status == 'error') {
            $errorMessage = [];

            foreach ($responseDecoded->errors as $error) {
                $errorMessage[] = $error->reason . '. ' . $error->message . '. ' . $error->detailed_message;
            }

            throw new BadResponseException(implode(PHP_EOL, $errorMessage));
        }

        return $this->createDto($responseDecoded, $searchUrl);
    }

    /**
     * @return string
     * @throws \Guzzle\Http\Exception\BadResponseException
     */
    public function searchJson()
    {
        return json_encode($this->search(), JSON_UNESCAPED_SLASHES);
    }

    /**
     * @param \stdClass $responseDecoded
     * @param $searchUrl
     * @return SearchResultDto
     */
    private function createDto(\stdClass $responseDecoded, $searchUrl)
    {
        $searchResult = new SearchResultDto();
        $searchResult->searchUrl = $searchUrl;

        foreach ($responseDecoded->table->rows as $row) {
            $dto = new TermDto();
            $dto->term = $row->c[0]->v;
            $dto->ranking = $row->c[1]->v;
            $dto->trendsUrl = self::TRENDS_URL . $row->c[2]->v;
            $dto->searchUrl = $row->c[3]->v;
            $dto->searchImageUrl = $row->c[3]->v . '&tbm=isch';

            $searchResult->results[] = $dto;
            $searchResult->totalResults++;
        }

        return $searchResult;
    }


    /**
     * @return string
     */
    private function prepareSearchUrl()
    {
        return self::SEARCH_URL .
            '?hl=' . $this->language .
            '&cat=' . $this->category .
            '&geo=' . $this->location .
            '&q=' . implode(',+', $this->query) .
            '&cid=' . $this->cid .
            '&date=' . ($this->lastDays ?: $this->monthInterval) .
            '&cmpt=q&content=1&export=3';
    }


    /**
     * @return json
     * @throws \Guzzle\Http\Exception\BadResponseException
     */
    public function HotTrendUrl()
    {

        $proxySec = $this->proxyList[rand(0,(count($this->proxyList)-1))];

        $response = $this->guzzleClient
            ->setBaseUrl($this->prepareHotTrendUrl(),array(
                'config' => [
                    'curl' => [
                        'CURLOPT_PROXY' => $proxySec ,
                        'CURLOPT_HTTPPROXYTUNNEL' => 1,
                        'CURLOPT_FOLLOWLOCATION' => 1,
                        'CURLOPT_HEADER' => 1
                    ],
                ]
            ))
            ->get()
            ->send();

        $responseBody = substr($response->getBody(true), 62, -2);
        if(empty($responseBody)) return false;
        preg_match_all('/.+?<a href="(.+?)">(.+?)<\/a>.+?/',$responseBody,$matches);
        $data = array();
        for ($i=0;$i <= (count($matches[1])-1);$i++){
            $data[] = array('title'=>$matches[2][$i],'trendUrl'=>$matches[1][$i]);
        }
        return json_encode($data, JSON_UNESCAPED_SLASHES);

    }

    /**
     * @return string
     */
    private function prepareHotTrendUrl()
    {
        return self::HOT_TREND_URL .
            '?pn=' . $this->location;
    }

    /**
     * @return string
     */
    public function HotTrendLocations()
    {
        $country_code=array('p30','p8','p44','p41','p18','p13','p38','p32','p43','p49','p29','p50','p16','p15','p48','p10','p45','p3','p19','p6','p27','p4','p37','p34','p21','p17','p52','p51','p25','p31','p47','p39','p14','p36','p5','p40','p23','p26','p42','p46','p12','p33','p24','p35','p9','p1','p28');
        $country_name=array('Argentina','Australia','Austria','Belgium','Brazil','Canada','Chile','Colombia','Czech Republic','Denmark','Egypt','Finland','France','Germany','Greece','Hong Kong','Hungary','India','Indonesia','Israel','Italy','Japan','Kenya','Malaysia','Mexico','Netherlands','Nigeria','Norway','Philippines','Poland','Portugal','Romania','Russia','Saudi Arabia','Singapore','South Africa','South Korea','Spain','Sweden','Switzerland','Taiwan','Thailand','Turkey','Ukraine','United Kingdom','United States','Vietnam');
        $result = array();
        for ($i=0;$i<=count($country_code)-1;$i++){
            $result[] = array('country_code'=>$country_code[$i],'country_name'=>$country_name[$i]);
        }
        return $result;
    }


    /**
     * @return string
     */
    private function prepareKeywordSuggestUrl()
    {
        return self::KEYWORD_SUGGEST_URL .
            '&hl=' . $this->location.
            '&q='. implode(',+', $this->query);
    }


    /**
     * @return json
     * @throws \Guzzle\Http\Exception\BadResponseException
     */
    public function KeywordSuggestUrl()
    {
        $proxySec = $this->proxyList[rand(0,(count($this->proxyList)-1))];
       // $xml = simplexml_load_file ( utf8_encode ( $this->prepareKeywordSuggestUrl()) );
        $response = $this->guzzleClient
            ->setBaseUrl($this->prepareKeywordSuggestUrl(),array(
                'config' => [
                    'curl' => [
                        'CURLOPT_PROXY' => $proxySec ,
                        'CURLOPT_HTTPPROXYTUNNEL' => 1,
                        'CURLOPT_FOLLOWLOCATION' => 1,
                        'CURLOPT_HEADER' => 1
                    ],
                ]
            ))
            ->get()
            ->send();

        $responseBody = substr($response->getBody(true), 62, -2);
        if(empty($responseBody)) return array('title'=>'hata','msg'=>'veri gelmedi.');
        $xml = simplexml_load_string(utf8_encode($response->getBody(true)));
        $suggest=array();
        if (@$xml) {
            foreach ( $xml->children() as $child ) {
                foreach ( $child->suggestion->attributes () as $data ) {
                    $suggest[] = (string) $data;
                }
            }
            return array('data'=>$suggest);
        }

    }


}