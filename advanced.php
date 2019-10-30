<?php
session_start();
if (!isset($_SESSION['login'])) {
    $_SESSION['notloggedin'] = 1;
    header('Location: index.php');
} else {
    include('Header.php');
    ?>

    <title>Attainment Analysis</title>
    </head>

    <script type="text/javascript">      
        $(function () {
            $('#academic_year1').multiselect({includeSelectAllOption: true});
            $('#academic_year2').multiselect({includeSelectAllOption: true});
            $('#academic_year3').multiselect({includeSelectAllOption: true});
            $('#academic_year4').multiselect({includeSelectAllOption: true});
            $('#academic_year5').multiselect({includeSelectAllOption: true});

            $('#term1').multiselect({includeSelectAllOption: true});
            $('#term2').multiselect({includeSelectAllOption: true});
            $('#term3').multiselect({includeSelectAllOption: true});
            $('#term4').multiselect({includeSelectAllOption: true});
            $('#term5').multiselect({includeSelectAllOption: true});
        });
        window.onload = function () {
            search();
        };
        function search() {
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
    </script>

    <body>
    
        <div class=" w3-responsive header" >
            <!-- Navigation bar -->        
            <?php include('navbar.php'); ?>

            <!--set color for current tab-->
            <script>
                document.getElementById("navAdvanced").style.backgroundColor = '#009688';
            </script>

            <!--End of Navictacoin bar-->

            <!--Drop menus-->
                <div class="w3-container">
                    <h4 class="w3-center">Al Sanawabar School: Attainment Analysis</h4>
                    <h4>Grade</h4>
                    <select id="grade" multiple="multiple"></select>

                    <table id="useroptions" class="w3-table-all w3-centered">
                    <tr>
                        <th><h4>Year</h4></th>
                        <th><select id="academic_year1" multiple="multiple"></select></th>
                        <th><select id="academic_year2" multiple="multiple"></select></th>
                        <th><select id="academic_year3" multiple="multiple"></select></th>
                        <th><select id="academic_year4" multiple="multiple"></select></th>
                        <th><select id="academic_year5" multiple="multiple"></select></th>
                    </tr>
                    <tr>
                        <th><h4>Term</h4></th>
                        <th><select id="term1" multiple="multiple"></select></th>
                        <th><select id="term2" multiple="multiple"></select></th>
                        <th><select id="term3" multiple="multiple"></select></th>
                        <th><select id="term4" multiple="multiple"></select></th>
                        <th><select id="term5" multiple="multiple"></select></th>
                    </tr>
                    <tr>
                        <td><h4>Arabic Language</h4></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><h4>English</h4></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
            </div>

<script type="text/javascript">
    var select = document.getElementById('grade');

    var httpgrades = new XMLHttpRequest();
    httpgrades.onreadystatechange = function () {
        if (this.readyState === 4) {
            var str = this.responseText;
            gradesArray = str.split("\t");
        }
    };

    httpgrades.open("GET", "sqldb/distinctGrades.php", false);
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

        <!--Initialize Academic Years->-->     
        <script type="text/javascript">
            var yearArray = ["Your Data Base is Empty!."];

            var httpyear = new XMLHttpRequest();
            httpyear.onreadystatechange = function () {
                if (this.readyState === 4) {
                    var str = this.responseText;
                    yearArray = str.split("\t");
                }
            };
            httpyear.open("GET", "sqldb/initAcademicYears.php", false);
            httpyear.send();

            var AY1 = document.getElementById('academic_year1');
            var AY2 = document.getElementById('academic_year2');
            var AY3 = document.getElementById('academic_year3');
            var AY4 = document.getElementById('academic_year4');
            var AY5 = document.getElementById('academic_year5');

            delete yearArray[yearArray.length - 1];

            for (var i in yearArray) {
                AY1.add(new Option(yearArray[i]));
                AY2.add(new Option(yearArray[i]));
                AY3.add(new Option(yearArray[i]));
                AY4.add(new Option(yearArray[i]));
                AY5.add(new Option(yearArray[i]));
            };

            $(function () {
                $('#academic_year1').multiselect({
                    includeSelectAllOption: true
                });

                $('#academic_year2').multiselect({
                    includeSelectAllOption: true
                });

                $('#academic_year3').multiselect({
                    includeSelectAllOption: true
                });

                $('#academic_year4').multiselect({
                    includeSelectAllOption: true
                });

                $('#academic_year5').multiselect({
                    includeSelectAllOption: true
                });                                 
            });
        </script>


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

                httpTerms.open("GET", "sqldb/distinctTerms.php", false);
                httpTerms.send();


                delete termsArray[termsArray.length - 1];

                for (var i in termsArray) {
                    term1.add(new Option(termsArray[i]));
                    term2.add(new Option(termsArray[i]));
                    term3.add(new Option(termsArray[i]));
                    term4.add(new Option(termsArray[i]));
                    term5.add(new Option(termsArray[i]));
                }
                ;

                $(function () {
                    $('#term1').multiselect({
                        includeSelectAllOption: true
                    });

                    $('#term2').multiselect({
                        includeSelectAllOption: true
                    });

                    $('#term3').multiselect({
                        includeSelectAllOption: true
                    });

                    $('#term4').multiselect({
                        includeSelectAllOption: true
                    });

                    $('#term5').multiselect({
                        includeSelectAllOption: true
                    });                                                                                
                });
        </script>

        
    </body>
    </html>

<?php } ?>
