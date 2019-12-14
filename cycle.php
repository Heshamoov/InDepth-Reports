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
<link rel="stylesheet" href="styles/advanced.css">

<title>Attainment Analysis</title>
</head>

<script type="text/javascript">      
    $(function () {
        $('#view').multiselect({includeSelectAllOption: false});
        $('#year1').multiselect({includeSelectAllOption: false});
        $('#year2').multiselect({includeSelectAllOption: false});
        $('#year3').multiselect({includeSelectAllOption: false});
        $('#cycle1').multiselect({includeSelectAllOption: false});
        $('#cycle2').multiselect({includeSelectAllOption: false});
        $('#cycle3').multiselect({includeSelectAllOption: false});
    });
</script>

<script type="text/javascript">
    function Cycle() {
        let view = $("#view option:selected").text();
        let year1 = $("#year1 option:selected").text();
        let year2 = $("#year2 option:selected").text();
        let year3 = $("#year3 option:selected").text();
        let cycle1 = $("#cycle1 option:selected").text();
        let cycle2 = $("#cycle2 option:selected").text();
        let cycle3 = $("#cycle3 option:selected").text();

        var httpCycle = new XMLHttpRequest();
        httpCycle.onreadystatechange = function () {
            if (this.readyState === 4) {
                document.getElementById("useroptions").innerHTML = this.responseText;
            }
        };

httpCycle.open("POST", "sqldb/CycleSearch.php?year1=" + year1 + "&year2=" + year2 + "&year3=" + year3 + "&cycle1=" + cycle1 + "&cycle2=" + cycle2 + "&cycle3=" + cycle3 + "&view=" + view, false);
httpCycle.send();
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
            <button class="w3-btn w3-white w3-border w3-round-large w3-hover-green" onclick="Cycle()">Cycle Analysis</button>                    
        </th>            
        <th>
            <select id="view" onchange="Cycle()">
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
                            // targetStyles: ['*']
                            css: 'styles/advanced.css'
                            })">
            </button>                    
        </th>            
    </table>
</div>

<div id="divprint">
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
            <th>Year</th>
            <th><select id="year1" onchange="Cycle()"></select></th>
            <th><select id="year2" onchange="Cycle()"></select></th>
            <th><select id="year3" onchange="Cycle()"></select></th>
        </tr>

        <tr>
            <th>Cycle</th>
            <th>
                <select id="cycle1" onchange="search()">
                    <option>Cycle 1</option>
                    <option>Cycle 2</option>
                    <option>Cycle 3</option>
                </select>
                <label id="T1L"></label>
            </th>
            <th>
                <select id="cycle2" onchange="search()">
                    <option>Cycle 1</option>
                    <option>Cycle 2</option>
                    <option>Cycle 3</option>
                </select>                
                <label id="T2L"></label>
            </th>
            <th>
                <select id="cycle3" onchange="search()">
                    <option>Cycle 1</option>
                    <option>Cycle 2</option>
                    <option>Cycle 3</option>
                </select>
                <label id="T3L"></label>
            </th>
        </tr>
        
        <tbody id="results"> </tbody>
    </table>

    <table id="InDepthDiv" style="width: 100%; margin: auto; color: gray; font-size: 10px; opacity: 0.5">
        <tr>
            <td id="InDepthTD" style="text-align: right;">Powered By <a href="https://www.indepth.ae">InDepth</a></td>
        </tr>
    </table>
</div>


<!-- Initialize Academic Years    -->
<script type="text/javascript">
    var year1  = document.getElementById('year1');
    var year2  = document.getElementById('year2');
    var year3  = document.getElementById('year3');

    var httpyear = new XMLHttpRequest();
    httpyear.onreadystatechange = function () {
        if (this.readyState === 4) {
            var str = this.responseText;
            yearArray = str.split("\t");
        }
    };
    httpyear.open("GET", "sqldb/years.php", false);
    httpyear.send();  

    $('#year1').multiselect('destroy');
    $('#year2').multiselect('destroy');
    $('#year3').multiselect('destroy');

    delete yearArray[yearArray.length - 1];

    // SY.add(new Option('Year'));

    for (var i in yearArray) {
        year1.add(new Option(yearArray[i]));
        year2.add(new Option(yearArray[i]));
        year3.add(new Option(yearArray[i]));
    };
</script>

</body>
</html>

<?php } 
