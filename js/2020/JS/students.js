let grade = $("#grade option:selected").text();
var httpSearch = new XMLHttpRequest();
httpSearch.onreadystatechange = function () {
    if (this.readyState === 4) {
        var str = this.responseText;
        namesArray = str.split("\t");
    }
};
httpSearch.open("POST", "js/2020/SQL/students.php?grade=" + grade, false);
httpSearch.send();

var studentsDropDown = document.getElementById('student');
while (studentsDropDown.length > 0)
    studentsDropDown.remove(0);

delete namesArray[namesArray.length - 1];

for (var i in namesArray)
    studentsDropDown.add(new Option(namesArray[i]));


function fill_students() {
    let grade = $("#grade option:selected").text();
    var httpSearch = new XMLHttpRequest();
    httpSearch.onreadystatechange = function () {
        if (this.readyState === 4) {
            var str = this.responseText;
            namesArray = str.split("\t");
        }
    };
    httpSearch.open("POST", "js/2020/SQL/students.php?grade=" + grade, false);
    httpSearch.send();

    var studentsDropDown = document.getElementById('student');
    while (studentsDropDown.length > 0)
        studentsDropDown.remove(0);

    delete namesArray[namesArray.length - 1];

    for (var i in namesArray)
        studentsDropDown.add(new Option(namesArray[i]));
}