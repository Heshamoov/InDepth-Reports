

document.getElementById("grade").addEventListener("change", fillBatches());


function fillBatches() {

    var selected_years = $("#academic_year option:selected");
    var selected_grades = $("#grade option:selected");

    var select = document.getElementById('batch');

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

    var httpBatches = new XMLHttpRequest();
    httpBatches.onreadystatechange = function () {
        if (this.readyState === 4) {
            var str = this.responseText;
            batchesArray = str.split("\t");
        }
    };

    httpBatches.open("GET", "sqldb/_batchesViaGradeYear.php?years=" + selected_years + "&grades=" + selected_grades, false);
    httpBatches.send();
    $('#batch').multiselect('destroy');

    delete batchesArray[batchesArray.length - 1];
    for (var i in batchesArray) {
        select.add(new Option(batchesArray[i]));
        //                 document.write(batchesArray[i]);
    }
    ;


    $(function () {
        $('#batch').multiselect({
            includeSelectAllOption: true
        });
    });
}

   