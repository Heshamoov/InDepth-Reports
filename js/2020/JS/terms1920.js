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

for (let i in tArray)
    selectt.add(new Option(tArray[i], tArray[i]));