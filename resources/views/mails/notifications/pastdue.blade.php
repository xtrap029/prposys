@component('mail::message')
Dear {{ $name }}
<br><br>
This is a reminder that the below issued PR or PO is already due for liquidation.
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
