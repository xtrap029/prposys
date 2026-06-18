@component('mail::message')
Dear {{ $name }}
<br><br>
Your transaction has been disapproved by {{ $approver }}.
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
<br>
Remarks: {{ $remarks }}

@component('mail::button', ['url' => $url])
View Transaction
@endcomponent

Thank you
@endcomponent
