var imgData = new Array();

$(function () {
    $('#search, #charttype').click(function () {

        var indexGrade, indexSubject, indexSection, indexCategory;

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



//Fill query values
            var min = 0, tableName,year, term, gender;
            t = index;
            {
                tableName = 'T' + t;
                for (var i = 0; i < 4; i++) {
                    if (i < 2) {
                        year = tableName + "-YR"; 
                        year = document.getElementById(year).options[document.getElementById(year).selectedIndex].text;
                        term = tableName + "-Term1";
                        term = document.getElementById(term).options[document.getElementById(term).selectedIndex].text;
                        gender = tableName + "-Gender1";
                        gender = document.getElementById(gender).options[document.getElementById(gender).selectedIndex].text;
                    } else {
                        year = tableName + "-YR";
                        year = document.getElementById(year).options[document.getElementById(year).selectedIndex].text;                        
                        term = tableName + "-Term2";
                        term = document.getElementById(term).options[document.getElementById(term).selectedIndex].text;
                        gender = tableName + "-Gender2";
                        gender = document.getElementById(gender).options[document.getElementById(gender).selectedIndex].text;
                    }

                    min = document.getElementById(tableName).rows[2].cells[i].childNodes[0].value;
                    var httpAbove = new XMLHttpRequest();
                    httpAbove.onreadystatechange = function () {
                        if (this.readyState === 4)
                            document.getElementById(tableName).rows[3].cells[i].innerHTML =
                                    this.responseText;
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


        function drawMaterial() {

            for (var i = 1; i < 3; i++) {



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
                    [gender1 + "-" + value1.toString() + '% and above in ' + term1, Number(result1), ' #006666'],
                    [gender1 + "-" + value2.toString() + '% and above in ' + term1, Number(result2), '#800000'],
                    [gender2 + "-" + value3.toString() + '% and above in ' + term2, Number(result3), ' #002f5a'],
                    [gender2 + "-" + value4.toString() + '% and above in ' + term2, Number(result4), '#d81c01'],
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
        }
        ;


    }
    );
});
    