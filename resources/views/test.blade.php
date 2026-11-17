<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Test of the using the own attribute for method's flag</title>
</head>
<body>

    <h1>Test of the using the own attribute for method's flag </h1>
    <p>Parameter AB in .env = {{ config('flags.AB') ? 'true' : 'false' }}
    <ul>
        <li><strong>Email</strong>: {{ $email }} </li>
        <li><strong>SMS</strong>: {{ $sms }}</li>
    </ul>
    <input type="button" value="Reload page" onclick="location.href='/?x='+Date.now();">

</body>
</html>
