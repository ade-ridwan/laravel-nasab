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
    <div class="container mt-4 fixed-top">
        @if ($personActive->gender == 'L')
            <a href="{{ route('couple.create') }}" class="btn btn-success">Tambah Pasangan</a>
        @endif
        <a href="{{ route('child.create') }}" class="btn btn-secondary">Tambah Anak</a>
        <span class="p-2 rounded text-white bg-danger">{{ $personActive->name }}</span>
    </div>
    <div id="tree"></div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
</script>

<!-- tree --->
<script src="{{ asset('js/familytree.js') }}"></script>

<script>
    var tree = new FamilyTree(document.getElementById("tree"), {
        nodeBinding: {
            field_0: "name",
            field_1: "gender",
            field_2: "url",
            img_0: "avatar"
        },
        nodes: {!! $tree !!},
    });

    FamilyTree.templates.myTemplate = Object.assign({}, FamilyTree.templates.john);
    FamilyTree.templates.myTemplate_male = Object.assign({}, FamilyTree.templates.myTemplate);
    FamilyTree.templates.myTemplate_male.node =
        '<circle cx="100" cy="100" r="100" fill="#039be5" stroke-width="1" stroke="#aeaeae"></circle>';
    FamilyTree.templates.myTemplate_female = Object.assign({}, FamilyTree.templates.myTemplate);

    FamilyTree.templates.myTemplate.field_2 =
        '<text style="font-size: 16px;" fill="#ffffff" x="100" y="60" text-anchor="middle">{val}</text>';
</script>


</html>
