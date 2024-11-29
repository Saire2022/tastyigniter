@if ($canRegister)
    {!! form_open([
        'role' => 'form',
        'method' => 'POST',
        'data-request' => 'account::onRegister',
    ]) !!}
    <div class="form-group">
        <input
            type="text"
            id="full-name"
            class="form-control input-lg"
            value="{{ set_value('full_name') }}"
            name="full_name"
            placeholder="@lang('Full Name')"
        >
        {!! form_error('full_name', '<span class="text-danger">', '</span>') !!}
    </div>
    <div class="form-group">
        <input
            type="text"
            id="email"
            class="form-control input-lg"
            value="{{ set_value('email') }}"
            name="email"
            placeholder="@lang('igniter.user::default.settings.label_email')">
        {!! form_error('email', '<span class="text-danger">', '</span>') !!}
    </div>
    <div class="form-group">
        <input
            type="text"
            id="identification"
            class="form-control input-lg"
            value=""
            name="identification"
            placeholder="Identification"
            maxlength="10"
        >
        {!! form_error('identification', '<span class="text-danger">', '</span>') !!}
    </div>

    <div class="form-group">
        <input
            type="tel"
            id="telephone"
            class="form-control input-lg"
            value="{{ set_value('telephone') }}"
            name="telephone"
            data-control="country-code-picker"
        >
        {!! form_error('telephone', '<span class="text-danger">', '</span>') !!}
    </div>

    <div class="form-group">
        <input
            type="text"
            id="customer_address"
            class="form-control input-lg"
            value="{{ set_value('customer_address') }}"
            name="customer_address"
            placeholder="@lang('igniter.user::default.settings.label_customer_address')"
        >
        {!! form_error('customer_address', '<span class="text-danger">', '</span>') !!}
    </div>
    <div class="row">
        <div class="col-12 mb-2">
            <button
                type="submit"
                class="btn btn-primary btn-block btn-lg"
                data-attach-loading
            >@lang('igniter.user::default.login.button_register')</button>
        </div>
        <div class="col-12 text-center">
            <a
                href="{{ site_url('account/login') }}"
                class="btn btn-link"
            >@lang('igniter.user::default.login.button_login')</a>
        </div>
    </div>
    {!! form_close() !!}
@else
    <p>@lang('igniter.user::default.login.alert_registration_disabled')</p>
@endif
{{--@dump(session()->all())--}}
