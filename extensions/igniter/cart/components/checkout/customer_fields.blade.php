<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="full-name">@lang('igniter.cart::default.checkout.label_full_name')</label>
            <input
                type="text"
                name="full_name"
                id="full-name"
                class="form-control"
                value="{{ set_value('full_name', $order->full_name) }}"/>
            {!! form_error('full_name', '<span class="text-danger">', '</span>') !!}
        </div>
    </div>

    <div class="col-sm-6">
        <div class="form-group">
            <label for="identification">@lang('igniter.cart::default.checkout.label_identification')</label>
            <input
                type="text"
                name="identification"
                id="identification"
                class="form-control"
                value="{{ set_value('identification', $order->identification) }}"
                disabled
            />
            {!! form_error('identification', '<span class="text-danger">', '</span>') !!}
        </div>
    </div>
{{--    <div class="col-sm-6">--}}
{{--        <div class="form-group">--}}
{{--            <label for="last-name">@lang('igniter.cart::default.checkout.label_identification')</label>--}}
{{--            <input--}}
{{--                type="text"--}}
{{--                name="identification"--}}
{{--                id="identification"--}}
{{--                class="form-control"--}}
{{--                value="{{ set_value('identification', $order->last_name) }}"/>--}}
{{--            {!! form_error('last_name', '<span class="text-danger">', '</span>') !!}--}}
{{--        </div>--}}
{{--    </div>--}}
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="telephone">@lang('igniter.cart::default.checkout.label_telephone')</label>
            <input
                type="text"
                name="telephone"
                id="telephone"
                class="form-control"
                data-control="country-code-picker"
                value="{{ set_value('telephone', $order->telephone) }}"/>
            {!! form_error('telephone', '<span class="text-danger">', '</span>') !!}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="email">@lang('igniter.cart::default.checkout.label_email')</label>
            <input
                type="text"
                name="email"
                id="email"
                class="form-control"
                value="{{ set_value('email', $order->email) }}"
                {!! $customer ? 'disabled' : '' !!} />
            {!! form_error('email', '<span class="text-danger">', '</span>') !!}
        </div>
    </div>
</div>
<div class="col-sm-8">
    <div class="form-group">
        <label for="">@lang('Address')</label>
        <input
            type="text"
            name="address[address_1]"
            class="form-control"
            value="{{ set_value('customer_address', $order->customer_address ?? '') }}"/>
        {!! form_error('address.address_1', '<span class="text-danger">', '</span>') !!}
    </div>
</div>
