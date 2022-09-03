function readfile(e) {
    return new Promise(((r, a) => {
        var n = new FileReader;
        n.onload = () => {
            r(n.result)
        }, n.readAsArrayBuffer(e)
    }))
}

const encpassphrase = document.getElementById('passphrase').value;
console.log(encpassphrase);

async function frUpload() {
    var API_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiJkaWQ6ZXRocjoweDI4ZjRFNDJEYjMxQWRiODdFYjQ3M2I2NmJjNjI1MTJlMzE4OEVGMjAiLCJpc3MiOiJuZnQtc3RvcmFnZSIsImlhdCI6MTYzNzUxODc3NDE4MCwibmFtZSI6IkRvcmV3In0._fktZLU7Uj0o3cJgPLlSJOBX3ajw2i-yUQxUfsTi1Yw';
    const form = document.getElementById("form");
    const file = form.file.files[0];

    if (file == null) {
        alert("Vui lòng chọn file!");
        return;
    };

    document.getElementById('filesize').value = file.size;
    document.getElementById('filename').value = file.name;
    document.getElementById('btnUpload').style.visibility = "hidden";
    document.getElementById('btnUpload').style.display = "none";

    var d1 = document.getElementById('dai');
    d1.insertAdjacentHTML('beforeend', '<img src="https://i.imgur.com/1TyFaOM.gif" />');

    var plaintextbytes = await readfile(file)
        .catch(function (err) {
            console.error(err);
        });
    var plaintextbytes = new Uint8Array(plaintextbytes);

    var pbkdf2iterations = 10000;
    var passphrasebytes = new TextEncoder("utf-8").encode(encpassphrase);
    var pbkdf2salt = window.crypto.getRandomValues(new Uint8Array(8));

    var passphrasekey = await window.crypto.subtle.importKey('raw', passphrasebytes, { name: 'PBKDF2' }, false, ['deriveBits'])
        .catch(function (err) {
            console.error(err);
        });
    console.log('passphrasekey imported');

    var pbkdf2bytes = await window.crypto.subtle.deriveBits({ "name": 'PBKDF2', "salt": pbkdf2salt, "iterations": pbkdf2iterations, "hash": 'SHA-256' }, passphrasekey, 384)
        .catch(function (err) {
            console.error(err);
        });
    console.log('pbkdf2bytes derived');
    pbkdf2bytes = new Uint8Array(pbkdf2bytes);

    keybytes = pbkdf2bytes.slice(0, 32);
    ivbytes = pbkdf2bytes.slice(32);

    var key = await window.crypto.subtle.importKey('raw', keybytes, { name: 'AES-CBC', length: 256 }, false, ['encrypt'])
        .catch(function (err) {
            console.error(err);
        });
    console.log('key imported');

    var cipherbytes = await window.crypto.subtle.encrypt({ name: "AES-CBC", iv: ivbytes }, key, plaintextbytes)
        .catch(function (err) {
            console.error(err);
        });

    if (!cipherbytes) {
        console.log('Error encrypting file.  See console log.');
        return;
    }

    console.log('plaintext encrypted');
    cipherbytes = new Uint8Array(cipherbytes);

    var resultbytes = new Uint8Array(cipherbytes.length + 16)
    resultbytes.set(new TextEncoder("utf-8").encode('Salted__'));
    resultbytes.set(pbkdf2salt, 8);
    resultbytes.set(cipherbytes, 16);

    var blob = new Blob([resultbytes], { type: 'application/download' });

    fetch("https://api.nft.storage/upload", {
        method: "post",
        headers: {
            Authorization: "Bearer " + API_KEY
        },
        body: blob
    }).then(data => data.json()).then(data => {
        console.log(data.value.cid);
        document.getElementById('filecate').value = data.value.cid;
        document.getElementById("form").submit();
    })
}