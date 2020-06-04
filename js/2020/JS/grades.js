let gradesArray = [];
let select = document.getElementById('grade');
let httpgrades = new XMLHttpRequest();
httpgrades.onreadystatechange = function () {
    if (this.readyState === 4) {
        let str = this.responseText;
        gradesArray = str.split("\t");
    }
};

httpgrades.open("GET", "js/2020/SQL/grades.php", false);
httpgrades.send();

$('#grade').multiselect('destroy');

delete gradesArray[gradesArray.length - 1];

select.add(new Option("Grade", "Grade"));
// for (let i in gradesArray){
    // select.add(new Option(gradesArray[i], gradesArray[i]));
    select.add(new Option('GR 1', 'GR 1'));
    select.add(new Option('GR 2', 'GR 2'));
    select.add(new Option('GR 3', 'GR 3'));
    select.add(new Option('GR 4', 'GR 4'));
    select.add(new Option('GR 5', 'GR 5'));
    select.add(new Option('GR 6', 'GR 6'));
    select.add(new Option('GR 7', 'GR 7'));
    select.add(new Option('GR 8', 'GR 8'));
    select.add(new Option('GR 9', 'GR 9'));
    select.add(new Option('GR10', 'GR10'));
    select.add(new Option('GR11', 'GR11'));
    select.add(new Option('GR12', 'GR12'));

