@component('mail::message')
Dear {{ $name }}
<br><br>
Your transaction has been approved by {{ $approver }}.
<br>
Project: {{ $project }}
<br>
Company: {{ $company }}
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
