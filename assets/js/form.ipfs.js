$("#f2").on("change", function() {
    var e = document.getElementById("f2"),
        a = e.files[0].name,
        c = encodeURI(a);
    fetch("https://api.nft.storage/upload", {
        method: "post",
        headers: {
            Authorization: "Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiJkaWQ6ZXRocjoweDI4ZjRFNDJEYjMxQWRiODdFYjQ3M2I2NmJjNjI1MTJlMzE4OEVGMjAiLCJpc3MiOiJuZnQtc3RvcmFnZSIsImlhdCI6MTYzNzUxODc3NDE4MCwibmFtZSI6IkRvcmV3In0._fktZLU7Uj0o3cJgPLlSJOBX3ajw2i-yUQxUfsTi1Yw"
        },
        body: e.files[0]
    }).then(e => e.json()).then(e => {
        console.log(e.value.cid);
        var i = "",
            t = a.substring(c.lastIndexOf(".") + 1),
            i = "gif" == (t = t.toLowerCase()) || "jpeg" == t || "jpg" == t || "png" == t || "webp" == t ? "[img]https://ipfs-gateway.cloud/ipfs/" + e.value.cid + "?filename=" + c + "[/img]" : "mp4" == t || "mkv" == t || "webm" == t || "mp3" == t || "wav" == t || "flac" == t || "m4a" == t || "wav" == t || "ogg" == t ? "[vid]https://ipfs-gateway.cloud/ipfs/" + e.value.cid + "?filename=" + c + "[/vid]" : "https://ipfs-gateway.cloud/ipfs/" + e.value.cid + "?filename=" + c,
            e = $("textarea").val();
        $("textarea").val(e + " " + i), c = a = null
    })
}), document.querySelector("#upload2").onclick = function() {
    document.querySelector("#f2").click()
};