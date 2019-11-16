        <table id="PageTitle" style="margin: auto; width: 70%;">
            <tr>
                <th id="SchoolLogoTH" style="text-align: center;" colspan="2">
                    <img id="SchoolLogo" src="images/sanawbar.jpg" style="width: 10%;">
                </th>
            </tr>
            <tr>
                <th id="SchoolName" style="text-align: center;" colspan="2">
                    Al Sanawbar School
                </th>
            </tr>
            <tr><br><br></tr>
            <tr>
                <th id="Performance">
                    Performance Indicator levels: Summary
                </th>
                <th id="Attainment" style="text-align: right;">
                    Attainment Progress Analysis
                </th>
            </tr>
        </table> 

        <table id="useroptions" class="w3-card" style="width: 70% margin: auto;">
            <thead>
                <tr>
                    <th id="TableTitle" colspan="6"></th>
                </tr>
            </thead>
            <tr>
                <th><label>Year</label></th>
                <th><select id="academic_year1" onchange="FillTerm(this, 'term1')"></select></th>
                <th><select id="academic_year2" onchange="FillTerm(this, 'term2')"></select></th>
                <th><select id="academic_year3" onchange="FillTerm(this, 'term3')"></select></th>
                <th><select id="academic_year4" onchange="FillTerm(this, 'term4')"></select></th>
                <th><select id="academic_year5" onchange="FillTerm(this, 'term5')"></select></th>
            </tr>
            <tr>
                <th><label>Term</label></th>
                <th><select id="term1" onchange="search()"></select></th>
                <th><select id="term2" onchange="search()"></select></th>
                <th><select id="term3" onchange="search()"></select></th>
                <th><select id="term4" onchange="search()"></select></th>
                <th><select id="term5" onchange="search()"></select></th>
            </tr>
            <tbody id="results"> </tbody>
        </table>

        <table id="InDepthDiv" style="width: 70%; margin: auto; color: gray; font-size: 10px; opacity: 0.5">
            <tr>
                <td id="InDepthTD" style="text-align: right;">Powered By <a href="https://www.indepth.ae">InDepth</a></td>
            </tr>
        </table>