$(document).ready(function(){
    let perPageAmount = 3;

    const id = [];
    const postContent = [];
    $(".newspost").each(function(index) {
        postContent.push($(".newspost")[index]);
        index++;
        id.push(index);
    });
    let amount = id.length;
    let pageAmount = Math.ceil(amount / perPageAmount);
    for(let a = 0; a < pageAmount; a++) {
        var currentPage = a;
        var page = $("<a class='page-element' href='#'></a>").text(currentPage + 1);
        $(".page-container").append(page);
    }
    // DEFAULT VIEW
    let pageIndex = 0;
    let pageNumber = 1;
    let visiblePosts = [];
    for(let p = 0 + (pageIndex * perPageAmount); p < perPageAmount + (pageIndex * perPageAmount); p++) {
        visiblePosts.push(p);
    };
    $(".newspost").each(function(index){
        let vp = index;
    });
    $("#post-container").html("");
    for(let vp = visiblePosts[0]; vp <= visiblePosts[perPageAmount - 1]; vp++) {
        if(vp >= id.length) {
            break;
        }
        $(postContent[vp]).appendTo("#post-container");
    }
    // CLICK SWITCH
    $(".page-element").on('click', function(e){
        e.stopPropagation();
        e.stopImmediatePropagation();
        e.preventDefault();
        let pageIndex = $(".page-element").index(this);
        if (pageIndex >= pageAmount) {
            pageIndex = pageIndex - pageAmount;
        }
        let pageNumber = pageIndex + 1;
        let visiblePosts = [];
        for(let p = 0 + (pageIndex * perPageAmount); p < perPageAmount + (pageIndex * perPageAmount); p++) {
            visiblePosts.push(p);
        };
        $(".newspost").each(function(index){
            let vp = index;
        });
        $("#post-container").html("");
        for(let vp = visiblePosts[0]; vp <= visiblePosts[perPageAmount - 1]; vp++) {
            if(vp >= id.length) {
                return;
            }
            $(postContent[vp]).appendTo("#post-container");
        }
    });
});