<div data-ng-controller="bloggerController" ng-init="init('{INSTANCEID}')">
    <h3 class="title">{{blogtitle}}</h3>
    <div class="standardbox" data-ng-repeat="blogEntry in blogData">
        <div>
            <span>
                {{blogEntry.header}}
            </span>
        </div>
        <div class="line">
        </div>
        <div>
            <span ng-bind-html="renderHtml(blogEntry.text)">
            </span>
        </div>
    </div>
</div>