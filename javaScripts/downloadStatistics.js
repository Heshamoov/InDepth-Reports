
function downloadStatistics() {

    var doc = new jsPDF('p', 'pt', 'a3');
    var header = function (data) {
        doc.setFontSize(18);
        doc.setFont('PTSans');
        doc.text("Subject Wise Statistics", 225, 50);
        doc.line(226, 53, 390, 53);// Header top margin
    };

    var table = doc.autoTableHtmlToJson(stablePDF);
    doc.autoTable(table.columns, table.data, {beforePageContent: header, theme: 'grid', margin: {top: 70, left: 40, right: 40}, columnStyles: {
            0: {columnWidth: 205},
            1: {columnWidth: 80},
            2: {columnWidth: 80},
            3: {columnWidth: 80}
        }, styles: {
            fontSize: 12,
            font: 'PTSans',
        }

    });

    var tableName = "";
    var i = 1;
    $('#subject').multiselect({includeSelectAllOption: true});
    var selected_subjects = $("#subject option:selected");
    selected_subjects.each(function () {

        tableName = 'TT' + i;
        var table = doc.autoTableHtmlToJson(document.getElementById(tableName));
        doc.autoTable(table.columns, table.data, {startY: doc.autoTable.previous.finalY + 14, margin: {left: 40, right: 40}, theme: 'grid', columnStyles: {
                0: {columnWidth: 205},
                1: {columnWidth: 80},
                2: {columnWidth: 80},
                3: {columnWidth: 80}
            }, styles: {
                fontSize: 12,
                font: 'PTSans'
            }});
        i++;
    });
    doc.save("Statistics.pdf");
}
    