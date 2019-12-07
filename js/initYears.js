var SY  = document.getElementById('studentYear');

var httpyear = new XMLHttpRequest();
httpyear.onreadystatechange = function () {
    if (this.readyState === 4) {
        var str = this.responseText;
        yearArray = str.split("\t");
    }
};
httpyear.open("GET", "sqldb/years.php", false);
httpyear.send();  

$('#studentYear').multiselect('destroy');

delete yearArray[yearArray.length - 1];

SY.add(new Option('Year'));

for (var i in yearArray) {
    SY.add(new Option(yearArray[i]));
};
