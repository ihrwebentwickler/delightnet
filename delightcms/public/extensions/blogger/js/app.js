MAINAPP.controller('bloggerController', ['$scope', 'DataSource', '$http', '$sce', function ($scope, DataSource, $http, $sce) {
    $scope.objBlogger = {};
    $scope.objBlogger.instanceId = 1;

    $scope.renderHtml = function (htmlCode) {
        return $sce.trustAsHtml(htmlCode);
    };

    DataSource.get("/public/extensions/blogger/configuration/blogger.json", function (data) {
        $scope.blogData = data.blogger.blogEntries[$scope.objBlogger.instanceId];
        $scope.blogtitle = data.blogger.blogMain[$scope.objBlogger.instanceId].blogtitle;
    });
}]);