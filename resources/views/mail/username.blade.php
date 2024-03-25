<x-mail::message>
<table class="judul"  align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td>
Username & Password
</td>
</tr>
</table>
<p>
Kami ingin memberitahu Anda tentang langkah-langkah penting untuk mengamankan akun Anda. Informasi login Anda untuk akun kami sudah dikirimkan sebelumnya dan tidak dapat diubah kecuali oleh admin
</p>
<table class="subcopy"  align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td>
username : {{ $username }}
</td>
<td>
password : {{ $password }}
</td>
</tr>
</table>
{{-- <x-mail::button :url="$url"> --}}



<p>
{{-- {!! $url !!} --}}
</p>
Thanks,<br>
{{ config('app.name') }}

</x-mail::message>
