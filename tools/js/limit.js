var ns6 = document.getElementById && !document.all

function restrictinput(maxlength, e, placeholder) {
    if (window.event && event.srcElement.value.length >= maxlength) return false
    else if (e.target && e.target == eval(placeholder) && e.target.value.length >= maxlength) {
        var pressedkey = /[a-zA-Zа-яА-Я0-9\.\,\/]/ //detect alphanumeric keys
        if (pressedkey.test(String.fromCharCode(e.which)))
            e.stopPropagation()
    }
}

function countlimit(maxlength, e, placeholder) {
    var form       = eval(placeholder)
    var lengthleft = maxlength - form.value.length
    var placeholderobj = document.all ? document.all[placeholder] : document.getElementById(placeholder)
    if (window.event || e.target && e.target == eval(placeholder)) {
        if (lengthleft < 0)
            form.value = form.value.substring(0, maxlength)
        placeholderobj.innerHTML = lengthleft
    }
}

function displaylimit(name, id, limit) {
    var form       = id != "" ? document.getElementById(id) : name
    var limit_text = '<strong><span id="'+form.toString()+'">' + limit + '</span></strong>'
    if (document.all || ns6)
        document.write(limit_text)
    if (document.all) {
        eval(form).onkeypress = function() { return restrictinput(limit, event ,form) }
        eval(form).onkeyup    = function() { countlimit(limit, event, form) }
    }
    else if (ns6) {
        document.body.addEventListener('keypress', function(event) { restrictinput(limit, event, form) }, true);
        document.body.addEventListener('keyup', function(event) { countlimit(limit, event, form) }, true);
    }
}