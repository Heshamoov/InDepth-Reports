<?php
session_start();
if (!isset($_SESSION['login'])) {
    $_SESSION['notloggedin'] = 1;
    header('Location: index.php');
} else {
    include('Header.php');
    ?>

    <title>Attainment Analysis</title>
    
    </head>

    <!--loder initialization-->
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
        });
        window.onload = function () {
            search();
        };
        function search() {
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
                    academicHeader = currentYear;
                } else {
                    message += " OR academic_years.name = '" + currentYear + "'";  //  grade like 'GR1' OR grade like 'GR10';
                    academicHeader += ", " + currentYear;
                }
            });
            if (message !== "")
                selected_years = message + ")";
            else
                selected_years = "";


            //Grades                
            var message = "";
            var gradeHeader = "";
            var grades_sql = "";
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
                    gradeHeader = currentGrade;
                } else {
                    message += " OR courses.course_name = '" + currentGrade + "'";  //  grade like 'GR1' OR grade like 'GR10';
                    gradeHeader += " , " + currentGrade;
                }
            });
            if (message !== "")
                grades_sql = message + ")";
            else
                grades_sql = "";


            //Batches
            var message = "";
            var batchHeader = "";
            selected_batches.each(function () {
                if (message === "") {
                    if (grades_sql !== "" || selected_years !== "")
                        message = " AND (batches.name = '" + $(this).text() + "' ";
                    else
                        message = " (batches.name = '" + $(this).text() + "' ";
                    batchHeader = $(this).text();
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
                    if (selected_batches !== "" || grades_sql !== "" || selected_years !== "")
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
                    if (selected_terms !== "" || grades_sql !== "" || selected_batches !== "" || selected_years !== "")
                        message = " AND (gender = '" + DB_Gender + "' ";
                    else
                        message = " (gender = '" + DB_Gender + "' ";
                    genderHeader = $(this).text();
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
                    if (selected_gender !== "" || selected_terms !== "" || grades_sql !== "" || selected_batches !== "" || selected_years !== "")
                        message = "  AND (student_categories.name = '" + currentCategory + "' ";
                    else
                        message = "  (student_categories.name = '" + currentCategory + "'";
                    categoryHeader = currentCategory;
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

            var message = "", subjectHeader = "", tableNumber = 0, currentGradeSQL = "";

    //              Generate Tables By Grades, Subjects
            var selected_grades = $("#grade option:selected");
            selected_grades.each(function () {
                var currentGrade = $(this).text();
                if (currentGrade !== "")
                    if (selected_years !== "")
                        currentGradeSQL = " AND (courses.course_name = '" + currentGrade + "') ";
                    else
                        currentGradeSQL = " (courses.course_name = '" + currentGrade + "') ";

                //Subjects
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

                    if (message === "") {
                        if (selected_terms !== "" || currentGradeSQL !== "" || selected_batches !== "" || selected_gender !== "" || selected_years !== "" || selected_category !== "")
                            message = "  AND (subjects.name  LIKE '" + currentSubject + "%' ";  //Add '%' to the end of the subject name: WHERE subject LIKE 'Math%'
                        else
                            message = "  (subjects.name LIKE '" + currentSubject + "%' ";
                        subjectHeader = currentSubject;
                    } else {
                        message += "OR subjects.name  LIKE '" + currentSubject + "%' ";
                        subjectHeader += " , " + currentSubject;
                    }

                    tableNumber++;
                    tableName = "T" + tableNumber;
                    var tableNeme2 = 'TT' + tableNumber;
                    document.getElementById(tableName).style.visibility = "Visible";
                    var table = document.getElementById(tableName);
                    var table2 = document.getElementById(tableNeme2);
                    table.rows[0].cells[0].innerHTML = currentGrade + " - " + currentSubject;  //head
                    table2.rows[0].cells[0].innerHTML = currentGrade + " - " + currentSubject; //head                        
                    //Academic //Total
                    var min = 0, max = 0;
                    for (var i = 1; i < 4; i++)
                    {
                        min = stable.rows[1].cells[i].childNodes[0].value;
                        max = stable.rows[1].cells[i].childNodes[2].value;
                        table.rows[1].cells[i].innerHTML = min + "% - " + max + "%";
                        table2.rows[1].cells[i].innerHTML = min + "% - " + max + "%";
                    }

                    // Total Count Subject-Wise
                    var httpTotal = new XMLHttpRequest();
                    httpTotal.onreadystatechange = function () {
                        if (this.readyState === 4) {
                            table.rows[2].cells[0].innerHTML = this.responseText;
                            table2.rows[2].cells[0].innerHTML = this.responseText;
                            document.getElementById("out").innerHTML = this.responseText;
                        }
                    };
                    httpTotal.open("POST", "sqldb/subjectCount.php?years=" + selected_years + "&grades=" + currentGradeSQL + "&batches=" + selected_batches + "&terms=" + selected_terms + "&gender=" + selected_gender + "&category=" + selected_category + "&subject=" + currentSubject, false);
                    httpTotal.send();


                    //Between values Subject wise
                    var min = 0, max = 0;
                    for (var i = 1; i < 4; i++)
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
                        httpBetween.open("POST", "sqldb/subjectBetween.php?years=" + selected_years + "&grades=" + currentGradeSQL + "&batches=" + selected_batches + "&terms=" + selected_terms + "&gender=" + selected_gender + "&category=" + selected_category + "&subject=" + currentSubject + "&min=" + min + "&max=" + max, false);
                        httpBetween.send();
                    }
                });
            });

            if (message !== "")
                selected_subjects = message + ")";
            else
                selected_subjects = "";

            if (academicHeader === "" && termHeader === "" && gradeHeader === "" && batchHeader === "" && subjectHeader === "" && genderHeader === "")
            {
                StatisticsTitle.rows[0].cells[0].innerHTML = "Year 2018-2019";
                StatisticsTitle.rows[0].cells[1].innerHTML = "GR1-A2019";
                StatisticsTitle.rows[0].cells[2].innerHTML = "Term1-2019";
                StatisticsTitle.rows[1].cells[0].innerHTML = "SUBJECTS";
                StatisticsTitlePDF.rows[0].cells[0].innerHTML = "Year 2018-2019 - GR1-A2019 - Term1-2019 ";
                StatisticsTitlePDF.rows[1].cells[0].innerHTML = "SUBJECTS"


    //                    stablePDF.rows[0].cells[0].innerHTML = "Year (2018-2019) Grade (GR1-A) Term 1";
    //                    stablePDF.rows[2].cells[0].innerHTML = "2018-2019";
            } else
            {
                StatisticsTitle.rows[0].cells[0].innerHTML = "Year " + academicHeader;
                StatisticsTitle.rows[0].cells[1].innerHTML = gradeHeader;
                StatisticsTitle.rows[0].cells[2].innerHTML = termHeader;
                StatisticsTitle.rows[1].cells[0].innerHTML = subjectHeader;
                stable.rows[2].cells[0].innerHTML = academicHeader;
                stablePDF.rows[2].cells[0].innerHTML = academicHeader;
                StatisticsTitlePDF.rows[0].cells[0].innerHTML = academicHeader + " - " + gradeHeader + " - " + termHeader;
                StatisticsTitlePDF.rows[1].cells[0].innerHTML = subjectHeader;
            }

            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
                if (this.readyState === 4)
                    document.getElementById("out").innerHTML = this.responseText;
            };
            xmlhttp.open("POST", "sqldb/attainmentSearch.php?years=" + selected_years + "&grades=" + grades_sql + "&batches=" + selected_batches + "&terms=" + selected_terms + "&gender=" + selected_gender + "&category=" + selected_category + "&subjects=" + selected_subjects, false);
            xmlhttp.send();

            //Total Count
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
                if (this.readyState === 4) {
                    stable.rows[2].cells[0].innerHTML = this.responseText;
                    stablePDF.rows[2].cells[0].innerHTML = this.responseText;
                    drawChart();
                }
            };
            xmlhttp.open("POST", "sqldb/count.php?years=" + selected_years + "&grades=" + grades_sql + "&batches=" + selected_batches + "&terms=" + selected_terms + "&gender=" + selected_gender + "&category=" + selected_category + "&subject=" + selected_subjects, false);
            xmlhttp.send();

            //Statistics Min-Max
            var min = 0, max = 0;
            for (var i = 1; i < 4; i++)
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
                xmlhttpm1.open("POST", "sqldb/between.php?years=" + selected_years + "&grades=" + grades_sql + "&batches=" + selected_batches + "&terms=" + selected_terms + "&gender=" + selected_gender + "&category=" + selected_category + "&subject=" + selected_subjects + "&min=" + min + "&max=" + max, false);
                xmlhttpm1.send();
            }
            document.getElementById('loading').style.visibility = 'hidden';
            document.getElementById('search').style.visibility = 'visible';}






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

    <body>
    <!-- Modal -->
    <div class="modal fade" id="printOptions" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Print</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-check">
                        <table>
                            <tr> <td>
                        <input class="form-check-input checkboxPrint" type="checkbox" name="selection[]" value="tCol1" id="tCol1" checked>
                        <label class="form-check-label" for="tCol1">
                            Year  &nbsp;&nbsp;&nbsp;&nbsp;
                        </label>
                                </td>

                                <td>
                        <input class="form-check-input checkboxPrint" type="checkbox" name="selection[]" value="tCol2" id="tCol2" checked>
                        <label class="form-check-label" for="tCol2">
                            Exam  &nbsp;&nbsp;&nbsp;&nbsp;
                        </label>
                                </td>


                                <td>
                                    <input class="form-check-input checkboxPrint" type="checkbox" name="selection[]" value="tCol3" id="tCol3" checked>
                                    <label class="form-check-label" for="tCol3">
                                        Grade  &nbsp;&nbsp;&nbsp;&nbsp;
                                    </label>
                                </td>


                                <td>
                                    <input class="form-check-input checkboxPrint" type="checkbox" name="selection[]" value="tCol4" id="tCol4" checked>
                                    <label class="form-check-label" for="tCol4">
                                        Total  &nbsp;&nbsp;&nbsp;&nbsp;
                                    </label>
                                </td>
                                <td>
                                    <input class="form-check-input checkboxPrint" type="checkbox" name="selection[]" value="tCol9" id="tCol9" checked>
                                    <label class="form-check-label" for="tCol9">
                                        Remarks  &nbsp;&nbsp;&nbsp;&nbsp;
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input class="form-check-input checkboxPrint" type="checkbox" name="selection[]" value="tCol5" id="tCol5" checked>
                                    <label class="form-check-label" for="tCol5">
                                        Count  &nbsp;&nbsp;&nbsp;&nbsp;
                                    </label>
                                </td>


                                <td>
                                    <input class="form-check-input checkboxPrint" type="checkbox" name="selection[]" value="tCol6" id="tCol6" checked>
                                    <label class="form-check-label" for="tCol6">
                                        Ratio  &nbsp;&nbsp;&nbsp;&nbsp;
                                    </label>
                                </td>

                                <td>
                                    <input class="form-check-input checkboxPrint" type="checkbox" name="selection[]" value="tCol7" id="tCol7" checked>
                                    <label class="form-check-label" for="tCol7">
                                        Attainment &nbsp;&nbsp;&nbsp;&nbsp;
                                    </label>
                                </td>

                                <td>
                                    <input class="form-check-input checkboxPrint"  type="checkbox" name="selection[]" value="tCol8" id="tCol8" checked>
                                    <label class="form-check-label" for="tCol8">
                                        Subject &nbsp;&nbsp;&nbsp;&nbsp;
                                    </label>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" onclick="return validateFormPrint()" class="btn btn-primary">Print</button>
                </div>
            </div>
        </div>
    </div>
        <div class="se-pre-con"></div>
        <div id="loader_div" class="loader_div"></div>

        <div class=" w3-responsive header" >

            <!-- Navigation bar -->        
            <?php include('navbar.php'); ?>

            <!--set color for current tab-->
            <script>
                document.getElementById("navAttainment").style.backgroundColor = '#009688';
            </script>

            <!--End of Navictacoin bar-->

            <!--Drop menus-->
            <div id="upperdiv" class="w3-container w3-mobile" style="padding-top: 10px; padding-bottom: 10px">
                <table id= "table1">

                    <tr>
                        <td></td> <td>Academic Year</td><td>Grade</td>
                        <td>Section</td>  <td>Subject</td>  <td>Term</td><td>Category</td><td></td><td></td>
                    </tr>
                    <tr><td>
                            <button class="w3-button w3-round-xlarge w3-hover-blue-gray w3-medium w3-custom" id="exportM" onclick="downloadStatistics() "><span class="material-icons">save_alt</span></button>


                        </td>
                        <td>
                            <select id="academic_year"  onchange="fillGrades()"  multiple="multiple"></select>
                        </td>
                        <td>
                            <select   id="grade"  onchange="fillBatches(); fillSubjects()"  multiple="multiple"></select>
                        </td>
                        <td>

                            <select  id ="batch"  onchange="fillSubjects()" multiple="multiple"  ></select>  
                        </td>
                        <td>
                            <div class=""> <select   style="max-width: 300px" id="subject" size="5"  onchange="fillTerms()"   multiple="multiple"></select></div>
                        </td>
                        <td>
                            <select id="term" multiple="multiple"></select>         
                        </td>

                        <td>
                            <select id="category" multiple="multiple"></select>
                        </td>

                        <td>
                            <button style="padding: 15px 32px 32px 32px;text-align: center ;font-size: 14px;" class="w3-button w3-hover-blue-gray w3-custom w3-round-large " id="search" onclick='search();' title="View attainment analysis"><span class="fa fa-search"></span></button>
                        </td>

                        <td>
