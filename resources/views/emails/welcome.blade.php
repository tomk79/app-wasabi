<!DOCTYPE html>
<html>
<body>
<h1>Welcome {{{ $user['name'] }}} to {{{ env("APP_NAME") }}}!</h1>
<p><a href="{{{ env('APP_URL') }}}" target="_blank">{{{ env('APP_URL') }}}</a></p>
</body>
</html>
