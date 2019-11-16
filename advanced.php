<?php
session_start();
if (!isset($_SESSION['login'])) {
    $_SESSION['notloggedin'] = 1;
    header('Location: index.php');
} else {
    include('Header.php');
    ?>


<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>

<!-- (Optional) Latest compiled and minified JavaScript translation files -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/i18n/defaults-*.min.js"></script>

    <title>Attainment Analysis</title>

<style type="text/css">
     #useroptions{
      font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
      border-collapse: collapse;
      width: 70%;
      height: 100%;
      margin: auto;
      text-align: center;
    }

    #useroptions td, #useroptions th {
      border: 1px solid #ddd;
      padding: 2px;
      text-align: center;
    }

    #useroptions tr:nth-child(even){background-color: #f2f2f2;}

    #useroptions tr:hover {background-color: #ddd;}

    #useroptions th {
      padding-top: 12px;
      padding-bottom: 12px;
    }
    .TableTitle {
        border: none;
    }
</style>

    </head>


    <script type="text/javascript">      
        $(function () {
            $('#academic_year1').multiselect({includeSelectAllOption: false});
            $('#academic_year2').multiselect({includeSelectAllOption: false});
            $('#academic_year3').multiselect({includeSelectAllOption: false});
            $('#academic_year4').multiselect({includeSelectAllOption: false});
            $('#academic_year5').multiselect({includeSelectAllOption: false});

            $('#term1').multiselect({includeSelectAllOption: false});
            $('#term2').multiselect({includeSelectAllOption: false});
            $('#term3').multiselect({includeSelectAllOption: false});
            $('#term4').multiselect({includeSelectAllOption: false});
            $('#term5').multiselect({includeSelectAllOption: false});

            $('#grade').multiselect({includeSelectAllOption: false});
            
            $('#studentYear').multiselect({includeSelectAllOption: false});
            $('#student').multiselect({includeSelectAllOption: false});
            
            $('#view').multiselect({includeSelectAllOption: false});


        });
        
        function FillStudents() {
            var selected_grade = $("#grade option:selected");
            var selected_year = $("#studentYear option:selected");

            var currentGrade = "";
            selected_grade.each(function() {   
                currentGrade = "(grade = '" + $(this).text() + "')";
            });

            var currentYear = "";
            selected_year.each(function() {   
                currentYear = "(acd_code = '" + $(this).text() + "')";
            });

            if (currentYear != "" && currentYear != "Year") {
                // Sending to Server
                var httpSearch = new XMLHttpRequest();
                httpSearch.onreadystatechange = function () {
                    if (this.readyState === 4) {
                    // document.getElementById('out').innerHTML = this.responseText;
                        var str = this.responseText;
                        namesArray = str.split("\t");
                    }
                };      
                httpSearch.open("POST", "sqldb/studentsNames.php?grade=" + currentGrade + "&year=" + currentYear, false);
                httpSearch.send();

                var studentsDropDown = document.getElementById('studentsDropDown');
                while (studentsDropDown.length > 0)
                    studentsDropDown.remove(0);

                $('#studentsDropDown').multiselect('destroy');

                delete namesArray[namesArray.length - 1];

                studentsDropDown.add(new Option('None'));

                for (var i in namesArray)
                    studentsDropDown.add(new Option(namesArray[i]));
                
                $(function () {
                    $('#studentsDropDown').multiselect({
                        includeSelectAllOption: true
                    });
                });

            }
        }

