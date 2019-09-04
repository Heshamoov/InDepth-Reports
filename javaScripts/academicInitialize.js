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

        var select = document.getElementById('academic_year');
        delete yearArray[yearArray.length - 1];
        for (var i in yearArray) {
            select.add(new Option(yearArray[i]));
        }
        ;
        $(function () {
            $('#academic_year').multiselect({
                includeSelectAllOption: true
            });
        });


