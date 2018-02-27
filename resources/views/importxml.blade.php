<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>
        Laravel
    </title>
</head>
<body>
    <form method="post" action="/update_xml" enctype="multipart/form-data">
        <input type="file" name="file" style="width:200px">
        <input type="submit">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
</body>
</html>