function search() {
    var selected_grades = $("#grade option:selected");
    var selected_student = $("#studentsDropDown option:selected");
    var selected_view = $("#view option:selected");
    
    var selected_years1 = $("#academic_year1 option:selected").text();
    if (selected_years1 != "Year")
            years1SQL = "(acd_code = '" + selected_years1 + "') ";

    var selected_years2 = $("#academic_year2 option:selected");
    var selected_years3 = $("#academic_year3 option:selected");
    var selected_years4 = $("#academic_year4 option:selected");
    var selected_years5 = $("#academic_year5 option:selected");

    var selected_terms1 = $("#term1 option:selected");
    var selected_terms2 = $("#term2 option:selected");
    var selected_terms3 = $("#term3 option:selected");
    var selected_terms4 = $("#term4 option:selected");
    var selected_terms5 = $("#term5 option:selected");

    // Current Year                   
    var currentGrade = "";
    selected_grades.each(function() {
        currentGrade = $(this).text();
    });

    if (currentGrade == "Grade")
        alert("View Changed, Click search again");
    else {
        // Current Student
        var currentStudent = "";
        selected_student.each(function()
        {
            currentStudent = $(this).text();
            if (currentStudent != "" && currentStudent != "None" )
                document.getElementById('TableTitle').innerHTML = currentGrade + "  -  " + currentStudent;
            else
                document.getElementById('TableTitle').innerHTML = currentGrade;
        });
        currentGrade = "(grade = '" + currentGrade + "')";

        // Current View                    
        var currentView = ""; 
        selected_view.each(function()
            {currentView = $(this).text();});


                                            
        //Generate selected years SQL statement YEARS 2
        var years2SQL = "";
        selected_years2.each(function () {
            var currentYear = $(this).text();
            if (currentYear != 'Year')
                if (years2SQL === "")
                    years2SQL = "(acd_code = '" + currentYear + "' ";
                else
                    years2SQL += " OR acd_code = '" + currentYear + "'";
        });

        if (years2SQL !== "") 
            years2SQL += ")";                    
                           
        //Generate selected years SQL statement YEARS 3
        var years3SQL = "";
        selected_years3.each(function () {
            var currentYear = $(this).text();
            if (currentYear != 'Year')
            if (years3SQL === "")
                years3SQL = "(acd_code = '" + currentYear + "' ";
            else
                years3SQL += " OR acd_code = '" + currentYear + "'";
        });

        if (years3SQL !== "") 
            years3SQL += ")";                    

        // //Generate selected years SQL statement YEARS 4
        var years4SQL = "";
        selected_years4.each(function () {
            var currentYear = $(this).text();
            if (currentYear != 'Year')
            if (years4SQL === "")
                years4SQL = "(acd_code = '" + currentYear + "' ";
            else
                years4SQL += " OR acd_code = '" + currentYear + "'";
        });

        if (years4SQL !== "") 
            years4SQL += ")";                    

        // //Generate selected years SQL statement YEARS 5
        var years5SQL = "";
        selected_years5.each(function () {
            var currentYear = $(this).text();
            if (currentYear != 'Year')
            if (years5SQL === "")
                years5SQL = "(acd_code = '" + currentYear + "' ";
            else
                years5SQL += " OR acd_code = '" + currentYear + "'";
        });

        if (years5SQL !== "") 
            years5SQL += ")";                    


        //Generate selected years SQL statement TERMS 1
        var terms1SQL = "";
        selected_terms1.each(function () {
            var currentTerm = $(this).text();

            if (terms1SQL === "")
                terms1SQL = " (REPLACE(exam_name, ' ', '') = REPLACE('" + currentTerm + "', ' ','') ";
            else
                terms1SQL += " OR REPLACE(exam_name, ' ', '') = REPLACE('" + currentTerm + "', ' ', '')";
        });


        if (terms1SQL !== "")
            terms1SQL += ")";

        //Generate selected years SQL statement TERMS 2
        var terms2SQL = "";
        selected_terms2.each(function () {
            var currentTerm = $(this).text();
            if (currentTerm != 'Term')
            if (terms2SQL === "")
                terms2SQL = " (REPLACE(exam_name, ' ', '') = REPLACE('" + currentTerm + "', ' ','') ";
            else
                terms2SQL += " OR REPLACE(exam_name, ' ', '') = REPLACE('" + currentTerm + "', ' ', '')";
        });


        if (terms2SQL !== "")
            terms2SQL += ")";                    


        // //Generate selected years SQL statement TERMS 3
        var terms3SQL = "";
        selected_terms3.each(function () {
            var currentTerm = $(this).text();
            if (currentTerm != 'Term')
            if (terms3SQL === "")
                terms3SQL = " (REPLACE(exam_name, ' ', '') = REPLACE('" + currentTerm + "', ' ','') ";
            else
                terms3SQL += " OR REPLACE(exam_name, ' ', '') = REPLACE('" + currentTerm + "', ' ', '')";
        });


        if (terms3SQL !== "")
            terms3SQL += ")";                    


        // //Generate selected years SQL statement TERMS 4
        var terms4SQL = "";
        selected_terms4.each(function () {
            var currentTerm = $(this).text();
            if (currentTerm != 'Term')
            if (terms4SQL === "")
                terms4SQL = " (REPLACE(exam_name, ' ', '') = REPLACE('" + currentTerm + "', ' ','') ";
            else
                terms4SQL += " OR REPLACE(exam_name, ' ', '') = REPLACE('" + currentTerm + "', ' ', '')";
        });


        if (terms4SQL !== "")
            terms4SQL += ")";                    


        // //Generate selected years SQL statement TERMS 5
        var terms5SQL = "";
        selected_terms5.each(function () {
            var currentTerm = $(this).text();
            if (currentTerm != 'Term')
            if (terms5SQL === "")
                terms5SQL = " (REPLACE(exam_name, ' ', '') = REPLACE('" + currentTerm + "', ' ','') ";
            else
                terms5SQL += " OR REPLACE(exam_name, ' ', '') = REPLACE('" + currentTerm + "', ' ', '')";
        });


        if (terms5SQL !== "")
            terms5SQL += ")";                    

        // Sending to Server
        var httpSearch = new XMLHttpRequest();
        httpSearch.onreadystatechange = function () {
            if (this.readyState === 4) {
                document.getElementById("results").innerHTML = this.responseText;
            }
        };
        
        // document.getElementById('out').innerHTML += "Before Sending " + years1SQL;
    httpSearch.open("POST", "sqldb/newAdvancedSearch.php?grades=" + currentGrade + 
    "&years1=" + years1SQL + "&years2=" + years2SQL + "&years3=" + years3SQL + "&years4=" + years4SQL + "&years5=" + years5SQL + 
    "&terms1=" + terms1SQL+ "&terms2=" + terms2SQL + "&terms3=" + terms3SQL+ "&terms4=" + terms4SQL +"&terms5=" + terms5SQL + "&view=" + currentView + "&student=" + currentStudent, false);

    httpSearch.send();
} 
}
</script>
<script type="text/javascript">
    function Cycle() {
        var selected_year = $("#studentYear option:selected");
        var year = "";
        selected_year.each(function() {   
            year = $(this).text();
        });

        if (year == "Year")
            alert("Select a Year");
        else
        {
            var table = document.getElementById('useroptions');
            var row = table.getElementsByTagName("tr");
            var deleting = false;
            // document.getElementById('out').innerHTML += row.length;
            if (row.length != 0)  deleting = true;
            while (deleting) {
                table.deleteRow(0);
                if (row.length == 0)
                    deleting = false;
            }


        var httpCycle = new XMLHttpRequest();
        httpCycle.onreadystatechange = function () {
            if (this.readyState === 4) {
                document.getElementById("useroptions").innerHTML += this.responseText;
            }
        };
        
        var selected_year = $("#studentYear option:selected");
        var year = "";
            selected_year.each(function() {   
                year = $(this).text();
            });

        var selected_view = $("#view option:selected");
        var currentView = ""; 
        selected_view.each(function()
            {currentView = $(this).text();});

        httpCycle.open("POST", "sqldb/CycleSearch.php?year=" + year + "&view=" + currentView, false);
        httpCycle.send();
    }

    }

