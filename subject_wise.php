<?php include('Header.php'); ?>
<title>Statistics based on subject</title>
</head>

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
            var indexGrade;
            var indexSubject;
            var indexSection;
            var indexCategory;
            for (var index = 1; index < 3; index++) {
                indexGrade = "T" + index + "-GR";
                indexSubject = "T" + index + "-SB";
                indexSection = "T" + index + "-SC";
                indexCategory = "T" + index + "-CA";
                var grade = document.getElementById(indexGrade).options[document.getElementById(indexGrade).selectedIndex].text;
                var category = $("#" + indexCategory + " option:selected");
                var subject = $("#" + indexSubject + " option:selected");
                var section = $("#" + indexSection + " option:selected");
                //Section            
                var message = "";
                var sectionHeader = "";
                section.each(function () {
                    var currentSection = $(this).text();
                    if (currentSection.indexOf("(") !== -1) {
                        var bracketIndex = currentSection.indexOf("(");
                        currentSection = currentSection.slice(0, bracketIndex);
                    }
                    if (message === "") {
                        if (section !== "")
                            message = " AND (batches.name = '" + currentSection + "' ";
                        else
                            message = " (batches.name = '" + currentSection + "'";
                        sectionHeader = " - " + currentSection;
                    } else {
                        message += " OR batches.name = '" + currentSection + "'";  //  grade like 'GR1' OR grade like 'GR10';
                        sectionHeader += " , " + currentSection;
                    }
                });
                if (message !== "")
                    section = message + ")";
                else
                    section = "";
                //Subject              
                var message = "";
                var subjectHeader = "";
                subject.each(function () {
                    var currentSubject = $(this).text();
                    if (currentSubject.indexOf("(") !== -1) {
                        var bracketIndex = currentSubject.indexOf("(");
                        currentSubject = currentSubject.slice(0, bracketIndex);
                    }
                    if (message === "") {
                        if (subject !== "")
                            message = " AND (subjects.name = '" + currentSubject + "' ";
                        else
                            message = " (subjects.name = '" + currentSubject + "'";
                        subjectHeader = " - " + currentSubject;
                    } else {
                        message += " OR subjects.name = '" + currentSubject + "'";  //  grade like 'GR1' OR grade like 'GR10';
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
                        output.value += term + " " + gender;
                        min = document.getElementById(tableName).rows[2].cells[i].childNodes[0].value;
                        var httpAbove = new XMLHttpRequest();
                        httpAbove.onreadystatechange = function () {
                            if (this.readyState === 4)
                                document.getElementById(tableName).rows[3].cells[i].innerHTML =
                                        this.responseText;
                        };
                        httpAbove.open("POST", "sqldb/marksAbove.php?term=" + term +
                                "&grade=" + grade + "&subject=" + subject + "&category=" + category +
                                "&gender=" + gender + "&min=" + min + "&section=" + section, false);
                        httpAbove.send();
                    }
                }
                google.charts.load('current', {packages: ['corechart', 'bar']});
                google.charts.setOnLoadCallback(drawMaterial);
            }
            function drawMaterial() {
                for (var i = 1; i < 3; i++) {
                    var indexGrade = "T" + i + "-GR";
                    var indexSubject = "T" + i + "-SB";
                    var indexSection = "T" + i + "-SC";
//        var grade = document.getElementById(indexGrade).options[document.getElementById(indexGrade).selectedIndex].text;
//        var subject = document.getElementById(indexSubject).options[document.getElementById(indexSubject).selectedIndex].text;
//        var section = document.getElementById(indexSection).options[document.getElementById(indexSection).selectedIndex].text;
//        
                    var value1, value2, value3, value4, result1, result2, result3, result4, tableName, chartName, gender1, gender2;
                    var value1, value2, value3, value4, result1, result2, result3, result4, tableName, table1, chartName, gender1, gender2;
                    tableName = 'T' + i;
                    table1 = 'TT' + i;
                    var tableName1 = document.getElementById(table1);
                    var term1 = document.getElementById(tableName + '-Term1').options[document.getElementById(tableName + '-Term1').selectedIndex].text;
                    var term2 = document.getElementById(tableName + '-Term2').options[document.getElementById(tableName + '-Term2').selectedIndex].text;
                    tableName1.rows[0].cells[3].innerHTML = subject;
                    var gender1 = document.getElementById(tableName + '-Gender1').options[document.getElementById(tableName + '-Gender1').selectedIndex].text;
                    if (gender1 === 'Both')
                    {
                        tableName1.rows[1].cells[1].innerHTML = term1 + 'Boys & Girls';
                    } else
                    {
                        tableName1.rows[1].cells[1].innerHTML = term1 + gender1;
                    }
                    var gender2 = document.getElementById(tableName + '-Gender2').options[document.getElementById(tableName + '-Gender2').selectedIndex].text;
                    if (gender2 === 'Both')
                    {
                        tableName1.rows[1].cells[5].innerHTML = term1 + 'Boys & Girls';
                    } else
                    {
                        tableName1.rows[1].cells[5].innerHTML = term2 + gender2;
                    }
                    value1 = document.getElementById(tableName).rows[2].cells[0].childNodes[0].value;
                    tableName1.rows[2].cells[0].innerHTML = 'Above ' + value1 + ' %';
                    value2 = document.getElementById(tableName).rows[2].cells[1].childNodes[0].value;
                    tableName1.rows[2].cells[2].innerHTML = 'Above ' + value2 + ' %';
                    value3 = document.getElementById(tableName).rows[2].cells[2].childNodes[0].value;
                    tableName1.rows[2].cells[4].innerHTML = 'Above ' + value3 + ' %';
                    value4 = document.getElementById(tableName).rows[2].cells[3].childNodes[0].value;
                    tableName1.rows[2].cells[6].innerHTML = 'Above ' + value4 + ' %';
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
                    data.addColumn('number', 'Students');
                    data.addColumn({type: 'string', role: 'style'});
                    data.addRows([
                        [gender1 + "-" + value1.toString() + '% and above', Number(result1), ' #0066cc'],
                        [gender1 + "-" + value2.toString() + '% and above', Number(result2), '#ff0000'],
                        [gender2 + "-" + value3.toString() + '% and above', Number(result3), ' #0066cc'],
                        [gender2 + "-" + value4.toString() + '% and above', Number(result4), '#ff0000'],
                    ]);
                    var options = {
                        title: '(' + term1 + " " + gender1 + ') VS (' + term2 + " " + gender2 + ") ",
//                        + grade + '-' + subject + '  for batch ' + section ,
                    };
                    chartName = 'chart' + i;
                    var e = document.getElementById("charttype");
                    var type = e.options[e.selectedIndex].value;
                    if (type === "coloumn") {
                        var materialChart = new google.visualization.ColumnChart(document.getElementById(chartName));
                        materialChart.draw(data, options);
                    }
                    if (type === "pie") {
                        var materialChart = new google.visualization.PieChart(document.getElementById(chartName));
                        materialChart.draw(data, options);
                    }
                    if (type === "histogram") {
                        var materialChart = new google.visualization.Histogram(document.getElementById(chartName));
                        materialChart.draw(data, options);
                    }
                    if (type === "linechart") {
                        var materialChart = new google.visualization.LineChart(document.getElementById(chartName));
                        materialChart.draw(data, options);
                    }
                    imgData[i] = materialChart.getImageURI();
                }
            }
            ;
        }
        );
    });
