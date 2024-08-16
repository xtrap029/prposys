@component('mail::message')
Dear {{ $name }}

<br><br>
Good day!
<br><br>
Thank you for assisting us with our query.
Please see the attached file for the proof of payment for {{ $purpose }}.
<br><br>
<i>
    Note: This is an automatic email sent from a no-reply account.
    Should you have questions or clarifications,
    please get in touch with the undersigned at {{ $requestor_email }}
</i>

@component('mail::button', ['url' => $url])
View Attachment
@endcomponent

<br><br>
Best regards,
<br>
{{ $requestor_name }}
@endcomponent
