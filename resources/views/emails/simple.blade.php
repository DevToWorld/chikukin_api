<!doctype html>
<html lang="ja">
<meta charset="utf-8" />
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, 'Noto Sans JP', 'Hiragino Kaku Gothic ProN', Meiryo, sans-serif; line-height:1.7; color:#1A1A1A;">
{!! $html !!}
@if(!empty($text))
<pre style="margin-top:16px; color:#555; white-space:pre-wrap;">{{ $text }}</pre>
@endif
</body>
</html>

