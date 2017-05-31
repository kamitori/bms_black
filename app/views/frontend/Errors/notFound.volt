<div ng-include="'home/welcome.tpl.html'" class="ng-scope">
    <div class="row welcome ng-scope">
        <div class="col-md-8">
            <h1 ng-if="!isLoggedIn" class="text-left ng-binding ng-scope">404 Error</h1>
            <h2 ng-if="!isLoggedIn" class="text-left ng-binding ng-scope">Page not found</h2>
            <div>
                <p ng-if="!isLoggedIn" class="text-left ng-binding ng-scope">
                  We cannot find the page you're looking for. <a href="{{baseURL}}">Click here</a> to go back to the home page.
                </p>
            </div>
        </div>
    </div>
</div>
{{ assets.outputCss() }}