<html>
    <head>
        <title>Angular Js Google Trends</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    </head>
    <body>
    <div ng-app="app">
        <div class="container" ng-controller="googleTrend">
            <div class="col-sm-12">
                <div class="alert alert-info" ng-if="msj">{{msj}}</div>
                <h1>Google Trendde ülkelere göre en şuanda <br/> en çok aranan görmek için ülke şeçin.</h1>
                <div class="col-md-12" style="margin-bottom: 20px;">
                    <div class="row">
                        <div class="col-md-6">
                            <h2>Google Trends De Şuanda </h2>
                            <div class="col-md-7">
                                <select class="form-control" ng-model="model" ng-value="{{model}}">
                                    <option ng-repeat="option in datas" value="{{option.country_code}}">{{option.country_name}}</option>
                                </select>
                                <span ng-if="model">seçilen : {{model}}</span>
                            </div>
                            <div class="col-md-5">
                                <button type="submit" class="btn btn-info" ng-click="getHottrend()">Getir</button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h2>Google Anahtar Kelime Önerisi </h2>
                            <div class="row">
                                <div class="col-md-7">
                                    <input type="text" ng-model="txtSearch" ng-value="txtSearch" class="form-control" placeholder="öneri almak istediğiniz kelimeyi girin." />
                                    <span ng-if="txtSearch">{{txtSearch}}</span>
                                </div>
                                <div class="col-md-5">
                                    <button type="submit" class="btn btn-info" ng-click="getKeywordSuggest(txtSearch)">Ara</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-12" style="margin-top: 30px;">
                    <div class="row">
                        <div class="col-md-6" >
                            <h2 ng-if="dataTrend">Şuanda Google Trends'deki Aramalar</h2>
                            <ul class="list-group" ng-if="dataTrend">
                                <li class="list-group-item" ng-repeat="d in dataTrend">{{d.title}} <button type="submit" class="btn btn-default btn-xs pull-right" ng-click="getWord(d.title)">Benzer Kelime</button></li>
                            </ul>
                            <h2 ng-if="keywordSuggest"><span style="color: brown">{{txtSearch}} </span> kelimesi için önerilen benzer kelimeler.</h2>
                            <ul class="list-group" ng-if="keywordSuggest.data">
                                <li class="list-group-item" ng-repeat="d in keywordSuggest.data">{{d}} <button type="submit" class="btn btn-default btn-xs pull-right" ng-click="getWord(d)">Trend'te olan benzer kelimeler</button></li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <div class="col-md-12" style="margin-bottom: 20px;">
                                <div class="row">
                                    <div class="col-md-7">
                                        <input type="text" ng-model="txtSearch" ng-value="txtSearch" class="form-control" />
                                        <span ng-if="txtSearch">{{txtSearch}}</span>
                                    </div>
                                    <div class="col-md-5">
                                        <button type="submit" class="btn btn-info" ng-click="getWord(txtSearch)">Ara</button>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="alert alert-info" ng-if="dataWord.totalResults">{{dataWord.totalResults}} tane sonuç bulundu. <a href="{{dataWord.searchUrl}}" target="_blank">Google Trend Linki</a></div>
                            <div class="alert alert-warning" ng-if="dataWord.title">{{dataWord.title}} bulundu. <br> {{dataWord.msg}}</div>
                            <table  class="table table-condensed" ng-if="dataWord.totalResults">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Ranking</th>
                                        <th>Search Url</th>
                                        <th>search Image Url</th>
                                        <th>Trend Url</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="d in dataWord.results">
                                        <td>{{d.term}}</td>
                                        <td>{{d.ranking}}</td>
                                        <td><a href="{{d.searchUrl}}" target="_blank" title="{{d.term}} google da ara">web'e bak</a></td>
                                        <td><a href="{{d.searchUrl}}" target="_blank" title="{{d.searchImageUrl}} google görsellere bak">görsellere bak</a></td>
                                        <td><a href="{{d.trendsUrl}}" target="_blank" title="{{d.term}} trend aramaları">trend'e bak</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" type="javascript"></script>
    <script src="../js/app.js"></script>
    </body>
</html>