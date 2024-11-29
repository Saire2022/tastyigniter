{!! form_open([
    'id' => 'booking-form',
    'role' => 'form',
    'method' => 'POST',
    'data-request' => $bookingEventHandler,
]) !!}

<div class="form-row">
    <div class="col-sm-6">
        <div class="form-group">
            <input
                type="text"
                name="full_name"
                id="full_name"
                class="form-control"
                placeholder="@lang('igniter.reservation::default.label_full_name')"
                value="{{ set_value('full_name', $reservation->full_name) }}"
            />
            {!! form_error('full_name', '<span class="text-danger">', '</span>') !!}
        </div>
    </div>

    <div class="col-sm-6">
        <div class="form-group">
            <input
                type="text"
                name="identification"
                id="identification"
                class="form-control"
                placeholder="@lang('igniter.reservation::default.label_identification')"
                value="{{ set_value('identification', $reservation->identification) }}"
            />
            {!! form_error('identification', '<span class="text-danger">', '</span>') !!}
        </div>
    </div>
</div>

<div class="form-row">
    <div class="col-sm-6">
        <div class="form-group">
            <input
                type="text"
                name="email"
                id="email"
                class="form-control"
                placeholder="@lang('igniter.reservation::default.label_email')"
                value="{{ set_value('email', $reservation->email) }}"
                {!! $customer ? 'disabled' : '' !!}
            />
            {!! form_error('email', '<span class="text-danger">', '</span>') !!}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <input
                type="text"
                name="telephone"
                id="telephone"
                class="form-control"
                data-control="country-code-picker"
                value="{{ set_value('telephone', $reservation->telephone) }}"
            />
            {!! form_error('telephone', '<span class="text-danger">', '</span>') !!}
        </div>
    </div>
</div>

<div class="form-group">
    <textarea
        name="comment"
        id="comment"
        class="form-control"
        rows="2"
        placeholder="@lang('igniter.reservation::default.label_comment')"
    >{{ set_value('comment', $reservation->comment) }}</textarea>
    {!! form_error('comment', '<span class="text-danger">', '</span>') !!}
</div>

<button
    type="submit"
    class="btn btn-primary btn-block btn-lg"
    data-attach-loading="disabled"
>@lang('igniter.reservation::default.button_reservation')</button>

{!! form_close() !!}
