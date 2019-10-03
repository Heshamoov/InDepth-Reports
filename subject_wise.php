<?php

session_start();
if (!isset($_SESSION['login'])) {
    $_SESSION['notloggedin'] = 1;
    header('Location: index.php');
} else {
    include('Header.php');
    ?>

<head>
    <title>Compare Subjects</title>
</head>

<body  onload="FillYears(), FillGrades('T1-YR', 'T1-GR'), FillGrades('T2-YR', 'T2-GR'),
               FillSections('T1-YR', 'T1-GR', 'T1-SC'), FillSections('T2-YR', 'T2-GR', 'T2-SC'),
               FillSubjects('T1-YR', 'T1-GR', 'T1-SB'), FillSubjects('T2-YR', 'T2-GR', 'T2-SB'),
               fillTerms1(), fillTerms2()">
    
    <script type="text/javascript" src="js/FillYears.js"></script>
    <script type="text/javascript" src="js/FillGrades.js"></script>
    <script type="text/javascript" src="js/FillSections.js"></script>
    <script type="text/javascript" src="js/FillSubjects.js"></script>
   
    <script src="js/jspdf.debug.js"></script>
    <script src="js/jspdf.plugin.autotable.js"></script>
    <script type="text/javascript" src="js/PrintTable.js"></script>
    <script>
        $(window).load(function () {
            // Animate loader off screen
            $(".se-pre-con").fadeOut("slow");
            ;
        });
    </script>


<script type="text/javascript">
    var imgData = new Array();
    $(function () {
        $('#search, #charttype').click(function () {
            var indexYear, indexGrade, indexSubject, indexSection, indexCategory;

            for (var index = 1; index < 3; index++) {
                indexYear     = "T" + index + "-YR";
                indexGrade    = "T" + index + "-GR";
                indexSubject  = "T" + index + "-SB";
                indexSection  = "T" + index + "-SC";
                indexCategory = "T" + index + "-CA";

                var year = document.getElementById(indexYear).options[document.getElementById(indexYear).selectedIndex].text;
                var grade = document.getElementById(indexGrade).options[document.getElementById(indexGrade).selectedIndex].text;
                var section = $("#" + indexSection + " option:selected");
                var subject = $("#" + indexSubject + " option:selected");
                var category = $("#" + indexCategory + " option:selected");

                //Section            
                var message = sectionHeader = "";
                section.each(function () {
                    var currentSection = $(this).text();
                    if (message === "") {
                        if (section !== "")
                            message = " AND (batches.name = '" + currentSection + "' ";
                        else
                            message = " (batches.name = '" + currentSection + "'";
                        sectionHeader = " - " + currentSection;
                    } else {
                        message += " OR batches.name = '" + currentSection + "'";
                        sectionHeader += " , " + currentSection;
                    }
                });
                if (message !== "")
                    section = message + ")";
                else
                    section = "";


                //Subject              
                var message = subjectHeader = "";
                subject.each(function () {
                    var currentSubject = "";
                    var firstSpace = true;
                    var subject = $(this).text();

                    // Extracting English letters and numbers and remove Arabic letters
                    for (var i = 0; i < subject.length; i++) {
                        if ((subject[i] >= 'A' && subject[i] <= 'z') || (subject[i] >= '0' && subject[i] <= '9'))
                            currentSubject += subject[i];
                        if (subject[i] === ' ' && firstSpace && i > 3) {
                            currentSubject += subject[i];
                            firstSpace = false;
                        }
                    }

                    if (message === "") {
                        if (subject !== "")
                            message = " AND (subjects.name LIKE '" + currentSubject + "%' ";
                        else
                            message = " (subjects.name LIKE '" + currentSubject + "'%";
                        subjectHeader = " - " + currentSubject;
                    } else {
                        message += " OR subjects.name LIKE '" + currentSubject + "'%";  //  grade like 'GR1' OR grade like 'GR10';
                        subjectHeader += " , " + currentSubject;
                    }
                });
                if (message !== "")
                    subject = message + ")";
                else
                    subject = "";

                //Category               
                var message = "";
                var categoryHeader = "";
                category.each(function () {
                    var currentCategory = $(this).text();
                    if (currentCategory.indexOf("(") !== -1) {
                        var bracketIndex = currentCategory.indexOf("(");
                        currentCategory = currentCategory.slice(0, bracketIndex);
                    }
                    if (message === "") {
                        if (category !== "")
                            message = " AND (student_categories.name = '" + currentCategory + "' ";
                        else
                            message = " (student_categories.name = '" + currentCategory + "'";
                        categoryHeader = " - " + currentCategory;
                    } else {
                        message += " OR student_categories.name = '" + currentCategory + "'";  //  grade like 'GR1' OR grade like 'GR10';
                        categoryHeader += " , " + currentCategory;
                    }
                });
                if (message !== "")
                    category = message + ")";
                else
                    category = "";

                // Between values Subject wise
                var min = 0, tableName, term, gender;
                t = index;
                {
                tableName = 'T' + t;
                for (var i = 0; i < 4; i++) {
                    if (i < 2) {
                        term = tableName + "-Term1";
                        term = document.getElementById(term).options[document.getElementById(term).selectedIndex].text;
                        gender = tableName + "-Gender1";
                        gender = document.getElementById(gender).options[document.getElementById(gender).selectedIndex].text;
                    } else {
                        term = tableName + "-Term2";
                        term = document.getElementById(term).options[document.getElementById(term).selectedIndex].text;
                        gender = tableName + "-Gender2";
                        gender = document.getElementById(gender).options[document.getElementById(gender).selectedIndex].text;
                    }

//              document.getElementById("chart2").innerHTML += term + gender;
                min = document.getElementById(tableName).rows[2].cells[i].childNodes[0].value;
                
                var httpAbove = new XMLHttpRequest();
                httpAbove.onreadystatechange = function () {
                    if (this.readyState === 4)
                        document.getElementById(tableName).rows[3].cells[i].innerHTML = this.responseText;
                };
                httpAbove.open("POST", "sqldb/marksAbove.php?year=" + year + "&term=" + term +
                        "&grade=" + grade + "&subject=" + subject + "&category=" + category +
                        "&gender=" + gender + "&min=" + min + "&section=" + section, false);

                httpAbove.send();
            }
        }
        google.charts.load('current', {packages: ['corechart', 'bar']});
        google.charts.setOnLoadCallback(drawMaterial);

    }

// Draw Chart and PDF Tables
function drawMaterial() {
    for (var i = 1; i < 3; i++) {
        var value1, value2, value3, value4, result1, result2, result3, result4, tableName, chartName, gender1, gender2, table1;
        
        tableName = 'T' + i;
        table1 = 'TT' + i;
        var tableName1 = document.getElementById(table1);
//        var year = document.getElementById(tableName + '-YR').options[document.getElementById(tableName + '-YR').selectedIndex].text;
//        var grade = document.getElementById(tableName + '-GR').options[document.getElementById(tableName + '-GR').selectedIndex].text;
//        var section = document.getElementById(tableName + '-SC').options[document.getElementById(tableName + '-SC').selectedIndex].text;
//        var subject = document.getElementById(tableName + '-SB').options[document.getElementById(tableName + '-SB').selectedIndex].text;
//        var category = document.getElementById(tableName + '-CA').options[document.getElementById(tableName + '-CA').selectedIndex].text;
        var term1 = document.getElementById(tableName + '-Term1').options[document.getElementById(tableName + '-Term1').selectedIndex].text;
        var term2 = document.getElementById(tableName + '-Term2').options[document.getElementById(tableName + '-Term2').selectedIndex].text;
        
//        if (year !== "")
//            tableName1.rows[0].cells[0].innerHTML = year;
//        if (grade !== "")
//            tableName1.rows[0].cells[1].innerHTML = grade;
//        if (section !== "")
//            tableName1.rows[0].cells[2].innerHTML = section;
//        if (subject !== "")
//            tableName1.rows[0].cells[3].innerHTML = subject;
//        tableName1.rows[0].cells[4].innerHTML = category;

        var gender1 = document.getElementById(tableName + '-Gender1').options[document.getElementById(tableName + '-Gender1').selectedIndex].text;
        if (gender1 === 'Both')
            tableName1.rows[1].cells[1].innerHTML = term1 + 'Boys & Girls';
         else
            tableName1.rows[1].cells[1].innerHTML = term1 + gender1;

        var gender2 = document.getElementById(tableName + '-Gender2').options[document.getElementById(tableName + '-Gender2').selectedIndex].text;
        if (gender2 === 'Both')
            tableName1.rows[1].cells[5].innerHTML = term1 + 'Boys & Girls';
        else
            tableName1.rows[1].cells[5].innerHTML = term2 + gender2;

        value1 = document.getElementById(tableName).rows[2].cells[0].childNodes[0].value;
        tableName1.rows[2].cells[0].innerHTML = 'Above ' + value1 + ' % in' + term1;

        value2 = document.getElementById(tableName).rows[2].cells[1].childNodes[0].value;
        tableName1.rows[2].cells[2].innerHTML = 'Above ' + value2 + ' % in' + term1;

        value3 = document.getElementById(tableName).rows[2].cells[2].childNodes[0].value;
        tableName1.rows[2].cells[4].innerHTML = 'Above ' + value3 + ' % in' + term2;

        value4 = document.getElementById(tableName).rows[2].cells[3].childNodes[0].value;
        tableName1.rows[2].cells[6].innerHTML = 'Above ' + value4 + ' % in' + term2;

        result1 = document.getElementById(tableName).rows[3].cells[0].innerHTML;
        tableName1.rows[3].cells[0].innerHTML = result1;

        result2 = document.getElementById(tableName).rows[3].cells[1].innerHTML;
        tableName1.rows[3].cells[2].innerHTML = result2;

        result3 = document.getElementById(tableName).rows[3].cells[2].innerHTML;
        tableName1.rows[3].cells[4].innerHTML = result3;

        result4 = document.getElementById(tableName).rows[3].cells[3].innerHTML;
        tableName1.rows[3].cells[6].innerHTML = result4;

        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Number of Students');
        data.addColumn('number', 'Marks');

        data.addColumn({type: 'string', role: 'style'});

        data.addRows([
            [gender1 + "-" + value1.toString() + '% and above in ' + term1, Number(result1), '#0000e6'],
            [gender1 + "-" + value2.toString() + '% and above in ' + term1, Number(result2), '#0000e6'],
            [gender2 + "-" + value3.toString() + '% and above in ' + term2, Number(result3), '#00b300'],
            [gender2 + "-" + value4.toString() + '% and above in ' + term2, Number(result4), ' #00b300']
        ]);
        var options = {
            title: '(' + term1 + " " + gender1 + ') VS (' + term2 + " " + gender2 + ") ",
            curveType: 'smooth'
        }

        var view = new google.visualization.DataView(data);
        view.setColumns([0, 1,
            {calc: "stringify",
                sourceColumn: 1,
                type: "string",
                role: "annotation"},
            2]);

        chartName = 'chart' + i;

        var e = document.getElementById("charttype");
        var type = e.options[e.selectedIndex].value;

        if (type === "coloumn") {
            var materialChart = new google.visualization.ColumnChart(document.getElementById(chartName));
            materialChart.draw(view, options);
        }
        if (type === "pie") {
            var materialChart = new google.visualization.PieChart(document.getElementById(chartName));
            materialChart.draw(data, options);
        }

        if (type === "barchart") {
            var materialChart = new google.visualization.BarChart(document.getElementById(chartName));
            materialChart.draw(data, options);
        }
        if (type === "linechart") {
            var materialChart = new google.visualization.LineChart(document.getElementById(chartName));
            materialChart.draw(view, options);
        }
        imgData[i] = materialChart.getImageURI();
    }
};
    });
});
</script>

<div class="se-pre-con"></div>

<div class=" w3-responsive header">
<!-- Navigation bar -->
<?php include('navbar.php'); ?>
<script>document.getElementById("navSubjectWise").style.backgroundColor = '#009688';</script>

<div id="upperdiv" class="w3-container" style="padding-top: 10px; padding-bottom: 10px;">   

    <table id= "table1">
    <tr>
        <td></td>
        <td>
            <button style="text-align: center ;" class="w3-button w3-hover-blue-gray w3-custom w3-medium w3-round-xlarge"
            id="search" title="Get students marks">View Results<span class="fa fa-search"></span></button>
        </td>
        <td></td>           
        <td>
        <select  class="w3-button w3-hover-blue-gray w3-custom w3-medium w3-round-xlarge" style="text-align: center" id="charttype" > 
            <option class="w3-round-xlarge" style="text-align: center"  value="barchart">Bar</option>
            <option class="w3-round-xlarge" style="text-align: center" value="coloumn">Column</option>
            <option class="w3-round-xlarge" style="text-align: center" value="linechart">Line</option>
            <option class="w3-round-xlarge" style="text-align: center;" value="pie" selected="selected">Pie Chart</option>            
        </select>
        </td>
        <td></td>               
    </tr>
    </table>
    
</div> <!--End of Upper Div Section-->

<div id="tables" style="height: 100vh; overflow: auto">
    <div class="w3-row w3-border">
        <div class="w3-container w3-half">
        <br>
        <table class=" w3-table-all w3-striped w3-bordered w3-centered w3-card-4" id="T1">
            <th colspan="4" class="w3-teal" style="font-size: 18px">
                <button style="float: left;" type='button' class="w3-button w3-hover-blue-gray"
                    onclick="PrintTable('TT1')" title="Print chart" value='Print'>
                    <i class="glyphicon glyphicon-print"></i>
                </button>

                <select id="T1-YR"></select>   
                <select id="T1-GR" ></select>
                <select id="T1-SC" multiple></select> 
                <select id="T1-SB" multiple></select>
                <select id="T1-CA" multiple></select>           
            </th>

                <tr>
                    <th colspan="2" class="w3-border-right">
                        <select id="T1-Term1"></select>
                        <select id="T1-Gender1">
                            <option>Boys</option>
                            <option>Girls</option>
                            <option>Both</option>
                        </select>            
                    </th>
                    <th colspan="2" class="w3-border-right">
                        <select id="T1-Term2"></select>
                        <select id="T1-Gender2">
                            <option>Girls</option>
                            <option>Boys</option>
                            <option>Both</option>
                        </select>            
                    </th>
                </tr>
                <tr>
                    <td class="w3-border-right"><input type="text" style = "font-style:initial ; font-size: 16px;" value= 80> % and above</td>
                    <td class="w3-border-right"><input type="text" style = "font-style:initial ; font-size: 16px;" value= 85> % and above</td>
                    <td class="w3-border-right"><input type="text" style = "font-style:initial ; font-size: 16px;" value= 90> % and above</td>
                    <td class="w3-border-right"><input type="text" style = "font-style:initial ; font-size: 16px;" value= 95> % and above</td>
                </tr>
                <tr>
                    <td class="w3-border-right">--</td>
                    <td class="w3-border-right">--</td>
                    <td class="w3-border-right">--</td>
                    <td class="w3-border-right">--</td>
                </tr>
            </table>
        
        <table id="TT1" class=" w3-table-all w3-striped w3-bordered w3-centered w3-card-4" hidden>
                <thead>
                <td> </td><td></td><td ></td><td ></td><td ></td><td ></td><td ></td></thead>
                <tbody>
                    <tr><td></td><td></td><td></td><td></td><td ></td><td ></td><td ></td></tr>
                    <tr><td></td><td></td><td></td><td></td><td ></td><td ></td><td ></td></tr>
                    <tr><td ></td><td></td><td></td><td></td><td ></td><td ></td><td></td></tr>
                </tbody>
        </table>
        <br>
        <div class="w3-half w3-card-4"  id="chart1"></div>
        </div>

        <div class="w3-container w3-half">
            <br>
            <table class=" w3-table-all w3-striped w3-centered w3-card-4" id="T2">  
                <th colspan="4" class="w3-teal" style="font-size: 18px">
                    <button style="float: left;" type='button' class="w3-button w3-hover-blue-gray"
                        onclick="PrintTable('TT2')" id='printbtn'  title="Print chart" value='Print'>
                        <i class="glyphicon glyphicon-print"></i>
                    </button>

                    <select id="T2-YR"></select>   
                    <select id="T2-GR" ></select>
                    <select id="T2-SC" multiple ></select>
                    <select id="T2-SB" multiple></select>
                    <select id="T2-CA" multiple></select>           
                </th> 
                <tr>
                    <th colspan="2" class="w3-border-right">
                        <select id="T2-Term1"></select>
                        <select id="T2-Gender1">
                            <option>Boys</option>
                            <option>Girls</option>
                            <option>Both</option>
                        </select>            
                    </th>
                    <th colspan="2" class="w3-border-right">
                        <select id="T2-Term2"></select>
                        <select id="T2-Gender2">
                            <option>Girls</option>
                            <option>Boys</option>
                            <option>Both</option>
                        </select>                    
                    </th>
                </tr>
                <tr>
                    <td class="w3-border-right"><input type="text" style = "font-style:initial ; font-size: 16px;" value= 80> % and above</td>
                    <td class="w3-border-right"><input type="text" style = "font-style:initial ; font-size: 16px;" value= 85> % and above</td>
                    <td class="w3-border-right"><input type="text" style = "font-style:initial ; font-size: 16px;" value= 90> % and above</td>
                    <td class="w3-border-right"><input type="text" style = "font-style:initial ; font-size: 16px;" value= 95> % and above</td>
                </tr>
                <tr>
                    <td class="w3-border-right">--</td>
                    <td class="w3-border-right">--</td>
                    <td class="w3-border-right">--</td>
                    <td class="w3-border-right">--</td>
                </tr>
            </table>

            <table id="TT2"  class=" w3-table-all w3-striped w3-bordered w3-centered w3-card-4" hidden>
                <thead><td></td><td ></td><td ></td><td ></td><td ></td><td ></td><td ></td></thead>
                <tbody>
                    <tr><td></td><td></td><td></td><td></td><td ></td><td ></td><td ></td></tr>
                    <tr><td></td><td></td><td></td><td></td><td ></td><td ></td><td ></td></tr>
                    <tr><td ></td><td></td><td></td><td></td><td ></td><td ></td><td></td></tr>
                </tbody>
            </table>
            <br>
            <div class="w3-half w3-card-4" id="chart2">
            </div>
        </div>
        </div>
        <br><br>
    </div>

</div>

<!--On-change event listener -->
<script type="text/javascript">
    document.getElementById("T1-YR").onchange = function () {
        FillGrades('T1-YR', 'T1-GR');
        FillSections('T1-YR', 'T1-GR', 'T1-SC');
        FillSubjects('T1-YR', 'T1-GR', 'T1-SB');
        fillTerms1();
        Result();
    };
    document.getElementById("T2-YR").onchange = function () {
        FillGrades('T2-YR', 'T2-GR');
        FillSections('T2-YR', 'T2-GR', 'T2-SC');
        FillSubjects('T2-YR', 'T2-GR', 'T2-SB');
        fillTerms2();
        Result();
    };
    document.getElementById("T1-GR").onchange = function () {
        FillSections('T1-YR', 'T1-GR', 'T1-SC');
        FillSubjects('T1-YR', 'T1-GR', 'T1-SB');
        fillTerms1();
        Result();
    };

    document.getElementById("T2-GR").onchange = function () {
        FillSections('T2-YR', 'T2-GR', 'T2-SC');
        FillSubjects('T2-YR', 'T2-GR', 'T2-SB');
        fillTerms2();
        Result();
    };

    document.getElementById("T1-SC").onchange = function () {
        FillSubjects('T1-YR', 'T1-GR', 'T1-SB');
        fillTerms1();
        Result();
    };

    document.getElementById("T2-SC").onchange = function () {
        FillSubjects('T2-YR', 'T2-GR', 'T2-SB');
        fillTerms2();
        Result();
    };
    document.getElementById("T2-GR").onchange = function () {
        FillSections('T2-YR', 'T2-GR', 'T2-SC')
        FillSubjects('T2-YR', 'T2-GR', 'T2-SB');
        fillTerms2();
        Result();
    };
    
    document.getElementById('T1-SB').onchange = function () {Result();};
    document.getElementById('T1-Gender1').onchange = function () {Result();};
    document.getElementById('T1-Gender2').onchange = function () {Result();};
    document.getElementById('T2-Gender1').onchange = function () {Result();};
    document.getElementById('T2-Gender2').onchange = function () {Result();};
    document.getElementById('T1-Term1').onchange   = function () {Result();};
    document.getElementById('T1-Term2').onchange   = function () {Result();};
    document.getElementById('T2-Term1').onchange   = function () {Result();};
    document.getElementById('T2-Term2').onchange   = function () {Result();};

    function Result() {document.getElementById("search").click();}
</script>  

<!--Initialize Terms Table 1-->
<script type="text/javascript">
    function fillTerms1() {
        var year = document.getElementById("T1-YR").options[
            document.getElementById("T1-YR").selectedIndex].text;

        var grade = document.getElementById("T1-GR").options[
            document.getElementById("T1-GR").selectedIndex].text;

        if (grade !== 'Select Grade') {
            var select1 = document.getElementById('T1-Term1');
            var select2 = document.getElementById('T1-Term2');

            while (select1.length > 0)
                select1.remove(0);

            while (select2.length > 0)
                select2.remove(0);

            var httpTerms = new XMLHttpRequest();
            httpTerms.onreadystatechange = function () {
                if (this.readyState === 4) {
                    var str = this.responseText;
                    termsArray = str.split("\t");
                }
            };
            httpTerms.open("GET", "sqldb/distinctTerms.php?grade=" + grade + "&year=" + year, false);
            httpTerms.send();

            $('#T1-Term1').multiselect('destroy');
            $('#T1-Term2').multiselect('destroy');

            delete termsArray[termsArray.length - 1];
            for (var i in termsArray) {
                select1.add(new Option(termsArray[i]));
                select2.add(new Option(termsArray[i]));

            }
            ;

        }
        $(function () {
            $('#T1-Term1').multiselect({
                includeSelectAllOption: true
            });
        });
        $(function () {
            $('#T1-Term2').multiselect({
                includeSelectAllOption: true
            });
        });
    }
    ;
</script>

<!--Initialize Terms Table 2-->
<script type="text/javascript">
        function fillTerms2() {
            var grade = document.getElementById("T2-GR").options[document.getElementById("T2-GR").selectedIndex].text;
            var year = document.getElementById("T2-YR").options[document.getElementById("T2-YR").selectedIndex].text;

            if (grade !== 'Select Grade') {
                var select1 = document.getElementById('T2-Term1');
                var select2 = document.getElementById('T2-Term2');


                while (select1.length > 0)
                    select1.remove(0);

                while (select2.length > 0)
                    select2.remove(0);

                var httpTerms = new XMLHttpRequest();
                httpTerms.onreadystatechange = function () {
                    if (this.readyState === 4) {
                        var str = this.responseText;
                        termsArray = str.split("\t");
                    }
                };
                httpTerms.open("GET", "sqldb/distinctTerms.php?grade=" + grade + "&year=" + year, false);
                httpTerms.send();
                $('#T2-Term1').multiselect('destroy');
                $('#T2-Term2').multiselect('destroy');

                delete termsArray[termsArray.length - 1];
                for (var i in termsArray) {
                    select1.add(new Option(termsArray[i]));
                    select2.add(new Option(termsArray[i]));

                }
                ;

            }
            $(function () {
                $('#T2-Term1').multiselect({
                    includeSelectAllOption: true
                });
            });
            $(function () {
                $('#T2-Term2').multiselect({
                    includeSelectAllOption: true
                });
            });
        }
        ;
    </script>

<script type="text/javascript">
        $(function () {
            $('#T1-Term1').multiselect({includeSelectAllOption: true});
        });
        $(function () {
            $('#T1-SC').multiselect({includeSelectAllOption: true});
        });
        $(function () {
            $('#T1-SB').multiselect({includeSelectAllOption: true});
        });

        $(function () {
            $('#T2-SC').multiselect({includeSelectAllOption: true});
        });
        $(function () {
            $('#T2-SB').multiselect({includeSelectAllOption: true});
        });
        $(function () {
            $('#T1-Term2').multiselect({includeSelectAllOption: true});
        });
        $(function () {
            $('#T2-Term1').multiselect({includeSelectAllOption: true});
        });
        $(function () {
            $('#T2-Term2').multiselect({includeSelectAllOption: true});
        });
        $(function () {
            $('#T1-Gender1').multiselect({includeSelectAllOption: true});
        });
        $(function () {
            $('#T1-Gender2').multiselect({includeSelectAllOption: true});
        });
        $(function () {
            $('#T2-Gender1').multiselect({includeSelectAllOption: true});
        });
        $(function () {
            $('#T2-Gender2').multiselect({includeSelectAllOption: true});
        });
    </script>

<!--Initialize Student Category drop down for table 1-->     
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
        var select = document.getElementById('T1-CA');
        delete categoryArray[categoryArray.length - 1];
        for (var i in categoryArray) {
            select.add(new Option(categoryArray[i]));
        }
        ;
        $(function () {
            $('#T1-CA').multiselect({
                includeSelectAllOption: true
            });
        });
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
        var select = document.getElementById('T2-CA');
        delete categoryArray[categoryArray.length - 1];
        for (var i in categoryArray) {
            select.add(new Option(categoryArray[i]));
        }
        ;
        $(function () {
            $('#T2-CA').multiselect({
                includeSelectAllOption: true
            });
        });
    </script>


<!----------Save PDF for table----------------->
<script>
    function downloadStatistics() {
        var doc = new jsPDF('pt', 'pt', 'a3');
        var header = function (data) {
            doc.setFontSize(16);
            doc.setFontStyle('PTSans');
            doc.text("Statistics Based on Subject", 210, 80);        // Header top margin
        };
        var tableName = "";
        tableName = 'TT1';
        var table = doc.autoTableHtmlToJson(document.getElementById(tableName));
        doc.autoTable(table.columns, table.data, {beforePageContent: header, margin: {top: 100, left: 40, right: 40}, styles: {
                fontSize: 12,
                halign: 'center',
                font: 'PTSans'
            }});
        doc.addImage(imgData[1], 'png', 80, 180, 420, 250);
        tableName = 'TT2';
        var table = doc.autoTableHtmlToJson(document.getElementById(tableName));
        doc.autoTable(table.columns, table.data, {beforePageContent: header, margin: {top: 450, left: 40, right: 40}, styles: {
                fontSize: 12,
                halign: 'center',
                font: 'PTSans'
            }});
        doc.addImage(imgData[2], 'png', 80, 550, 420, 250);
        doc.save("Statistics.pdf");
    }
</script>

</body>
</html>

<?php } ?>