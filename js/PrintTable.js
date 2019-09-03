function PrintTable(table) {
    var doc = new jsPDF('p', 'pt');
    var res = doc.autoTableHtmlToJson(document.getElementById(table));
    doc.autoTable(res.columns, res.data, {margin: {top: 80}});
    var header = function (data) {
        doc.setFontSize(18);
        doc.setTextColor(40);
        doc.setFontStyle('normal');
        doc.text("Testing Report", data.settings.margin.left, 50);
    };
    var options = {
        beforePageContent: header,
        margin: {top: 80}, startY: doc.autoTableEndPosY() + 20
    };
    doc.autoTable(res.columns, res.data, options);
    doc.save("Students.pdf");
}