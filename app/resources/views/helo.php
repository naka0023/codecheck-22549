<!doctype html>

<html>
<head>
    <title>Sample</title>
    <style>
    body { color:gray; }
    h1 { font-size:18pt; font-weight:bold; }
    </style>
</head>
<body>
    <h1>Sample</h1>
    <p><?php echo $message; ?></p>
    <form action="/helo" method="POST">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="text" name="str">
        <input type="submit">
    </form>
</body>