<x-mail::message>
# More Information Needed

We are processing your claim for **{{ $claim->spot->name }}**, but we need some more information from you.

**Notes from our team:**
{{ $notes }}

Please reply to this email or update your claim with the requested information.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
