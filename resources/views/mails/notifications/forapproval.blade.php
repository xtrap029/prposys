@component('mail::message')
Dear {{ $name }}
<br><br>
A transaction submitted by {{ $requestor }} is pending your approval.
<br>
Project: {{ $project }}
<br>
PR/PO Number: {{ $no }}
<br>
Purpose: {{ $purpose }}
<br>
Amount: {{ $amount }}

@component('mail::button', ['url' => $url])
View Transaction
@endcomponent

Thank you
@endcomponent
