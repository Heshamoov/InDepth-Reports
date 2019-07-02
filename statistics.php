<?php include('Header.php'); ?>

<title>Statistics</title>

</head>

<!--loader initialization-->
<script>
    $(window).load(function () {
        // Animate loader off screen
        $(".se-pre-con").fadeOut("slow");
        ;
    });
</script>







<script type="text/javascript">


    $(function () {
        $('#academic_year').multiselect({includeSelectAllOption: true});
        $('#term').multiselect({includeSelectAllOption: true});
        $('#grade').multiselect({includeSelectAllOption: true});
        $('#batch').multiselect({includeSelectAllOption: true});
        $('#subject').multiselect({includeSelectAllOption: true});
        $('#gender').multiselect({includeSelectAllOption: true});
        $('#category').multiselect({includeSelectAllOption: true});



        $(document).on("ready click", function () {

            google.charts.setOnLoadCallback(drawChart);
            google.charts.setOnLoadCallback(drawChartSubjects);

            var selected_years = $("#academic_year option:selected");
            var selected_terms = $("#term option:selected");
            var selected_grades = $("#grade option:selected");
            var selected_batches = $("#batch option:selected");
            var selected_subjects = $("#subject option:selected");
            var selected_gender = $("#gender option:selected");
            var selected_category = $("#category option:selected");


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

                    academicHeader = " - " + currentYear;
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
                        message = "  AND (subjects.name  LIKE '" + currentSubject + "%' ";  //Add '%' to the end of the subject name: WHERE subject LIKE 'Math%' 
                    else
                        message = "  (subjects.name LIKE '" + currentSubject + "%' ";
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
                httpTotal.open("POST", "sqldb/subjectCount.php?years=" + selected_years + "&grades=" + selected_grades + "&batches=" + selected_batches + "&terms=" + selected_terms + "&gender=" + selected_gender + "&category=" + selected_category + "&subject=" + currentSubject, false);
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
                    httpBetween.open("POST", "sqldb/subjectBetween.php?years=" + selected_years + "&grades=" + selected_grades + "&batches=" + selected_batches + "&terms=" + selected_terms + "&gender=" + selected_gender + "&category=" + selected_category + "&subject=" + currentSubject + "&min=" + min + "&max=" + max, false);
                    httpBetween.send();
                }
            });


            if (message !== "")
                selected_subjects = message + ")";
            else
                selected_subjects = "";

            stable.rows[0].cells[0].innerHTML = "Statistics: " + termHeader + " " + gradeHeader + " " + batchHeader + "" + "  " + subjectHeader + "  " + genderHeader;
            stablePDF.rows[0].cells[0].innerHTML = termHeader + " " + gradeHeader + " " + batchHeader + " " + " ( " + subjectHeader + " ) " + genderHeader;

            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
                if (this.readyState === 4)
                    document.getElementById("out").innerHTML = this.responseText;
            };
            xmlhttp.open("POST", "sqldb/statisticsSearch.php?years=" + selected_years + "&grades=" + selected_grades + "&batches=" + selected_batches + "&terms=" + selected_terms + "&gender=" + selected_gender + "&category=" + selected_category + "&subject=" + selected_subjects, false);
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
            xmlhttp.open("POST", "sqldb/count.php?years=" + selected_years + "&grades=" + selected_grades + "&batches=" + selected_batches + "&terms=" + selected_terms + "&gender=" + selected_gender + "&category=" + selected_category + "&subject=" + selected_subjects, false);
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
                xmlhttpm1.open("POST", "sqldb/between.php?years=" + selected_years + "&grades=" + selected_grades + "&batches=" + selected_batches + "&terms=" + selected_terms + "&gender=" + selected_gender + "&category=" + selected_category + "&subject=" + selected_subjects + "&min=" + min + "&max=" + max, false);
                xmlhttpm1.send();
            }

        });
    });




</script>

<script>
    var imgData = new Array();

    google.charts.load("current", {
        packages: ['corechart']
    });
    google.charts.setOnLoadCallback(drawChart);
    google.charts.setOnLoadCallback(drawChartSubjects);

    function drawChart() {
        var value1, value2, value3, value4, value5, value6, result1, result2, result3, tableName, header;
        var tableName = document.getElementById("stable");
        value1 = tableName.rows[1].cells[2].childNodes[0].value;
        value2 = tableName.rows[1].cells[2].childNodes[2].value;
        value3 = tableName.rows[1].cells[3].childNodes[0].value;
        value4 = tableName.rows[1].cells[3].childNodes[2].value;
        value5 = tableName.rows[1].cells[4].childNodes[0].value;
        value6 = tableName.rows[1].cells[4].childNodes[2].value;
        result1 = tableName.rows[2].cells[2].innerHTML;
        result2 = tableName.rows[2].cells[3].innerHTML;
        result3 = tableName.rows[2].cells[4].innerHTML;
        header = tableName.rows[0].cells[0].innerHTML;



        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Number of Students');
        data.addColumn('number', 'Students');
        data.addColumn({type: 'string', role: 'style'});

        data.addRows([
            [value1.toString() + '% - ' + value2.toString() + "% ", Number(result1), ' yellow'],
            [value3.toString() + '% - ' + value4.toString() + "% ", Number(result2), 'orange'],
            [value5.toString() + '% - ' + value6.toString() + "%", Number(result3), ' lime'],
        ]);
        var options = {title: header, legend: {position: "none"}};


        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
        chart.draw(data, options);
        imgData[0] = chart.getImageURI();


    }
    ;





    function drawChartSubjects() {

        for (t = 1; t < 13; t++)
        {
            table = "T" + t;
            var value1, value2, value3, result1, result2, result3, tableName, header;
            var tableName = document.getElementById(table);

            value1 = tableName.rows[1].cells[2].innerHTML;
            value2 = tableName.rows[1].cells[3].innerHTML;
            value3 = tableName.rows[1].cells[4].innerHTML;
            result1 = tableName.rows[2].cells[2].innerHTML;
            result2 = tableName.rows[2].cells[3].innerHTML;
            result3 = tableName.rows[2].cells[4].innerHTML;
            header = tableName.rows[0].cells[0].innerHTML;

            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Number of Students');
            data.addColumn('number', 'Students');
            data.addColumn({type: 'string', role: 'style'});

            data.addRows([
                [value1.toString(), Number(result1), ' yellow'],
                [value2.toString(), Number(result2), 'orange'],
                [value3.toString(), Number(result3), ' lime'],
            ]);
            var options = {title: header, legend: {position: "none"}};

            chartName = "chart" + t;
            var chartS = new google.visualization.ColumnChart(document.getElementById(chartName));
            chartS.draw(data, options);
            imgData[t] = chartS.getImageURI();

        }
    }
    ;