<button class="w3-button w3-round-xlarge w3-hover-blue-gray w3-medium w3-custom" id="exportM" data-toggle="modal" data-target="#printOptions" title="Export Statistics as PDF"> <span class="material-icons">save_alt</span></button>
                        </td>

                    </tr>

                </table>

            </div>



            <!--Drop menus-->

            <div class="w3-container w3-col m4 l5 w3-mobile" id="tables" style="overflow: scroll;top: 0;  bottom: 0; height: 100vh;">
                <table align= center; id="StatisticsTitle" style="width: 100%; text-align: center;  border: 1px solid black;">
                    <tr>
                        <td align='left' style="padding:5px; border: 1px solid black;"></td>
                        <td align='center' style="padding:5px; border: 1px solid black;"></td>
                        <td align='right' style="padding:5px; border: 1px solid black;"></td>
                    </tr>
                    <tr>
                        <td align='center' colspan="3"></td>
                    </tr>                    
                </table> <br>
                <table hidden align= center; id="StatisticsTitlePDF" style="width: 100%; text-align: center;">
                    <tr>
                        <td style="padding:5px;"></td>

                    </tr>
                    <tr>
                        <td colspan="3"></td>
                    </tr>                    
                </table>


                <!--stable-->   <table class=" w3-table-all w3-striped w3-bordered w3-centered w3-card-4" id="stable">
                    <th colspan="4" class="w3-custom " style="font-size: 16px">
                        STATISTICS
                    </th>
                    
                    <tr>
                        <th class="w3-border-right">Total</th>
                        <th class="w3-border-right"><input id="percent11" type="text" value= 50>% - <input id="percente12" type="text" value=100>%</th>
                        <th class="w3-border-right"><input id="percent21" type="text" value=65>% - <input id="percente22" type="text" value=100>%</th>
                        <th class="w3-border-right"><input id="percent31" type="text" value=75>% - <input id="percente32" type="text" value=100>%</th>
                    </tr>
                    <tr>
                        <td class="w3-border-right"></td>
                        <td class="w3-border-right"></td>
                        <td class="w3-border-right"></td>
                        <td class="w3-border-right"></td>
                    </tr>
                </table>
                <br><br>

                <!--stablePDF--><table id="stablePDF" style="font-size: 100px" hidden>
                    <thead>
                        <tr>
                            <th colspan="5" style="text-align: center">STATISTICS</th>
                            <th></th>
                            <th></th>
                            <th></th>

                        </tr>
                    </thead>
                    <tbody> 
                        <tr>
                            <th>Marks Count</th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>

                <!--T1-->       <table id="T1" class=" w3-table-all w3-striped w3-bordered w3-centered w3-card-4" >
                    <th colspan="4" class="w3-custom" style="font-size: 16px">Subject</th>
                    
                    <tr>
                        <th class="w3-border-right">Total</th>
                        <th class="w3-border-right"></th><th class="w3-border-right"></th><th class="w3-border-right"></th>
                    </tr>
                    <tr>
                        <td class="w3-border-right"></td><td class="w3-border-right"></td>
                        <td class="w3-border-right"></td><td class="w3-border-right"></td>
                    </tr>
                </table>
                <br>

                <!--T2-->       <table id="T2" class=" w3-table-all w3-striped w3-bordered w3-centered w3-card-4">  
                    <th colspan="4" class="w3-custom" style="font-size: 16px;">Subject</th>
                    
                    <tr>
                        <th class="w3-border-right">Total</th>
                        <th class="w3-border-right"></th><th class="w3-border-right"></th><th class="w3-border-right"></th>
                    </tr>
                    <tr>
                        <td class="w3-border-right"></td><td class="w3-border-right"></td>
                        <td class="w3-border-right"></td><td class="w3-border-right"></td>
                    </tr>
                </table>

                <br>

                <!--T3-->       <table id="T3" class=" w3-table-all w3-striped w3-bordered w3-centered w3-card-4">  
                    <th colspan="4" class="w3-custom" style="font-size: 16px">Subject</th>
                    
                    <tr>
                        <th class="w3-border-right">Total</th>
                        <th class="w3-border-right"></th><th class="w3-border-right"></th><th class="w3-border-right"></th>
                    </tr>
                    <tr>
                        <td class="w3-border-right"></td><td class="w3-border-right"></td>
                        <td class="w3-border-right"></td><td class="w3-border-right"></td>
                    </tr>
                </table>

                <br>

                <!--T4-->       <table id="T4" class=" w3-table-all w3-striped w3-bordered w3-centered w3-card-4">
                    <th colspan="4" class="w3-custom" style="font-size: 16px">Subject</th>
                    <
                    <tr>
                        <th class="w3-border-right">Total</th>
                        <th class="w3-border-right"></th><th class="w3-border-right"></th><th class="w3-border-right"></th>
                    </tr>
                    <tr>
                        <td class="w3-border-right"></td><td class="w3-border-right"></td>
                        <td class="w3-border-right"></td><td class="w3-border-right"></td>
                    </tr>
                </table>

                <br>

                <!--T5-->       <table id="T5" class=" w3-table-all w3-striped w3-bordered w3-centered w3-card-4">
                    <th colspan="4" class="w3-custom" style="font-size: 16px">Subject</th>
                   
                    <tr>
                        <th class="w3-border-right">Total</th>
                        <th class="w3-border-right"></th><th class="w3-border-right"></th><th class="w3-border-right"></th>
                    </tr>
                    <tr>
                        <td class="w3-border-right"></td><td class="w3-border-right"></td>
                        <td class="w3-border-right"></td><td class="w3-border-right"></td>
                    </tr>
                </table>

                <br>

                <!--T6-->       <table id="T6" class=" w3-table-all w3-striped w3-bordered w3-centered w3-card-4">
                    <th colspan="4" class="w3-custom" style="font-size: 16px">Subject</th>
                    
                    <tr>
                        <th class="w3-border-right">Total</th>
                        <th class="w3-border-right"></th><th class="w3-border-right"></th><th class="w3-border-right"></th>
                    </tr>
                    <tr>
                        <td class="w3-border-right"></td><td class="w3-border-right"></td>
                        <td class="w3-border-right"></td><td class="w3-border-right"></td>
                    </tr>
                </table>

                <br>

                <!--T7-->       <table id="T7" class=" w3-table-all w3-striped w3-bordered w3-centered w3-card-4">
                    <th colspan="4" class="w3-custom" style="font-size: 16px">Subject</th>
                    
                    <tr>
                        <th class="w3-border-right">Total</th>
                        <th class="w3-border-right"></th><th class="w3-border-right"></th><th class="w3-border-right"></th>
                    </tr>
                    <tr>
                        <td class="w3-border-right"></td><td class="w3-border-right"></td>
                        <td class="w3-border-right"></td><td class="w3-border-right"></td>
                    </tr>
                </table>

                <br>

                <!--T8-->       <table id="T8" class=" w3-table-all w3-striped w3-bordered w3-centered w3-card-4">
                    <th colspan="4" class="w3-custom" style="font-size: 16px">Subject</th>
                    
                    <tr>
                        <th class="w3-border-right">Total</th>
                        <th class="w3-border-right"></th><th class="w3-border-right"></th><th class="w3-border-right"></th>
                    </tr>
                    <tr>
                        <td class="w3-border-right"></td><td class="w3-border-right"></td>
                        <td class="w3-border-right"></td><td class="w3-border-right"></td>
                    </tr>
                </table>

                <br>

                <!--T9-->       <table id="T9" class=" w3-table-all w3-striped w3-bordered w3-centered w3-card-4">
                    <th colspan="4" class="w3-custom" style="font-size: 16px">Subject</th>
                    =
                    <tr>
                        <th class="w3-border-right">Total</th>
                        <th class="w3-border-right"></th><th class="w3-border-right"></th><th class="w3-border-right"></th>
                    </tr>
                    <tr>    
                        <td class="w3-border-right"></td><td class="w3-border-right"></td>
                        <td class="w3-border-right"></td><td class="w3-border-right"></td>
                    </tr>
                </table>

                <br>

                <!--T10-->       <table id="T10" class=" w3-table-all w3-striped w3-bordered w3-centered w3-card-4">
                    <th colspan="4" class="w3-custom" style="font-size: 16px">Subject</th>
                    
                    <tr>
                        <th class="w3-border-right">Total</th>
                        <th class="w3-border-right"></th><th class="w3-border-right"></th><th class="w3-border-right"></th>
                    </tr>
                    <tr>
                        <td class="w3-border-right"></td><td class="w3-border-right"></td>
                        <td class="w3-border-right"></td><td class="w3-border-right"></td>
                    </tr>
                </table>

                <br>

                <!--T11-->       <table id="T11" class=" w3-table-all w3-striped w3-bordered w3-centered w3-card-4">
                    <th colspan="4" class="w3-custom" style="font-size: 16px">Subject</th>
                    
                    <tr>
                        <th class="w3-border-right">Total</th>
                        <th class="w3-border-right"></th><th class="w3-border-right"></th><th class="w3-border-right"></th>
                    </tr>
                    <tr>
                        <td class="w3-border-right"></td><td class="w3-border-right"></td>
                        <td class="w3-border-right"></td><td class="w3-border-right"></td>
                    </tr>
                </table>

                <br>

                <!--T12-->       <table id="T12" class=" w3-table-all w3-striped w3-bordered w3-centered w3-card-4">
                    <th colspan="4" class="w3-custom" style="font-size: 16px">Subject</th>
                    
                    <tr>
                        <th class="w3-border-right">Total</th>
                        <th class="w3-border-right"></th><th class="w3-border-right"></th><th class="w3-border-right"></th>
                    </tr>
                    <tr>
                        <td class="w3-border-right"></td><td class="w3-border-right"></td>
                        <td class="w3-border-right"></td><td class="w3-border-right"></td>
                    </tr>
                </table>
            </div>
            <div class="w3-col m8 l7 w3-card-4 w3-mobile" id="rightdiv" style = "height:100vh; overflow: scroll; padding-top: 10px; padding-left: 10px; padding-right: 10px"> 
                <!--Downloading table  11:52 AM-->   
                <br>
                <div id="outdiv"> <h1 hidden>Al Sanawabar School: Attainment Analysis</h1>
                <table id="out"  class='w3-table-all'></table></div>

                <table id="TT1" hidden>
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
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
                        </tr>
                    </tbody>
                </table>
                <table id="TT2" hidden>
                    <thead><tr><th></th><th></th><th></th><th></th></tr></thead>
                    <tbody><tr><td>Total</td><td></td><td></td><td></td></tr>
                        <tr><td></td><td></td><td></td><td></td></tr></tbody>
                </table>
                <table id="TT3" hidden>
                    <thead><tr><th></th><th></th><th></th><th></th></tr></thead>
                    <tbody><tr><td>Total</td><td></td><td></td><td></td></tr>
                        <tr><td></td><td></td><td></td><td></td></tr></tbody>
                </table>
                <table id="TT4" hidden>
                    <thead><tr><th></th><th></th><th></th><th></th></tr></thead>
                    <tbody><tr><td>Total</td><td></td><td></td><td></td></tr>
                        <tr><td></td><td></td><td></td><td></td></tr></tbody>
                </table>
                <table id="TT5" hidden>
                    <thead><tr><th></th><th></th><th></th><th></th></tr></thead>
                    <tbody><tr><td>Total</td><td></td><td></td><td></td></tr>
                        <tr><td></td><td></td><td></td><td></td></tr></tbody>
                </table>
                <table id="TT6" hidden>
                    <thead><tr><th></th><th></th><th></th><th></th></tr></thead>
                    <tbody><tr><td>Total</td><td></td><td></td><td></td></tr>
                        <tr><td></td><td></td><td></td><td></td></tr></tbody>
                </table>
                <table id="TT7" hidden>
                    <thead><tr><th></th><th></th><th></th><th></th></tr></thead>
                    <tbody><tr><td>Total</td><td></td><td></td><td></td></tr>
                        <tr><td></td><td></td><td></td><td></td></tr></tbody>
                </table>
                <table id="TT8" hidden>
                    <thead><tr><th></th><th></th><th></th><th></th></tr></thead>
                    <tbody><tr><td>Total</td><td></td><td></td><td></td></tr>
                        <tr><td></td><td></td><td></td><td></td></tr></tbody>
                </table>
                <table id="TT9" hidden>
                    <thead><tr><th></th><th></th><th></th><th></th></tr></thead>
                    <tbody><tr><td>Total</td><td></td><td></td><td></td></tr>
                        <tr><td></td><td></td><td></td><td></td></tr></tbody>
                </table>
                <table id="TT10" hidden>
                    <thead><tr><th></th><th></th><th></th><th></th></tr></thead>
                    <tbody><tr><td>Total</td><td></td><td></td><td></td></tr>
                        <tr><td></td><td></td><td></td><td></td></tr></tbody>
                </table>
                <table id="TT11" hidden>
                   <thead><tr><th></th><th></th><th></th><th></th></tr></thead>
                    <tbody><tr><td>Total</td><td></td><td></td><td></td></tr>
                        <tr><td></td><td></td><td></td><td></td></tr></tbody>
                </table>
                <table id="TT12" hidden>
                    <thead><tr><th></th><th></th><th></th><th></th></tr></thead>
                    <tbody><tr><td>Total</td><td></td><td></td><td></td></tr>
                        <tr><td></td><td></td><td></td><td></td></tr></tbody>
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

        </script><!--
        
        
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
                // var selected_batches = $("#batch option:selected");

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


                // var message = "";
                // selected_batches.each(function () {
                //     if (message === "") {
                //         if (selected_years !== "" || selected_grades !== "")
                //             message = " AND (batches.name = '" + $(this).text() + "' ";
                //         else
                //             message = " (batches.name = '" + $(this).text() + "' ";
                //     } else {
                //         message += " OR batches.name = '" + $(this).text() + "' ";
                //     }
                // });
                //
                // if (message !== "") {
                //
                //     selected_batches = message + ")";
                // } else
                //     selected_batches = "";





                var httpSubjects = new XMLHttpRequest();
                httpSubjects.onreadystatechange = function () {
                    if (this.readyState === 4) {
                        var str = this.responseText;
                        subjectsArray = str.split("\t");
                    }
                };

                httpSubjects.open("GET", "sqldb/_subjectsViaBatchGradeYear.php?years=" + selected_years + "&grades=" + selected_grades , false);
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


        <!--
        Initialize Term drop down     --> 
        <!--Initialize Term drop down--> 
        <script type="text/javascript">
            document.getElementById("subject").addEventListener("change", fillTerms());
            function fillTerms() {
                var selected_years = $("#academic_year option:selected");
                var selected_grades = $("#grade option:selected");
                var selected_batches = $("#batch option:selected");
                var selected_subjects = $("#subject option:selected");
                var select = document.getElementById('term');
                while (select.length > 0)
                    select.remove(0);
                var message = "";
                selected_years.each(function () {
                    if (message === "")
                        message = "   (academic_years.name = '" + $(this).text() + "'";
                    else
                        message += " OR academic_years.name = '" + $(this).text() + "'";
                });
                if (message !== "")
                    selected_years = message + ")";
                else
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

                if (message !== "")
                    selected_grades = message + ")";
                else
                    selected_grades = "";

                var message = "";
                selected_batches.each(function () {
                    if (message === "") {
                        if (selected_years !== "" || selected_grades !== "")
                            message = " AND (batches.name = '" + $(this).text() + "' ";
                        else
                            message = " (batches.name = '" + $(this).text() + "' ";
                    } else
                        message += " OR batches.name = '" + $(this).text() + "' ";
                });

                if (message !== "")
                    selected_batches = message + ")";
                else
                    selected_batches = "";

    //                var message = "";
    //                selected_subjects.each(function () {
    //                    if (message === "") {
    //                        if (selected_years !== "" || selected_grades !== "" || selected_batches !== "")
    //                            message = " AND (subjects.name = '" + $(this).text() + "' ";
    //                        else
    //                            message = " (subjects.name = '" + $(this).text() + "' ";
    //                    } else
    //                        message += " OR subjects.name = '" + $(this).text() + "' ";
    //                });
    //
    //                if (message !== "")
    //                    selected_subjects = message + ")";
    //                else
                selected_subjects = "";

                var httpTerms = new XMLHttpRequest();
                httpTerms.onreadystatechange = function () {
                    if (this.readyState === 4) {
                        var str = this.responseText;
    //                        document.getElementById("out").innerHTML = this.responseText;                        
                        termsArray = str.split("\t");
                    }
                };

                httpTerms.open("GET", "sqldb/_TermsViaYearGradeSectionSubject.php?years=" + selected_years + "&grades=" + selected_grades + "&batches=" + selected_batches + "&subjects=" + selected_subjects, false);
                httpTerms.send();

                $('#term').multiselect('destroy');
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
            }
        </script>










        <!--Initialize Student Category drop down for table 2-->     
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

    <script>
        function downloadStatistics() {
            var doc = new jsPDF('p', 'pt', 'a4');
            var header = function (data) {
                doc.setFontSize(18);
                doc.setTextColor(0);
                doc.setFont('PTSans');
                //                    doc.setFontStyle('bold');
                doc.text("Subject Wise Statistics", 225, 50);
                doc.line(226, 53, 390, 53);// Header top margin
            };
            var table = doc.autoTableHtmlToJson(document.getElementById("StatisticsTitlePDF"));
            doc.autoTable(table.columns, table.data, {beforePageContent: header, theme: 'plain', margin: {top: 70, left: 40, right: 40},
                styles: {
                    fontSize: 12,
                    font: 'PTSans',
                    overflow: 'linebreak', columnWidth: 'wrap'
                }, bodyStyles: {valign: 'top'},
                columnStyles: {
                    0: {
                        columnWidth: 'auto',
                        columnHeight: 'auto'
                    }
                }});
            var table = doc.autoTableHtmlToJson(stablePDF);
            doc.autoTable(table.columns, table.data, {startY: doc.autoTable.previous.finalY + 14, beforePageContent: header, theme: 'grid', margin: {top: 70, left: 40, right: 40}, columnStyles: {
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

        <script>
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

        <script>
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
        var validateFormPrint = function() {
            var checks = $(".checkboxPrint:not(:checked)").map(function() {
                return $(this).val();
            }).get()
            // alert(checks); 
            printJS({printable: 'outdiv', type: 'html', base64: true, showModal: true,
                header: '<u><h1>Al Sanawbar School - Attainment Analysis</h1></u><br><br>', targetStyles: '*', honorColor: true, repeatTableHeader: true,
                scanstyles: true, ignoreElements: checks});
            return false;
        }
    </script>

    </body>
    </html>

<?php } ?>
