var navigat = $(".nav-item, .nav-link");
var pagetitle = $("#title");
let pagename1 = pagetitle.textContent;
lightup();
function lightup() {
    if (pagename1 == null) {
        return;
    } 
    if (pagename1 == "index") {
        var activelink = $("#indexlink");
        activelink.setAttribute("id", "active");
    } else if (pagename1 == "shop") {
        var activelink = $("#shoplink");
        activelink.setAttribute("id", "active");
    } else if (pagename1 == "rules") {
        var activelink = $("#ruleslink");
        activelink.setAttribute("id", "active");
    } else {
        return;
    };
}