<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Coba Tree</title>
</head>

<body>
    <div id="tree"></div>
</body>
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

    FamilyTree.templates.myTemplate.nodeMenuButton =
        '<button>Halaman</button>';
</script>


</html>
