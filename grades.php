<?php
session_start();
if (!isset($_SESSION['login'])) {
    $_SESSION['notloggedin'] = 1;
    header('Location: index.php');
} else {
    include('Header.php');
    ?>

<title>Attainment Analysis</title>

<style type="text/css">
    #SubjectExam th, #SubjectExam td, #SubjectExam tr {
        border: 3px solid black;
    }
</style>

</head>


<script type="text/javascript">
    $( document ).ready(function() {
        document.getElementById('pp').click();
});
</script>


<script type="text/javascript">      
    $(function () {
        $('#subject').multiselect({includeSelectAllOption: false});
        $('#exam').multiselect({includeSelectAllOption: false});

        var select = document.getElementById('subject');
        var httpsubjects = new XMLHttpRequest();
        httpsubjects.onreadystatechange = function () {
            if (this.readyState === 4) {
                var str = this.responseText;
                subjectsArray = str.split("\t");
            }
        };

        httpsubjects.open("GET", "sqldb/subjects.php", false);
        httpsubjects.send();

        $('#subject').multiselect('destroy');

        delete subjectsArray[subjectsArray.length - 1];
        
        select.add(new Option("Subject"));

        for (var i in subjectsArray)
            select.add(new Option(subjectsArray[i]));

         $(function () {
            $('#subject').multiselect({
                includeSelectAllOption: true
            });
        });  

// *******************   Exams ******************
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
         $(function () {
            $('#exam').multiselect({
                includeSelectAllOption: true
                });
        });

    });
</script>


<script type="text/javascript">
    function search() {
        var selected_subject = $("#subject option:selected");
        var subject = "";
        selected_subject.each(function() {   
            subject = $(this).text();
            document.getElementById('out').innerHTML = subject;
        });

        if (subject == "Subject")
            alert("Select a Subject");
        else
        {
            
            var selected_exam = $("#exam option:selected");
            var exam = "";
            selected_exam.each(function() {   
                exam = $(this).text();
                document.getElementById('out').innerHTML += exam;
            });

            if (exam == "Exam")
                alert("Select a Exam");
            else {
                var httpsearch = new XMLHttpRequest();
                httpsearch.onreadystatechange = function () {
                    if (this.readyState === 4) {
                        document.getElementById("result").innerHTML += this.responseText;
                    }
                };
                
                document.getElementById('out').innerHTML += subject;
                httpsearch.open("POST", "sqldb/trafficSearch.php?subject=" + subject + "&exam=" + exam, false);
                httpsearch.send();
            }
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
                <button id='pp' class='w3-button w3-ripple w3-hover-green w3-round-xxlarge fa fa-print w3-xlarge' 
                        onclick="printJS({
                                documentTitle: 'Attainment Progress Analysis - Al Sanawbar School',
                                printable: 'divprint',
                                type: 'html',
                                showModal:true,
                                ignoreElements: ['pp'],
                                // targetStyles: ['*']
                                css: 'styles/grades.css'
                                })">
                </button>                    
            </th>            
        </table>
    </div>


<div id="divprint" class="divprint" style="background-color: gray;">
<div class="pdfcenter">
    <table align='center'>
        <tr>
            <th id="SchoolLogo" style="text-align: center;" colspan="2">
                <img id="SchoolLogo" src="images/sanawbar.jpg" style="width: 10%;">
            </th>
        </tr>
        <tr>
            <th id="SchoolName" style="text-align: center;" colspan="2">
                Al Sanawbar School
            </th>
        </tr>
        <tr>
            <th id="Performance">
                Performance Indicator levels: Summary
            </th>
            <th id="Attainment" style="text-align: right;">
                Attainment Progress Analysis
            </th>
        </tr>
    </table>
</div>
<!-- ****************************************************************************************************** -->

    <table id="useroptions" class="w3-card w3-centered w3-table">       <!--Header Table-->
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
<!-- ****************************************************************************************************** -->        

    <table id="SubjectExam" class="w3-card w3-centered">
        <thead>
            <tr>
                <th></th>
                <th class="w3-yellow" colspan="4">
                    Subject name                        
                </th>
                <th colspan="10">
                   <select id="subject" onchange="search()"></select>
                </th>
            </tr>
            <tr>
                <th></th>
                <th class="w3-yellow" colspan="4">Exam name</th>
                <th colspan="8">
                    <select id="exam" onchange="search()"></select>
                </th>
                <th class="w3-blue Vtext" rowspan="2" colspan="2">
                    attainment judgment
                </th>
            </tr>
            <tr>
                <th></th>
                <th class="w3-yellow" colspan="4">2017</th>
                <th class="w3-yellow" colspan="4">2018</th>
                <th class="w3-yellow" colspan="4">2019</th>
            </tr>
            <tr>
                <td></td>
                <td>
                    Number of Students
                </td>
                <td class="w3-green Vtext">
                    % students achieving levels above Expectations
                </td>
                <td class="w3-yellow Vtext">
                    % students achieving levels minimum Expectaions
                </td>
                <td class="w3-red Vtext">
                    % students achieving levels below Expectaions
                </td>

                <!-- ******************************************** -->
                <td>
                    Number of Students
                </td>
                <td class="w3-green Vtext">
                    % students achieving levels above Expectations
                </td>
                <td class="w3-yellow Vtext">
                    % students achieving levels minimum Expectaions
                </td>
                <td class="w3-red Vtext">
                    % students achieving levels below Expectaions
                </td>

                <!-- ******************************************** -->
                <td>
                    Number of Students
                </td>
                <td class="w3-green Vtext">
                    % students achieving levels above Expectations
                </td>
                <td class="w3-yellow Vtext">
                    % students achieving levels minimum Expectaions
                </td>
                <td class="w3-red Vtext">
                    % students achieving levels below Expectaions
                </td>

                <td class="w3-blue">
                    Attainment benchmark judgment for tde latest year
                </td>
                <td class="w3-blue">
                    Trend in attainment over time
                </td>
            </tr>
        </thead>
        
        <tbody id="result"></tbody>
            
    </table>


    <table id="InDepthDiv" style="width: 100%; margin: auto; color: gray; font-size: 10px; opacity: 0.5">
        <tr>
            <td id="InDepthTD" style="text-align: right;">Powered By <a href="https://www.indepth.ae">InDepth</a></td>
        </tr>
    </table>

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
