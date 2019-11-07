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
            selected_grade.each(function()        
            {   
                currentGrade = "(grade = '" + $(this).text() + "')";
            });

            var currentYear = "";
            selected_year.each(function()        
            {   
                currentYear = "(acd_code = '" + $(this).text() + "')";
            });

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

            $('#studentsDropDown').multiselect('destroy');

            delete namesArray[namesArray.length - 1];

            for (var i in namesArray) {
                studentsDropDown.add(new Option(namesArray[i]));
            };

            $(function () {
                $('#studentsDropDown').multiselect({
                    includeSelectAllOption: true
                });
            });            
        }

        function search() {
            var selected_grades = $("#grade option:selected");
            
            
            var selected_years1 = $("#academic_year1 option:selected");
            var selected_years2 = $("#academic_year2 option:selected");
            var selected_years3 = $("#academic_year3 option:selected");
            var selected_years4 = $("#academic_year4 option:selected");
            var selected_years5 = $("#academic_year5 option:selected");

            var selected_terms1 = $("#term1 option:selected");
            var selected_terms2 = $("#term2 option:selected");
            var selected_terms3 = $("#term3 option:selected");
            var selected_terms4 = $("#term4 option:selected");
            var selected_terms5 = $("#term5 option:selected");

            var selected_view = $("#view option:selected");                   //View
            var currentView = "";
            selected_view.each(function()
            {
               currentView = $(this).text();
            });
            

            var selected_student = $("#studentsDropDown option:selected");        //Student
            var currentStudent = "";
            selected_student.each(function()
            {
               currentStudent = $(this).text();
            });                    
                    var currentGrade = "";
                    selected_grades.each(function()        
                    {   
                        currentGrade = "(grade = '" + $(this).text() + "')";
                    });

                    //Generate selected years SQL statement YEARS 1
                    var years1SQL = "";
                    selected_years1.each(function ()
                    {
                        var currentYear = $(this).text();

                        if (years1SQL === "")
                            years1SQL = "(acd_code = '" + currentYear + "' ";
                        else
                            years1SQL += " OR acd_code = '" + currentYear + "'";
                    });
                
                    if (years1SQL !== "") 
                        years1SQL += ")";
                    
                    //Generate selected years SQL statement YEARS 2
                    var years2SQL = "";
                    selected_years2.each(function () {
                        var currentYear = $(this).text();
                        if (currentYear != 'Select Year')
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
                        if (currentYear != 'Select Year')
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
                        if (currentYear != 'Select Year')
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
                        if (currentYear != 'Select Year')
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
                        if (currentTerm != 'Select Term')
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
                        if (currentTerm != 'Select Term')
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
                        if (currentTerm != 'Select Term')
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
                        if (currentTerm != 'Select Term')
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
                    
httpSearch.open("POST", "sqldb/newAdvancedSearch.php?grades=" + currentGrade + 
"&years1=" + years1SQL + "&years2=" + years2SQL + "&years3=" + years3SQL + "&years4=" + years4SQL + "&years5=" + years5SQL + 
"&terms1=" + terms1SQL+ "&terms2=" + terms2SQL + "&terms3=" + terms3SQL+ "&terms4=" + terms4SQL +"&terms5=" + terms5SQL + "&view=" + currentView + "&student=" + currentStudent
, false);

httpSearch.send();
}
</script>

<body>
    
<div class="w3-responsive header" >
    <!-- Navigation bar -->        
    <?php include('navbar.php'); ?>

    <!--set color for current tab-->
    <script>document.getElementById("navAdvanced").style.backgroundColor = '#009688';</script>
</div>


<!--Drop menus-->
<h3 class="w3-center">Attainment Progress Analysis - Al Sanawabar School</h3>

<!-- Debug Console -->
<label id="out"></label>

<!-- Select Grade -->
<div class="w3-container w3-center" id="main">
    <label class="w3-large w3-container">Grade</label>
    <select id="grade"></select>
    <button class="w3-button w3-ripple w3-hover-green w3-round-xxlarge fa fa-search w3-xlarge" onclick="search()"></button>
    
    <button id='pp' class='w3-button w3-ripple w3-hover-green w3-round-xxlarge fa fa-print w3-xlarge' 
            onclick="printJS({
                    documentTitle: 'Attainment Progress Analysis - Al Sanawbar School',
                    printable: 'useroptions',
                    type: 'html',
                    ignoreElements: ['pp'],
                    targetStyles: ['*'],
                    css: 'styles/pdf.css',
                })">
                </button>
