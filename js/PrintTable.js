//     function PrintTable () {
//         document.getElementById('T1L').innerHTML = $('#term1').children('option:selected').text();
//         document.getElementById('T2L').innerHTML = $('#term2').children('option:selected').text();
//         document.getElementById('T3L').innerHTML = $('#term3').children('option:selected').text();

//         $('#term1').multiselect('destroy');
//         $('#term2').multiselect('destroy');
//         $('#term3').multiselect('destroy');

// document.getElementById('pp').click();
        
//         $('#term1').multiselect({includeSelectAllOption: false});
//         $('#term2').multiselect({includeSelectAllOption: false});
//         $('#term3').multiselect({includeSelectAllOption: false});

//         var term1 = document.getElementById('term1');
//         var term2 = document.getElementById('term2');
//         var term3 = document.getElementById('term3');

//         var httpTerms = new XMLHttpRequest();
//         httpTerms.onreadystatechange = function () {
//             if (this.readyState === 4) {
//                 var str = this.responseText;
//                 termsArray = str.split("\t");
//             }
//         };

//         httpTerms.open("GET", "sqldb/terms.php", false);
//         httpTerms.send();

//         delete termsArray[termsArray.length - 1];


//         for (var i in termsArray) {
//             term1.add(new Option(termsArray[i]));
//             term2.add(new Option(termsArray[i]));
//             term3.add(new Option(termsArray[i]));
//         }        

//         document.getElementById("term1").value = document.getElementById('T1L').textContent;
//         document.getElementById("term2").value = document.getElementById('T2L').textContent;
//         document.getElementById("term3").value = document.getElementById('T3L').textContent;


//         document.getElementById('T1L').innerHTML = "";
//         document.getElementById('T2L').innerHTML = "";
//         document.getElementById('T3L').innerHTML = "";

//         search();
//     }