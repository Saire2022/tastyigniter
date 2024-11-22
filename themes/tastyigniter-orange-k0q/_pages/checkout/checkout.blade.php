---
title: 'main::lang.checkout.title'
layout: default
permalink: /checkout
'[account]': null
'[localSearch]':
    hideSearch: 1
'[localBox]':
    paramFrom: location
    redirect: home
    defaultOrderType: collection
    showLocalThumb: 0
    localThumbWidth: 80.0
    localThumbHeight: 80.0
    menusPage: local/menus
    localSearchAlias: localSearch
'[cartBox]':
    showCartItemThumb: 0
    cartItemThumbWidth: null
    cartItemThumbHeight: null
    limitCartItemOptionsValues: 0.0
    checkStockCheckout: 1
    pageIsCheckout: 1
    pageIsCart: 0
    hideZeroOptionPrices: 0
    checkoutPage: checkout/checkout
    localBoxAlias: localBox
'[checkout]':
    showCountryField: 0
description: ''
---
<div class="container">
    <div class="row py-4">
        <div class="col col-lg-8">
            @partial('localBox::container')

            <div class="card my-1">
                <div class="card-body">
                    @partial('account/welcome')
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    @component('checkout')
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            @partial('cartBox::container')
        </div>
    </div>
</div>
