@component('mail::message')

    {{ __('Hello!') }}<br />
    <p>{{ __('springs.spring') }} <b><a href="{{ $url }}">{{ $name }}</a></b> {{ __('springs.spring_confirmed_text', [config('app.name')]) }}</p>
    {{  __('Regards') }},<br />
    {{ config('app.name') }} {{ __('springs.team') }}

@endcomponent
