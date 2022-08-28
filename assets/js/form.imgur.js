function imgur(e, a) {
    document.querySelector(e).onchange = function() {
        var e = this.files[0];
        if (e && e.type.match(/image.*/)) {
            var t = new FormData;
            t.append("image", e);
            var n = new XMLHttpRequest;
            n.open("POST", "https://api.imgur.com/3/image.json"), n.upload.onprogress = function(e) {
                if (e.lengthComputable) {
                    var t = Math.floor(e.loaded / e.total * 100) + "%";
                    a.loading(t)
                }
            }, n.onload = function() {
                var e = JSON.parse(n.responseText);
                if (200 === e.status && !0 === e.success) {
                    var t = e.data;
                    a.loaded(t.link, t.type, t.size, t.datetime)
                } else window.alert("Lỗi: Tải lên thất bại")
            }, n.setRequestHeader("Authorization", "Client-ID 71ae7b89253621e"), n.send(t)
        } else window.alert("Chỉ cho phép chọn ảnh")
    }
}
document.querySelector("#upload").onclick = function() {
    document.querySelector("#f").click()
}, imgur("#f", {
    loading: function(e) {
        document.querySelector("#upload").innerHTML = '<i class="fa fa-upload" aria-hidden="true"></i>'
    },
    loaded: function(e, a, t, n) {
        var o = $("textarea").val();
        $("textarea").val(o + " [img]" + e + "[/img]"), document.querySelector("#upload").innerHTML = '<i class="fa fa-upload" aria-hidden="true"></i>'
    }
});