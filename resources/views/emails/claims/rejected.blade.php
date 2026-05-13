<x-mail::message>
# Claim Rejected

We regret to inform you that your claim for **{{ $claim->spot->name }}** has been rejected.

**Reason:**
{{ $claim->rejection_reason }}

If you believe this is a mistake, please contact our support team.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
