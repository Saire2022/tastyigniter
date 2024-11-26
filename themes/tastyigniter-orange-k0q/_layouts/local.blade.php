---
description: 'Local layout'
'[session]':
    security: all
'[staticMenu mainMenu]':
    code: main-menu
'[staticMenu footerMenu]':
    code: footer-menu
'[newsletter]': {  }
'[localSearch]': null
'[localBox]':
    paramFrom: location
    showLocalThumb: 0
'[categories]': null
'[cartBox]':
    checkStockCheckout: 1
    showCartItemThumb: 1
    pageIsCheckout: 0
    pageIsCart: 0
---
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="{{ App::getLocale() }}" class="h-100">
<head>
    @partial('head')
</head>
<body class="d-flex flex-column h-100 {{ $this->page->bodyClass }}">
    <main role="main">
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row py-4 h-100">
                    <div class="col-lg-2">
                        @partial('sidebar')
                    </div>
                    <div class="col-lg-6">
                        <div class="categories affix-categories">
                            @component('categories')
                        </div>
                        <div class="content">
                            @partial('localBox::container')

                            @page
                        </div>
                    </div>

                    <div class="col-lg-4">
                        @partial('cartBox::container')
                    </div>
                </div>
            </div>
        </div>
    </main>

    @partial('eucookiebanner')
    @partial('scripts')
</body>
</html>
