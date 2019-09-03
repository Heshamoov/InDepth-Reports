
    var imgData = new Array();

    google.charts.load("current", {
        packages: ['corechart']
    });
    google.charts.setOnLoadCallback(drawChart);
    google.charts.setOnLoadCallback(drawChartSubjects);

    function drawChart() {
        var value1, value2, value3, value4, value5, value6, result1, result2, result3, tableName, header;
        var tableName = document.getElementById("stable");
        value1 = tableName.rows[1].cells[2].childNodes[0].value;
        value2 = tableName.rows[1].cells[2].childNodes[2].value;
        value3 = tableName.rows[1].cells[3].childNodes[0].value;
        value4 = tableName.rows[1].cells[3].childNodes[2].value;
        value5 = tableName.rows[1].cells[4].childNodes[0].value;
        value6 = tableName.rows[1].cells[4].childNodes[2].value;
        result1 = tableName.rows[2].cells[2].innerHTML;
        result2 = tableName.rows[2].cells[3].innerHTML;
        result3 = tableName.rows[2].cells[4].innerHTML;
        header = tableName.rows[0].cells[0].innerHTML;



        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Number of Students');
        data.addColumn('number', 'Students');
        data.addColumn({type: 'string', role: 'style'});

        data.addRows([
            [value1.toString() + '% - ' + value2.toString() + "% ", Number(result1), ' yellow'],
            [value3.toString() + '% - ' + value4.toString() + "% ", Number(result2), 'orange'],
            [value5.toString() + '% - ' + value6.toString() + "%", Number(result3), ' lime'],
        ]);
        var options = {title: header, legend: {position: "none"}};


        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
        chart.draw(data, options);
        imgData[0] = chart.getImageURI();


    }
    ;





    function drawChartSubjects() {

        for (t = 1; t < 13; t++)
        {
            table = "T" + t;
            var value1, value2, value3, result1, result2, result3, tableName, header;
            var tableName = document.getElementById(table);

            value1 = tableName.rows[1].cells[2].innerHTML;
            value2 = tableName.rows[1].cells[3].innerHTML;
            value3 = tableName.rows[1].cells[4].innerHTML;
            result1 = tableName.rows[2].cells[2].innerHTML;
            result2 = tableName.rows[2].cells[3].innerHTML;
            result3 = tableName.rows[2].cells[4].innerHTML;
            header = tableName.rows[0].cells[0].innerHTML;

            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Number of Students');
            data.addColumn('number', 'Students');
            data.addColumn({type: 'string', role: 'style'});

            data.addRows([
                [value1.toString(), Number(result1), ' yellow'],
                [value2.toString(), Number(result2), 'orange'],
                [value3.toString(), Number(result3), ' lime'],
            ]);
            var options = {title: header, legend: {position: "none"}};

            chartName = "chart" + t;
            var chartS = new google.visualization.ColumnChart(document.getElementById(chartName));
            chartS.draw(data, options);
            imgData[t] = chartS.getImageURI();

        }
    }
    ;



