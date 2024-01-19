var conversion = null;

function cryptoUpdate() {
    $("#cryptoprice").text(($("#amount").val() * conversion).toFixed(2));
};
function conversionUpdate() {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "/api_connect/getprice.php", true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState != 4) return;
        loaded = true;
        if (xhr.status == 200) {         
            cryptoresponse = JSON.parse(xhr.responseText);
            conversion = cryptoresponse.response.price;
            $(".conversion").each(function(){
                $(this).text(Number(conversion).toFixed(2));
            });
        } else {
            alert("Ошибка загрузки скрипта");
            console.log(xhr.status + ": " + xhr.statusText);
        }
    }
    loaded = false;
    setTimeout(ShowLoading, 20000);
    xhr.send();
}
$(document).ready(function() {
    conversionUpdate();
});