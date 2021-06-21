<?php
session_start();
if (!isset($_SESSION['login'])) {
    $_SESSION['notloggedin'] = 1;
    header('Location: index.php');
} else {
    include('Header.php');
    ?>


    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>

    <!-- (Optional) Latest compiled and minified JavaScript translation files -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/i18n/defaults-*.min.js"></script>

    <link rel="stylesheet" type="text/css" href="styles/advanced.css">

    <title>Attainment Analysis 2020</title>
    <script type="text/javascript" src="js/2020/JS/search.js"></script>

    <body>
    <div class="w3-responsive">
        <?php include('navbar.php'); ?>
        <script>document.getElementById("navAdvanced").style.backgroundColor = '#009688';</script>
    </div>

    <div id="debug"></div>

    <?php include('inc/benchmarks.php') ?>
    <th style="display: none"><select id="grade" onchange="fill_students();search()"></select></th>
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
    <!--<th>
        <select id="student" onchange="search()"></select>
    </th>-->
    <th>
        <select id="view" onchange="search()">
            <option>Attainment</option>
            <option>Percentage</option>
            <option>Attainment - Percentage</option>
            <option>Average</option>
        </select>
    </th>
    <th>
        <button class='w3-button w3-ripple w3-hover-green w3-round-xxlarge fa fa-print w3-xlarge'
                onclick="PrintTable()"></button>
        <button id='pp' hidden class='w3-button w3-ripple w3-hover-green w3-round-xxlarge fa fa-print w3-xlarge'
                onclick="printJS({
                documentTitle: 'Progress Analysis Attainment Report - Al Sanawbar School',
                printable: 'divPrint',type: 'html', showModal:true,
                ignoreElements: ['term1','term2','term3','term4','term5','grade1','grade2','grade3','grade4','grade5'],
                css: 'styles/advancedPDF.css'})">
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
                <th>2016 - 2017</th>
                <th>2017 - 2018</th>
                <th>2018 - 2019</th>
                <th>2019 - 2020</th>
                <th>2020 - 2021</th>
            </tr>
            <tr>
                <th><label>Grade</label></th>
                <th>
                    <select id="grade1" onchange="search()"></select>
                    <label id="G1L"></label>
                </th>
                <th>
                    <select id="grade2" onchange="search()"></select>
                    <label id="G2L"></label>
                </th>
                <th>
                    <select id="grade3" onchange="search()"></select>
                    <label id="G3L"></label>
                </th>
                <th>
                    <select id="grade4" onchange="search()"></select>
                    <label id="G4L"></label>
                </th>
                <th>
                    <select id="grade5" onchange="search()"></select>
                    <label id="G5L"></label>
                </th>
            </tr>
            <tr>
                <th><label>Term</label></th>
                <th>
                    <select id="term1" onchange="search()"></select>
                    <label id="T1L"></label>
                </th>
                <th>
                    <select id="term2" onchange="search()"></select>
                    <label id="T2L"></label>
                </th>
                <th>
                    <select id="term3" onchange="search()"></select>
                    <label id="T3L"></label>
                </th>
                <th>
                    <select id="term4" onchange="search()"></select>
                    <label id="T4L"></label>
                </th>
                <th>
                    <select id="term5" onchange="search()"></select>
                    <label id="T5L"></label>
                </th>
            </tr>

            <tbody id="results"></tbody>
        </table>

        <table id="InDepthDiv" style="width: 100%; margin: auto; color: gray; font-size: 10px; opacity: 0.5">
            <tr>
                <td id="InDepthTD" style="text-align: right;">Powered By <a href="https://www.indepth.ae">InDepth</a>
                </td>
            </tr>
        </table>
    </div>

    <script src="js/2020/JS/grades.js"></script>
    <!--    <script src="js/2020/JS/students.js"></script>-->
    <script src="js/2020/JS/terms.js"></script>
    <script src="js/2020/JS/print_table.js"></script>
    </body>
    </html>

<?php } 
