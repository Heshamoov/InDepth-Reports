let term1 = document.getElementById('term1');
let term2 = document.getElementById('term2');
let term3 = document.getElementById('term3');

let httpTerms = new XMLHttpRequest();
httpTerms.onreadystatechange = function () {
    if (this.readyState === 4) {
        var str = this.responseText;
        termsArray = str.split("\t");
    }
};

httpTerms.open("GET", "sqldb/terms.php", false);
httpTerms.send();



$('#terms1').multiselect('destroy');
$('#terms2').multiselect('destroy');
$('#terms3').multiselect('destroy');


delete termsArray[termsArray.length - 1];


for (let i in termsArray) {
    term1.add(new Option(termsArray[i]));
    term2.add(new Option(termsArray[i]));
    term3.add(new Option(termsArray[i]));
}

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

httpTerms.open("GET", "js/2020/SQL/terms1920.php", false);
httpTerms.send();


$('#term').multiselect('destroy');

delete termsArray[termsArray.length - 1];

for (let i in termsArray)
    term.add(new Option(termsArray[i]));