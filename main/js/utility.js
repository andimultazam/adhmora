var domainname = "http://localhost/adhmora/api/";

function getValue(varname) {
    var url = window.location.href;
    var qparts = '';
    var ampExists = (url.indexOf('?') >= 0) ? true : false;
    if (!ampExists) return "";
    qparts = url.split("?");
    if (qparts.length == 0) {
        return "";
    }
    var query = qparts[1];
    var vars = query.split("&");
    var value = "";
    for (i=0;i<vars.length;i++) {
        var parts = vars[i].split("=");
        if (parts[0] == varname) {
            value = parts[1];
            break;
        }
    }
    value = unescape(value);
    value = value.replace(/\+/g," ");
    return value;
}