</script>
<body>   
    <div class="w3-responsive" >
        <?php include('navbar.php'); ?>
        <script>document.getElementById("navAdvanced").style.backgroundColor = '#009688';</script>
    </div>
    <label id="out"></label>                            <!-- Debug Console -->
    
    <div class="w3-container">   <!-- DropDowns-->
        <table class="w3-table-all w3-card w3-gray">
            <th>
                <select style="float:left;" id="studentYear" onchange="FillStudents()"></select> <!-- Year DropDown -->                    
            </th>            
            <th>
                <button class="w3-btn w3-white w3-border w3-round-large w3-hover-green" onclick="Cycle()">Cycle Analysis</button>                    
            </th>            
            <th>
                <select id="grade" onchange="FillStudents(), search()"></select>     <!-- Grade DropDown -->                    
            </th>
            <th>
                <select id="studentsDropDown" onchange="FillYears(), search()"></select>          <!-- Students DropDown -->                    
            </th>
            <th>
                <select id="view" onchange="search()">
                    <option>Attainment</option>
                    <option>Percentage</option>
                    <option>Attainment - Percentage</option>
                </select>
            </th>
            <th>
                <button id='pp' class='w3-button w3-ripple w3-hover-green w3-round-xxlarge fa fa-print w3-xlarge' 
                        onclick="printJS({
                                documentTitle: 'Attainment Progress Analysis - Al Sanawbar School',
                                printable: 'divprint',
                                type: 'html',
                                showModal:true,
                                ignoreElements: ['pp'],
                                targetStyles: ['*'],
                                css: 'styles/advanced.css'
                                })">
                </button>                    
            </th>            
        </table>
    </div> 
    <div class="w3-center" id="divprint">
        
        <table id="PageTitle" style="margin: auto; width: 70%;">
            <tr>
                <th id="SchoolLogoTH" style="text-align: center;" colspan="2">
                    <img id="SchoolLogo" src="images/sanawbar.jpg" style="width: 10%;">
                </th>
            </tr>
            <tr>
                <th id="SchoolName" style="text-align: center;" colspan="2">
                    Al Sanawbar School
                </th>
            </tr>
            <tr><br><br></tr>
            <tr>
                <th id="Performance">
                    Performance Indicator levels: Summary
                </th>
                <th id="Attainment" style="text-align: right;">
                    Attainment Progress Analysis
                </th>
            </tr>
        </table> 

        <table id="useroptions" class="w3-card">
            <thead>
                <tr>
                    <th id="TableTitle" colspan="6"></th>
                </tr>
            </thead>
            <tr>
                <th><label>Year</label></th>
                <th><select id="academic_year1" onchange="FillTerm(this, 'term1')"></select></th>
                <th><select id="academic_year2" onchange="FillTerm(this, 'term2')"></select></th>
                <th><select id="academic_year3" onchange="FillTerm(this, 'term3')"></select></th>
                <th><select id="academic_year4" onchange="FillTerm(this, 'term4')"></select></th>
                <th><select id="academic_year5" onchange="FillTerm(this, 'term5')"></select></th>
            </tr>
            <tr>
                <th><label>Term</label></th>
                <th><select id="term1" onchange="search()"></select></th>
                <th><select id="term2" onchange="search()"></select></th>
                <th><select id="term3" onchange="search()"></select></th>
                <th><select id="term4" onchange="search()"></select></th>
                <th><select id="term5" onchange="search()"></select></th>
            </tr>
            <tbody id="results"> </tbody>
        </table>

        <table id="InDepthDiv" style="width: 70%; margin: auto; color: gray; font-size: 10px; opacity: 0.5">
            <tr>
                <td id="InDepthTD" style="text-align: right;">Powered By <a href="https://www.indepth.ae">InDepth</a></td>
            </tr>
        </table>
    </div>
