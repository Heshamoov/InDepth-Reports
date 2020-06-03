$(function () {
    $('#year').multiselect({includeSelectAllOption: false});
    $('#grade').multiselect({includeSelectAllOption: false});
    $('#gender').multiselect({includeSelectAllOption: false});
    $('#student').multiselect({includeSelectAllOption: false});
    $('#nationality').multiselect({includeSelectAllOption: false});
    $('#view').multiselect({includeSelectAllOption: false});
    $('#term1').multiselect({includeSelectAllOption: false});
    $('#term2').multiselect({includeSelectAllOption: false});
    $('#term3').multiselect({includeSelectAllOption: false});
    $('#term4').multiselect({includeSelectAllOption: false});
});


function search() {
    let Grade = $("#grade option:selected").text();
    let Gender = $("#gender option:selected").text();
    let Nationality = $("#nationality option:selected").text();
    let Student = $("#student option:selected").text();
    let View = $("#view option:selected").text();
    let Term1 = $("#term1 option:selected").text();
    let Term2 = $("#term2 option:selected").text();
    let Term3 = $("#term3 option:selected").text();
    let Term4 = $("#term4 option:selected").text();

    // terms1SQL += " OR REPLACE(exam_name, ' ', '') = REPLACE('" + currentTerm + "', ' ', '')";
    let httpSearch = new XMLHttpRequest();
    httpSearch.onreadystatechange = function () {
        if (this.readyState === 4)
            document.getElementById("results").innerHTML = this.responseText;
    };

    httpSearch.open("POST", "js/2020/SQL/grade_performance.php?Grade=" + Grade + "&Gender=" + Gender +
        "&Nationality=" + Nationality + "&Student=" + Student +
        "&Term1=" + Term1 + "&Term2=" + Term2 + "&Term3=" + Term3 + "&Term4=" + Term4 +
        "&View=" + View, false);
    httpSearch.send();
}