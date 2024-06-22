@component('mail::message')
Dear {{ $name }}
<br><br>
Please see proof of payment.

@component('mail::button', ['url' => $url])
View Attachment
@endcomponent

<br><br>
Thank you
@endcomponent
