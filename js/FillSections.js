function FillSections(Year, Grade, Section) {
    var year = document.getElementById(Year).options[document.getElementById(Year).selectedIndex].text;
    var grade = document.getElementById(Grade).options[document.getElementById(Grade).selectedIndex].text;

    if (grade !== 'Select Grade') {
        var select = document.getElementById(Section);
        
        while (select.length > 0)
            select.remove(0);

        var httpSections = new XMLHttpRequest();
        httpSections.onreadystatechange = function () {
            if (this.readyState === 4) {
                var str = this.responseText;
                // document.getElementById('chart2').innerHTML = this.responseText;
                sectionsArray = str.split("\t");
            }
        };
        httpSections.open("GET", "sqldb/distinctBatches.php?year=" + year + "&grade=" + grade, false);
        httpSections.send();

        $(select).multiselect('destroy');
        delete sectionsArray[sectionsArray.length - 1];
        for (var i in sectionsArray) {
            select.add(new Option(sectionsArray[i]));
        }
        ;
        $(function () {
            $(select).multiselect({
                includeSelectAllOption: true
            });
        });
    }
};