</div>


        <script type="text/javascript">
            function FillYears() {
                var selected_years1 = $("#academic_year1 option:selected");
                var selected_years2 = $("#academic_year2 option:selected");
                var selected_years3 = $("#academic_year3 option:selected");
                var selected_years4 = $("#academic_year4 option:selected");
                var selected_years5 = $("#academic_year5 option:selected");

                var years1 = document.getElementById('academic_year1');
                var years2 = document.getElementById('academic_year2');
                var years3 = document.getElementById('academic_year3');
                var years4 = document.getElementById('academic_year4');
                var years5 = document.getElementById('academic_year5');

                while (years1.length > 0) years1.remove(0);

                while (years2.length > 0) years2.remove(0);
                
                while (years3.length > 0) years3.remove(0);
                
                while (years4.length > 0) years4.remove(0);

                while (years5.length > 0) years5.remove(0);

                $('#academic_year1').multiselect('destroy');
                $('#academic_year2').multiselect('destroy');
                $('#academic_year3').multiselect('destroy');
                $('#academic_year4').multiselect('destroy');
                $('#academic_year5').multiselect('destroy');

                var selected_student = $("#studentsDropDown option:selected");      
                var currentStudent = "";
                selected_student.each(function()
                {
                    currentStudent = $(this).text();
                });                

                var httpYears = new XMLHttpRequest();
                httpYears.onreadystatechange = function () {
                    if (this.readyState === 4) {
                        var str = this.responseText;
                        // document.getElementById('out').innerHTML += this.responseText;
                        yearsArray = str.split("\t");
                    }
                };
                httpYears.open("POST", "sqldb/YearsViaStudent.php?student=" + currentStudent, false);
                httpYears.send();                

                delete yearsArray[yearsArray.length - 1];

                years2.add(new Option('Year'));
                years3.add(new Option('Year'));
                years4.add(new Option('Year'));
                years5.add(new Option('Year'));

                for (var i in yearsArray) {
                    years1.add(new Option(yearsArray[i]));
                    years2.add(new Option(yearsArray[i]));
                    years3.add(new Option(yearsArray[i]));
                    years4.add(new Option(yearsArray[i]));
                    years5.add(new Option(yearsArray[i]));
                };
                $(function () {
                    $('#academic_year1').multiselect({
                        includeSelectAllOption: false
                    });
                });
                $(function () {
                    $('#academic_year2').multiselect({
                        includeSelectAllOption: false
                    });
                });
                $(function () {
                    $('#academic_year3').multiselect({
                        includeSelectAllOption: false
                    });
                });
                $(function () {
                    $('#academic_year4').multiselect({
                        includeSelectAllOption: false
                    });
                });
                $(function () {
                    $('#academic_year5').multiselect({
                        includeSelectAllOption: false
                    });
                });
                // search();
            }
        </script>


        <script type="text/javascript">
            function FillTerm(year, term) {
                var year = $(year).children('option:selected').text();

                var selected_student = $("#studentsDropDown option:selected");      
                var currentStudent = "";
                    selected_student.each(function()
                    {
                        currentStudent = $(this).text();
                    });                

                var termsArray = "";
                var httpTerms = new XMLHttpRequest();
                httpTerms.onreadystatechange = function () {
                    if (this.readyState === 4) {
                        var str = this.responseText;
                        // document.getElementById('out').innerHTML += this.responseText;
                        termsArray = str.split("\t");
                    }
                };
                httpTerms.open("POST", "sqldb/termsViaYear.php?year=" + year + "&student=" + currentStudent, false);
                httpTerms.send();

                var termDropdown = document.getElementById(term);

                while (termDropdown.length > 0)
                    termDropdown.remove(0);


                var name = '#' + term;
                $(name).multiselect('destroy');

                delete termsArray[termsArray.length - 1];


                for (var i in termsArray) {
                    termDropdown.add(new Option(termsArray[i]));
                };

                $(function () {
                    $(name).multiselect({
                        includeSelectAllOption: true
                    });
                });

                search();
            }
        </script>

        <!-- Print Date -->
        <!-- <script type="text/javascript">
            n =  new Date();
            y = n.getFullYear();
            m = n.getMonth() + 1;
            d = n.getDate();
            document.getElementById("date").innerHTML = "Date " + m + "/" + d + "/" + y;
        </script>
         -->
        <!-- Initialize Grades    -->
        <script type="text/javascript">
            var select = document.getElementById('grade');

            var httpgrades = new XMLHttpRequest();
            httpgrades.onreadystatechange = function () {
                if (this.readyState === 4) {
                    var str = this.responseText;
                    gradesArray = str.split("\t");
                }
            };

            httpgrades.open("GET", "sqldb/grades.php", false);
            httpgrades.send();

            $('#grade').multiselect('destroy');

            delete gradesArray[gradesArray.length - 1];
            
            select.add(new Option("Grade"));
            for (var i in gradesArray)
                select.add(new Option(gradesArray[i]));
        </script>                

        <!-- Initialize Academic Years    -->
        <script type="text/javascript">
            var AY1 = document.getElementById('academic_year1');
            var AY2 = document.getElementById('academic_year2');
            var AY3 = document.getElementById('academic_year3');
            var AY4 = document.getElementById('academic_year4');
            var AY5 = document.getElementById('academic_year5');
            var SY  = document.getElementById('studentYear');

            var httpyear = new XMLHttpRequest();
            httpyear.onreadystatechange = function () {
                if (this.readyState === 4) {
                    var str = this.responseText;
                    yearArray = str.split("\t");
                }
            };
            httpyear.open("GET", "sqldb/years.php", false);
            httpyear.send();  

            $('#academic_year1').multiselect('destroy');
            $('#academic_year2').multiselect('destroy');
            $('#academic_year3').multiselect('destroy');
            $('#academic_year4').multiselect('destroy');
            $('#academic_year5').multiselect('destroy');
            $('#studentYear').multiselect('destroy');

            delete yearArray[yearArray.length - 1];

            AY2.add(new Option('Year'));
            AY3.add(new Option('Year'));
            AY4.add(new Option('Year'));
            AY5.add(new Option('Year'));
            SY.add(new Option('Year'));

            for (var i in yearArray) {
                AY1.add(new Option(yearArray[i]));
                AY2.add(new Option(yearArray[i]));
                AY3.add(new Option(yearArray[i]));
                AY4.add(new Option(yearArray[i]));
                AY5.add(new Option(yearArray[i]));
                SY.add(new Option(yearArray[i]));
            };
        </script>

        <!-- Initialize Terms    -->
        <script type="text/javascript">      
                var term1 = document.getElementById('term1');
                var term2 = document.getElementById('term2');
                var term3 = document.getElementById('term3');
                var term4 = document.getElementById('term4');
                var term5 = document.getElementById('term5');

                var httpTerms = new XMLHttpRequest();
                httpTerms.onreadystatechange = function () {
                    if (this.readyState === 4) {
                        var str = this.responseText;
                        termsArray = str.split("\t");
                    }
                };

                httpTerms.open("GET", "sqldb/terms.php", false);
                httpTerms.send();


                
                $('#terms1').multiselect('destroy');
                $('#terms2').multiselect('destroy');
                $('#terms3').multiselect('destroy');
                $('#terms4').multiselect('destroy');
                $('#terms5').multiselect('destroy');

                delete termsArray[termsArray.length - 1];

                term2.add(new Option('Term'));
                term3.add(new Option('Term'));
                term4.add(new Option('Term'));
                term5.add(new Option('Term'));

                for (var i in termsArray)
                    term1.add(new Option(termsArray[i]));
        </script>
        
    </body>
    </html>

<?php } 
