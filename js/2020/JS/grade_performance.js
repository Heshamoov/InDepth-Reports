$(function () {
    $('#grade').multiselect({includeSelectAllOption: false});
    $('#term').multiselect({includeSelectAllOption: false});
});


function search() {
    let Grade = $("#grade option:selected").text();
    let Term = $("#term option:selected").text();

    let httpSearch = new XMLHttpRequest();
    httpSearch.onreadystatechange = function () {
        if (this.readyState === 4)
            document.getElementById("results").innerHTML = this.responseText;
    };

    httpSearch.open("POST", "js/2020/SQL/grade_performance.php?Grade=" + Grade + "&Term=" + Term, false);
    httpSearch.send();
}