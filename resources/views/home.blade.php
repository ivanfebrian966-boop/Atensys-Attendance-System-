<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>home | {{ config('app.name', 'Laravel') }}</title>
</head>

<body>

    <ul>
        @foreach ($fungsional as $nama_fitur => $deskripsi_fitur)
        <li> {{ $nama_fitur }}: {{ $deskripsi_fitur }}</li>
        @endforeach
    </ul>

</body>

</html>