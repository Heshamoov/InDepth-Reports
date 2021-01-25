// 2016 - 2017
let term1 = document.getElementById('term1');
let httpTerms = new XMLHttpRequest();
httpTerms.onreadystatechange = function () {
    if (this.readyState === 4) {
        let str = this.responseText;
        terms1Array = str.split("\t");
    }
};

httpTerms.open("GET", "js/2020/SQL/terms1617.php?", false);
httpTerms.send();

delete terms1Array[terms1Array.length - 1];

for (var i in terms1Array)
    term1.add(new Option(terms1Array[i]));


// 2017 - 2018
let term2 = document.getElementById('term2');
httpTerms = new XMLHttpRequest();
httpTerms.onreadystatechange = function () {
    if (this.readyState === 4) {
        let str = this.responseText;
        terms2Array = str.split("\t");
    }
};
httpTerms.open("GET", "js/2020/SQL/terms1718.php?", false);
httpTerms.send();

delete terms2Array[terms2Array.length - 1];

for (var i in terms2Array)
    term2.add(new Option(terms2Array[i]));


// 2018 - 2019
term3 = document.getElementById('term3');
httpTerms = new XMLHttpRequest();
httpTerms.onreadystatechange = function () {
    if (this.readyState === 4) {
        let str = this.responseText;
        terms3Array = str.split("\t");
    }
};

httpTerms.open("GET", "js/2020/SQL/terms1819.php?", false);
httpTerms.send();

delete terms3Array[terms3Array.length - 1];

for (var i in terms3Array)
    term3.add(new Option(terms3Array[i]));

// 2019 - 2020
let term4 = document.getElementById('term4');
let httpterms = new XMLHttpRequest();
httpterms.onreadystatechange = function () {
    if (this.readyState === 4) {
        let str = this.responseText;
        terms4Array = str.split("\t");
    }
};

httpterms.open("GET", "js/2020/SQL/terms1920.php", false);
httpterms.send();

delete terms4Array[terms4Array.length - 1];

for (let i in terms4Array)
    term4.add(new Option(terms4Array[i]));




// 2020 - 2021
let term5 = document.getElementById('term5');
let httpTerms2021 = new XMLHttpRequest();
httpTerms2021.onreadystatechange = function () {
    if (this.readyState === 4) {
        let str = this.responseText;
        terms5Array = str.split("\t");
    }
};

httpTerms2021.open("GET", "js/2020/SQL/terms2021.php", false);
httpTerms2021.send();

delete terms5Array[terms5Array.length - 1];

for (let i in terms5Array)
    term5.add(new Option(terms5Array[i]));