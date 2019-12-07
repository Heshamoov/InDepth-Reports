// $('#term1').multiselect({includeSelectAllOption: false});
// $('#term2').multiselect({includeSelectAllOption: false});
// $('#term3').multiselect({includeSelectAllOption: false});

var term1 = document.getElementById('term1');
var term2 = document.getElementById('term2');
var term3 = document.getElementById('term3');

var httpTerms = new XMLHttpRequest();
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


for (var i in termsArray) {
    term1.add(new Option(termsArray[i]));
    term2.add(new Option(termsArray[i]));
    term3.add(new Option(termsArray[i]));
}