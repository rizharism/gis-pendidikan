@component('mail::message')
# Kode Reset Password

Halo,

Kami menerima permintaan reset password untuk akun Anda di **{{ config('app.name') }}**.
Gunakan kode berikut untuk mereset password Anda:

@component('mail::panel')
<div style="text-align:center;font-size:36px;font-weight:bold;letter-spacing:8px;color:#1e293b;">
{{ $code }}
</div>
@endcomponent

Kode ini berlaku selama **15 menit** dan hanya dapat digunakan satu kali.
Jika Anda tidak meminta reset password, abaikan email ini.

Salam,<br>
{{ config('app.name') }}
@endcomponent
