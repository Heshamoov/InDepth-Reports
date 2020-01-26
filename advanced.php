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

<link rel="stylesheet" type="text/css" href="styles/advanced.css">

<title>Attainment Analysis</title>

</head>

<script type="text/javascript">      
    $(function () {
        $('#year').multiselect({includeSelectAllOption: false});
        $('#grade').multiselect({includeSelectAllOption: false});
        $('#gender').multiselect({includeSelectAllOption: false});
        $('#student').multiselect({includeSelectAllOption: false});
        $('#suggestedNames').multiselect({includeSelectAllOption: false});
        $('#nationality').multiselect({includeSelectAllOption: false});
        $('#view').multiselect({includeSelectAllOption: false});
        $('#term1').multiselect({includeSelectAllOption: false});
        $('#term2').multiselect({includeSelectAllOption: false});
        $('#term3').multiselect({includeSelectAllOption: false});
        $('#term4').multiselect({includeSelectAllOption: false});
        $('#term1d').multiselect({includeSelectAllOption: false});
    });
        
function FillStudents() {
    let namesArray = "";
    let Grade = $("#grade option:selected").text();
    let httpSearch = new XMLHttpRequest();
    httpSearch.onreadystatechange = function () {
        if (this.readyState === 4) {
            // document.getElementById('debug').innerHTML = this.responseText;
            let str = this.responseText;
            namesArray = str.split("\t");
        }
    };      
    httpSearch.open("POST", "sqldb/studentsNames.php?grade=" + Grade, false);
    httpSearch.send();

    let studentsDropDown = document.getElementById('student');
    while (studentsDropDown.length > 0)
        studentsDropDown.remove(0);

    $('#student').multiselect('destroy');

    delete namesArray[namesArray.length - 1];

    studentsDropDown.add(new Option('Student'));

    for (let i in namesArray)
        studentsDropDown.add(new Option(namesArray[i]));
    
    $(function () {
        $('#student').multiselect({
            includeSelectAllOption: true
        });
    });

}
function suggestNames(){
    let Student = $("#student option:selected").text();
    let namesArray = "";
    let httpSearch = new XMLHttpRequest();
    httpSearch.onreadystatechange = function () {
        if (this.readyState === 4) {
            // document.getElementById('debug').innerHTML = this.responseText;
            let str = this.responseText;
            namesArray = str.split("\t");
        }
    };
    httpSearch.open("POST", "sqldb/suggestNames.php?student=" + Student, false);
    httpSearch.send();

    let studentsDropDown = document.getElementById('suggestedNames');
    while (studentsDropDown.length > 0)
        studentsDropDown.remove(0);

    $('#suggestedNames').multiselect('destroy');

    delete namesArray[namesArray.length - 1];

    studentsDropDown.add(new Option('Best Match'));

    for (let i in namesArray)
        studentsDropDown.add(new Option(namesArray[i]));

    $(function () {
        $('#suggestedNames').multiselect({
            includeSelectAllOption: true
        });
    });

}
function search() {
    let Grade = $("#grade option:selected").text();
    let Gender = $("#gender option:selected").text();
    let Nationality = $("#nationality option:selected").text();
    let Student = $("#student option:selected").text();
    let SuggestedStudent = $("#suggestedNames option:selected").text();
    let View = $("#view option:selected").text();
    let Term1 = $("#term1 option:selected").text();
    let Term2 = $("#term2 option:selected").text();
    let Term3 = $("#term3 option:selected").text();
    let Term4 = $("#term4 option:selected").text();

    let Title = "";

    if (Grade !== "Grade")
        Title = Grade;
    if (Student === "" || Student === "Student" )
        Title = Title + "&nbsp&nbsp&nbsp-&nbsp&nbsp&nbsp" + Nationality + "&nbsp&nbsp&nbsp-&nbsp&nbsp&nbsp" + Gender;
    else
        Title = Title + "&nbsp&nbsp&nbsp-&nbsp&nbsp&nbsp" + Student;
         

    

    document.getElementById('TableTitle').innerHTML = Title;
    
    // terms1SQL += " OR REPLACE(exam_name, ' ', '') = REPLACE('" + currentTerm + "', ' ', '')";
    let httpSearch = new XMLHttpRequest();
    httpSearch.onreadystatechange = function () {
        if (this.readyState === 4)
            document.getElementById("results").innerHTML = this.responseText;
        };
        
    httpSearch.open("POST", "sqldb/newAdvancedSearch.php?Grade=" + Grade + "&Gender=" + Gender +
    "&Nationality=" + Nationality + "&Student=" + Student + "&SuggestedName=" + SuggestedStudent +
    "&Term1=" + Term1 + "&Term2=" + Term2 + "&Term3=" + Term3 + "&Term4=" + Term4 +
    "&View=" + View, false);
    httpSearch.send();
}
</script>

