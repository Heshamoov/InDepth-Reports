function FillSubjects(Year, Grade, Subject) {
    var year = document.getElementById(Year).options[document.getElementById(Year).selectedIndex].text;
    var grade = document.getElementById(Grade).options[document.getElementById(Grade).selectedIndex].text;
    
    if (year !== 'Select Year' || grade !== 'Select Grade') {
        year = "academic_years.name = '" + year + "' ";
        grade = "And courses.course_name = '" + grade + "' ";

        var subject = document.getElementById(Subject);
        while (subject.length > 0)
            subject.remove(0);

        var httpSubjects = new XMLHttpRequest();
        httpSubjects.onreadystatechange = function () {
            if (this.readyState === 4) {
                var str = this.responseText;
//                 document.getElementById('chart2').innerHTML = this.responseText;
                subjectsArray = str.split("\t");
            }
        };
        httpSubjects.open("GET", "sqldb/_subjectsViaBatchGradeYear.php?years=" + year + "&grades=" + grade, false);
        httpSubjects.send();

        $(subject).multiselect('destroy');
        delete subjectsArray[subjectsArray.length - 1];
        for (var i in subjectsArray)
            subject.add(new Option(subjectsArray[i]));
        
        $(function () {
            $(subject).multiselect({
                includeSelectAllOption: true
            });
        });
    }
};