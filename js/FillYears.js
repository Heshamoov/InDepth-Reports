function FillYears() {
    var yearArray = ["Your Data Base is Empty!."];
    var httpyear = new XMLHttpRequest();
    httpyear.onreadystatechange = function () {
        if (this.readyState === 4) {
            var str = this.responseText;
            yearArray = str.split("\t");
        }
    };
    httpyear.open("GET", "sqldb/initAcademicYears.php", false);
    httpyear.send();

    var select = document.getElementById('T1-YR');
    var select2 = document.getElementById('T2-YR');

    delete yearArray[yearArray.length - 1];

    for (var i in yearArray) {
        select.add(new Option(yearArray[i]));
        select2.add(new Option(yearArray[i]));
    }

    $(function () {
        $('#T1-YR').multiselect({
            includeSelectAllOption: true
        });
        $('#T2-YR').multiselect({
            includeSelectAllOption: true
        });
    });
}