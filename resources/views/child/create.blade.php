<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laravel Dasar Nasab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>

<body>
    <div class="container p-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tambah Anak</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('child.store') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Anak dari pasangan </label>
                        <select name="parent_id" id="parent_id" class="form-select">
                            @foreach ($couples as $item)
                                <option value="{{ $item->id }}">Bapak {{ $item->husband->name }} & Ibu
                                    {{ $item->wife->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Anak</label>
                        <input type="text" name="name" class="form-control" id="name"
                            placeholder="Nama Anak">
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Jenis Kelamin</label>
                        <select name="gender" id="gender" class="form-select">
                            <option value="L">Laki-Laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