</script>

<body >

    <div class="se-pre-con"></div>


    <div class=" w3-responsive header" >

        <!-- Navigation bar -->        
        <?php include('navbar.php'); ?>

        <!--set color for current tab-->
        <script>
            document.getElementById("navStatistics").style.backgroundColor = '#009688';
        </script>

        <!--End of Navictacoin bar-->

        <!--Drop menus-->
        <div id="upperdiv" class="w3-container w3-mobile" style="padding-top: 10px; padding-bottom: 10px">
            <table id= "table1">

                <tr>
                    <td></td><td>Academic Year</td><td>Grade</td>
                    <td>Section</td>  <td>Subject</td>  <td>Term</td><td>Gender</td><td>Category</td><td></td>
                </tr>
                <tr>
                    <td>
                        <button class="w3-button w3-round-xlarge w3-hover-blue-gray w3-medium w3-custom" id="exportS" onclick="downloadStatistics()()" title="Export Statistics as PDF">                          <span class="material-icons">save_alt</span></button>
                    </td>
                    <td>
                        <select   id="academic_year" onchange="fillGrades()"  multiple="multiple"></select>   
                    </td>
                    <td>
                        <select     id="grade" onchange="fillBatches()" multiple="multiple"   ></select>   
                    </td>
                    <td >

                        <select  id ="batch"  onchange="fillSubjects()"  multiple="multiple"  ></select>  
                    </td>
                    <td>
                        <select id="subject"  multiple="multiple"></select>
                    </td>
                    <td>
                        <select id="term" multiple="multiple"></select>         
                    </td>
                    <td>
                        <select id="gender"  multiple="multiple"> 
                            <option>Male</option>
                            <option>Female</option> 
                        </select>
                    </td>
                    <td>
                        <select id="category" multiple="multiple"></select>         
                    </td>

                    <td>
                        <button style="padding: 15px 32px 32px 32px;text-align: center ;font-size: 14px;" class="w3-button w3-hover-blue-gray w3-custom w3-round-large " id="search" title="View Results"><span class="fa fa-search"></span></button>
                    </td>

                    <td>
                        <button  class="w3-button w3-hover-blue-gray w3-custom w3-medium w3-round-xlarge" id="exportM" onclick="downloadStudents()" title="Export Marks as PDF"> <span class="material-icons ">save_alt</span></button>
                    </td>

                </tr>

            </table>

        </div>

        <!--Drop menus-->

        <div class="w3-container w3-col m4 l5 w3-mobile" id="tables" style="overflow: scroll;top: 0;  bottom: 0; height: 100vh; " >
            <textarea id="output" rows="10" cols="50" hidden></textarea>
            <br>
            <table class=" w3-table-all w3-striped w3-bordered w3-centered w3-card-4" id="stable">  
                <th colspan="4" class="w3-custom " style="font-size: 16px">Statistics 
                </th>
                <th colspan="1" class="w3-custom">  <button  style="float: right;"type="button" class="btn w3-button w3-hover-blue-gray w3-custom" 
                                                             data-toggle="popover" >
                        <span class="material-icons ">signal_cellular_alt</span>
                    </button></th>


                <tr>
                    <th class="w3-border-right">Academic Year</th><th class="w3-border-right">Marks Count</th>
                    <th class="w3-border-right"><input id="percent11" type="text" value= 50>% - <input id="percente12" type="text" value=100>%</th>
                    <th class="w3-border-right"><input id="percent21" type="text" value=65>% - <input id="percente22" type="text" value=100>%</th>
                    <th class="w3-border-right"><input id="percent31" type="text" value=75>% - <input id="percente32" type="text" value=100>%</th>
                </tr>
                <tr>
                    <td class="w3-border-right">2017-2018</td>
                    <td class="w3-border-right"></td>
                    <td class="w3-border-right"></td>
                    <td class="w3-border-right"></td>
                    <td class="w3-border-right"></td>
                </tr>


            </table>
            <br><br>

            <table id="stablePDF" style="font-size: 100px" hidden>
                <thead>
                    <tr>
                        <th colspan="5"></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody> 
                    <tr>
                        <th>Year</th>
                        <th>Total Number</th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                    <tr>
                        <td>2018-2019</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>

            <table id="T1" class=" w3-table-all w3-striped w3-bordered w3-centered w3-card-4" >
                <th colspan="4" class="w3-custom" style="font-size: 16px">Subject</th>
                <th colspan="1" class="w3-custom">  <button  style="float: right;"type="button" class="btn w3-button w3-hover-blue-gray w3-custom" 
                                                             data-toggle="popoverSubject1" onclick="drawChartSubjects();">
                        <span class="material-icons ">signal_cellular_alt</span>
                    </button></th>


                <tr>
                    <th class="w3-border-right">Academic Year</th><th class="w3-border-right">Total</th>
                    <th class="w3-border-right"></th><th class="w3-border-right"></th><th class="w3-border-right"></th>
                </tr>
                <tr>
                    <td class="w3-border-right"></td><td class="w3-border-right"></td><td class="w3-border-right"></td>
                    <td class="w3-border-right"></td><td class="w3-border-right"></td>
                </tr>
            </table>
            <br>

            <table class=" w3-table-all w3-striped w3-bordered w3-centered w3-card-4" id="T2">  
                <th colspan="4" class="w3-custom" style="font-size: 16px;">Subject</th>
                <th colspan="1" class="w3-custom">  <button  style="float: right;"type="button" class="btn w3-button w3-hover-blue-gray w3-custom" 
                                                             data-toggle="popoverSubject2" onclick="drawChartSubjects();">
                        <span class="material-icons ">signal_cellular_alt</span>
                    </button></th>
                <tr>
                    <th class="w3-border-right">Academic Year</th><th class="w3-border-right">Total</th>
                    <th class="w3-border-right"></th><th class="w3-border-right"></th><th class="w3-border-right"></th>
                </tr>
                <tr>
                    <td class="w3-border-right"></td><td class="w3-border-right"></td><td class="w3-border-right"></td>
                    <td class="w3-border-right"></td><td class="w3-border-right"></td>
                </tr>
            </table>

            <br>

            <table class=" w3-table-all w3-striped w3-bordered w3-centered w3-card-4" id="T3">  
                <th colspan="4" class="w3-custom" style="font-size: 16px">Subject</th>
                <th colspan="1" class="w3-custom">  <button  style="float: right;"type="button" class="btn w3-button w3-hover-blue-gray w3-custom" 
                                                             data-toggle="popoverSubject3" onclick="drawChartSubjects();">
                        <span class="material-icons ">signal_cellular_alt</span>
                    </button></th>
                <tr>
                    <th class="w3-border-right">Academic Year</th><th class="w3-border-right">Total</th>
                    <th class="w3-border-right"></th><th class="w3-border-right"></th><th class="w3-border-right"></th>
                </tr>
                <tr>
                    <td class="w3-border-right"></td><td class="w3-border-right"></td><td class="w3-border-right"></td>
                    <td class="w3-border-right"></td><td class="w3-border-right"></td>
                </tr>
            </table>

            <br>

            <table class=" w3-table-all w3-striped w3-bordered w3-centered w3-card-4" id="T4">
                <th colspan="4" class="w3-custom" style="font-size: 16px">Subject</th>
                <th colspan="1" class="w3-custom">  <button  style="float: right;"type="button" class="btn w3-button w3-hover-blue-gray w3-custom" 
                                                             data-toggle="popoverSubject4" onclick="drawChartSubjects();">
                        <span class="material-icons ">signal_cellular_alt</span>
                    </button></th>
                <tr>
                    <th class="w3-border-right">Academic Year</th><th class="w3-border-right">Total</th>
                    <th class="w3-border-right"></th><th class="w3-border-right"></th><th class="w3-border-right"></th>
                </tr>
                <tr>
                    <td class="w3-border-right"></td><td class="w3-border-right"></td><td class="w3-border-right"></td>
                    <td class="w3-border-right"></td><td class="w3-border-right"></td>
                </tr>
            </table>

            <br>

            <table class=" w3-table-all w3-striped w3-bordered w3-centered w3-card-4" id="T5">  
                <th colspan="4" class="w3-custom" style="font-size: 16px">Subject</th>
                <th colspan="1" class="w3-custom">  <button  style="float: right;"type="button" class="btn w3-button w3-hover-blue-gray w3-custom" 
                                                             data-toggle="popoverSubject5" onclick="drawChartSubjects();">
                        <span class="material-icons ">signal_cellular_alt</span>
                    </button></th>
                <tr>
                    <th class="w3-border-right">Academic Year</th><th class="w3-border-right">Total</th>
                    <th class="w3-border-right"></th><th class="w3-border-right"></th><th class="w3-border-right"></th>
                </tr>
                <tr>
                    <td class="w3-border-right"></td><td class="w3-border-right"></td><td class="w3-border-right"></td>
                    <td class="w3-border-right"></td><td class="w3-border-right"></td>
                </tr>
            </table>

            <br>

            <table class=" w3-table-all w3-striped w3-bordered w3-centered w3-card-4" id="T6">  
                <th colspan="4" class="w3-custom" style="font-size: 16px">Subject</th>
                <th colspan="1" class="w3-custom">  <button  style="float: right;"type="button" class="btn w3-button w3-hover-blue-gray w3-custom" 
                                                             data-toggle="popoverSubject6" onclick="drawChartSubjects();">
                        <span class="material-icons ">signal_cellular_alt</span>
                    </button></th>
                <tr>
                    <th class="w3-border-right">Academic Year</th><th class="w3-border-right">Total</th>
                    <th class="w3-border-right"></th><th class="w3-border-right"></th><th class="w3-border-right"></th>
                </tr>
                <tr>
                    <td class="w3-border-right"></td><td class="w3-border-right"></td><td class="w3-border-right"></td>
                    <td class="w3-border-right"></td><td class="w3-border-right"></td>
                </tr>
            </table>

            <br>

            <table class=" w3-table-all w3-striped w3-bordered w3-centered w3-card-4" id="T7">  
                <th colspan="4" class="w3-custom" style="font-size: 16px">Subject</th>
                <th colspan="1" class="w3-custom">  <button  style="float: right;"type="button" class="btn w3-button w3-hover-blue-gray w3-custom" 
                                                             data-toggle="popoverSubject7" onclick="drawChartSubjects();">
                        <span class="material-icons ">signal_cellular_alt</span>
                    </button></th>
                <tr>
                    <th class="w3-border-right">Academic Year</th><th class="w3-border-right">Total</th>
                    <th class="w3-border-right"></th><th class="w3-border-right"></th><th class="w3-border-right"></th>
                </tr>
                <tr>
                    <td class="w3-border-right"></td><td class="w3-border-right"></td><td class="w3-border-right"></td>
                    <td class="w3-border-right"></td><td class="w3-border-right"></td>
                </tr>
            </table>

            <br>

            <table class=" w3-table-all w3-striped w3-bordered w3-centered w3-card-4" id="T8">  
                <th colspan="4" class="w3-custom" style="font-size: 16px">Subject</th>
                <th colspan="1" class="w3-custom">  <button  style="float: right;"type="button" class="btn w3-button w3-hover-blue-gray w3-custom" 
                                                             data-toggle="popoverSubject8" onclick="drawChartSubjects();">
                        <span class="material-icons ">signal_cellular_alt</span>
                    </button></th>
                <tr>
                    <th class="w3-border-right">Academic Year</th><th class="w3-border-right">Total</th>
                    <th class="w3-border-right"></th><th class="w3-border-right"></th><th class="w3-border-right"></th>
                </tr>
                <tr>
                    <td class="w3-border-right"></td><td class="w3-border-right"></td><td class="w3-border-right"></td>
                    <td class="w3-border-right"></td><td class="w3-border-right"></td>
                </tr>
            </table>

            <br>

            <table class=" w3-table-all w3-striped w3-bordered w3-centered w3-card-4" id="T9">  
                <th colspan="4" class="w3-custom" style="font-size: 16px">Subject</th>
                <th colspan="1" class="w3-custom">  <button  style="float: right;"type="button" class="btn w3-button w3-hover-blue-gray w3-custom" 
                                                             data-toggle="popoverSubject9" onclick="drawChartSubjects();">
                        <span class="material-icons ">signal_cellular_alt</span>
                    </button></th>
                <tr>
                    <th class="w3-border-right">Academic Year</th><th class="w3-border-right">Total</th>
                    <th class="w3-border-right"></th><th class="w3-border-right"></th><th class="w3-border-right"></th>
                </tr>
                <tr>    
                    <td class="w3-border-right"></td><td class="w3-border-right"></td><td class="w3-border-right"></td>
                    <td class="w3-border-right"></td><td class="w3-border-right"></td>
                </tr>
            </table>

            <br>

            <table class=" w3-table-all w3-striped w3-bordered w3-centered w3-card-4" id="T10">  
                <th colspan="4" class="w3-custom" style="font-size: 16px">Subject</th>
                <th colspan="1" class="w3-custom">  <button  style="float: right;"type="button" class="btn w3-button w3-hover-blue-gray w3-custom" 
                                                             data-toggle="popoverSubject10" onclick="drawChartSubjects();">
                        <span class="material-icons ">signal_cellular_alt</span>
                    </button></th>
                <tr>
                    <th class="w3-border-right">Academic Year</th><th class="w3-border-right">Total</th>
                    <th class="w3-border-right"></th><th class="w3-border-right"></th><th class="w3-border-right"></th>
                </tr>
                <tr>
                    <td class="w3-border-right"></td><td class="w3-border-right"></td><td class="w3-border-right"></td>
                    <td class="w3-border-right"></td><td class="w3-border-right"></td>
                </tr>
            </table>

            <br>

            <table class=" w3-table-all w3-striped w3-bordered w3-centered w3-card-4" id="T11">  
                <th colspan="4" class="w3-custom" style="font-size: 16px">Subject</th>
                <th colspan="1" class="w3-custom">  <button  style="float: right;"type="button" class="btn w3-button w3-hover-blue-gray w3-custom" 
                                                             data-toggle="popoverSubject11" onclick="drawChartSubjects();">
                        <span class="material-icons ">signal_cellular_alt</span>
                    </button></th>
                <tr>
                    <th class="w3-border-right">Academic Year</th><th class="w3-border-right">Total</th>
                    <th class="w3-border-right"></th><th class="w3-border-right"></th><th class="w3-border-right"></th>
                </tr>
                <tr>
                    <td class="w3-border-right"></td><td class="w3-border-right"></td><td class="w3-border-right"></td>
                    <td class="w3-border-right"></td><td class="w3-border-right"></td>
                </tr>
            </table>

            <br>

            <table class=" w3-table-all w3-striped w3-bordered w3-centered w3-card-4" id="T12">  
                <th colspan="4" class="w3-custom" style="font-size: 16px">Subject</th>
                <th colspan="1" class="w3-custom">  <button  style="float: right;"type="button" class="btn w3-button w3-hover-blue-gray w3-custom" 
                                                             data-toggle="popoverSubject12" onclick="drawChartSubjects();">
                        <span class="material-icons ">signal_cellular_alt</span>
                    </button></th>
                <tr>
                    <th class="w3-border-right">Academic Year</th><th class="w3-border-right">Total</th>
                    <th class="w3-border-right"></th><th class="w3-border-right"></th><th class="w3-border-right"></th>
                </tr>
                <tr>
                    <td class="w3-border-right"></td><td class="w3-border-right"></td><td class="w3-border-right"></td>
                    <td class="w3-border-right"></td><td class="w3-border-right"></td>
                </tr>
            </table>

        </div>

        <div class="w3-col m8 l7 w3-card-4 w3-mobile" id="rightdiv" style = "height:100vh; overflow: scroll; padding-top: 10px; padding-left: 10px; padding-right: 10px"> 
            <!--Downloading table  11:52 AM-->   
            <br>

            <table class="w3-table-all w3-card-4 w3-striped w3-hoverable" id="out" ></table>
            <table id="TT1" hidden>
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Year</td>
                        <td>Total Number</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <table id="TT2" hidden>
                <thead><tr><th></th><th></th><th></th><th></th><th></th></tr></thead>
                <tbody><tr><td>Year</td><td>Total Number</td><td></td><td></td><td></td></tr>
                    <tr><td></td><td></td><td></td><td></td><td></td></tr></tbody>
            </table>
            <table id="TT3" hidden>
                <thead><tr><th></th><th></th><th></th><th></th><th></th></tr></thead>
                <tbody><tr><td>Year</td><td>Total Number</td><td></td><td></td><td></td></tr>
                    <tr><td></td><td></td><td></td><td></td><td></td></tr></tbody>
            </table>
            <table id="TT4" hidden>
                <thead><tr><th></th><th></th><th></th><th></th><th></th></tr></thead>
                <tbody><tr><td>Year</td><td>Total Number</td><td></td><td></td><td></td></tr>
                    <tr><td></td><td></td><td></td><td></td><td></td></tr></tbody>
            </table>
            <table id="TT5" hidden>
                <thead><tr><th></th><th></th><th></th><th></th><th></th></tr></thead>
                <tbody><tr><td>Year</td><td>Total Number</td><td></td><td></td><td></td></tr>
                    <tr><td></td><td></td><td></td><td></td><td></td></tr></tbody>
            </table>
            <table id="TT6" hidden>
                <thead><tr><th></th><th></th><th></th><th></th><th></th></tr></thead>
                <tbody><tr><td>Year</td><td>Total Number</td><td></td><td></td><td></td></tr>
                    <tr><td></td><td></td><td></td><td></td><td></td></tr></tbody>
            </table>
            <table id="TT7" hidden>
                <thead><tr><th></th><th></th><th></th><th></th><th></th></tr></thead>
                <tbody><tr><td>Year</td><td>Total Number</td><td></td><td></td><td></td></tr>
                    <tr><td></td><td></td><td></td><td></td><td></td></tr></tbody>
            </table>
            <table id="TT8" hidden>
                <thead><tr><th></th><th></th><th></th><th></th><th></th></tr></thead>
                <tbody><tr><td>Year</td><td>Total Number</td><td></td><td></td><td></td></tr>
                    <tr><td></td><td></td><td></td><td></td><td></td></tr></tbody>
            </table>
            <table id="TT9" hidden>
                <thead><tr><th></th><th></th><th></th><th></th><th></th></tr></thead>
                <tbody><tr><td>Year</td><td>Total Number</td><td></td><td></td><td></td></tr>
                    <tr><td></td><td></td><td></td><td></td><td></td></tr></tbody>
            </table>
            <table id="TT10" hidden>
                <thead><tr><th></th><th></th><th></th><th></th><th></th></tr></thead>
                <tbody><tr><td>Year</td><td>Total Number</td><td></td><td></td><td></td></tr>
                    <tr><td></td><td></td><td></td><td></td><td></td></tr></tbody>
            </table>
            <table id="TT11" hidden>
                <thead><tr><th></th><th></th><th></th><th></th><th></th></tr></thead>
                <tbody><tr><td>Year</td><td>Total Number</td><td></td><td></td><td></td></tr>
                    <tr><td></td><td></td><td></td><td></td><td></td></tr></tbody>
            </table>
            <table id="TT12" hidden>
                <thead><tr><th></th><th></th><th></th><th></th><th></th></tr></thead>
                <tbody><tr><td>Year</td><td>Total Number</td><td></td><td></td><td></td></tr>
                    <tr><td></td><td></td><td></td><td></td><td></td></tr></tbody>
            </table>    
        </div>

        <button onclick="topFunction()" style="left:0; padding: 10px;" class=" w3-hover-blue-gray w3-small w3-round-xxlarge" id="myBtn" title="Scroll to top"><span class="glyphicon glyphicon-arrow-up"style="font-size: 25px;" ></span></button>

        <!--Scroll Handling-->
    </div>
    <script>
        document.getElementById("tables").onscroll = function () {
            scrollFunction();
        };
        function scrollFunction() {
            if (document.getElementById("tables").scrollTop > 50) {
                document.getElementById("myBtn").style.display = "block";
            } else
                document.getElementById("myBtn").style.display = "none";
        }
        ;
        function topFunction() {
            document.getElementById("tables").scrollTop = 0;


        }
        ;
    </script>

    <!--Initialize Academic Years->-->     
    <script type="text/javascript">
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

    </script>
    <!--
    
             

    <!--Initialize Grade drop down-->     
    <script type="text/javascript">
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

    </script>

