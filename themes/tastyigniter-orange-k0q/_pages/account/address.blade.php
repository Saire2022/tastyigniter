---
title: 'main::lang.account.address.title'
layout: account
permalink: '/account/address/:addressId?'
'[accountAddressBook]': null
description: ''
---
<div class="container">
    <div class="row py-5">
        <div class="col-sm-3">
            @partial('account/sidebar')
        </div>

        <div class="col-sm-9">
            <div class="card">
                <div class="card-body">
                    @component('accountAddressBook')
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
  <p> This is the first theme changes</p>
 </div>