</script>

<body  onload="fillSections1(), fillSections2(), fillSubjects1(), fillSubjects2()">
    <div class="se-pre-con"></div>

    <div class=" w3-responsive header">
        <!-- Navigation bar -->
        <?php include('navbar.php'); ?>
        <script>
            document.getElementById("navSubjectWise").style.backgroundColor = '#009688';
        </script>
        <!-- End of Navigation bar -->
        <div id="upperdiv" class="w3-container" style="padding-top: 10px; padding-bottom: 10px;">   

            <table id= "table1">
                <tr>
                    <td><button style="text-align: center ;" class="w3-button w3-round-xlarge w3-medium w3-hover-blue-gray w3-center w3-custom" id="exportS" onclick="downloadStatistics()" title="Export Data as PDF" >Export Data  <span class="material-icons">print</span></button>
                    </td>
                    <td></td>
                    <td><button style="text-align: center ;" class="w3-button w3-hover-blue-gray w3-custom w3-medium w3-round-xlarge" id="search" title="Get students marks">View Results  <span class="fa fa-search"></span></button></td>
                    <td></td>
                    <td><select  class="w3-button w3-hover-blue-gray w3-custom w3-medium w3-round-xlarge" style="text-align: center" id="charttype" > 
                            <option class="w3-round-xlarge" style="text-align: center;" selected="selected" value="pie">Pie Chart</option> 
                            <option class="w3-round-xlarge"style="text-align: center"  value="coloumn">Column Chart</option>
                            <option class="w3-round-xlarge"style="text-align: center"  value="linechart">Line Chart</option> 
                            <option class="w3-round-xlarge"style="text-align: center"  value="histogram">Histogram</option> 


                        </select></td>
                    <td><button  style="text-align: center ;" class="w3-button w3-hover-blue-gray w3-custom w3-medium w3-round-xlarge" id="exportM" onclick="downloadStudents()" title="Export Marks as PDF">Export Mark  <span class="material-icons ">save_alt</span></button></td>
                </tr>
            </table>
        </div>

        <div id="tables" style="height: 100vh; overflow: auto">

            <!--Tables-->
            <!--////////////////////////            Table 1     ///////////////////////////////////////////////////-->
            <textarea id="output" rows="10" cols="50" hidden></textarea>
            <div class="w3-row w3-border">
                <div class="w3-container w3-half">
                    <br>
                    <table class=" w3-table-all w3-striped w3-bordered w3-centered w3-card-4" id="T1">  
                        <th colspan="4" class="w3-teal" style="font-size: 18px">
                            <button style="float: left;"type='button'class="w3-button w3-hover-blue-gray" hidden onclick="printDiv(chart1)"id='printbtn'  title="Print chart"value='Print'>
                                <i class="glyphicon glyphicon-print"></i></button>
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
                            <td class="w3-border-right"><input type="text" style = "font-style:initial ; font-size: 16px;"value= 80> % and above</td>
                            <td class="w3-border-right"><input type="text" style = "font-style:initial ; font-size: 16px;"value= 85> % and above</td>
                            <td class="w3-border-right"><input type="text" style = "font-style:initial ; font-size: 16px;"value= 90> % and above</td>
                            <td class="w3-border-right"><input type="text" style = "font-style:initial ; font-size: 16px;"value= 95> % and above</td>
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
                            <button style="float: left;"type='button'class="w3-button w3-hover-blue-gray" hidden onclick="printDiv(chart2)"id='printbtn'  title="Print chart"value='Print'>
                                <i class="glyphicon glyphicon-print"></i></button>
                            <select id="T2-GR" ></select>
                            <select id="T2-SC" multiple ></select>
                            <select id="T2-SB" multiple ></select>
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
                            <td class="w3-border-right"><input type="text" style = "font-style:initial ; font-size: 16px;"value= 80> % and above</td>
                            <td class="w3-border-right"><input type="text" style = "font-style:initial ; font-size: 16px;"value= 85> % and above</td>
                            <td class="w3-border-right"><input type="text" style = "font-style:initial ; font-size: 16px;"value= 90> % and above</td>
                            <td class="w3-border-right"><input type="text" style = "font-style:initial ; font-size: 16px;"value= 95> % and above</td>
                        </tr>
                        <tr>
                            <td class="w3-border-right">--</td>
                            <td class="w3-border-right">--</td>
                            <td class="w3-border-right">--</td>
                            <td class="w3-border-right">--</td>
                        </tr>
                    </table>

                    <table id="TT2" hidden>
                        <thead>
                        <td> </td><td ></td><td ></td><td ></td><td ></td><td ></td><td ></td></thead>
                        <tbody>
                            <tr><td></td><td></td><td></td><td></td><td ></td><td ></td><td ></td></tr>
                            <tr><td></td><td></td><td></td><td></td><td ></td><td ></td><td ></td></tr>
                            <tr><td ></td><td></td><td></td><td></td><td ></td><td ></td><td></td></tr>
                        </tbody>
                    </table>
                    <br>
                    <div class="w3-half w3-card-4" id="chart2"></div>
                </div>

            </div>
            <br><br>
            <!--////////////////////////            Table 2     ///////////////////////////////////////////////////-->

        </div>

        <!--////////////////////////            Table 3     ///////////////////////////////////////////////////-->

    </div>
