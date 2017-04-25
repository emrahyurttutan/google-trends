<?php
/**
 * Google Trends
 * 22.04.2017
 * Emrah YURTTUTAN <emrah@yurttutan.net>
 *
 */
header('Content-Type: application/json');
if(isset($_GET['frm'])){
	switch ($_GET['frm']) {
		case 'trend' :
            require __DIR__ . '/../vendor/autoload.php';
            try {
            	if(isset($_GET['kelime'])){
                    $results = (new Gsoares\GoogleTrends\Search())
                        //->setCategory(Gsoares\GoogleTrends\Category::BEAUTY_AND_FITNESS)
                        ->setLocation('TR')
                        ->setLanguage('tr-TR')
                        ->addWord(isset($_GET['kelime']) ? $_GET['kelime'] : 'adana')
                        ->setLastDays(isset($_GET['tarih']) ? $_GET['tarih'] : 30)
                        ->searchJson();
				}else {
                    $results = (new Gsoares\GoogleTrends\Search())
                        ->setLocation('TR')
                        ->setLanguage('tr-TR')
                        ->setLastDays(isset($_GET['tarih']) ? $_GET['tarih'] : 30)
                        ->searchJson();
				}
                if(isset($results)){
                    echo $results;
                }else {
                    echo json_encode(array('title'=>'hata','msg'=>'veri dönmedi...'));
                }
            } catch (\Exception $e) {
                echo json_encode(array('title'=>'hata','msg'=>trim($e->getMessage())));

            }
		break;
		case 'hottrend' :
            require __DIR__ . '/../vendor/autoload.php';
            try {
                $results = (new Gsoares\GoogleTrends\Search())
                    ->setLocation(isset($_GET['code']) ? $_GET['code'] : 'p24')
                    ->setLanguage('tr-TR')
                    ->HotTrendUrl();
                if(isset($results)){
                    echo $results;
                }else {
                    echo json_encode(array('title'=>'hata','msg'=>'veri dönmedi...'));
                }
            } catch (\Exception $e) {
                echo json_encode(array('title'=>'hata','msg'=>trim($e->getMessage())));

            }
        break;
		case 'hottrendlocation' :
            require __DIR__ . '/../vendor/autoload.php';
            try {
                $results = (new Gsoares\GoogleTrends\Search())->HotTrendLocations();
                if(is_array($results)){
                    echo json_encode($results, JSON_UNESCAPED_SLASHES);
                }else {
                    echo json_encode(array('title'=>'hata','msg'=>'veri dönmedi...'));
                }
            } catch (\Exception $e) {
                echo json_encode(array('title'=>'hata','msg'=>trim($e->getMessage())));

            }
			break;

        case 'googleKeywordSuggest' :
            require __DIR__ . '/../vendor/autoload.php';
            try {
                $results = (new Gsoares\GoogleTrends\Search())
                    ->setLocation(isset($_GET['code']) ? $_GET['code'] : 'tr')
                    ->addWord(isset($_GET['kelime']) ? $_GET['kelime'] : 'istanbul')
                    ->KeywordSuggestUrl();
                echo json_encode($results, JSON_UNESCAPED_SLASHES);
            } catch (\Exception $e) {
                echo json_encode(array('title'=>'hata','msg'=>trim($e->getMessage())));

            }
            break;
		default :
			echo 'AT Kafas?!';
			break;
	}
}else {
    echo 'Çakal Kafas?!';
}