<body>
<div class="w3-responsive" >
    <?php include('navbar.php'); ?>
    <script>document.getElementById("navAdvanced").style.backgroundColor = '#009688';</script>
</div>

<div id="debug"></div>

<div class="w3-container">   <!-- DropDowns-->
    <table class="w3-table-all w3-card w3-gray">
        <th>
            <div class="w3-container">
              <div class="w3-dropdown-hover">
                <button class="w3-button w3-green">Benchmark</button>
                <div class="w3-dropdown-content w3-bar-block w3-border">
                  <a href="advanced.php" class="w3-bar-item w3-button w3-hover-green">Attainment</a>
                  <a href="advanced2020.php" class="w3-bar-item w3-button w3-hover-green">2020 Attainment</a>
                  <a href="cycle.php" class="w3-bar-item w3-button w3-hover-green">Cycle</a>
                  <a href="grades.php" class="w3-bar-item w3-button w3-hover-green">Grades</a>
                </div>
              </div>
            </div>            
      </th>   
      <th>
            <select id="grade" onchange="search(); FillStudents()"></select>
        </th>
        <th>
            <select id="nationality" onchange="search()">
                <option>Nationality: ALL</option>
                <option>Citizens</option>
                <option>Expats</option>
            </select>
        </th>        
        <th>
            <select id="gender" onchange="search()">
                <option>Gender: ALL</option>
                <option>Boys</option>
                <option>Girls</option>
            </select>
        </th>
        <th>
            <select id="student" onchange="search(); suggestNames()"></select>
        </th>
        <th>
            <select id="suggestedNames" onchange="search()"></select>
        </th>
        <th>
            <select id="view" onchange="search()">
                <option>Attainment</option>
                <option>Percentage</option>
                <option>Attainment - Percentage</option>
            </select>
        </th>
<th>
<button class='w3-button w3-ripple w3-hover-green w3-round-xxlarge fa fa-print w3-xlarge' onclick="PrintTable()"></button>
<button id='pp' hidden class='w3-button w3-ripple w3-hover-green w3-round-xxlarge fa fa-print w3-xlarge' 
    onclick="printJS({
        documentTitle: 'Grade/Student Progress Analysis - Al Sanawbar School',
        printable: 'divPrint',
        type: 'html',
        showModal:true,
        ignoreElements: [],
        css: 'styles/advancedPDF.css'
    })">
</button>                    
</th>            
    </table>
</div>
     
<div id="divPrint">
    <table id="PageTitle" style="margin: auto; width: 100%;">
        <tr>
            <th id="SchoolLogoTH" style="text-align: center;" colspan="2">
                <img id="SchoolLogo" src="images/sanawbar.jpg" style="width: 5%;">
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
                Grade/Student Progress Analysis
            </th>
        </tr>
    </table> 

    <table id="useroptions" class="w3-card">
        <thead>
            <tr>
                <th id="TableTitle" colspan="6" class="w3-center"></th>
            </tr>
        </thead>

        <tr class="dropdownTR">
            <th><label>Year</label></th>
            <th class="c67">2016 - 2017</th>
            <th class="c78">2017 - 2018</th>
            <th class="c89">2018 - 2019</th>
            <th class="c90">2019 - 2020</th>
        </tr>

        <tr>
            <th><label>Term</label></th>
            <th class="c67">
                <select id="term1" onchange="search()"></select>
                <label id="T1L"></label>
            </th>
            <th class="c78">
                <select id="term2" onchange="search()"></select>
                <label id="T2L"></label>
            </th>
            <th class="c89">
                <select id="term3" onchange="search()"></select>
                <label id="T3L"></label>
            </th>
            <th class="c92">
                <select id="term4" onchange="search()"></select>
                <label id="T4L"></label>
            </th>
        </tr>
        
        <tbody id="results"> </tbody>
    </table>

    <table id="InDepthDiv" style="width: 100%; margin: auto; color: gray; font-size: 10px; opacity: 0.5">
        <tr>
            <td id="today"></td>
            <td id="InDepthTD" style="text-align: right;">Powered By <a href="https://www.indepth.ae">InDepth</a></td>
        </tr>
    </table>
