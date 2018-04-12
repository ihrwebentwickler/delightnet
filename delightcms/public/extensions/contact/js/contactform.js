MAINAPP.controller('ContactController', ['$scope', 'DataSource', function ($scope, DataSource) {
    $scope.Contact = {};
    var strCaptchaImgLink = "/index.php?cmd=contact&action=" + GLOBAL.STATUS.lang + "&query=getCaptchaimage";
    DataSource.get(strCaptchaImgLink, function (data) {
        $scope.Contact.captchaImage = data;
    });

    $scope.getCaptcha = function () {
        DataSource.get(strCaptchaImgLink, function (data) {
            $scope.Contact.captchaImage = data;
        });
    };
}]);

MAINAPP.directive('captcha', ['DataSource', function (DataSource) {
    return {
        restrict: 'A',
        link: function (scope, element) {
            scope.$watch('Contact.captchaImage', function (newValue) {
                if (newValue) {
                    angular.element(element).html(newValue);
                }
            }, true);
        }
    };
}]);