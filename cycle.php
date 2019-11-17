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
        $('#Year').multiselect({includeSelectAllOption: false});            
        $('#view').multiselect({includeSelectAllOption: false});
    });
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
                                // targetStyles: ['*']
                                css: 'styles/advanced.css'
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
            <tr><br></tr>
            <tr>
                <th id="Performance">
                    Performance Indicator levels: Summary
                </th>
                <th id="Attainment" style="text-align: right;">
                    Attainment Progress Analysis
                </th>
            </tr>
        </table> 

        <table id="useroptions" class="w3-card w3-table-all w3-centered"></table>

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
