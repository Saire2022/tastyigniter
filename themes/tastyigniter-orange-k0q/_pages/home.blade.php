---
title: 'main::lang.home.title'
permalink: /
description: ''
layout: blank
'[setlocal]':
    alias: '[setlocal]'
'[featuredItems]':
    alias: '[featuredItems]'
    title: null
    items: null
    limit: 12
    itemsPerRow: 3
    itemWidth: 400
    itemHeight: 300
---
{{--@component('slider')--}}

{{--@component('localSearch')--}}

{{--@component('featuredItems')--}}

{{--@component('tablesList')--}}

{{--@dump(session('local_info.id'))--}}
@component('setlocal')

