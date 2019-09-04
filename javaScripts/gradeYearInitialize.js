document.getElementById("academic_year").addEventListener("change", fillGrades());


function fillGrades() {

    var selected_years = $("#academic_year option:selected");
    var select = document.getElementById('grade');
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

    var httpgrades = new XMLHttpRequest();
    httpgrades.onreadystatechange = function () {
        if (this.readyState === 4) {
            var str = this.responseText;
            gradesArray = str.split("\t");
        }
    };
    httpgrades.open("GET", "sqldb/distinctGrades.php?years=" + selected_years, false);
    httpgrades.send();
    $('#grade').multiselect('destroy');

    delete gradesArray[gradesArray.length - 1];
    for (var i in gradesArray) {
        select.add(new Option(gradesArray[i]));

    }
    ;


    $(function () {
        $('#grade').multiselect({
            includeSelectAllOption: true
        });
    });
}
