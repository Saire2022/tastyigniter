---
description: 'This is blank layout'
---
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="{{ App::getLocale() }}" class="h-100">
<head>
    @partial('head')
</head>
<body class="d-flex flex-column h-100">
    <main role="main">
        <div id="page-wrapper">
            @page
        </div>
    </main>
    <div id="notification">
        @partial('flash')
    </div>
    @partial('eucookiebanner')
    @partial('scripts')
</body>
</html>