</div>

<script src="js/jspdf.debug.js"></script>
<script src="js/jspdf.plugin.autotable.js"></script>
<script>
                                function generate() {
                                    var doc = new jsPDF('p', 'pt');
                                    var res = doc.autoTableHtmlToJson(document.getElementById("out"));
                                    doc.autoTable(res.columns, res.data, {margin: {top: 80}});
                                    var header = function (data) {
                                        doc.setFontSize(18);
                                        doc.setTextColor(40);
                                        doc.setFontStyle('normal');
                                        doc.text("Testing Report", data.settings.margin.left, 50);
                                    };
                                    var options = {
                                        beforePageContent: header,
                                        margin: {top: 80}, startY: doc.autoTableEndPosY() + 20
                                    };
                                    doc.autoTable(res.columns, res.data, options);
                                    doc.save("Students.pdf");
                                }
</script>

<script type="text/javascript">
    function generate2() {
        var doc = new jsPDF('p', 'pt');
        var res = doc.autoTableHtmlToJson(document.getElementById("stablePDF"));
        doc.autoTable(res.columns, res.data, {margin: {top: 80}});
        var header = function (data) {
            doc.setFontSize(18);
            doc.setTextColor(40);
            doc.setFontStyle('normal');
            doc.text("Statistics Report", data.settings.margin.left, 50);
        };
        var options = {
            beforePageContent: header,
            margin: {top: 80}, startY: doc.autoTableEndPosY() + 20
        };
        doc.autoTable(res.columns, res.data, options);
        doc.save("Statistics.pdf");
    }
