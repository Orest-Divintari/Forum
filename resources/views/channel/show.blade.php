<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    @foreach($threads as $thread)
        <p> {{ $thread->title }} </p>
        <p> {{ $thread->body }} </p>
    @endforeach
</body>
</html>