// 2016 - 2017
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

    for (var i in termsArray)
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

    for (var i in termsArray)
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

    for (var i in termsArray)
        term.add(new Option(termsArray[i]));


// 2019 - 2020
let termsArray = [];
let select = document.getElementById('term4');
let httpterms = new XMLHttpRequest();
httpterms.onreadystatechange = function () {
    if (this.readyState === 4) {
        let str = this.responseText;
        termsArray = str.split("\t");
    }
};

httpterms.open("GET", "js/2020/SQL/grades.php", false);
httpterms.send();

$('#term4').multiselect('destroy');

delete termsArray[gradesArray.length - 1];

select.add(new Option("Grade", "Grade"));
for (let i in termsArray)
    select.add(new Option(termsArray[i], termsArray[i]));