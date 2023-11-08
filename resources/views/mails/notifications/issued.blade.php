@component('mail::message')
Dear {{ $name }}
<br><br>
Your transaction has been changed to Issued.
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

Kindly liquidate with complete receipts and attachments.
<br><br>
Thank you
@endcomponent
