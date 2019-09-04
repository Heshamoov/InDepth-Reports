document.getElementById("tables").onscroll = function () {
    scrollFunction();
};
function scrollFunction() {
    if (document.getElementById("tables").scrollTop > 50) {
        document.getElementById("myBtn").style.display = "block";
    } else
        document.getElementById("myBtn").style.display = "none";
}
;
function topFunction() {
    document.getElementById("tables").scrollTop = 0;


}
;

