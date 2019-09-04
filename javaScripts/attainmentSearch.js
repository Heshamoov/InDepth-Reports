

$(function () {
    $('#term #grade #batch #subject #gender #academic_year #category').multiselect({includeSelectAllOption: true});




    $(document).on("ready click", function () {

        google.charts.setOnLoadCallback(drawChart);
        google.charts.setOnLoadCallback(drawChartSubjects);

        var selected_terms = $("#term option:selected");
        var selected_grades = $("#grade option:selected");
        var selected_batches = $("#batch option:selected");
        var selected_subjects = $("#subject option:selected");
        var selected_years = $("#academic_year option:selected");
        var selected_category = $("#category option:selected");
        var selected_gender = $("#gender option:selected");



        //Academic Years                
        var message = "";
        var academicHeader = "";
        selected_years.each(function () {
            var currentYear = $(this).text();
            if (currentYear.indexOf("(") !== -1) {
                var bracketIndex = currentYear.indexOf("(");
                currentYear = currentYear.slice(0, bracketIndex);
            }
            if (message === "") {
                message = "   (academic_years.name = '" + currentYear + "' ";

                academicHeader = "  " + currentYear;
            } else {
                message += " OR academic_years.name = '" + currentYear + "'";  //  grade like 'GR1' OR grade like 'GR10';
                academicHeader += " , " + currentYear;
            }
        });
        if (message !== "")
            selected_years = message + ")";
        else
            selected_years = "";


        //Grades                
        var message = "";
        var gradeHeader = "";
        selected_grades.each(function () {
            var currentGrade = $(this).text();
            if (currentGrade.indexOf("(") !== -1) {
                var bracketIndex = currentGrade.indexOf("(");
                currentGrade = currentGrade.slice(0, bracketIndex);
            }
            if (message === "") {
                if (selected_years !== "")
                    message = " AND (courses.course_name = '" + currentGrade + "' ";
                else
                    message = " (courses.course_name = '" + currentGrade + "'";
                gradeHeader = " - " + currentGrade;
            } else {
                message += " OR courses.course_name = '" + currentGrade + "'";  //  grade like 'GR1' OR grade like 'GR10';
                gradeHeader += " , " + currentGrade;
            }
        });
        if (message !== "")
            selected_grades = message + ")";
        else
            selected_grades = "";


        //Batches
        var message = "";
        var batchHeader = "";
        selected_batches.each(function () {
            if (message === "") {
                if (selected_grades !== "" || selected_years !== "")
                    message = " AND (batches.name = '" + $(this).text() + "' ";
                else
                    message = " (batches.name = '" + $(this).text() + "' ";
                batchHeader = " - " + $(this).text();
            } else {
                message += " OR batches.name = '" + $(this).text() + "' ";
                batchHeader += " , " + $(this).text();
            }
        });
        if (message !== "")
            selected_batches = message + ")";
        else
            selected_batches = "";


        //Terms
        var message = "";
        var termHeader = "";
        selected_terms.each(function () {
            if (message === "") {
                if (selected_batches !== "" || selected_grades !== "" || selected_years !== "")
                    message = " AND (exam_groups.name = '" + $(this).text() + "'";
                else
                    message = "   (exam_groups.name = '" + $(this).text() + "'";

                termHeader = $(this).text();
            } else {
                message += " OR exam_groups.name = '" + $(this).text() + "'";
                termHeader += " , " + $(this).text();
            }
        });
        if (message !== "")
            selected_terms = message + ")";
        else
            selected_terms = "";


        //Gender
        var message = "";
        var genderHeader = "";
        selected_gender.each(function () {

            var DB_Gender = "";
            if ($(this).text() === 'Male')
                DB_Gender = 'm';
            else if ($(this).text() === 'Female')
                DB_Gender = 'f';

            if (message === "") {
                if (selected_terms !== "" || selected_grades !== "" || selected_batches !== "" || selected_years !== "")
                    message = " AND (gender = '" + DB_Gender + "' ";
                else
                    message = " (gender = '" + DB_Gender + "' ";
                genderHeader = " - " + $(this).text();
            } else {
                message += "OR gender = '" + DB_Gender + "' ";
                genderHeader += " , " + $(this).text();
            }
        });
        if (message !== "")
            selected_gender = message + ")";
        else
            selected_gender = "";


        //Category               
        var message = "";
        var categoryHeader = "";
        selected_category.each(function () {
            var currentCategory = $(this).text();
            if (currentCategory.indexOf("(") !== -1) {
                var bracketIndex = currentCategory.indexOf("(");
                currentCategory = currentCategory.slice(0, bracketIndex);
            }
            if (message === "") {
                if (selected_gender !== "" || selected_terms !== "" || selected_grades !== "" || selected_batches !== "" || selected_years !== "")
                    message = "  AND (student_categories.name = '" + currentCategory + "' ";
                else
                    message = "  (student_categories.name = '" + currentCategory + "'";
                categoryHeader = " - " + currentCategory;
            } else {
                message += " OR student_categories.name = '" + currentCategory + "'";  //  grade like 'GR1' OR grade like 'GR10';
                categoryHeader += " , " + currentCategory;
            }
        });
        if (message !== "")
            selected_category = message + ")";
        else
            selected_category = "";




        //Generate Tables
        for (var i = 1; i < 13; i++)
        {
            var tableName = 'T' + i;
            document.getElementById(tableName).style.visibility = "hidden";
        }
        var message = "";
        var subjectHeader = "";
        var tableNumber = 0;

        //Subjects
        selected_subjects.each(function () {
            var currentSubject = "";
            var firstSpace = true;
            var subject = $(this).text();
            for (var i = 0; i < subject.length; i++) {       // Extracting English letters and numbers and remove Arabic letters                
                if ((subject[i] >= 'A' && subject[i] <= 'z') || (subject[i] >= '0' && subject[i] <= '9'))
                    currentSubject += subject[i];
                if (subject[i] === ' ' && firstSpace && i > 3) {
                    currentSubject += subject[i];
                    firstSpace = false;
                }
            }

            tableNumber++;
            if (message === "") {
                if (selected_terms !== "" || selected_grades !== "" || selected_batches !== "" || selected_gender !== "" || selected_years !== "" || selected_category !== "")
                    message = " AND (subjects.name  LIKE '" + currentSubject + "%' ";  //Add '%' to the end of the subject name: WHERE subject LIKE 'Math%' 
                else
                    message = " (subjects.name LIKE '" + currentSubject + "%' ";
                subjectHeader = " - " + currentSubject;
            } else {
                message += "OR subjects.name  LIKE '" + currentSubject + "%' ";
                subjectHeader += " , " + currentSubject;
            }


            tableName = "T" + tableNumber;
            var tableNeme2 = 'TT' + tableNumber;
            document.getElementById(tableName).style.visibility = "Visible";
            var table = document.getElementById(tableName);
            var table2 = document.getElementById(tableNeme2);
            table.rows[0].cells[0].innerHTML = currentSubject;  //head
            table2.rows[0].cells[0].innerHTML = currentSubject; //head                        
            //Academic //Total
            var min = 0, max = 0;                                                                    // Head values
            for (var i = 2; i < 5; i++)
            {
                min = stable.rows[1].cells[i].childNodes[0].value;
                max = stable.rows[1].cells[i].childNodes[2].value;
                table.rows[1].cells[i].innerHTML = min + "% - " + max + "%";
                table2.rows[1].cells[i].innerHTML = min + "% - " + max + "%";
            }

            //Academic Year value
            stablePDF.rows[2].cells[0].innerHTML = "2018-2019";
            table.rows[2].cells[0].innerHTML = "2018-2019";
            table2.rows[2].cells[0].innerHTML = "2018-2019";

            // Total value Subject wise
            var httpTotal = new XMLHttpRequest();
            httpTotal.onreadystatechange = function () {
                if (this.readyState === 4) {
                    table.rows[2].cells[1].innerHTML = this.responseText;
                    table2.rows[2].cells[1].innerHTML = this.responseText;
                }
            };
            httpTotal.open("POST", "sqldb/subjectCount.php?terms=" + selected_terms + "&years=" + selected_years + "&grades=" + selected_grades + "&batches=" + selected_batches + "&subject=" + currentSubject + "&gender=" + selected_gender + "&category=" + selected_category, false);
            httpTotal.send();




            //Between values Subject wise
            var min = 0, max = 0;
            for (var i = 2; i < 5; i++)
            {
                min = stable.rows[1].cells[i].childNodes[0].value;
                max = stable.rows[1].cells[i].childNodes[2].value;
                var httpBetween = new XMLHttpRequest();
                httpBetween.onreadystatechange = function () {
                    if (this.readyState === 4) {
                        table.rows[2].cells[i].innerHTML = this.responseText;
                        table2.rows[2].cells[i].innerHTML = this.responseText;

                    }
                };
                httpBetween.open("POST", "sqldb/subjectBetween.php?terms=" + selected_terms + "&years=" + selected_years + "&grades=" + selected_grades + "&batches=" + selected_batches + "&subject=" + currentSubject + "&gender=" + selected_gender + "&category=" + selected_category + "&min=" + min + "&max=" + max, false);
                httpBetween.send();
            }
        });

        if (message !== "")
            selected_subjects = message + ")";
        else
            selected_subjects = "";

        stable.rows[0].cells[0].innerHTML = academicHeader + " " + termHeader + " " + gradeHeader + " " + batchHeader + "" + "  " + subjectHeader + "  " + genderHeader;
        stablePDF.rows[0].cells[0].innerHTML = termHeader + " " + gradeHeader + " " + batchHeader + " " + " ( " + subjectHeader + " ) " + genderHeader;

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState === 4)
                document.getElementById("out").innerHTML = this.responseText;
        };
        xmlhttp.open("POST", "sqldb/attainmentSearch.php?terms=" + selected_terms + "&years=" + selected_years + "&grades=" + selected_grades + "&batches=" + selected_batches + "&subjects=" + selected_subjects + "&gender=" + selected_gender + "&category=" + selected_category, false);
        xmlhttp.send();

        //Total Count
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState === 4) {

                stable.rows[2].cells[1].innerHTML = this.responseText;
                stablePDF.rows[2].cells[1].innerHTML = this.responseText;
                drawChart();
            }
        };
        xmlhttp.open("POST", "sqldb/count.php?terms=" + selected_terms + "&years=" + selected_years + "&grades=" + selected_grades + "&batches=" + selected_batches + "&subject=" + selected_subjects + "&gender=" + selected_gender + "&category=" + selected_category, false);
        xmlhttp.send();

        //Statistics Min-Max
        var min = 0, max = 0;
        for (var i = 2; i < 5; i++)
        {
            min = stable.rows[1].cells[i].childNodes[0].value;
            max = stable.rows[1].cells[i].childNodes[2].value;
            stablePDF.rows[1].cells[i].innerHTML = min + "% - " + max + "%";
            var xmlhttpm1 = new XMLHttpRequest();
            xmlhttpm1.onreadystatechange = function () {

                if (this.readyState === 4) {
                    stable.rows[2].cells[i].innerHTML = this.responseText;
                    stablePDF.rows[2].cells[i].innerHTML = this.responseText;
                    drawChart();
                }
            };
            xmlhttpm1.open("POST", "sqldb/between.php?terms=" + selected_terms + "&years=" + selected_years + "&grades=" + selected_grades + "&batches=" + selected_batches + "&subject=" + selected_subjects + "&gender=" + selected_gender + "&category=" + selected_category + "&min=" + min + "&max=" + max, false);
            xmlhttpm1.send();
        }
    });
});




    