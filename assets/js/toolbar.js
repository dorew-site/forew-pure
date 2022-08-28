function tag(text1, text2, form, textarea) {
    if ((document.selection)) {
        document.form.msg.focus();
        document.form.document.selection.createRange().text = text1 + document.form.document.selection.createRange().text + text2
    } else if (document.forms[form].elements[textarea].selectionStart != undefined) {
        var element = document.forms[form].elements[textarea];
        var str = element.value;
        var start = element.selectionStart;
        var length = element.selectionEnd - element.selectionStart;
        element.value = str.substr(0, start) + text1 + str.substr(start, length) + text2 + str.substr(start + length)
    } else {
        document.form.msg.value += text1 + text2
    }
}

function show_hide(a) {
    b = document.getElementById(a);
    if (b.style.display == "none") {
        b.style.display = "block"
    } else {
        b.style.display = "none"
    }
}