let gradesArray = [];
let GradesQuery = new XMLHttpRequest();
GradesQuery.onreadystatechange = function () {
    if (this.readyState === 4) {
        let str = this.responseText;
        gradesArray = str.split("\t");
    }
};
GradesQuery.open("POST", "js/2020/SQL/grades.php", false);
GradesQuery.send();


let select = document.getElementById('grade');
while (select.length > 0) select.remove(0);
delete gradesArray[gradesArray.length - 1];
for (let i in gradesArray) select.add(new Option(gradesArray[i], gradesArray[i]));


let grade1 = document.getElementById('grade1');
while (grade1.length > 0) grade1.remove(0);
for (let i in gradesArray) grade1.add(new Option(gradesArray[i], gradesArray[i]));

let grade2 = document.getElementById('grade2');
while (grade2.length > 0) grade2.remove(0);
for (let i in gradesArray) grade2.add(new Option(gradesArray[i], gradesArray[i]));

let grade3 = document.getElementById('grade3');
while (grade3.length > 0) grade3.remove(0);
for (let i in gradesArray) grade3.add(new Option(gradesArray[i], gradesArray[i]));

let grade4 = document.getElementById('grade4');
while (grade4.length > 0) grade4.remove(0);
for (let i in gradesArray) grade4.add(new Option(gradesArray[i], gradesArray[i]));

let grade5 = document.getElementById('grade5');
while (grade5.length > 0) grade5.remove(0);
for (let i in gradesArray) grade5.add(new Option(gradesArray[i], gradesArray[i]));

