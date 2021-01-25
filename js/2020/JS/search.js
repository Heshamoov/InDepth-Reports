$(function () {
    $('#year').multiselect({includeSelectAllOption: false});
    $('#grade').multiselect({includeSelectAllOption: false});
    $('#grade1').multiselect({includeSelectAllOption: false});
    $('#grade2').multiselect({includeSelectAllOption: false});
    $('#grade3').multiselect({includeSelectAllOption: false});
    $('#grade4').multiselect({includeSelectAllOption: false});
    $('#grade5').multiselect({includeSelectAllOption: false});
    $('#gender').multiselect({includeSelectAllOption: false});
    $('#student').multiselect({includeSelectAllOption: false});
    $('#nationality').multiselect({includeSelectAllOption: false});
    $('#view').multiselect({includeSelectAllOption: false});
    $('#term1').multiselect({includeSelectAllOption: false});
    $('#term2').multiselect({includeSelectAllOption: false});
    $('#term3').multiselect({includeSelectAllOption: false});
    $('#term4').multiselect({includeSelectAllOption: false});
    $('#term5').multiselect({includeSelectAllOption: false});
});


function search() {
    let Grade = $("#grade option:selected").text();
    let Grade1 = $("#grade1 option:selected").text();
    let Grade2 = $("#grade2 option:selected").text();
    let Grade3 = $("#grade3 option:selected").text();
    let Grade4 = $("#grade4 option:selected").text();
    let Grade5 = $("#grade5 option:selected").text();

    let Gender = $("#gender option:selected").text();
    let Nationality = $("#nationality option:selected").text();
    let Student = $("#student option:selected").text();
    let View = $("#view option:selected").text();
    let Term1 = $("#term1 option:selected").text();
    let Term2 = $("#term2 option:selected").text();
    let Term3 = $("#term3 option:selected").text();
    let Term4 = $("#term4 option:selected").text();
    let Term5 = $("#term5 option:selected").text();

    let Title = "";

    if (Grade !== "Grade")
        Title = Grade;
    if (Student === "" || Student === "Student")
        Title = Title + "&nbsp&nbsp&nbsp-&nbsp&nbsp&nbsp" + Nationality + "&nbsp&nbsp&nbsp-&nbsp&nbsp&nbsp" + Gender;
    else
        Title = Title + "&nbsp&nbsp&nbsp-&nbsp&nbsp&nbsp" + Student;


    document.getElementById('TableTitle').innerHTML = Title;

    // terms1SQL += " OR REPLACE(exam_name, ' ', '') = REPLACE('" + currentTerm + "', ' ', '')";
    let httpSearch = new XMLHttpRequest();
    httpSearch.onreadystatechange = function () {
        if (this.readyState === 4)
            document.getElementById("results").innerHTML = this.responseText;
    };

    httpSearch.open("POST", "js/2020/SQL/new_attainment.php?Grade=" + Grade + "&Grade1=" + Grade1 + "&Grade2=" + Grade2 + "&Grade3=" + Grade3 +
        "&Grade4=" + Grade4 + "&Grade5=" + Grade5 + "&Gender=" + Gender + "&Nationality=" + Nationality + "&Student=" + Student + "&Term1=" + Term1 + "&Term2=" + Term2 + "&Term3=" + Term3 + "&Term4=" + Term4 + "&Term5=" + Term5 +
        "&View=" + View, false);
    httpSearch.send();
}