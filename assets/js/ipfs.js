$("#uploadfile").on("change", function() {
    var e = document.getElementById("uploadfile"),
        a = e.files[0].name,
        c = encodeURI(a);
    document.getElementById('btnUpload').style.visibility = "hidden";
    document.getElementById('btnUpload').style.display = "none";
    $("#filename").val(e.files[0].name);
    $("#filesize").val(e.files[0].size);
    var d1 = document.getElementById('dai');
    d1.insertAdjacentHTML('beforeend', '<img src="https://i.imgur.com/1TyFaOM.gif" />');
    fetch("https://api.nft.storage/upload", {
        method: "post",
        headers: {
            Authorization: "Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiJkaWQ6ZXRocjoweDI4ZjRFNDJEYjMxQWRiODdFYjQ3M2I2NmJjNjI1MTJlMzE4OEVGMjAiLCJpc3MiOiJuZnQtc3RvcmFnZSIsImlhdCI6MTYzNzUxODc3NDE4MCwibmFtZSI6IkRvcmV3In0._fktZLU7Uj0o3cJgPLlSJOBX3ajw2i-yUQxUfsTi1Yw"
        },
        body: e.files[0]
    }).then(e => e.json()).then(e => {
        console.log(e.value.cid);
        $("#filecate").val(e.value.cid);
        document.getElementById("form").submit();
    })
}), document.querySelector("#uploadfile").onclick = function() {
    document.querySelector("#btnUpload").click()
};