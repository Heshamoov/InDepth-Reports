let tArray = [];
let selectt = document.getElementById('term');
let httpt = new XMLHttpRequest();
httpt.onreadystatechange = function () {
    if (this.readyState === 4) {
        let str = this.responseText;
        tArray = str.split("\t");
    }
};

httpt.open("GET", "js/2020/SQL/terms1920.php", false);
httpt.send();

$('#term').multiselect('destroy');

delete tArray[tArray.length - 1];

// for (let i in tArray)
//     selectt.add(new Option(tArray[i], tArray[i]));

    selectt.add(new Option("Term 1", "Term 1"));
    selectt.add(new Option("Term 2", "Term 2"));
    selectt.add(new Option("Term 3", "Term 3"));