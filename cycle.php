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
        $('#trend').multiselect({includeSelectAllOption: false});
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
        let trend = $("#trend option:selected").text();

        if (trend == 'Details')
            document.getElementById('trendheader').innerHTML = 'Trend';
        else
            document.getElementById('trendheader').innerHTML = '';

        var httpCycle = new XMLHttpRequest();
        httpCycle.onreadystatechange = function () {
            if (this.readyState === 4) {
                document.getElementById("results").innerHTML = this.responseText;
            }
        };

httpCycle.open("POST", "sqldb/CycleSearch.php?year1=" + year1 + "&year2=" + year2 + "&year3=" + year3 + "&cycle1=" + cycle1 + "&cycle2=" + cycle2 + "&cycle3=" + cycle3 + "&view=" + view + "&trend=" + trend, false);
httpCycle.send();
    }
</script>

<body>
    <div class="w3-responsive" >
        <?php include('navbar.php'); ?>
        <script>document.getElementById("navAdvanced").style.backgroundColor = '#009688';</script>
    </div>
<!-- Debug Console -->
<!-- <div id="debug"></div>                             -->
    
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
            <select class="dropdown" id="trend" onchange="Cycle()">
                <option>Trend</option>
                <option>Details</option>
            </select>
        </th>
        <th>
            <button class='w3-button w3-ripple w3-hover-green w3-round-xxlarge fa fa-print w3-xlarge' onclick="PrintTable()"></button>
            <button id='pp' hidden class='w3-button w3-ripple w3-hover-green w3-round-xxlarge fa fa-print w3-xlarge' 
                    onclick="printJS({
                            documentTitle: 'Attainment Progress Analysis - Al Sanawbar School',
                            printable: 'divprint',
                            type: 'html',
                            showModal:true,
                            ignoreElements: ['pp'],
                            // targetStyles: ['*']
                            css: 'styles/cyclePDF.css'
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
        <tr class="dropdownTR">
            <th>Year</th>
            <th>
                <select id="year1"></select>
                <label id="y1l"></label>
            </th>
            <th>
                <select id="year2"></select>
                <label id="y2l"></label>
            </th>
            <th>
                <select id="year3"></select>
                <label id="y3l"></label>
            </th>
        </tr>

        <tr>
            <th>Cycle</th>
            <th>
                <select id="cycle1">
                    <option>Cycle 1</option>
                    <option>Cycle 2</option>
                    <option>Cycle 3</option>
                </select>
                <label id="c1l"></label>
            </th>
            <th>
                <select id="cycle2">
                    <option>Cycle 1</option>
                    <option>Cycle 2</option>
                    <option>Cycle 3</option>
                </select>                
                <label id="c2l"></label>
            </th>
            <th>
                <select id="cycle3">
                    <option>Cycle 1</option>
                    <option>Cycle 2</option>
                    <option>Cycle 3</option>
                </select>
                <label id="c3l"></label>
            </th>
            <th id="trendheader"></th>
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

<script type="text/javascript">
    function PrintTable () {
        document.getElementById('y1l').innerHTML = $('#year1').children('option:selected').text();
        document.getElementById('y2l').innerHTML = $('#year2').children('option:selected').text();
        document.getElementById('y3l').innerHTML = $('#year3').children('option:selected').text();

        document.getElementById('c1l').innerHTML = $('#cycle1').children('option:selected').text();        
        document.getElementById('c2l').innerHTML = $('#cycle2').children('option:selected').text();        
        document.getElementById('c3l').innerHTML = $('#cycle3').children('option:selected').text();        

        // var year1d = document.getElementById('year1');
        // var year2d = document.getElementById('year2');
        // var year3d = document.getElementById('year3');
        // while (year1d.length > 0) year1d.remove(0);
        // while (year2d.length > 0) year2d.remove(0);
        // while (year3d.length > 0) year3d.remove(0);

        // var cycle1d = document.getElementById('cycle1');
        // var cycle1d = document.getElementById('cycle2');
        // var cycle1d = document.getElementById('cycle3');
        //  while (cycle1d.length > 0) cycle1d.remove(0);
        //  while (cycle2d.length > 0) cycle2d.remove(0);
        //  while (cycle3d.length > 0) cycle3d.remove(0);        


        $('#year1').multiselect('destroy');
        $('#year2').multiselect('destroy');
        $('#year3').multiselect('destroy');
        $('#cycle1').multiselect('destroy');
        $('#cycle2').multiselect('destroy');
        $('#cycle3').multiselect('destroy');

document.getElementById('pp').click();
        
        $('#year1').multiselect({includeSelectAllOption: false});
        $('#year2').multiselect({includeSelectAllOption: false});
        $('#year3').multiselect({includeSelectAllOption: false});

        var httpyear = new XMLHttpRequest();
        httpyear.onreadystatechange = function () {
            if (this.readyState === 4) {
                // document.getElementById('debug').innerHTML = this.responseText;
                var str = this.responseText;
                yearArray = str.split("\t");
            }
        };
        httpyear.open("GET", "sqldb/years.php", false);
        httpyear.send();  

        delete yearArray[yearArray.length - 1];

        // SY.add(new Option('Year'));

        for (var i in yearArray) {
            year1.add(new Option(yearArray[i]));
            year2.add(new Option(yearArray[i]));
            year3.add(new Option(yearArray[i]));
        };        

        document.getElementById("year1").value = document.getElementById('y1l').textContent;
        document.getElementById("year2").value = document.getElementById('y2l').textContent;
        document.getElementById("year3").value = document.getElementById('y3l').textContent;

        document.getElementById('y1l').innerHTML = "";
        document.getElementById('y2l').innerHTML = "";
        document.getElementById('y3l').innerHTML = "";


        $('#cycle1').multiselect({includeSelectAllOption: false});
        $('#cycle2').multiselect({includeSelectAllOption: false});
        $('#cycle3').multiselect({includeSelectAllOption: false});

        var cycle1 = document.getElementById('cycle1');
        var cycle2 = document.getElementById('cycle2');
        var cycle3 = document.getElementById('cycle3');

        for (i=1;i<4;i++) {
            cycle1.add(new Option('Cycle ' + i));
            cycle2.add(new Option('Cycle ' + i));
            cycle3.add(new Option('Cycle ' + i));
        }        

        document.getElementById("cycle1").value = document.getElementById('c1l').textContent;
        document.getElementById("cycle2").value = document.getElementById('c2l').textContent;
        document.getElementById("cycle3").value = document.getElementById('c3l').textContent;

        document.getElementById('c1l').innerHTML = "";
        document.getElementById('c2l').innerHTML = "";
        document.getElementById('c3l').innerHTML = "";


        // search();
    }
</script>
</body>
</html>

<?php } 