</div>

<script>
    let d = new Date();
    let year = d.getFullYear();
    let month = d.getMonth() + 1;
    let day = d.getDate();
    let date = day + "/" + month + "/" + year;

    document.getElementById("today").innerHTML = date;
</script>

<!-- Initialize Years -->
<!--<script src="js/initYears.js"></script>-->
<!-- Initialize Grades    -->                       
<script src="js/initGrades.js"></script>
<!-- Initialize Terms -->
<script src="js/initTerms.js"></script>

<script type="text/javascript">
    function PrintTable () {
        document.getElementById('T1L').innerHTML = $('#term1').children('option:selected').text();
        document.getElementById('T2L').innerHTML = $('#term2').children('option:selected').text();
        document.getElementById('T3L').innerHTML = $('#term3').children('option:selected').text();
        document.getElementById('T4L').innerHTML = $('#term4').children('option:selected').text();

        $('#term1').multiselect('destroy');
        $('#term2').multiselect('destroy');
        $('#term3').multiselect('destroy');
        $('#term4').multiselect('destroy');

        if(document.getElementById('c67').innerHTML === "-")
            $('.c67').remove();
        if(document.getElementById('c78').innerHTML === "-")
            $('.c78').remove();
        if(document.getElementById('c89').innerHTML === "-")
            $('.c89').remove();
        if(document.getElementById('c90').innerHTML === "-")
            $('.c90').remove();

document.getElementById('pp').click();
        
        $('#term1').multiselect({includeSelectAllOption: false});
        $('#term2').multiselect({includeSelectAllOption: false});
        $('#term3').multiselect({includeSelectAllOption: false});
        $('#term4').multiselect({includeSelectAllOption: false});

        let term1 = document.getElementById('term1');
        let term2 = document.getElementById('term2');
        let term3 = document.getElementById('term3');
        let term4 = document.getElementById('term4');

        let httpTerms = new XMLHttpRequest();
        httpTerms.onreadystatechange = function () {
            if (this.readyState === 4) {
                let str = this.responseText;
                termsArray = str.split("\t");
            }
        };

        httpTerms.open("GET", "sqldb/terms.php", false);
        httpTerms.send();

        delete termsArray[termsArray.length - 1];


        for (let i in termsArray) {
            term1.add(new Option(termsArray[i]));
            term2.add(new Option(termsArray[i]));
            term3.add(new Option(termsArray[i]));
        }

        // 2019 - 2020

        httpTerms = new XMLHttpRequest();
        httpTerms.onreadystatechange = function () {
            if (this.readyState === 4) {
                let str = this.responseText;
                termsArray = str.split("\t");
            }
        };

        httpTerms.open("GET", "js/2020/SQL/terms1920.php?", false);
        httpTerms.send();

        delete termsArray[termsArray.length - 1];

        for (let i in termsArray)
            term4.add(new Option(termsArray[i]));

        document.getElementById("term1").value = document.getElementById('T1L').textContent;
        document.getElementById("term2").value = document.getElementById('T2L').textContent;
        document.getElementById("term3").value = document.getElementById('T3L').textContent;
        document.getElementById("term4").value = document.getElementById('T4L').textContent;


        document.getElementById('T1L').innerHTML = "";
        document.getElementById('T2L').innerHTML = "";
        document.getElementById('T3L').innerHTML = "";
        document.getElementById('T4L').innerHTML = "";

    }
</script>
</body>
</html>

<?php } 
