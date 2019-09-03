function downloadPopoverStatistics() {
    var doc = new jsPDF('p', 'pt');
    var header = function (data) {
        doc.setFontSize(18);
        doc.setFontStyle('PTSans');
        doc.text("Statistics", 225, 50);
        doc.line(226, 53, 290, 53);// Header top margin
    };
    doc.addImage(imgData[0], 'jpg', 80, 180, 300, 150);
    var table = doc.autoTableHtmlToJson(stablePDF);
    doc.autoTable(table.columns, table.data, {beforePageContent: header, theme: 'grid', margin: {top: 70, left: 40, right: 40}, columnStyles: {
            0: {columnWidth: 205},
            1: {columnWidth: 80},
            2: {columnWidth: 80},
            3: {columnWidth: 80},
            4: {columnWidth: 80}

        }, styles: {
            fontSize: 12,
            font: 'PTSans',
            halign: 'center'

        }

    });


    doc.save("Statistics.pdf");
}

function downloadPopoverSubjects(tno) {
    var doc = new jsPDF('p', 'pt');
    var tableName = "";
    var header = function (data) {
        doc.setFontSize(18);
        doc.setFontStyle('PTSans');
        doc.text("Subject wise Statistics", 225, 50);
        doc.line(226, 53, 390, 53);// Header top margin
    };


    tableName = "TT" + tno;
    doc.addImage(imgData[tno], 'png', 80, 180, 300, 200);
    var table = doc.autoTableHtmlToJson(document.getElementById(tableName));
    doc.autoTable(table.columns, table.data, {beforePageContent: header, theme: 'grid', margin: {top: 70, left: 40, right: 40}, columnStyles: {
            0: {columnWidth: 205},
            1: {columnWidth: 80},
            2: {columnWidth: 80},
            3: {columnWidth: 80},
            4: {columnWidth: 80}
        }, styles: {
            fontSize: 12,
            font: 'PTSans',
            halign: 'center'
        }

    });

    doc.save("Subject.pdf");
}