<!--
    Initialize Batch drop down     --> 
<script type="text/javascript">
    
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

    </script>

    
    
    
    
    <!--
    Initialize Subject drop down     --> 
<script type="text/javascript">
    
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

    </script>
    
    
    
    
    

<!--    Initialize Subject drop down     
    <script type="text/javascript">
        var subjectsArray = ["Your Data Base is Empty!."];

        var httpSubjects = new XMLHttpRequest();
        httpSubjects.onreadystatechange = function () {
            if (this.readyState === 4) {
                var str = this.responseText;
                subjectsArray = str.split("\t");
            }
        };
        httpSubjects.open("GET", "sqldb/initSubjects.php", false);
        httpSubjects.send();

        var select = document.getElementById('subject');

        delete subjectsArray[subjectsArray.length - 1];
        for (var i in subjectsArray) {
            select.add(new Option(subjectsArray[i]));
        }
        ;

        $(function () {
            $('#subject').multiselect({
                includeSelectAllOption: true
            });
        });
    </script>-->

    <!--Term drop down  AND Tables initializer-->  
    <script type="text/javascript">
        for (var i = 1; i < 13; i++)
        {
            var tableName = 'T' + i;
            document.getElementById(tableName).style.visibility = "hidden";
        }
        var termsArray = ["Your Data Base is Empty!."];
        var httpterms = new XMLHttpRequest();
        httpterms.onreadystatechange = function () {
            if (this.readyState === 4) {
                var str = this.responseText;
                termsArray = str.split("\t");
            }
        };
        httpterms.open("GET", "sqldb/displayTerms.php", false);
        httpterms.send();

        var select = document.getElementById('term');
        delete termsArray[termsArray.length - 1];
        for (var i in termsArray) {
            select.add(new Option(termsArray[i]));
        }
        ;

        $(function () {
            $('#term').multiselect({
                includeSelectAllOption: true
            });
        });
    </script>


    <!--Initialize Student Category -->     
    <script type="text/javascript">
        var categoryArray = ["Your Data Base is Empty!."];

        var httpcategory = new XMLHttpRequest();
        httpcategory.onreadystatechange = function () {
            if (this.readyState === 4) {
                var str = this.responseText;
                categoryArray = str.split("\t");
            }
        };
        httpcategory.open("GET", "sqldb/distinctStudentCategory.php", false);
        httpcategory.send();

        var select = document.getElementById('category');
        delete categoryArray[categoryArray.length - 1];
        for (var i in categoryArray) {
            select.add(new Option(categoryArray[i]));
        }
        ;
        $(function () {
            $('#category').multiselect({
                includeSelectAllOption: true
            });
        });

    </script>



    --><script>
        function downloadStudents() {

            var doc = new jsPDF('p', 'pt', 'a4');
            var table = doc.autoTableHtmlToJson(document.getElementById("out"));
            var header = function (data) {
                doc.setFontSize(16);
                doc.setFontStyle('normal');
                doc.text("Students List", 250, 50);
                doc.line(250, 53, 335, 53);// Header top margin
                // Header top margin
            };

            doc.autoTable(table.columns, table.data, {beforePageContent: header, theme: 'grid', margin: {top: 70}, styles: {
                    fontSize: 12,
                    font: 'PTSans'
                }
            });
            doc.save("Students.pdf");
        }
    </script><!--
    --><script>
        function downloadStatistics() {

            var doc = new jsPDF('p', 'pt');
            var header = function (data) {
                doc.setFontSize(18);
                doc.setFont('PTSans');
                doc.text("Subject Wise Statistics", 225, 50);
                doc.line(226, 53, 390, 53);// Header top margin
            };

            var table = doc.autoTableHtmlToJson(stablePDF);
            doc.autoTable(table.columns, table.data, {beforePageContent: header, theme: 'grid', margin: {top: 70, left: 40, right: 40}, columnStyles: {
                    0: {columnWidth: 205},
                    1: {columnWidth: 80},
                    2: {columnWidth: 80},
                    3: {columnWidth: 80}
                }, styles: {
                    fontSize: 12,
                    font: 'PTSans',
                }

            });

            var tableName = "";
            var i = 1;
            $('#subject').multiselect({includeSelectAllOption: true});
            var selected_subjects = $("#subject option:selected");
            selected_subjects.each(function () {

                tableName = 'TT' + i;
                var table = doc.autoTableHtmlToJson(document.getElementById(tableName));
                doc.autoTable(table.columns, table.data, {startY: doc.autoTable.previous.finalY + 14, margin: {left: 40, right: 40}, theme: 'grid', columnStyles: {
                        0: {columnWidth: 205},
                        1: {columnWidth: 80},
                        2: {columnWidth: 80},
                        3: {columnWidth: 80}
                    }, styles: {
                        fontSize: 12,
                        font: 'PTSans'
                    }});
                i++;
            });
            doc.save("Statistics.pdf");
        }
    </script>

    --><script>
        function downloadPopoverStatistics() {
            var doc = new jsPDF('p', 'pt');
            var header = function (data) {
                doc.setFontSize(18);
                doc.setFontStyle('PTSans');
                doc.text("Statistics", 225, 50);
                doc.line(226, 53, 290, 53);// Header top margin
            };
            doc.addImage(imgData[0], 'jpg', 80, 180, 300, 150);
            var table = doc.autoTableHtmlToJson(stablePDF);
            doc.autoTable(table.columns, table.data, {beforePageContent: header, theme: 'grid', margin: {top: 70, left: 40, right: 40}, columnStyles: {
                    0: {columnWidth: 205},
                    1: {columnWidth: 80},
                    2: {columnWidth: 80},
                    3: {columnWidth: 80},
                    4: {columnWidth: 80}

                }, styles: {
                    fontSize: 12,
                    font: 'PTSans',
                    halign: 'center'

                }

            });


            doc.save("Statistics.pdf");
        }
    </script>

    --><script>
        function downloadPopoverSubjects(tno) {
            var doc = new jsPDF('p', 'pt');
            var tableName = "";
            var header = function (data) {
                doc.setFontSize(18);
                doc.setFontStyle('PTSans');
                doc.text("Subject wise Statistics", 225, 50);
                doc.line(226, 53, 390, 53);// Header top margin
            };


            tableName = "TT" + tno;
            doc.addImage(imgData[tno], 'png', 80, 180, 300, 200);
            var table = doc.autoTableHtmlToJson(document.getElementById(tableName));
            doc.autoTable(table.columns, table.data, {beforePageContent: header, theme: 'grid', margin: {top: 70, left: 40, right: 40}, columnStyles: {
                    0: {columnWidth: 205},
                    1: {columnWidth: 80},
                    2: {columnWidth: 80},
                    3: {columnWidth: 80},
                    4: {columnWidth: 80}
                }, styles: {
                    fontSize: 12,
                    font: 'PTSans',
                    halign: 'center'
                }

            });

            doc.save("Subject.pdf");
        }
    </script>


    <script>

        $(document).ready(function () {
            $('[data-toggle="popover"]').popover(
                    {
                        trigger: "manual",
                        html: true,
                        content: function () {

                            return $('#popcontainer').html();
                        }
                    }).on("mouseenter", function () {
                var _this = this;
                $(this).popover("show");
                $(".popover").on("mouseleave", function () {
                    $(_this).popover('hide');
                });
            });

        });

    </script>

    <script>

        $(document).ready(function () {
            $('[data-toggle="popoverSubject1"]').popover(
                    {
                        trigger: "manual",
                        html: true,
                        content: function () {

                            return $('#popcontainerSubject1').html();
                        }
                    }).on("mouseenter", function () {
                var _this = this;
                $(this).popover("show");
                $(".popover").on("mouseleave", function () {
                    $(_this).popover('hide');
                });
            });

        });

    </script>


    <script>

        $(document).ready(function () {
            $('[data-toggle="popoverSubject2"]').popover(
                    {
                        trigger: "manual",
                        html: true,
                        content: function () {

                            return $('#popcontainerSubject2').html();
                        }
                    }).on("mouseenter", function () {
                var _this = this;
                $(this).popover("show");
                $(".popover").on("mouseleave", function () {
                    $(_this).popover('hide');
                });
            });

        });

    </script>

    <script>

        $(document).ready(function () {
            $('[data-toggle="popoverSubject3"]').popover(
                    {
                        trigger: "manual",
                        html: true,
                        content: function () {

                            return $('#popcontainerSubject3').html();
                        }
                    }).on("mouseenter", function () {
                var _this = this;
                $(this).popover("show");
                $(".popover").on("mouseleave", function () {
                    $(_this).popover('hide');
                });
            });

        });

    </script>

    <script>

        $(document).ready(function () {
            $('[data-toggle="popoverSubject4"]').popover(
                    {
                        trigger: "manual",
                        html: true,
                        content: function () {

                            return $('#popcontainerSubject4').html();
                        }
                    }).on("mouseenter", function () {
                var _this = this;
                $(this).popover("show");
                $(".popover").on("mouseleave", function () {
                    $(_this).popover('hide');
                });
            });

        });

    </script>

    <script>

        $(document).ready(function () {
            $('[data-toggle="popoverSubject5"]').popover(
                    {
                        trigger: "manual",
                        html: true,
                        content: function () {

                            return $('#popcontainerSubject5').html();
                        }
                    }).on("mouseenter", function () {
                var _this = this;
                $(this).popover("show");
                $(".popover").on("mouseleave", function () {
                    $(_this).popover('hide');
                });
            });

        });

    </script>

    <script>

        $(document).ready(function () {
            $('[data-toggle="popoverSubject6"]').popover(
                    {
                        trigger: "manual",
                        html: true,
                        content: function () {

                            return $('#popcontainerSubject6').html();
                        }
                    }).on("mouseenter", function () {
                var _this = this;
                $(this).popover("show");
                $(".popover").on("mouseleave", function () {
                    $(_this).popover('hide');
                });
            });

        });

    </script>

    <script>

        $(document).ready(function () {
            $('[data-toggle="popoverSubject7"]').popover(
                    {
                        trigger: "manual",
                        html: true,
                        content: function () {

                            return $('#popcontainerSubject7').html();
                        }
                    }).on("mouseenter", function () {
                var _this = this;
                $(this).popover("show");
                $(".popover").on("mouseleave", function () {
                    $(_this).popover('hide');
                });
            });

        });

    </script>

    <script>

        $(document).ready(function () {
            $('[data-toggle="popoverSubject8"]').popover(
                    {
                        trigger: "manual",
                        html: true,
                        content: function () {

                            return $('#popcontainerSubject8').html();
                        }
                    }).on("mouseenter", function () {
                var _this = this;
                $(this).popover("show");
                $(".popover").on("mouseleave", function () {
                    $(_this).popover('hide');
                });
            });

        });

    </script>

    <script>

        $(document).ready(function () {
            $('[data-toggle="popoverSubject9"]').popover(
                    {
                        trigger: "manual",
                        html: true,
                        content: function () {

                            return $('#popcontainerSubject9').html();
                        }
                    }).on("mouseenter", function () {
                var _this = this;
                $(this).popover("show");
                $(".popover").on("mouseleave", function () {
                    $(_this).popover('hide');
                });
            });

        });

    </script>


    <script>

        $(document).ready(function () {
            $('[data-toggle="popoverSubject10"]').popover(
                    {
                        trigger: "manual",
                        html: true,
                        content: function () {

                            return $('#popcontainerSubject10').html();
                        }
                    }).on("mouseenter", function () {
                var _this = this;
                $(this).popover("show");
                $(".popover").on("mouseleave", function () {
                    $(_this).popover('hide');
                });
            });

        });

    </script>

    <script>

        $(document).ready(function () {
            $('[data-toggle="popoverSubject11"]').popover(
                    {
                        trigger: "manual",
                        html: true,
                        content: function () {

                            return $('#popcontainerSubject11').html();
                        }
                    }).on("mouseenter", function () {
                var _this = this;
                $(this).popover("show");
                $(".popover").on("mouseleave", function () {
                    $(_this).popover('hide');
                });
            });

        });

    </script>

    <script>

        $(document).ready(function () {
            $('[data-toggle="popoverSubject12"]').popover(
                    {
                        trigger: "manual",
                        html: true,
                        content: function () {

                            return $('#popcontainerSubject12').html();
                        }
                    }).on("mouseenter", function () {
                var _this = this;
                $(this).popover("show");
                $(".popover").on("mouseleave", function () {
                    $(_this).popover('hide');
                });
            });

        });

    </script>


    <div id = "popcontainer" class="popover-content-el hide " style="width:400px; "  >

        <div id="chart_div" style="width:400px; "  >

        </div>
        <h6   style="float: left; cursor: pointer; color: gray">Click to view details</h6>
        <button class="  w3-hover-teal  w3-round-xxlarge " id="exportS" style="float: right; margin-bottom: 10px; color: teal" onclick="downloadPopoverStatistics()" title="Download Graph">
            <span class="material-icons">save_alt</span></button>
    </div>

    <div id = "popcontainerSubject1" class="popover-content-el hide  " style="width:400px; "  >
        <div id="chart1" style="width:400px; "  >

        </div>
        <h6   style="float: left;cursor: pointer; color: gray">Click to view details</h6>
        <button class="  w3-hover-teal  w3-round-xxlarge "  style="float: right; margin-bottom: 10px; color: teal" onclick="downloadPopoverSubjects(1)" title="Download Graph">
            <span class="material-icons">save_alt</span></button>
    </div>

    <div id = "popcontainerSubject2" class="popover-content-el hide " style="width:400px; "  >
        <div id="chart2" style="width:400px; "  >

        </div>
        <h6   style="float: left;cursor: pointer; color: gray">Click to view details</h6>
        <button class="  w3-hover-teal  w3-round-xxlarge "  style="float: right; margin-bottom: 10px; color: teal" onclick="downloadPopoverSubjects(2)" title="Download Graph">
            <span class="material-icons">save_alt</span></button>
    </div>

    <div id = "popcontainerSubject3" class="popover-content-el hide " style="width:400px; "  >
        <div id="chart3" style="width:400px; "  >

        </div>
        <h6   style="float: left;cursor: pointer; color: gray">Click to view details</h6>
        <button class=" w3-hover-teal w3-round-xxlarge "  style="float: right; color: teal; margin-bottom: 10px" onclick="downloadPopoverSubjects(3)" title="Download Graph">
            <span class="material-icons">save_alt</span></button>
    </div>
    <div id = "popcontainerSubject4" class="popover-content-el hide " style="width:400px; "  >
        <div id="chart4" style="width:400px; "  >

        </div>
        <h6   style="float: left; cursor: pointer; color: gray">Click to view details</h6>
        <button class="w3-hover-teal w3-round-xxlarge"  style="float: right;color : teal; margin-bottom: 10px" onclick="downloadPopoverSubjects(4)" title="Download Graph">
            <span class="material-icons">save_alt</span></button>
    </div>
    <div id = "popcontainerSubject5" class="popover-content-el hide  " style="width:400px; "  >
        <div id="chart5" style="width:400px; "  >

        </div>
        <h6   style="float: left;cursor: pointer; color: gray">Click to view details</h6>
        <button class="  w3-hover-teal w3-round-xxlarge "  style="float: right; margin-bottom: 10px; color :teal" onclick="downloadPopoverSubjects(5)" title="Download Graph">
            <span class="material-icons">save_alt</span></button>
    </div>
    <div id = "popcontainerSubject6" class="popover-content-el hide " style="width:400px; "  >
        <div id="chart6" style="width:400px; "  >

        </div>
        <h6   style="float: left;cursor: pointer; color: gray">Click to view details</h6>
        <button class="w3-hover-teal  w3-round-xxlarge "  style="float: right;color :teal; margin-bottom: 10px" onclick="downloadPopoverSubjects(6)" title="Download Graph">
            <span class="material-icons">save_alt</span></button>
    </div>
    <div id = "popcontainerSubject7" class="popover-content-el hide " style="width:400px; "  >
        <div id="chart7" style="width:400px; "  >

        </div>
        <h6   style="float: left;cursor: pointer; color: gray">Click to view details</h6>
        <button class="  w3-hover-teal  w3-round-xxlarge "  style="float: right; margin-bottom: 10px; color : teal" onclick="downloadPopoverSubjects(7)" title="Download Graph">
            <span class="material-icons">save_alt</span></button>
    </div>
    <div id = "popcontainerSubject8" class="popover-content-el hide " style="width:400px; "  >
        <div id="chart8" style="width:400px; "  >

        </div>
        <h6   style="float: left;cursor: pointer; color: gray">Click to view details</h6>
        <button class=" w3-hover-teal  w3-round-xxlarge "  style="float: right; margin-bottom: 10px; color : teal" onclick="downloadPopoverSubjects(8)" title="Download Graph">
            <span class="material-icons">save_alt</span></button>
    </div>
    <div id = "popcontainerSubject9" class="popover-content-el hide " style="width:400px; "  >
        <div id="chart9" style="width:400px; "  >

        </div>
        <h6   style="float: left;cursor: pointer; color: gray">Click to view details</h6>
        <button class="  w3-hover-teal w3-round-xxlarge "  style="float: right; margin-bottom: 10px; color:teal" onclick="downloadPopoverSubjects(9)" title="Download Graph">
            <span class="material-icons">save_alt</span></button>
    </div>
    <div id = "popcontainerSubject10" class="popover-content-el hide " style="width:400px; "  >
        <div id="chart10" style="width:400px; "  >

        </div>
        <h6   style="float: left;cursor: por-blue-grayinter; color: gray">Click to view details</h6>
        <button class="  w3-hover-teal w3-round-xxlarge "  style="float: right; color:teal; margin-bottom: 10px" onclick="downloadPopoverSubjects(10)" title="Download Graph">
            <span class="material-icons">save_alt</span></button>
    </div>
    <div id = "popcontainerSubject11" class="popover-content-el hide " style="width:400px; "  >
        <div id="chart11" style="width:400px; "  >

        </div>
        <h6   style="float: left;cursor: pointer; color: gray">Click to view details</h6>
        <button class="  w3-hover-teal w3-round-xxlarge "  style="float: right; margin-bottom: 10px; color : teal" onclick="downloadPopoverSubjects(11)" title="Download Graph">
            <span class="material-icons">save_alt</span></button>
    </div>
    <div id = "popcontainerSubject12" class="popover-content-el hide " style="width:400px; "  >
        <div id="chart12" style="width:400px; "  >

        </div>
        <h6   style="float: left;cursor: pointer; color: gray">Click to view details</h6>
        <button class=" w3-hover-teal  w3-round-xxlarge "  style="float: right;color : teal; margin-bottom: 10px" onclick="downloadPopoverSubjects(12)" title="Download Graph">
            <span class="material-icons">save_alt</span></button>
    </div>


</body>
</html>

