/**
 * 24.04.2017
 * @uthor Emrah YURTTUTAN <emrah@yurttutan.net>
 */
(function (angular) {
    function googleTrendController($scope, $http, $log,$window) {
        $scope.model = null;
        $scope.getLocations = function () {
            $http.get("ajax.php?frm=hottrendlocation").then(function (success){
                $scope.datas = success.data;
                $log.info(success);
                $log.info("Data alındı.");
            },function (e){
                $log.info(e);
            });
        }

        $scope.getHottrend = function () {
            if($scope.model){
                $http.get("ajax.php?frm=hottrend&code="+$scope.model).then(function (success){
                    $scope.dataTrend = success.data;
                    $log.info(success.data);
                    $log.info("Data alındı.");
                },function (e){
                    $log.info(e);
                });
            }
        }


        $scope.getWord = function (kelime) {
            $scope.txtSearch = kelime;
            if(kelime){
                $http.get("ajax.php?frm=trend&kelime="+kelime).then(function (success){
                    $scope.dataWord = success.data;
                    $log.info(success.data);
                    $log.info("Data alındı.");
                },function (e){
                    $log.info(e);
                });
            }
        }

        $scope.getKeywordSuggest = function (kelime) {
            $scope.txtSearch = kelime;
            if(kelime){
                $http.get("ajax.php?frm=googleKeywordSuggest&kelime="+kelime).then(function (success){
                    $scope.keywordSuggest = success.data;
                    $log.info(success.data);
                    $scope.dataTrend =null;
                    $log.info("Data alındı.");
                },function (e){
                    $log.info(e);
                });
            }
        }

        $scope.model = 'p24';
        $scope.txtSearch =null;
        if($scope.model){
            $log.info($scope.model);
        }
        $scope.getLocations();
    }
    var app = angular.module("app", []);
    app.controller("googleTrend", ["$scope", "$http", "$log", "$window", googleTrendController]);
})(angular);