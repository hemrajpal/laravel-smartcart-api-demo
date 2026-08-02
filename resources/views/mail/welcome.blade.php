<x-mail::message>
# Welcome to SmartCart 🎉

Hello **{{ $user->name }}**,

Thank you for registering with **SmartCart**.

We're excited to have you with us.

<x-mail::button :url="config('app.frontend_url', config('app.url'))">
Start Shopping
</x-mail::button>

If you have any questions, just reply to this email.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>