</script>
</div>
<!--Onchange event listener -->
<script type="text/javascript">
    document.getElementById("T1-GR").onchange = function () {
        fillSections1();
        fillSubjects1();
        Result();
    };
    document.getElementById("T2-GR").onchange = function () {
        fillSections2();
        fillSubjects2();
        Result();
    };
    document.getElementById('T1-Gender1').onchange = function () {
        Result();
    };
    document.getElementById('T1-Gender2').onchange = function () {
        Result();
    };
    document.getElementById('T2-Gender1').onchange = function () {
        Result();
    };
    document.getElementById('T2-Gender2').onchange = function () {
        Result();
    };
    document.getElementById('T1-Term1').onchange = function () {
        Result();
    };
    document.getElementById('T1-Term2').onchange = function () {
        Result();
    };
    document.getElementById('T2-Term1').onchange = function () {
        Result();
    };
    document.getElementById('T2-Term2').onchange = function () {
        Result();
    };
//document.getElementById('T1-SC').onchange = function() {Result();};
//document.getElementById('T1-SB').onchange = function() {Result();};
//document.getElementById('T1-SR').onchange = function() {Result();};
//document.getElementById('T2-SC').onchange = function() {Result();};
//document.getElementById('T2-SB').onchange = function() {Result();};
//document.getElementById('T2-SR').onchange = function() {Result();};
//document.getElementById('T2-CA').onchange = function() {Result();};
//document.getElementById('T1-CA').onchange = function() {Result();};
    function Result() {
        document.getElementById("search").click();
    }
