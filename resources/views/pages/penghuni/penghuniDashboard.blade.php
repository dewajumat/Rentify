<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    {{-- @foreach ($dp as $name)
        <h1>hi {{ $name  }}</h1>
    @endforeach --}}
    @foreach ($dataPenghuni as $dp)
        <h1>hi {{ $dp->name  }}</h1>
        <h1>your email is {{ $dp->email }}</h1>
    @endforeach
    
    
    <table>
        <tr>
            <th class="text-center">Nama</th>
            <th class="text-center">Email</th>
            <th class="text-center">Id</th>
            <th class="text-center">Role</th>
        </tr>
            <tr>
                <td>{{ $dp->name }}</td>
                <td>{{ $dp->email }}</td>
                <td>{{ $dp->penghuni_id }}</td>
                <td>{{ $dp->role }}</td>
            </tr>
        <input type="text" name="" id="" placeholder="{{ $dp->name }}">
    </table>
    <button><a href="/logout">LogOut</a></button>
</body>
</html>