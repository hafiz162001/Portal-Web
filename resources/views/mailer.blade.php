<br/>
dear {{ $data['email'] }}
<br/>
{{ $data['desc'] }}
<br/><br/>
@if (is_array($data['otp']))
    @foreach ( $data['otp'] as $otp)
        {{ $otp }}<br/>
    @endforeach
@else
    {{ $data['otp'] }}<br/>
@endif

<br/>
Thank you<br/>
Our contact detail as of below<br/>
EVORIA<br/>
