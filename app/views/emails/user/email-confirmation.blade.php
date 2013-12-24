Hi {{ $user->username }},

you've started the change-email process.

To complete it, you have to click on the following link, to confirm the new email address:
{{ route('user.perform.email-confirmation', ['confirmationHash' => $confirmationHash]) }}
