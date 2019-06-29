<!doctype html>
<html lang="en">
    <head>
        <title>Import Information</title>
        <link rel="icon" type="image/png" href="CSS/imges/PageLogo.PNG" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel = "stylesheet" type = "text/css" href = "css/style.css">
        <link rel="stylesheet" href="css/resultstyle.css">
        <link rel="stylesheet" href="css/www.w3schools.com_w3css_4_w3.css">
        <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-black.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.0.3/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <link href="http://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="http://cdn.rawgit.com/davidstutz/bootstrap-multiselect/master/dist/css/bootstrap-multiselect.css" rel="stylesheet" type="text/css" />
        <script src="http://cdn.rawgit.com/davidstutz/bootstrap-multiselect/master/dist/js/bootstrap-multiselect.js" type="text/javascript"></script>
        <script src="https://s3.amazonaws.com/api_play/src/js/jquery-2.1.1.min.js"></script>
        <script src="https://s3.amazonaws.com/api_play/src/js/vkbeautify.0.99.00.beta.js"></script>
        <script src="https://s3.amazonaws.com/api_play/src/js/common.js"></script>

        <style>
            body,h1,h2,h3,h4,h5,h6 {font-family: "Lato", sans-serif}
            .w3-bar,h1,button,select {font-family: "Montserrat", sans-serif}
            .w3-custom {color:#fff !important; background-color:#009688 !important}
            .fa-anchor,.fa-coffee {font-size:200px}
        </style>
        <script>
            $(function () {
                $("#display").click(function () {
                    document.getElementById('output').value = '';
                    var xmlhttpD = new XMLHttpRequest();
                    xmlhttpD.onreadystatechange = function () {
                        if (this.readyState === 4) {
                            document.getElementById("output").value = this.responseText;
                        }
                    };
                    xmlhttpD.open("POST", "sqldb/display.php", true);
                    xmlhttpD.send();
                });
            });
        </script>

        <script>
            $(function () {
                $("#save2db").click(function () {
                    if (validateTable())
                    {
                        var xmlhttp = new XMLHttpRequest();
                        xmlhttp.onreadystatechange = function () {
                            if (this.readyState === 4) {
                                document.getElementById("output").value += this.responseText;
                            }
                        };
                        xmlhttp.open("POST", "sqldb/createStudentsTable.php", false);
                        xmlhttp.send();
                        var sqlstr = sqlid, end, temp;
                        var start = 0;
                        for (var i = 0; i < 17; i++)
                        {
                            sqlstr = sqlid.substr(start, 5000);
                            end = sqlstr.lastIndexOf(")");   // 'gr2-2018)
                            start += end + 2;
                            temp = sqlstr.substr(0, end + 1) + ";";
                            var xmlhttp = new XMLHttpRequest();
                            xmlhttp.onreadystatechange = function () {
                                if (this.readyState === 4) {
                                    document.getElementById("output").value += this.responseText + "\n*******\n";
                                }
                            };
                            xmlhttp.open("POST", "sqldb/insertStudents.php?q=" + temp, false);
                            xmlhttp.send();
                        }
                        document.getElementById("save2db").style.display = "none";
                    } else
                    {
                        alert("Generate table first, press on Get Students.");
                    }
                });
            });
        </script>

        <script>
            $(function () {
                $("#marks2db").click(function () {
                    if (validateTable())
                    {
                        var xmlhttp = new XMLHttpRequest();
                        xmlhttp.onreadystatechange = function () {
                            if (this.readyState === 4) {
                                document.getElementById("output").value += this.responseText;
                            }
                        };
                        xmlhttp.open("POST", "sqldb/createMarksTable.php", false);
                        xmlhttp.send();

                        var sqlstr = sqlmarks, end, temp;
                        var start = 0;
                        for (var i = 0; i < 5; i++)
                        {
                            sqlstr = sqlmarks.substr(start, 5000);
                            end = sqlstr.lastIndexOf(")");   // 'gr2-2018)
                            start += end + 2;
                            temp = sqlstr.substr(0, end + 1) + ";";
                            var xmlhttp = new XMLHttpRequest();
                            xmlhttp.onreadystatechange = function () {
                                if (this.readyState === 4) {
                                    document.getElementById("output").value += this.responseText + "\n*******\n";
                                }
                            };
                            xmlhttp.open("POST", "sqldb/insertMarks.php?q=" + temp, false);
                            xmlhttp.send();
                        }
                        document.getElementById("marks2db").style.display = "none";
                    } else
                    {
                        alert("Generate table first.");
                    }
                });
            });
        </script>

        <script>
            function validateTable() {
                if (document.getElementById("students").value === 1)
                {
                    return true;
                } else
                {
                    return true;
                }
            }
        </script>

        <script>
            $(function () {
                $("#search-student").click(function () {
                    var iurl = $("#iurl").val();
                    var token = $("#token").val();
                    var parameters = "";
                    $('.key-input').each(function () {
                        if ($(this).val().trim().length > 0)
                        {
                            if (parameters.length === 0)
                            {
                                parameters = "search[" + $(this).val() + "]=" + $(this).parent().parent().children().children(".value-input").val();
                            } else
                            {
                                parameters += "&search[" + $(this).val() + "]=" + $(this).parent().parent().children().children(".value-input").val();
                            }
                        }
                    });
                    output.value += "call search_student()\n";
                    search_student(iurl, token, parameters);
                    document.getElementById("save2db").style.display = "block";
                    document.getElementById("marks2db").style.display = "none";
                });
            });
        </script>
        <script>
            $(function () {
                $("#search-marks").click(function () {
                    var iurl = $("#iurl").val();
                    var token = $("#token").val();
                    var group = $("#group").val();
                    var batch = $("#batch").val();
                    var grade = $("#grade").val();
                    search_exam_score(iurl, token, group, batch, grade);
                    document.getElementById("marks2db").style.display = "block";
                    document.getElementById("save2db").style.display = "none";
                });
            });
        </script>
        <script>
            function search_exam_score(iurl, token, group, batch, grade) {
                try
                {
                    var xhr = new XMLHttpRequest();
                    xhr.onreadystatechange = function ()
                    {
                        output.value += "xml http requesting " + xhr.readyState + "\n";
                        if (xhr.readyState === 4)
                        {
                            output.value += "\ncall Generate_Marks_Table()\n";
                            Generate_Marks_Table(this);
                        }
                    };

                    xhr.open('GET', iurl + "/api/exam_scores?search[exam_exam_group_name_equals]=" + group + "&search[exam_exam_group_batch_name_equals]=" + batch + "&search[exam_exam_group_batch_course_code_equals]=" + grade, false);
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    xhr.setRequestHeader('Authorization', 'Token token="' + token + '"');
                    xhr.send();
                } catch (err)
                {
                    alert(err.message);
                }
            }
            ;
        </script>
        <script>
            function Generate_Marks_Table(xml) {
                output.value += "Drawing marks table..\n";
                var i, grade = "", batch = "";
                sqlmarks = "";
                var xmlDoc = xml.responseXML;
                var x = xmlDoc.getElementsByTagName("exam_score");
                table = "<th>No.</th><th>Admissin Number</th><th>Exam Group</th><th>Grade</th><th>Batch</th><th>Subject</th><th>Mark</th>";
                output.value += "analysing xml respond\n";
                for (i = 0; i < x.length; i++) {
                    table += "<tr>";
                    table += "<td>" + (i + 1) + "</td>";

                    table += "<td>" + x[i].getElementsByTagName("student")[0].childNodes[0].nodeValue + "</td>";
                    sqlmarks += "(" + x[i].getElementsByTagName("student")[0].childNodes[0].nodeValue + ",";

                    table += "<td>" + x[i].getElementsByTagName("exam_group")[0].childNodes[0].nodeValue + "</td>";
                    sqlmarks += "'" + x[i].getElementsByTagName("exam_group")[0].childNodes[0].nodeValue + "',";

                    batch = x[i].getElementsByTagName("batch")[0].childNodes[0].nodeValue;
                    if (batch.length === 11) {
                        grade = batch.slice(0, 3);
                        batch = batch.slice(6);
                    } else {
                        grade = batch.slice(0, 4);
                        batch = batch.slice(7);
                    }

                    table += "<td>" + grade + "</td><td>" + batch + "</td>";
                    sqlmarks += "'" + grade + "','" + batch + "',";

                    table += "<td>" + x[i].getElementsByTagName("subject")[0].childNodes[0].nodeValue + "</td>";
                    sqlmarks += "'" + x[i].getElementsByTagName("subject")[0].childNodes[0].nodeValue + "',";

                    table += "<td>" + x[i].getElementsByTagName("marks")[0].childNodes[0].nodeValue + "</td>";
                    sqlmarks += x[i].getElementsByTagName("marks")[0].childNodes[0].nodeValue + ")";
                    if (i === (x.length - 1))
                        sqlmarks += ";";
                    else
                        sqlmarks += ",";
                    table += "</tr>";

                }
                document.getElementById("students").innerHTML = table;
                document.getElementById("students").value = 1;
            }

        </script>

        <script>
            function search_student(iurl, token, parameters) {
                try
                {
                    var xhr = new XMLHttpRequest();
                    xhr.onreadystatechange = function (e)
                    {
                        output.value += "xml http requesting " + xhr.readyState + "\n";
                        if (xhr.readyState === 4)
                        {
                            output.value += "\ncall Generating_Table()\n";
                            Generate_Table(this);
                        }
                    };
                    xhr.open('GET', iurl + "/api/students?" + parameters);
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    xhr.setRequestHeader('Authorization', 'Token token="' + token + '"');
                    xhr.send();
                } catch (err)
                {
                    alert(err.message);
                }
            }
            ;
        </script>
        <script>
            function Generate_Table(xml) {
                output.value += "Drawing table..\n";
                var i, fixname, batch, grade;
                sqlid = "";
                var xmlDoc = xml.responseXML;
                var x = xmlDoc.getElementsByTagName("student");
                table = "<th>No.</th><th>Admissin Number</th><th>Name</th><th>Grade</th><th>Batch</th>";
                output.value += "analysing xml respond\n";
                for (i = 0; i < x.length; i++) {
                    table += "<tr>";

                    table += "<td>" + (i + 1) + "</td>";

                    table += "<td>" + x[i].getElementsByTagName("admission_no")[0].childNodes[0].nodeValue + "</td>";
                    sqlid += "(" + x[i].getElementsByTagName("admission_no")[0].childNodes[0].nodeValue + ",";
                    table += "<td>" + x[i].getElementsByTagName("student_name")[0].childNodes[0].nodeValue + "</td>";
                    fixname = x[i].getElementsByTagName("student_name")[0].childNodes[0].nodeValue;
                    fixname = fixname.replace("'", "");
                    fixname = fixname.replace("'", "");
                    sqlid += "'" + fixname + "',";

                    batch = x[i].getElementsByTagName("batch_name")[0].childNodes[0].nodeValue;
                    if (batch.length === 11) {
                        grade = batch.slice(0, 3);
                        batch = batch.slice(6);
                    } else {
                        grade = batch.slice(0, 4);
                        batch = batch.slice(7);
                    }

                    table += "<td>" + grade + "</td><td>" + batch + "</td>";

                    sqlid += "'" + grade + "','" + batch + "')";

                    if (i === (x.length - 1))
                        sqlid += ";";
                    else
                        sqlid += ",";
                    table += "</tr>";
                }
                document.getElementById("students").innerHTML = table;
                document.getElementById("students").value = 1;
            }

        </script>

    </head>
    <body>
        <?php $token = $_POST['token']; ?>
        <?php $iurl = $_POST['iurl']; ?>
        <!-- Navigation bar -->
        <?php include('navbar.php'); ?>
        <!-- End of Navigation bar -->

        <div class="w3-container w3-quarter">
            <h4>Student Search</h4>
            <input id="iurl" type="text" value="<?php echo $iurl ?>"/>
            <input id="token" type="text" value="<?php echo $token ?>"/> 
            <table id="lefttabel">        
                <tr>
                    <th>Admission Number:</th>
                    <td>
                        <input class="key-input" type="hidden" value="admission_no"/>
                        <input class="value-input" type="text" placeholder="Empty to view all"/>
                    </td>
                </tr>
                <th colspan="2">Exam Group:</th>
                <tr>
                    <td colspan="2">
                        <select name="group" id="group">
                            <option>Term1 Mark 2017-2018</option>
                            <option>Term2 Mark 2017-2018</option>
                            <option>Term3 Mark 2017-2018</option>
                            <option>Final Mark 2017-2018</option>
                        </select>
                    </td>
                </tr>
                <th>Batch:</th><th>Grade</th>
                <tr>
                    <td>
                        <select name="batch" id="batch">
                            <option>A2018</option>
                            <option>B2018</option>
                            <option>C2018</option>
                            <option>D2018</option>
                            <option>E2018</option>
                            <option>F2018</option>
                        </select>
                    </td>
                    <td>
                        <select name="grade" id="grade">
                            <option>KG1</option>
                            <option>KG2</option>
                            <option>GR1</option>
                            <option>GR2</option>
                            <option>GR3</option>
                            <option>GR4</option>
                            <option>GR5</option>
                            <option>GR6</option>
                            <option>GR7</option>
                            <option>GR8</option>
                            <option>GR9</option>
                            <option>GR10</option>
                            <option>GR11</option>
                            <option>GR12</option>
                        </select>
                    </td>
                </tr>
            </table>
            <br>
            <table id="btns">
                <tr>
                    <td>
                        <button class="w3-button w3-block" type="button" id="search-student">Get Students</button>
                    </td>
                    <td>
                        <button class="w3-button w3-block" type="button" id="search-marks"> Get Marks</button>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <br>
                        <button class="w3-button w3-block" type="button" id="save2db" hidden>Import to DB</button>
                        <button class="w3-button w3-block" type="button" id="marks2db" hidden>Marks to DB</button>
                    </td>
                </tr>
                <tr>
                    <td>
                        <button class="w3-button w3-block"  type="button" id="importDetails">Import Details </button>
                    </td>
                </tr>
            </table>
            <br><br> <input class="w3-button" style="width: 45%;" type="button" value="Imported Grades" id="display"/><br>
            <br><textarea style="display: block"id="output" cols="20" rows="10" placeholder="Please import the marks only one time, Do not  duplicate them."></textarea>
            <!--<p>Developer console:</p>-->
        </div>
        <div class=" w3-container w3-threequarter">
            <div style="border: 1px #009688 solid; background-image: url(css/imges/statistics.jpg);">
                <h1 style="text-align: center; font-size: 3vw;">Import Page</h4>
                    <h3 style="text-align: center; font-size: 2vw;">Import any data you want from your school in order to perform data analysis.</h3>
                    <strong><p style="text-align: center; font-size: 1vw;">Choose as many grades and batches, click Get students or Get Marks.</p></strong>
                    <strong><P style="text-align: center; font-size: 1vw;">A table will be generated for you, click Import to Data Base.</P></strong>
                    <strong><p style="text-align: center; font-size: 1vw;">After importing all the needed information that you want reports about, choose the report type from navigation menu.</p></strong>
            </div>

            <div>
                <button onclick="topFunction()" id="myBtn" title="Go to top">Top</button>
                <script>
                    // When the user scrolls down 20px from the top of the document, show the button
                    window.onscroll = function () {
                        scrollFunction();
                    };
                    function scrollFunction() {
                        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                            document.getElementById("myBtn").style.display = "block";
                        } else
                            document.getElementById("myBtn").style.display = "none";
                    }
                    ;
                    // When the user clicks on the button, scroll to the top of the document
                    function topFunction() {
                        document.body.scrollTop = 0;
                        document.documentElement.scrollTop = 0;
                    }
                    ;
                </script>
                <br><table id="students"></table><br>
            </div>
        </div>
    </body>

    <script>
        var out = document.getElementById('output');
        $(function () {
            $("#importDetails").click(function () {
                var iurl = $("#iurl").val();
                var token = $("#token").val();
                var admissionStr;
                var grade = document.getElementById('grade').value;
                var batch = document.getElementById('batch').value;
                out.value = "";

                var table = "<th>No.</th><th>MOE</th><th>Name</th><th>Grade</th><th>Batch</th>\n\
                                    <th>Admission Date</th><th>Gender</th><th>Nationality</th>\n\
                                    <th>Phone</th><th>Mobile</th>";

                var studentsCountHttp = new XMLHttpRequest();
                studentsCountHttp.onreadystatechange = function () {
                    if (this.readyState === 4)
                        admissionStr = this.responseText;
                };

                studentsCountHttp.open("GET", "sqldb/distinctStudents.php?grade=" + grade + "&batch=" + batch, false);
                studentsCountHttp.send();

                var admissionArray = admissionStr.split(" ");

                for (var i = 0; i < admissionArray.length; i++) {
                    out.value += admissionArray[i] + "\n";
                    try {
                        var xhr = new XMLHttpRequest();
                        xhr.onreadystatechange = function () {
                            output.value += "Requsting " + admissionArray[i] + "\n";
                            if (xhr.readyState === 4) {
                                table += generateDetailsTable(this, admissionArray[i], i);
                                //                                        out.value += this.responseText + "\n";
                            }
                        };
                        xhr.open('GET', iurl + "/api/students/" + admissionArray[i], false);
                        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                        xhr.setRequestHeader('Authorization', 'Token token="' + token + '"');
                        xhr.send();
                    } catch (err)
                    {
                        alert(err.message);
                    }
                }
                document.getElementById('students').innerHTML = table;
            });
        });

        function generateDetailsTable(xml, admission, rowNumber) {
            var fixname, row = "", grade, batch, sqlDetails;
            output.value += "Drawing table..\n";
            var xmlDoc = xml.responseXML;
            var x = xmlDoc.getElementsByTagName("student");

            row += "<tr><td>" + (rowNumber + 1) + "</td>";
            output.value += (rowNumber + 1) + "\n";

            row += "<td>" + admission + "</td>";
            output.value += admission + "\n";

            fixname = x[0].getElementsByTagName("student_name")[0].childNodes[0].nodeValue;
            fixname = fixname.replace("'", "");
            fixname = fixname.replace("'", "");
            output.value += fixname + "\n";
            row += "<td>" + fixname + "</td>";

            batch = x[0].getElementsByTagName("batch_name")[0].childNodes[0].nodeValue;
            if (batch.length === 11) {
                grade = batch.slice(0, 3);
                batch = batch.slice(6);
            } else {
                grade = batch.slice(0, 4);
                batch = batch.slice(7);
            }
            row += "<td>" + grade + "</td><td>" + batch + "</td>";
            output.value += grade + " " + batch + "\n";

            row += "<td>" + x[0].getElementsByTagName("admission_date")[0].childNodes[0].nodeValue + "</td>";
            output.value += x[0].getElementsByTagName("admission_date")[0].childNodes[0].nodeValue + " \n";
            sqlDetails = "`Admission Date` = '" + x[0].getElementsByTagName("admission_date")[0].childNodes[0].nodeValue + "', ";

            row += "<td>" + x[0].getElementsByTagName("gender")[0].childNodes[0].nodeValue + "</td>";
            output.value += x[0].getElementsByTagName("gender")[0].childNodes[0].nodeValue + " \n";
            if (x[0].getElementsByTagName("gender")[0].childNodes[0].nodeValue === 'f')
            {
                sqlDetails += "`Gender` = 'Female', ";
                output.value += "Female\n";
            } else
            {
                sqlDetails += "`Gender` = 'Male', ";
                output.value += "Male\n";
            }

            row += "<td>" + x[0].getElementsByTagName("nationality")[0].childNodes[0].nodeValue + "</td>";
            output.value += x[0].getElementsByTagName("nationality")[0].childNodes[0].nodeValue + " \n";
            sqlDetails += "Nationality = N'" + x[0].getElementsByTagName("nationality")[0].childNodes[0].nodeValue + "', ";


            row += "<td>" + x[0].getElementsByTagName("phone")[0].childNodes[0].nodeValue + "</td>";
            output.value += x[0].getElementsByTagName("phone")[0].childNodes[0].nodeValue + " \n";
            sqlDetails += "Phone = '" + x[0].getElementsByTagName("phone")[0].childNodes[0].nodeValue + "', ";

            row += "<td>" + x[0].getElementsByTagName("mobile")[0].childNodes[0].nodeValue + "</td>";
            output.value += x[0].getElementsByTagName("mobile")[0].childNodes[0].nodeValue + " \n";
            sqlDetails += "Mobile = '" + x[0].getElementsByTagName("mobile")[0].childNodes[0].nodeValue + "' ";
            sqlDetails += "WHERE moe = " + admission + ";";
            row += "</tr>";

            var studentsDetailsHttp = new XMLHttpRequest();
            studentsDetailsHttp.onreadystatechange = function () {
                if (this.readyState === 4) {
                    output.value += this.responseText;
                }
            };

            studentsDetailsHttp.open("GET", "sqldb/updateStudents.php?sql=" + sqlDetails, false);
            studentsDetailsHttp.send();

            return row;
        }
    </script>


</html>