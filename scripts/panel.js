var links = null
var link = ""
var loaded = true
var data = 
{
    title: "",
    desc: "",
    link: ""
}
var href = ""
var page = 
{
    title: $("title"),
    desc: $("#panel-content")
}

OnLoad();

window.onpopstate = function () {
    OnLoad()
};

function OnLoad() {
    var link = window.location.pathname;

    href = link.replace("panel/", "");
    LinkClick(href);
}

function InitLinks() {
    links = $(".link_internal");
    for (var i = 0; i < links.length; i++) {
        links[i].addEventListener("click", function (e) {
            e.preventDefault();
            LinkClick(e.currentTarget.getAttribute("href"));
        });
    }
}

function LinkClick(href)
{ 
    var props = href.split("/");

    switch(props[1])
    {   
        case "":
        case "main":
            SendRequest("?page=main", href);
            break;
        case "crypto":
            SendRequest("?page=crypto", href);
            break;
        case "2fa":
            SendRequest("?page=2fa", href);
            break;    
        case "vote":
            SendRequest("?page=vote", href);
            break;
        case "help":
            SendRequest("?page=help", href);
            break;
        default:
            SendRequest("?page=" + props[1], href);
            break;
    };
}

function SendRequest(query, link) {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "/panel/core.php" + query, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState != 4) return;
        loaded = true;
        if (xhr.status == 200) {
            GetData(JSON.parse(xhr.responseText), link);
            getVariables();
        } else {
            console.log("Ошибка загрузки скрипта. Подробнее:" + xhrvar.status + ": " + xhrvar.statusText);
        }
    }
    loaded = false;
    setTimeout(ShowLoading, 1000);
    xhr.send();
}

function GetData(response, link) {
    data = {
        title: response.title,
        desc: response.desc,
        link: link
    };

    UpdatePage();
}

function ShowLoading() {
    // if(!loaded) {
    //     $("body").html("Слишком много XHR-запросов! <a href='/'>Главная</a>");
    // }
}

function UpdatePage() {
    $("title").text(data.title);
    $("#panel-content").html(data.desc);
    window.history.pushState(data.desc, data.title, "/panel" + data.link);

    InitLinks();
}

function getVariables() {
    var xhrvar = new XMLHttpRequest();
    xhrvar.open("GET", "/panel/vars.php");
    xhrvar.onreadystatechange = function() {
        if (xhrvar.readyState != 4) return;
        loaded = true;
        if (xhrvar.status == 200) {
            getVariableData(JSON.parse(xhrvar.responseText));
        } else {
            console.log("Ошибка загрузки скрипта. Подробнее:" + xhrvar.status + ": " + xhrvar.statusText);
        }
    }
    xhrvar.send();
}

function getVariableData(responsevar) {
    phpvar = {
        userid: responsevar.userid,
        user: responsevar.user,
        balance: responsevar.balance,
    };

    updateVariableText();
}

function updateVariableText() {
    $("#__php_user").text(phpvar.user);
    $("#__php_userid").text(phpvar.userid);
    $("#__php_balance").text(phpvar.balance);
}

function cryptoForm() {
    var $formdata = new FormData();
    $formdata.append("receiver", $("#receiver").val());
    $formdata.append("amount", $("#amount").val());
    $.ajax({
        url: "/api_connect/transfer.php",
        type: "POST",
        data: $formdata,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (response) {
            processMessage(response);
        }
    })
};

function logOut() {
    $.ajax({
        url: "/auth/logout.php",
        type: "POST"
    });
    location.reload();
};

function teleportToSpawn() {
    $.ajax({
        url: "/panel/teleport.php",
        type: "POST",
        success: function (response) {
            processMessage(response);
        }
    });
};

function processMessage(response) {
    $("#__php_message").html(response.msg);
}