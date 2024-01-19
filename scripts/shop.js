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
    title: $("#actualtitle"),
    desc: $("#newshop")
}

OnLoad();

window.onpopstate = function () {
    OnLoad()
};

function OnLoad() {
    var link = window.location.pathname;

    href = link.replace("shop/", "");
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
        case "index.php":
        case "main":
            SendRequest("?page=main", href);
            break;
        case "buy":
            if(props.length == 3 && !isNaN(props[2]) && Number(props[2]) > 0)
            {
                SendRequest("?page=buy&id=" + props[2], href);
            }
            break;
        
    };
}

function SendRequest(query, link) {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "/shop/core.php" + query, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState != 4) return;
        loaded = true;
        if (xhr.status == 200) {
            GetData(JSON.parse(xhr.responseText), link);      
            xhr.addEventListener('load', conversionUpdate());
            getVariables();
        } else {
            console.log("Ошибка загрузки скрипта. Подробнее: " + xhr.status + ": " + xhr.statusText);
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
    $("#newshop").html(data.desc);
    window.history.pushState(data.desc, data.title, "/shop" + data.link);

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
    if (phpvar.user == null || phpvar.user == undefined) {
        $("#__php_user").html('<span style="line-height: 30px; font-size: 16px; position: relative; bottom: 4px;">Вы не авторизованы. <a href="/login">Войти</a> или <a href="/register/">зарегистрироваться</a></span>');
    }
}

function offerPurchase(offerid) {
    var $formdata = new FormData();
    if ($("#username").val() == "") {
        if (phpvar.user == null || phpvar.user == undefined) {
            $("#__php_message").html('<span>Введите никнейм.</span>');
        } else {
        $formdata.append("username", $("#__php_user").text());
        }
    } else {
        $formdata.append("username", $("#username").val());
    }
    $formdata.append("amount", $("#amount").val());
    $formdata.append("cryptoprice", Number($("#conversion").text()));
    $formdata.append("offerid", offerid);
    $.ajax({
        url: "/api_connect/purchase.php",
        type: "POST",
        data: $formdata,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (response) {
            processMessage(response);
        }
    })
}

function processMessage(response) {
    if (response.msg.includes("http")) {
        window.location.replace(response.msg);
    } else {
        $("#__php_message").html(response.msg);
    }
}

function case9Update() {
    $("#caseprice").text(($("#amount").val() * 100).toFixed(2));
};