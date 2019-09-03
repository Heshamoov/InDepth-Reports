

document.getElementById("subject").addEventListener("change", fillTerms());


function fillTerms() {

    var selected_years = $("#academic_year option:selected");
    var selected_grades = $("#grade option:selected");
    var selected_batches = $("#batch option:selected");
    var selected_subjects = $("#subject option:selected");


    var select = document.getElementById('term');

    while (select.length > 0)
        select.remove(0);

    var message = "";
    selected_years.each(function () {
        if (message === "") {

            message = "   (academic_years.name = '" + $(this).text() + "'";
        } else {
            message += " OR academic_years.name = '" + $(this).text() + "'";
        }
    });

    if (message !== "") {

        selected_years = message + ")";
    } else
        selected_years = "";


    var message = "";
    selected_grades.each(function () {
        if (message === "") {
            if (selected_years !== "")
                message = " AND (courses.course_name = '" + $(this).text() + "' ";
            else
                message = " (courses.course_name = '" + $(this).text() + "' ";
        } else {
            message += " OR courses.course_name = '" + $(this).text() + "' ";
        }
    });

    if (message !== "") {

        selected_grades = message + ")";
    } else
        selected_grades = "";


    var message = "";
    selected_batches.each(function () {
        if (message === "") {
            if (selected_years !== "" || selected_grades !== "")
                message = " AND (batches.name = '" + $(this).text() + "' ";
            else
                message = " (batches.name = '" + $(this).text() + "' ";
        } else {
            message += " OR batches.name = '" + $(this).text() + "' ";
        }
    });

    if (message !== "") {

        selected_batches = message + ")";
    } else
        selected_batches = "";


    var message = "";
    selected_subjects.each(function () {
        if (message === "") {
            if (selected_years !== "" || selected_grades !== "" || selected_batches !== "")
                message = " AND (subjects.name = '" + $(this).text() + "' ";
            else
                message = " (subjects.name = '" + $(this).text() + "' ";
        } else {
            message += " OR subjects.name = '" + $(this).text() + "' ";
        }
    });

    if (message !== "") {

        selected_subjects = message + ")";
    } else
        selected_subjects = "";





    var httpTerms = new XMLHttpRequest();
    httpTerms.onreadystatechange = function () {
        if (this.readyState === 4) {
            var str = this.responseText;
            termsArray = str.split("\t");
        }
    };

    httpTerms.open("GET", "sqldb/_TermsViaYearGradeSectionSubject.php?years=" + selected_years + "&grades=" + selected_grades + "&batches=" + selected_batches + "&subjects=" + selected_subjects, false);
    httpTerms.send();
    $('#term').multiselect('destroy');

    delete termsArray[termsArray.length - 1];
    for (var i in termsArray) {
        select.add(new Option(termsArray[i]));
        //                 document.write(termsArray[i]);
    }
    ;


    $(function () {
        $('#term').multiselect({
            includeSelectAllOption: true
        });
    });
}

    