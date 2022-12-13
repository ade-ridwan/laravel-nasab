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
                <h3 class="card-title">Tambah Pasangan</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('couple.store') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Istri</label>
                        <input type="text" name="name" class="form-control" id="name"
                            placeholder="Nama Istri">
                    </div>
                    <div class="mb-3">
                        <label for="date_wedding" class="form-label">Tanggal Pernikahan</label>
                        <input type="date" name="date_wedding" class="form-control" id="date_wedding"
                            placeholder="Tanggal">
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
