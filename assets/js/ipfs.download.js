function readfile(n) {
    return new Promise((e, t) => {
        var o = new FileReader;
        o.onload = () => {
            e(o.result)
        }, o.readAsArrayBuffer(n)
    })
}
async function decryptfile(e, t, o) {
    var n = await readfile(e).catch(function(e) {
            console.error(e)
        }),
        r = new Uint8Array(n),
        e = new TextEncoder("utf-8").encode(o),
        n = r.slice(8, 16),
        e = await window.crypto.subtle.importKey("raw", e, {
            name: "PBKDF2"
        }, !1, ["deriveBits"]).catch(function(e) {
            console.error(e)
        });
    console.log("passphrasekey imported");
    e = await window.crypto.subtle.deriveBits({
        name: "PBKDF2",
        salt: n,
        iterations: 1e4,
        hash: "SHA-256"
    }, e, 384).catch(function(e) {
        console.error(e)
    });
    console.log("pbkdf2bytes derived"), e = new Uint8Array(e), keybytes = e.slice(0, 32), ivbytes = e.slice(32), r = r.slice(16);
    e = await window.crypto.subtle.importKey("raw", keybytes, {
        name: "AES-CBC",
        length: 256
    }, !1, ["decrypt"]).catch(function(e) {
        console.error(e)
    });
    console.log("key imported");
    r = await window.crypto.subtle.decrypt({
        name: "AES-CBC",
        iv: ivbytes
    }, e, r).catch(function(e) {
        console.error(e)
    });
    console.log("ciphertext decrypted"), r = new Uint8Array(r);
    r = new Blob([r], {
        type: "application/download"
    });
    console.log(t), console.log(o);
    const c = document.createElement("a");
    c.href = URL.createObjectURL(r), c.download = t, c.click()
}
async function downloadURL(t, e, o, span) {
    document.getElementById("dl" + span).insertAdjacentHTML("beforeend", '<br/>Đang tải xuống...'), fetch("https://cloudflare-ipfs.com/ipfs/" + e).then(e => e.blob()).then(e => {
        decryptfile(e, t, o), document.getElementById("dl" + span).innerHTML = ""
    }).catch(console.error)
}