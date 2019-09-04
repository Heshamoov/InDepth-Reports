
var categoryArray = ["Your Data Base is Empty!."];

var httpcategory = new XMLHttpRequest();
httpcategory.onreadystatechange = function () {
    if (this.readyState === 4) {
        var str = this.responseText;
        categoryArray = str.split("\t");
    }
};
httpcategory.open("GET", "sqldb/distinctStudentCategory.php", false);
httpcategory.send();

var select = document.getElementById('category');
delete categoryArray[categoryArray.length - 1];
for (var i in categoryArray) {
    select.add(new Option(categoryArray[i]));
}
;
$(function () {
    $('#category').multiselect({
        includeSelectAllOption: true
    });
});

   