function terms (let year) {
let term1 = document.getElementById('term1');

let httpTerms = new XMLHttpRequest();
httpTerms.onreadystatechange = function () {
    if (this.readyState === 4) {
        let str = this.responseText;
        termsArray = str.split("\t");
    }
};

httpTerms.open("GET", "sqldb/2020/terms1617.php", false);
httpTerms.send();



$('#terms1').multiselect('destroy');
$('#terms2').multiselect('destroy');
$('#terms3').multiselect('destroy');
$('#terms4').multiselect('destroy');


delete termsArray[termsArray.length - 1];


for (var i in termsArray)
    term1.add(new Option(termsArray[i]));
}


var term2 = document.getElementById('term2');
var term3 = document.getElementById('term3');
var term4 = document.getElementById('term4');