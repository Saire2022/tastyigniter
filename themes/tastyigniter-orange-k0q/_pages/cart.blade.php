---
title: 'main::lang.cart.title'
layout: blank
permalink: /cart
'[localSearch]': null
'[localBox]': null
'[cartBox]':
    pageIsCart: 1
description: ''
---
<div class="container">
    <div class="row py-4">
        <div class="col col-lg-6 m-auto">
            <div class="cart-buttons">
                <a
                    class="btn btn-link btn-block btn-md"
                    href="{{ restaurant_url('local/menus') }}?table_id={{ session('tableId') }}"
                >@lang('igniter.cart::default.text_add_more_items')</a>
            </div>

            @partial('cartBox::container')
        </div>
    </div>
</div>