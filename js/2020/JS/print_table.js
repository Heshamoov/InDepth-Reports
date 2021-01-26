function PrintTable () {
    document.getElementById('T1L').innerHTML = $('#term1').children('option:selected').text();
    document.getElementById('T2L').innerHTML = $('#term2').children('option:selected').text();
    document.getElementById('T3L').innerHTML = $('#term3').children('option:selected').text();
    document.getElementById('T4L').innerHTML = $('#term4').children('option:selected').text();
    document.getElementById('T5L').innerHTML = $('#term5').children('option:selected').text();

    document.getElementById('G1L').innerHTML = $('#grade1').children('option:selected').text();
    document.getElementById('G2L').innerHTML = $('#grade2').children('option:selected').text();
    document.getElementById('G3L').innerHTML = $('#grade3').children('option:selected').text();
    document.getElementById('G4L').innerHTML = $('#grade4').children('option:selected').text();
    document.getElementById('G5L').innerHTML = $('#grade5').children('option:selected').text();


    document.getElementById('pp').click();

    document.getElementById('T1L').innerHTML = '';
    document.getElementById('T2L').innerHTML = '';
    document.getElementById('T3L').innerHTML = '';
    document.getElementById('T4L').innerHTML = '';
    document.getElementById('T5L').innerHTML = '';

    document.getElementById('G1L').innerHTML = '';
    document.getElementById('G2L').innerHTML = '';
    document.getElementById('G3L').innerHTML = '';
    document.getElementById('G4L').innerHTML = '';
    document.getElementById('G5L').innerHTML = '';



    /*
        $('#term1').multiselect({includeSelectAllOption: false});
        $('#term2').multiselect({includeSelectAllOption: false});
        $('#term3').multiselect({includeSelectAllOption: false});
        $('#term4').multiselect({includeSelectAllOption: false});

    // 2016 - 2017
        let termsArray = 0;
        let term = document.getElementById('term1');
        let httpTerms = new XMLHttpRequest();
        httpTerms.onreadystatechange = function () {
            if (this.readyState === 4) {
                let str = this.responseText;
                termsArray = str.split("\t");
            }
        };

        httpTerms.open("GET", "js/2020/SQL/terms1617.php?", false);
        httpTerms.send();


        $('#term').multiselect('destroy');

        delete termsArray[termsArray.length - 1];

        for (let i in termsArray)
            term.add(new Option(termsArray[i]));

    // 2017 - 2018

        term = document.getElementById('term2');

        httpTerms = new XMLHttpRequest();
        httpTerms.onreadystatechange = function () {
            if (this.readyState === 4) {
                let str = this.responseText;
                termsArray = str.split("\t");
            }
        };

        httpTerms.open("GET", "js/2020/SQL/terms1718.php?", false);
        httpTerms.send();


        $('#term').multiselect('destroy');

        delete termsArray[termsArray.length - 1];

        for (let i in termsArray)
            term.add(new Option(termsArray[i]));


    // 2018 - 2019

        term = document.getElementById('term3');

        httpTerms = new XMLHttpRequest();
        httpTerms.onreadystatechange = function () {
            if (this.readyState === 4) {
                let str = this.responseText;
                termsArray = str.split("\t");
            }
        };

        httpTerms.open("GET", "js/2020/SQL/terms1819.php?", false);
        httpTerms.send();


        $('#term').multiselect('destroy');

        delete termsArray[termsArray.length - 1];

        for (let i in termsArray)
            term.add(new Option(termsArray[i]));


    // 2019 - 2020

        term = document.getElementById('term4');

        httpTerms = new XMLHttpRequest();
        httpTerms.onreadystatechange = function () {
            if (this.readyState === 4) {
                let str = this.responseText;
                // document.getElementById("debug").innerHTML = this.responseText;
                termsArray = str.split("\t");
            }
        };

        httpTerms.open("GET", "js/2020/SQL/terms1920.php?", false);
        httpTerms.send();


        $('#term').multiselect('destroy');

        delete termsArray[termsArray.length - 1];

        for (let i in termsArray)
            term.add(new Option(termsArray[i]));


        document.getElementById('T1L').innerHTML = "";
        document.getElementById('T2L').innerHTML = "";
        document.getElementById('T3L').innerHTML = "";
        document.getElementById('T4L').innerHTML = "";*/
}