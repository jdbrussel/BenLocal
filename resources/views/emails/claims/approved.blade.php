<x-mail::message>
# Claim Approved

Congratulations! Your claim for **{{ $claim->spot->name }}** has been approved.

You can now manage your business information and respond to reviews through your dashboard.

<x-mail::button :url="config('app.url') . '/owner'">
Go to Dashboard
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
