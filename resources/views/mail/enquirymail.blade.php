@component('mail::message')
{{__('Admin/backend.first_name')}} : {{$requests->first_name}}
<br>
{{__('Admin/backend.last_name')}} : {{$requests->last_name}}
<br>
{{__('Admin/backend.email')}} : {{$requests->email}}
<br>
{{__('Frontend.phone')}} : {{$requests->phone}}
<br>

{{$requests->message}}

@component('mail::button', ['url' => url('/')])
@lang('Frontend.home_page')
@endcomponent

@lang('Frontend.thanks'),<br>
{{ config('app.name') }}
@endcomponent
