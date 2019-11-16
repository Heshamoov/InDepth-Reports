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

<!-- <style type="text/css">
    .Vtext {
    text-align:center;
    white-space:nowrap;
    transform-origin:50% 50%;
    -webkit-transform: rotate(90deg);
    -moz-transform: rotate(90deg);
    -ms-transform: rotate(90deg);
    -o-transform: rotate(90deg);
    transform: rotate(90deg);
    
}
.Vtext:before {
    content:'';
    padding-top:110%;/* takes width as reference, + 10% for faking some extra padding */
    display:inline-block;
    vertical-align:middle;
}

</style> -->
</head>

<script type="text/javascript">      
    $(function () {
        $('#Year').multiselect({includeSelectAllOption: false});
        $('#grade').multiselect({includeSelectAllOption: false});
        $('#exam').multiselect({includeSelectAllOption: false});
    });
</script>

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

<!-- Initialize Exams    -->
<script type="text/javascript">
        var exam = document.getElementById('exam');

        var httpTerms = new XMLHttpRequest();
        httpTerms.onreadystatechange = function () {
            if (this.readyState === 4) {
                var str = this.responseText;
                termsArray = str.split("\t");
            }
        };

        httpTerms.open("GET", "sqldb/terms.php", false);
        httpTerms.send();
    
        $('#exam').multiselect('destroy');

        delete termsArray[termsArray.length - 1];

        for (var i in termsArray)
            exam.add(new Option(termsArray[i]));
</script>

<script type="text/javascript">
    function Cycle() {
        var selected_year = $("#Year option:selected");
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
        
            var selected_year = $("#Year option:selected");
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
            <div class="w3-container">
              <div class="w3-dropdown-hover">
                <button class="w3-button w3-green">Benchmark</button>
                <div class="w3-dropdown-content w3-bar-block w3-border">
                  <a href="advanced.php" class="w3-bar-item w3-button w3-hover-green">Attainment</a>
                  <a href="cycle.php" class="w3-bar-item w3-button w3-hover-green">Cycle</a>
                  <a href="grades.php" class="w3-bar-item w3-button w3-hover-green">Grades</a>
                </div>
              </div>
            </div>            
        </th>
            <th>
                <select id="Year"></select> <!-- Year DropDown -->                    
            </th>            
            <th>
                <button class="w3-btn w3-white w3-border w3-round-large w3-hover-green" onclick="Cycle()">Cycle Analysis</button>                    
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
                                targetStyles: ['*']
                                // css: 'styles/advanced.css'
                                })">
                </button>                    
            </th>            
        </table>
    </div>


    <div id="divprint" style="width: 70%; margin: auto;">
        
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
                    Attainment Progress Analysis
                </th>
            </tr>
        </table> 

        <table id="useroptions" class="w3-card w3-table w3-bordered w3-centered">       <!--Header Table-->
            <thead>
                <tr>
                    <th class="w3-yellow">School Type:</th>
                    <th colspan="3">KG-12</th>
                </tr>
                <tr>
                    <th class="w3-yellow">School Name</th> <th>Al Sanawbar School</th>
                    <th class="w3-yellow">ID</th>          <th>12345</th>
                </tr>
                <tr>
                    <th class="w3-yellow">Region</th>      <th>Abu Dhabi - Al Ain</th>
                    <th class="w3-yellow">Curriculum</th>  <th>US Curriculum</th>
                </tr>
            </thead>
        </table>
        
        <br>

        <table id="SubjectExam" class="w3-card w3-table w3-bordered w3-centered">
            <thead>
                <tr>
                    <th class="w3-yellow" colspan="3">
                        Subject name                        
                    </th>
                    <th colspan="8">
                       <select id="grade" onchange=""></select>                     
                    </th>
                </tr>
                <tr>
                    <th class="w3-yellow" colspan="3">Exam name</th>
                    <th colspan="6">
                        <select id="exam" onchange=""></select>
                    </th>
                    <th class="w3-blue Vtext" rowspan="2" colspan="2">
                        attainment judgment
                    </th>
                </tr>
                <tr>
                    <th class="w3-yellow" colspan="3">2017</th>
                    <th class="w3-yellow" colspan="3">2018</th>
                    <th class="w3-yellow" colspan="3">2019</th>
                </tr>
                <tr>
                    <th class="w3-green Vtext">
                        % students achieving levels above Expectations
                    </th>
                    <th class="w3-yellow Vtext">
                        % students achieving levels minimum Expectaions
                    </th>
                    <th class="w3-red Vtext">
                        % students achieving levels below Expectaions
                    </th>

                    <th class="w3-green Vtext">
                        % students achieving levels above Expectations
                    </th>
                    <th class="w3-yellow Vtext">
                        % students achieving levels minimum Expectaions
                    </th>
                    <th class="w3-red Vtext">
                        % students achieving levels below Expectaions
                    </th>

                    <th class="w3-green Vtext">
                        % students achieving levels above Expectations
                    </th>
                    <th class="w3-yellow Vtext">
                        % students achieving levels minimum Expectaions
                    </th>
                    <th class="w3-red Vtext">
                        % students achieving levels below Expectaions
                    </th>

                    <th class="w3-blue">
                        Attainment benchmark judgment for the latest year
                    </th>
                    <th class="w3-blue">
                        Trend in attainment over time
                    </th>
                </tr>

            </thead>
            
        </table>

        <table id="InDepthDiv" style="width: 100%; margin: auto; color: gray; font-size: 10px; opacity: 0.5">
            <tr>
                <td id="InDepthTD" style="text-align: right;">Powered By <a href="https://www.indepth.ae">InDepth</a></td>
            </tr>
        </table>

        <br><br><br>
    </div>
</div>


<!-- Initialize Academic Years    -->
<script type="text/javascript">
    var SY  = document.getElementById('Year');

    var httpyear = new XMLHttpRequest();
    httpyear.onreadystatechange = function () {
        if (this.readyState === 4) {
            var str = this.responseText;
            yearArray = str.split("\t");
        }
    };
    httpyear.open("GET", "sqldb/years.php", false);
    httpyear.send();  

    $('#Year').multiselect('destroy');

    delete yearArray[yearArray.length - 1];

    SY.add(new Option('Year'));

    for (var i in yearArray) {
        SY.add(new Option(yearArray[i]));
    };
</script>

</body>
</html>

<?php } 
