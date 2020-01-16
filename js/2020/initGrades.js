let gradesArray = [];
let select = document.getElementById('grade');
let httpgrades = new XMLHttpRequest();
httpgrades.onreadystatechange = function () {
    if (this.readyState === 4) {
        let str = this.responseText;
        gradesArray = str.split("\t");
    }
};

httpgrades.open("GET", "js/2020/grades.php", false);
httpgrades.send();

$('#grade').multiselect('destroy');

delete gradesArray[gradesArray.length - 1];

select.add(new Option("Grade"));
for (let i in gradesArray)
    select.add(new Option(gradesArray[i]));