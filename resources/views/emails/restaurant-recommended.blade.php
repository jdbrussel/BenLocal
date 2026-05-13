<x-mail::message>
# Your restaurant was recommended!

Great news! **{{ $spot->name }}** was recommended by a local on BenLocal.

Claim your spot now to manage your profile, respond to reviews, and reach more local customers.

<x-mail::button :url="$claimUrl">
Claim Your Spot
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
