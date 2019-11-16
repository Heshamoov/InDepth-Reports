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
    #SubjectExam th, #SubjectExam td, #SubjectExam tr {
        border: 3px solid black;
    }
  /*  .Vtext {
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
    padding-top:110%;/* takes width as reference, + 10% for faking some extra padding 
    display:inline-block;
    vertical-align:middle;
}
*/
</style>
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


    <div id="divprint" style="width: 80%; margin: auto;">
        
        <table id="PageTitle" style="margin: auto; width: 100%">
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
        <br>

        <table id="SubjectExam" class="w3-card w3-centered">
            <thead>
                <tr>
                    <th></th>
                    <th class="w3-yellow" colspan="4">
                        Subject name                        
                    </th>
                    <th colspan="10">
                       <select id="grade" onchange=""></select>                     
                    </th>
                </tr>
                <tr>
                    <th></th>
                    <th class="w3-yellow" colspan="4">Exam name</th>
                    <th colspan="8">
                        <select id="exam" onchange=""></select>
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
            <tbody>
                <tr>
                    <th style="white-space: nowrap">Grade 1</th>
                    <td id="g11"></td>
                    <td id="g12"></td>
                    <td id="g13"></td>
                    <td id="g14"></td>
                    <td id="g15"></td>
                    <td id="g16"></td>
                    <td id="g17"></td>
                    <td id="g18"></td>
                    <td id="g19"></td>
                    <td id="g110"></td>
                    <td id="g111"></td>
                    <td id="g112"></td>
                    <td id="g113"></td>
                    <td id="g114"></td>
                </tr>

               <tr>
                    <th style="white-space: nowrap">Grade 2</th>
                    <td id="g21"></td>
                    <td id="g22"></td>
                    <td id="g23"></td>
                    <td id="g24"></td>
                    <td id="g25"></td>
                    <td id="g26"></td>
                    <td id="g27"></td>
                    <td id="g28"></td>
                    <td id="g29"></td>
                    <td id="g210"></td>
                    <td id="g211"></td>
                    <td id="g212"></td>
                    <td id="g213"></td>
                    <td id="g214"></td>
                </tr>

               <tr>
                    <th style="white-space: nowrap">Grade 3</th>
                    <td id="g31"></td>
                    <td id="g32"></td>
                    <td id="g33"></td>
                    <td id="g34"></td>
                    <td id="g35"></td>
                    <td id="g36"></td>
                    <td id="g37"></td>
                    <td id="g38"></td>
                    <td id="g39"></td>
                    <td id="g310"></td>
                    <td id="g311"></td>
                    <td id="g312"></td>
                    <td id="g313"></td>
                    <td id="g314"></td>
                </tr>

               <tr>
                    <th style="white-space: nowrap">Grade 4</th>
                    <td id="g21"></td>
                    <td id="g42"></td>
                    <td id="g43"></td>
                    <td id="g44"></td>
                    <td id="g45"></td>
                    <td id="g46"></td>
                    <td id="g47"></td>
                    <td id="g48"></td>
                    <td id="g49"></td>
                    <td id="g410"></td>
                    <td id="g411"></td>
                    <td id="g412"></td>
                    <td id="g413"></td>
                    <td id="g414"></td>
                </tr>

               <tr>
                    <th style="white-space: nowrap">Grade 5</th>
                    <td id="g51"></td>
                    <td id="g52"></td>
                    <td id="g53"></td>
                    <td id="g54"></td>
                    <td id="g55"></td>
                    <td id="g56"></td>
                    <td id="g57"></td>
                    <td id="g58"></td>
                    <td id="g59"></td>
                    <td id="g510"></td>
                    <td id="g511"></td>
                    <td id="g512"></td>
                    <td id="g513"></td>
                    <td id="g514"></td>
                </tr>

               <tr>
                    <th style="white-space: nowrap">Grade 6</th>
                    <td id="g61"></td>
                    <td id="g62"></td>
                    <td id="g63"></td>
                    <td id="g64"></td>
                    <td id="g65"></td>
                    <td id="g66"></td>
                    <td id="g67"></td>
                    <td id="g68"></td>
                    <td id="g69"></td>
                    <td id="g610"></td>
                    <td id="g611"></td>
                    <td id="g612"></td>
                    <td id="g613"></td>
                    <td id="g614"></td>
                </tr>                                                               

               <tr>
                    <th style="white-space: nowrap">Grade 7</th>
                    <td id="g71"></td>
                    <td id="g72"></td>
                    <td id="g73"></td>
                    <td id="g74"></td>
                    <td id="g75"></td>
                    <td id="g76"></td>
                    <td id="g77"></td>
                    <td id="g78"></td>
                    <td id="g79"></td>
                    <td id="g710"></td>
                    <td id="g711"></td>
                    <td id="g712"></td>
                    <td id="g713"></td>
                    <td id="g714"></td>
                </tr>

               <tr>
                    <th style="white-space: nowrap">Grade 8</th>
                    <td id="g81"></td>
                    <td id="g82"></td>
                    <td id="g83"></td>
                    <td id="g84"></td>
                    <td id="g85"></td>
                    <td id="g86"></td>
                    <td id="g87"></td>
                    <td id="g88"></td>
                    <td id="g89"></td>
                    <td id="g810"></td>
                    <td id="g811"></td>
                    <td id="g812"></td>
                    <td id="g813"></td>
                    <td id="g814"></td>
                </tr>                                              

               <tr>
                    <th style="white-space: nowrap">Grade 9</th>
                    <td id="g91"></td>
                    <td id="g92"></td>
                    <td id="g93"></td>
                    <td id="g94"></td>
                    <td id="g95"></td>
                    <td id="g96"></td>
                    <td id="g97"></td>
                    <td id="g98"></td>
                    <td id="g99"></td>
                    <td id="g910"></td>
                    <td id="g911"></td>
                    <td id="g912"></td>
                    <td id="g913"></td>
                    <td id="g914"></td>
                </tr>

               <tr>
                    <th style="white-space: nowrap">Grade 10</th>
                    <td id="g101"></td>
                    <td id="g102"></td>
                    <td id="g103"></td>
                    <td id="g104"></td>
                    <td id="g105"></td>
                    <td id="g106"></td>
                    <td id="g107"></td>
                    <td id="g108"></td>
                    <td id="g109"></td>
                    <td id="g1010"></td>
                    <td id="g1011"></td>
                    <td id="g1012"></td>
                    <td id="g1013"></td>
                    <td id="g1014"></td>
                </tr>

               <tr>
                    <th style="white-space: nowrap">Grade 11</th>
                    <td id="g111"></td>
                    <td id="g112"></td>
                    <td id="g113"></td>
                    <td id="g114"></td>
                    <td id="g115"></td>
                    <td id="g116"></td>
                    <td id="g117"></td>
                    <td id="g118"></td>
                    <td id="g119"></td>
                    <td id="g1110"></td>
                    <td id="g1111"></td>
                    <td id="g1112"></td>
                    <td id="g1113"></td>
                    <td id="g1114"></td>
                </tr>  

               <tr>
                    <th style="white-space: nowrap">Grade 12</th>
                    <td id="g121"></td>
                    <td id="g122"></td>
                    <td id="g123"></td>
                    <td id="g124"></td>
                    <td id="g125"></td>
                    <td id="g126"></td>
                    <td id="g127"></td>
                    <td id="g128"></td>
                    <td id="g129"></td>
                    <td id="g1210"></td>
                    <td id="g1211"></td>
                    <td id="g1212"></td>
                    <td id="g1213"></td>
                    <td id="g1214"></td>
                </tr>                                                                                                         
            </tbody>
            
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
