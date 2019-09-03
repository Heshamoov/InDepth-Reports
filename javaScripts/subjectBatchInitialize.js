

document.getElementById("batch").addEventListener("change", fillSubjects());


function fillSubjects() {

    var selected_years = $("#academic_year option:selected");
    var selected_grades = $("#grade option:selected");
    var selected_batches = $("#batch option:selected");

    var select = document.getElementById('subject');

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





    var httpSubjects = new XMLHttpRequest();
    httpSubjects.onreadystatechange = function () {
        if (this.readyState === 4) {
            var str = this.responseText;
            subjectsArray = str.split("\t");
        }
    };

    httpSubjects.open("GET", "sqldb/_subjectsViaBatchGradeYear.php?years=" + selected_years + "&grades=" + selected_grades + "&batches=" + selected_batches, false);
    httpSubjects.send();
    $('#subject').multiselect('destroy');

    delete subjectsArray[subjectsArray.length - 1];
    for (var i in subjectsArray) {
        select.add(new Option(subjectsArray[i]));
        //                 document.write(batchesArray[i]);
    }
    ;


    $(function () {
        $('#subject').multiselect({
            includeSelectAllOption: true


        });
    });
}

    