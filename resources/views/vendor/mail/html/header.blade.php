@props(['url'])
<tr>
<td class="header" style="text-align: center;">
<a href="{{ $url }}" style="display: inline-block; text-decoration: none; text-align: center;">
<img src="{{ asset('IMGs/Hu Logo 1.png') }}" class="logo" alt="{{ config('app.name') }}" style="max-height: 75px; width: auto; display: block; margin: 0 auto 10px auto;">
<span style="color: #124375; font-size: 19px; font-weight: bold; font-family: 'Cairo', sans-serif; display: block;">{{ $slot }}</span>
</a>
</td>
</tr>
