---
title: 'main::lang.account.title'
layout: account
permalink: /account
'[localBox]': null
'[cartBox]': null
description: ''
---
<div class="container">
    <div class="row py-5">
        <div class="col-sm-3">
            @partial('account/sidebar')
        </div>

        <div class="col-sm-9">
            @component('account')
        </div>
    </div>
</div>