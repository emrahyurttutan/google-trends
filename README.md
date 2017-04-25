# PHP Google Trends API and Google Keyword Suggest

Angularjs Google Arama Trendleri de arama yapma ve Google Anahtar Kelime Önericisi

-- With Angularjs, you can also search Google Insights for Search and Google Keyword Suggestion
## Installation

To get the latest version of this package require it in your `composer.json` file.

~~~
"emrahyurttutan/google-trends": "dev-master"
~~~

Run `composer update emrahyurttutan/google-trends` to install it.


Demo
======
http://yurttutan.net/gtrend/web/


Samples
=============
Adana kelimesinde trende olan aramalar

Trending searches in Adana
```php
<?php
 $results = (new Gsoares\GoogleTrends\Search())
      ->setLocation('TR')
        ->setLanguage('tr-TR')
        ->addWord('adana')
        ->setLastDays(30)
        ->searchJson();
    
/* {
   searchUrl: http://www.google.com/trends/fetchComponent?hl=tr-TR&cat=&geo=TR&q=adana&cid=TOP_QUERIES_0_0&date=today+1-m&cmpt=q&content=1&export=3,
   totalResults: 10,
   -results: (10)[
   -{
   term: "adana hava",
   ranking: 100,
   searchUrl: https://www.google.com/search?q=%22adana+hava%22,
   searchImageUrl: https://www.google.com/search?q=%22adana+hava%22&tbm=isch,
   trendsUrl: http://www.google.com/trends/explore#geo=TR&date=today+1-m&cmpt=q&q=%22adana+hava%22&cat
   },
   -{
   term: "hava durumu",
   ranking: 95,
   searchUrl: https://www.google.com/search?q=%22hava+durumu%22,
   searchImageUrl: https://www.google.com/search?q=%22hava+durumu%22&tbm=isch,
   trendsUrl: http://www.google.com/trends/explore#geo=TR&date=today+1-m&cmpt=q&q=%22hava+durumu%22&cat
   },
   -{
   term: "adana hava durumu",
   ranking: 95,
   searchUrl: https://www.google.com/search?q=%22adana+hava+durumu%22,
   searchImageUrl: https://www.google.com/search?q=%22adana+hava+durumu%22&tbm=isch,
   trendsUrl: http://www.google.com/trends/explore#geo=TR&date=today+1-m&cmpt=q&q=%22adana+hava+durumu%22&cat
   },
   -{
   term: "adana son dakika",
   ranking: 25,
   searchUrl: https://www.google.com/search?q=%22adana+son+dakika%22,
   searchImageUrl: https://www.google.com/search?q=%22adana+son+dakika%22&tbm=isch,
   trendsUrl: http://www.google.com/trends/explore#geo=TR&date=today+1-m&cmpt=q&q=%22adana+son+dakika%22&cat
   },
   -{
   term: "adana haber",
   ranking: 25,
   searchUrl: https://www.google.com/search?q=%22adana+haber%22,
   searchImageUrl: https://www.google.com/search?q=%22adana+haber%22&tbm=isch,
   trendsUrl: http://www.google.com/trends/explore#geo=TR&date=today+1-m&cmpt=q&q=%22adana+haber%22&cat
   },
   -{
   term: "adana merkez",
   ranking: 20,
   searchUrl: https://www.google.com/search?q=%22adana+merkez%22,
   searchImageUrl: https://www.google.com/search?q=%22adana+merkez%22&tbm=isch,
   trendsUrl: http://www.google.com/trends/explore#geo=TR&date=today+1-m&cmpt=q&q=%22adana+merkez%22&cat
   },
   -{
   term: "adana haberleri",
   ranking: 20,
   searchUrl: https://www.google.com/search?q=%22adana+haberleri%22,
   searchImageUrl: https://www.google.com/search?q=%22adana+haberleri%22&tbm=isch,
   trendsUrl: http://www.google.com/trends/explore#geo=TR&date=today+1-m&cmpt=q&q=%22adana+haberleri%22&cat
   },
   -{
   term: "adana seyhan",
   ranking: 15,
   searchUrl: https://www.google.com/search?q=%22adana+seyhan%22,
   searchImageUrl: https://www.google.com/search?q=%22adana+seyhan%22&tbm=isch,
   trendsUrl: http://www.google.com/trends/explore#geo=TR&date=today+1-m&cmpt=q&q=%22adana+seyhan%22&cat
   },
   -{
   term: "son dakika haberleri",
   ranking: 15,
   searchUrl: https://www.google.com/search?q=%22son+dakika+haberleri%22,
   searchImageUrl: https://www.google.com/search?q=%22son+dakika+haberleri%22&tbm=isch,
   trendsUrl: http://www.google.com/trends/explore#geo=TR&date=today+1-m&cmpt=q&q=%22son+dakika+haberleri%22&cat
   },
   -{
   term: "01 adana",
   ranking: 15,
   searchUrl: https://www.google.com/search?q=%2201+adana%22,
   searchImageUrl: https://www.google.com/search?q=%2201+adana%22&tbm=isch,
   trendsUrl: http://www.google.com/trends/explore#geo=TR&date=today+1-m&cmpt=q&q=%2201+adana%22&cat
   }
   ]
   }
*/
    
?>
