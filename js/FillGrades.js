function FillGrades(term,grade) {
    var year = document.getElementById(term).options[document.getElementById(term).selectedIndex].text;
        year = "academic_years.name = '" + year + "'";

    var GradeSelect = document.getElementById(grade);
    
    while (GradeSelect.length > 0)
        GradeSelect.remove(0);

    var httpgrades = new XMLHttpRequest();
    httpgrades.onreadystatechange = function () {
        if (this.readyState === 4) {
            var str = this.responseText;
//            document.getElementById("chart2").innerHTML = this.responseText;
            gradesArray = str.split("\t");
        }
    };
    httpgrades.open("GET", "sqldb/distinctGrades.php?year=" + year, false);
    httpgrades.send();

    $(GradeSelect).multiselect('destroy');

    delete gradesArray[gradesArray.length - 1];

    for (var i in gradesArray)
        GradeSelect.add(new Option(gradesArray[i]));

    $(function () {
        $(GradeSelect).multiselect({
            includeSelectAllOption: true
            });
    });
};