</script>    
<!--Terms VIA Year-->
<!--<script type="text/javascript">
document.getElementById('T1-Year').onchange = function() {fetchTerms("T1");};
document.getElementById('T2-Year').onchange = function() {fetchTerms("T2");};
document.getElementById('T3-Year').onchange = function() {fetchTerms("T3");};
document.getElementById('T4-Year').onchange = function() {fetchTerms("T4");};
function fetchTerms(table) {
        var tableYear= table + '-Year';
        for (var T=1; T<3; T++) {
                var year = document.getElementById(tableYear).options[document.getElementById(tableYear).selectedIndex].text;
                var tableTerm = table + '-Term' + T;
                var select = document.getElementById(tableTerm);
                while (select.length > 0)
                        select.remove(0);
                $('#T1-Term1').multiselect('destroy');
                         select.add( new Option(year));
                $(function () {$('#T1-Term1').multiselect({includeSelectAllOption: true});});
        }
}; 
</script>-->

<!--Initialize Terms-->
<script type="text/javascript">
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
    for (T = 1; T < 3; T++) {
//                var select = document.getElementById('T' + T + '-Year');
//                while (select.length > 0)
//                        select.remove(0);
        delete termsArray[termsArray.length - 1];
//                for( var i in termsArray )
//                        select.add( new Option( termsArray[i] ) );    
        for (c = 1; c < 3; c++) {
            var tableName = 'T' + T + '-Term' + c;
            var select = document.getElementById(tableName);
            while (select.length > 0)
                select.remove(0);
            delete termsArray[termsArray.length - 1];
            for (var i in termsArray)
                select.add(new Option(termsArray[i]));
        }
    }
    $(function () {
        $('#T1-Term1').multiselect({includeSelectAllOption: true});
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


<!--Initialize Grade drop down for table1-->     
<script type="text/javascript">
    var gradesArray = ["Your Data Base is Empty!."];
    var httpgrades = new XMLHttpRequest();
    httpgrades.onreadystatechange = function () {
        if (this.readyState === 4) {
            var str = this.responseText;
            gradesArray = str.split("\t");
        }
    };
    httpgrades.open("GET", "sqldb/initGrades.php", false);
    httpgrades.send();
    var select = document.getElementById('T1-GR');
    delete gradesArray[gradesArray.length - 1];
    for (var i in gradesArray) {
        select.add(new Option(gradesArray[i]));
    }
    ;
    $(function () {
        $('#T1-GR').multiselect({
            includeSelectAllOption: true
        });
    });
</script>

<!--Initialize Grade drop down for table 2-->     
<script type="text/javascript">
    var gradesArray = ["Your Data Base is Empty!."];
    var httpgrades = new XMLHttpRequest();
    httpgrades.onreadystatechange = function () {
        if (this.readyState === 4) {
            var str = this.responseText;
            gradesArray = str.split("\t");
        }
    };
    httpgrades.open("GET", "sqldb/initGrades.php", false);
    httpgrades.send();
    var select = document.getElementById('T2-GR');
    delete gradesArray[gradesArray.length - 1];
    for (var i in gradesArray) {
        select.add(new Option(gradesArray[i]));
    }
    ;
    $(function () {
        $('#T2-GR').multiselect({
            includeSelectAllOption: true
        });
    });
</script>


<!--Sections VIA Grades -->
<script type="text/javascript">
    function fillSections1() {
        var grade = document.getElementById("T1-GR").options[document.getElementById("T1-GR").selectedIndex].text;
        if (grade !== 'Select Grade') {
            var select = document.getElementById('T1-SC');
            while (select.length > 0)
                select.remove(0);
            var httpSections = new XMLHttpRequest();
            httpSections.onreadystatechange = function () {
                if (this.readyState === 4) {
                    var str = this.responseText;
                    sectionsArray = str.split("\t");
                }
            };
            httpSections.open("GET", "sqldb/distinctBatches.php?grade=" + grade, false);
            httpSections.send();
            $('#T1-SC').multiselect('destroy');
            delete sectionsArray[sectionsArray.length - 1];
            for (var i in sectionsArray) {
                select.add(new Option(sectionsArray[i]));
            }
            ;
            $(function () {
                $('#T1-SC').multiselect({
                    includeSelectAllOption: true
                });
            });
        }
    }
    ;
</script>

<!--Sections VIA Grades for table 2-->
<script type="text/javascript">
    function fillSections2() {
        var grade = document.getElementById("T2-GR").options[document.getElementById("T2-GR").selectedIndex].text;
        if (grade !== 'Select Grade') {
            var select = document.getElementById('T2-SC');
            while (select.length > 0)
                select.remove(0);
            var httpSections = new XMLHttpRequest();
            httpSections.onreadystatechange = function () {
                if (this.readyState === 4) {
                    var str = this.responseText;
                    sectionsArray = str.split("\t");
                }
            };
            httpSections.open("GET", "sqldb/distinctBatches.php?grade=" + grade, false);
            httpSections.send();
            $('#T2-SC').multiselect('destroy');
            delete sectionsArray[sectionsArray.length - 1];
            for (var i in sectionsArray) {
                select.add(new Option(sectionsArray[i]));
            }
            ;
            $(function () {
                $('#T2-SC').multiselect({
                    includeSelectAllOption: true
                });
            });
        }
    }
    ;
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

<!--Subject VIA Grades for table 1-->
<script type="text/javascript">
    function fillSubjects1() {
        var grade = document.getElementById("T1-GR").options[document.getElementById("T1-GR").selectedIndex].text;
        if (grade !== 'Select Grade') {
            var select = document.getElementById('T1-SB');
            while (select.length > 0)
                select.remove(0);
            var httpSubjects = new XMLHttpRequest();
            httpSubjects.onreadystatechange = function () {
                if (this.readyState === 4) {
                    var str = this.responseText;
                    subjectsArray = str.split("\?");
                }
            };
            httpSubjects.open("GET", "sqldb/distinctSubjects.php?grade=" + grade, false);
            httpSubjects.send();
            $('#T1-SB').multiselect('destroy');
            delete subjectsArray[subjectsArray.length - 1];
            for (var i in subjectsArray) {
                select.add(new Option(subjectsArray[i]));
            }
            ;
            $(function () {
                $('#T1-SB').multiselect({
                    includeSelectAllOption: true
                });
            });
        }
    }
    ;
</script>
<!--Subject VIA Grades for table 2-->
<script type="text/javascript">
    function fillSubjects2() {
        var grade = document.getElementById("T2-GR").options[document.getElementById("T2-GR").selectedIndex].text;
        if (grade !== 'Select Grade') {
            var select = document.getElementById('T2-SB');
            while (select.length > 0)
                select.remove(0);
            var httpSubjects = new XMLHttpRequest();
            httpSubjects.onreadystatechange = function () {
                if (this.readyState === 4) {
                    var str = this.responseText;
                    subjectsArray = str.split("\?");
                }
            };
            httpSubjects.open("GET", "sqldb/distinctSubjects.php?grade=" + grade, false);
            httpSubjects.send();
            $('#T2-SB').multiselect('destroy');
            delete subjectsArray[subjectsArray.length - 1];
            for (var i in subjectsArray) {
                select.add(new Option(subjectsArray[i]));
            }
            ;
            $(function () {
                $('#T2-SB').multiselect({
                    includeSelectAllOption: true
                });
            });
        }
    }
    ;
</script>



<!----------Save PDF for table----------------->

<script>
    function downloadStatistics() {
        var doc = new jsPDF('p', 'pt', 'a4');
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