</div>
<br>
<div class="w3-container w3-center w3-light-gray">
<label class="w3-large w3-container">Year</label>                   <!-- Year DropDown -->
<select id="studentYear" onchange="FillStudents()"></select>

<label class="w3-large w3-container">Student Name</label>           <!-- Students DropDown -->
<select id="studentsDropDown" onchange="search()"></select>

<!-- <select class="selectpicker show-tick" id="studentsDropDown" onchange="search()" data-live-search="true" title="Student Name"
    data-style="btn-success">
  <optgroup label="Picnic">
    <option>Mustard</option>
    <option>Ketchup</option>
    <option>Relish</option>
  </optgroup>
  <optgroup label="Camping">
    <option>Tent</option>
    <option>Flashlight</option>
    <option>Toilet Paper</option>
  </optgroup>
</select> -->



<label class="w3-large w3-container">View</label>
<select id="view" onchange="search()">
    <option>Attainment</option>
    <option>Percentage</option>
    <option>Attainment - Percentage</option>
</select>
</div>
<br>

<div class="w3-container">
    <table id="useroptions" class="w3-container w3-table-all w3-card w3-centered">
        <tr>
            <th><label class="w3-large">Year</label></th>
            <th><select id="academic_year1"></select></th>
            <th><select id="academic_year2"></select></th>
            <th><select id="academic_year3"></select></th>
            <th><select id="academic_year4"></select></th>
            <th><select id="academic_year5"></select></th>
        </tr>
        <tr>
            <th><label class="w3-large">Term</label></th>
            <th><select id="term1"></select></th>
            <th><select id="term2"></select></th>
            <th><select id="term3"></select></th>
            <th><select id="term4"></select></th>
            <th><select id="term5"></select></th>
        </tr>
        <tbody id="results"> </tbody>
    </table>
</div>
</div>
    
</div>




        
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
            
            for (var i in gradesArray) {
                select.add(new Option(gradesArray[i]));
            };
            
            $(function () {
                $('#grade').multiselect({
                    includeSelectAllOption: true
                });
            });
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

            AY2.add(new Option('Select Year'));
            AY3.add(new Option('Select Year'));
            AY4.add(new Option('Select Year'));
            AY5.add(new Option('Select Year'));
            SY.add(new Option('Select Year'));

            for (var i in yearArray) {
                AY1.add(new Option(yearArray[i]));
                AY2.add(new Option(yearArray[i]));
                AY3.add(new Option(yearArray[i]));
                AY4.add(new Option(yearArray[i]));
                AY5.add(new Option(yearArray[i]));
                SY.add(new Option(yearArray[i]));
            };

            // $(function () {
            //     $('#academic_year1').multiselect({
            //         includeSelectAllOption: true
            //     });
            // });

            //  $(function () {
            //     $('#academic_year2').multiselect({
            //         includeSelectAllOption: true
            //     });
            // });

            //  $(function () {
            //     $('#academic_year3').multiselect({
            //         includeSelectAllOption: true
            //     });
            // });
            //  $(function () {
            //     $('#academic_year4').multiselect({
            //         includeSelectAllOption: true
            //     });
            // });
            //  $(function () {
            //     $('#academic_year5').multiselect({
            //         includeSelectAllOption: true
            //     });                                 
    // });
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

                term2.add(new Option('Select Term'));
                term3.add(new Option('Select Term'));
                term4.add(new Option('Select Term'));
                term5.add(new Option('Select Term'));

                for (var i in termsArray) {
                    term1.add(new Option(termsArray[i]));
                    term2.add(new Option(termsArray[i]));
                    term3.add(new Option(termsArray[i]));
                    term4.add(new Option(termsArray[i]));
                    term5.add(new Option(termsArray[i]));
                }
                ;
        </script>
        
    </body>
    </html>

<?php } 
