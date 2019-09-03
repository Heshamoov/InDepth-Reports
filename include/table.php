   <div class="w3-container w3-col m4 l5 w3-mobile" id="tables" style="overflow: scroll;top: 0;  bottom: 0; height: 100vh; " >
            <textarea id="output" rows="10" cols="50" hidden></textarea>
            <br>
            <table class=" w3-table-all w3-striped w3-bordered w3-centered w3-card-4" id="stable">  
                <th colspan="4" class="w3-custom " style="font-size: 16px">Statistics 
                </th>
                <th colspan="1" class="w3-custom">  <button  style="float: right;"type="button" class="btn w3-button w3-hover-blue-gray w3-custom" 
                                                             data-toggle="popover" >
                        <span class="material-icons ">signal_cellular_alt</span>
                    </button></th>


                <tr>
                    <th class="w3-border-right">Academic Year</th><th class="w3-border-right">Marks Count</th>
                    <th class="w3-border-right"><input id="percent11" type="text" value= 50>% - <input id="percente12" type="text" value=100>%</th>
                    <th class="w3-border-right"><input id="percent21" type="text" value=65>% - <input id="percente22" type="text" value=100>%</th>
                    <th class="w3-border-right"><input id="percent31" type="text" value=75>% - <input id="percente32" type="text" value=100>%</th>
                </tr>
                <tr>
                    <td class="w3-border-right">2017-2018</td>
                    <td class="w3-border-right"></td>
                    <td class="w3-border-right"></td>
                    <td class="w3-border-right"></td>
                    <td class="w3-border-right"></td>
                </tr>


            </table>
            <br><br>

            <table id="stablePDF" style="font-size: 100px" hidden>
                <thead>
                    <tr>
                        <th colspan="5"></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody> 
                    <tr>
                        <th>Year</th>
                        <th>Total Number</th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                    <tr>
                        <td>2018-2019</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>

            <table id="T1" class=" w3-table-all w3-striped w3-bordered w3-centered w3-card-4" >
                <th colspan="4" class="w3-custom" style="font-size: 16px">Subject</th>
                <th colspan="1" class="w3-custom">  <button  style="float: right;"type="button" class="btn w3-button w3-hover-blue-gray w3-custom" 
                                                             data-toggle="popoverSubject1" onclick="drawChartSubjects();">
                        <span class="material-icons ">signal_cellular_alt</span>
                    </button></th>


                <tr>
                    <th class="w3-border-right">Academic Year</th><th class="w3-border-right">Total</th>
                    <th class="w3-border-right"></th><th class="w3-border-right"></th><th class="w3-border-right"></th>
                </tr>
                <tr>
                    <td class="w3-border-right"></td><td class="w3-border-right"></td><td class="w3-border-right"></td>
                    <td class="w3-border-right"></td><td class="w3-border-right"></td>
                </tr>
            </table>
            <br>

            <table class=" w3-table-all w3-striped w3-bordered w3-centered w3-card-4" id="T2">  
                <th colspan="4" class="w3-custom" style="font-size: 16px;">Subject</th>
                <th colspan="1" class="w3-custom">  <button  style="float: right;"type="button" class="btn w3-button w3-hover-blue-gray w3-custom" 
                                                             data-toggle="popoverSubject2" onclick="drawChartSubjects();">
                        <span class="material-icons ">signal_cellular_alt</span>
                    </button></th>
                <tr>
                    <th class="w3-border-right">Academic Year</th><th class="w3-border-right">Total</th>
                    <th class="w3-border-right"></th><th class="w3-border-right"></th><th class="w3-border-right"></th>
                </tr>
                <tr>
                    <td class="w3-border-right"></td><td class="w3-border-right"></td><td class="w3-border-right"></td>
                    <td class="w3-border-right"></td><td class="w3-border-right"></td>
                </tr>
            </table>

            <br>

            <table class=" w3-table-all w3-striped w3-bordered w3-centered w3-card-4" id="T3">  
                <th colspan="4" class="w3-custom" style="font-size: 16px">Subject</th>
                <th colspan="1" class="w3-custom">  <button  style="float: right;"type="button" class="btn w3-button w3-hover-blue-gray w3-custom" 
                                                             data-toggle="popoverSubject3" onclick="drawChartSubjects();">
                        <span class="material-icons ">signal_cellular_alt</span>
                    </button></th>
                <tr>
                    <th class="w3-border-right">Academic Year</th><th class="w3-border-right">Total</th>
                    <th class="w3-border-right"></th><th class="w3-border-right"></th><th class="w3-border-right"></th>
                </tr>
                <tr>
                    <td class="w3-border-right"></td><td class="w3-border-right"></td><td class="w3-border-right"></td>
                    <td class="w3-border-right"></td><td class="w3-border-right"></td>
                </tr>
            </table>

            <br>

            <table class=" w3-table-all w3-striped w3-bordered w3-centered w3-card-4" id="T4">
                <th colspan="4" class="w3-custom" style="font-size: 16px">Subject</th>
                <th colspan="1" class="w3-custom">  <button  style="float: right;"type="button" class="btn w3-button w3-hover-blue-gray w3-custom" 
                                                             data-toggle="popoverSubject4" onclick="drawChartSubjects();">
                        <span class="material-icons ">signal_cellular_alt</span>
                    </button></th>
                <tr>
                    <th class="w3-border-right">Academic Year</th><th class="w3-border-right">Total</th>
                    <th class="w3-border-right"></th><th class="w3-border-right"></th><th class="w3-border-right"></th>
                </tr>
                <tr>
                    <td class="w3-border-right"></td><td class="w3-border-right"></td><td class="w3-border-right"></td>
                    <td class="w3-border-right"></td><td class="w3-border-right"></td>
                </tr>
            </table>

            <br>

            <table class=" w3-table-all w3-striped w3-bordered w3-centered w3-card-4" id="T5">  
                <th colspan="4" class="w3-custom" style="font-size: 16px">Subject</th>
                <th colspan="1" class="w3-custom">  <button  style="float: right;"type="button" class="btn w3-button w3-hover-blue-gray w3-custom" 
                                                             data-toggle="popoverSubject5" onclick="drawChartSubjects();">
                        <span class="material-icons ">signal_cellular_alt</span>
                    </button></th>
                <tr>
                    <th class="w3-border-right">Academic Year</th><th class="w3-border-right">Total</th>
                    <th class="w3-border-right"></th><th class="w3-border-right"></th><th class="w3-border-right"></th>
                </tr>
                <tr>
                    <td class="w3-border-right"></td><td class="w3-border-right"></td><td class="w3-border-right"></td>
                    <td class="w3-border-right"></td><td class="w3-border-right"></td>
                </tr>
            </table>

            <br>

            <table class=" w3-table-all w3-striped w3-bordered w3-centered w3-card-4" id="T6">  
                <th colspan="4" class="w3-custom" style="font-size: 16px">Subject</th>
                <th colspan="1" class="w3-custom">  <button  style="float: right;"type="button" class="btn w3-button w3-hover-blue-gray w3-custom" 
                                                             data-toggle="popoverSubject6" onclick="drawChartSubjects();">
                        <span class="material-icons ">signal_cellular_alt</span>
                    </button></th>
                <tr>
                    <th class="w3-border-right">Academic Year</th><th class="w3-border-right">Total</th>
                    <th class="w3-border-right"></th><th class="w3-border-right"></th><th class="w3-border-right"></th>
                </tr>
                <tr>
                    <td class="w3-border-right"></td><td class="w3-border-right"></td><td class="w3-border-right"></td>
                    <td class="w3-border-right"></td><td class="w3-border-right"></td>
                </tr>
            </table>

            <br>

            <table class=" w3-table-all w3-striped w3-bordered w3-centered w3-card-4" id="T7">  
                <th colspan="4" class="w3-custom" style="font-size: 16px">Subject</th>
                <th colspan="1" class="w3-custom">  <button  style="float: right;"type="button" class="btn w3-button w3-hover-blue-gray w3-custom" 
                                                             data-toggle="popoverSubject7" onclick="drawChartSubjects();">
                        <span class="material-icons ">signal_cellular_alt</span>
                    </button></th>
                <tr>
                    <th class="w3-border-right">Academic Year</th><th class="w3-border-right">Total</th>
                    <th class="w3-border-right"></th><th class="w3-border-right"></th><th class="w3-border-right"></th>
                </tr>
                <tr>
                    <td class="w3-border-right"></td><td class="w3-border-right"></td><td class="w3-border-right"></td>
                    <td class="w3-border-right"></td><td class="w3-border-right"></td>
                </tr>
            </table>

            <br>

            <table class=" w3-table-all w3-striped w3-bordered w3-centered w3-card-4" id="T8">  
                <th colspan="4" class="w3-custom" style="font-size: 16px">Subject</th>
                <th colspan="1" class="w3-custom">  <button  style="float: right;"type="button" class="btn w3-button w3-hover-blue-gray w3-custom" 
                                                             data-toggle="popoverSubject8" onclick="drawChartSubjects();">
                        <span class="material-icons ">signal_cellular_alt</span>
                    </button></th>
                <tr>
                    <th class="w3-border-right">Academic Year</th><th class="w3-border-right">Total</th>
                    <th class="w3-border-right"></th><th class="w3-border-right"></th><th class="w3-border-right"></th>
                </tr>
                <tr>
                    <td class="w3-border-right"></td><td class="w3-border-right"></td><td class="w3-border-right"></td>
                    <td class="w3-border-right"></td><td class="w3-border-right"></td>
                </tr>
            </table>

            <br>

            <table class=" w3-table-all w3-striped w3-bordered w3-centered w3-card-4" id="T9">  
                <th colspan="4" class="w3-custom" style="font-size: 16px">Subject</th>
                <th colspan="1" class="w3-custom">  <button  style="float: right;"type="button" class="btn w3-button w3-hover-blue-gray w3-custom" 
                                                             data-toggle="popoverSubject9" onclick="drawChartSubjects();">
                        <span class="material-icons ">signal_cellular_alt</span>
                    </button></th>
                <tr>
                    <th class="w3-border-right">Academic Year</th><th class="w3-border-right">Total</th>
                    <th class="w3-border-right"></th><th class="w3-border-right"></th><th class="w3-border-right"></th>
                </tr>
                <tr>    
                    <td class="w3-border-right"></td><td class="w3-border-right"></td><td class="w3-border-right"></td>
                    <td class="w3-border-right"></td><td class="w3-border-right"></td>
                </tr>
            </table>

            <br>

            <table class=" w3-table-all w3-striped w3-bordered w3-centered w3-card-4" id="T10">  
                <th colspan="4" class="w3-custom" style="font-size: 16px">Subject</th>
                <th colspan="1" class="w3-custom">  <button  style="float: right;"type="button" class="btn w3-button w3-hover-blue-gray w3-custom" 
                                                             data-toggle="popoverSubject10" onclick="drawChartSubjects();">
                        <span class="material-icons ">signal_cellular_alt</span>
                    </button></th>
                <tr>
                    <th class="w3-border-right">Academic Year</th><th class="w3-border-right">Total</th>
                    <th class="w3-border-right"></th><th class="w3-border-right"></th><th class="w3-border-right"></th>
                </tr>
                <tr>
                    <td class="w3-border-right"></td><td class="w3-border-right"></td><td class="w3-border-right"></td>
                    <td class="w3-border-right"></td><td class="w3-border-right"></td>
                </tr>
            </table>

            <br>

            <table class=" w3-table-all w3-striped w3-bordered w3-centered w3-card-4" id="T11">  
                <th colspan="4" class="w3-custom" style="font-size: 16px">Subject</th>
                <th colspan="1" class="w3-custom">  <button  style="float: right;"type="button" class="btn w3-button w3-hover-blue-gray w3-custom" 
                                                             data-toggle="popoverSubject11" onclick="drawChartSubjects();">
                        <span class="material-icons ">signal_cellular_alt</span>
                    </button></th>
                <tr>
                    <th class="w3-border-right">Academic Year</th><th class="w3-border-right">Total</th>
                    <th class="w3-border-right"></th><th class="w3-border-right"></th><th class="w3-border-right"></th>
                </tr>
                <tr>
                    <td class="w3-border-right"></td><td class="w3-border-right"></td><td class="w3-border-right"></td>
                    <td class="w3-border-right"></td><td class="w3-border-right"></td>
                </tr>
            </table>

            <br>

            <table class=" w3-table-all w3-striped w3-bordered w3-centered w3-card-4" id="T12">  
                <th colspan="4" class="w3-custom" style="font-size: 16px">Subject</th>
                <th colspan="1" class="w3-custom">  <button  style="float: right;"type="button" class="btn w3-button w3-hover-blue-gray w3-custom" 
                                                             data-toggle="popoverSubject12" onclick="drawChartSubjects();">
                        <span class="material-icons ">signal_cellular_alt</span>
                    </button></th>
                <tr>
                    <th class="w3-border-right">Academic Year</th><th class="w3-border-right">Total</th>
                    <th class="w3-border-right"></th><th class="w3-border-right"></th><th class="w3-border-right"></th>
                </tr>
                <tr>
                    <td class="w3-border-right"></td><td class="w3-border-right"></td><td class="w3-border-right"></td>
                    <td class="w3-border-right"></td><td class="w3-border-right"></td>
                </tr>
            </table>

        </div>

        <div class="w3-col m8 l7 w3-card-4 w3-mobile" id="rightdiv" style = "height:100vh; overflow: scroll; padding-top: 10px; padding-left: 10px; padding-right: 10px"> 
            <!--Downloading table  11:52 AM-->   
            <br>
            <div id ="outheader"> <h4  style="text-align: center"> STUDENT LIST</h4>
                <table class="w3-table-all w3-card-4 w3-striped w3-hoverable" id="out" ></table> </div>
            <table id="TT1" hidden>
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Year</td>
                        <td>Total Number</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <table id="TT2" hidden>
                <thead><tr><th></th><th></th><th></th><th></th><th></th></tr></thead>
                <tbody><tr><td>Year</td><td>Total Number</td><td></td><td></td><td></td></tr>
                    <tr><td></td><td></td><td></td><td></td><td></td></tr></tbody>
            </table>
            <table id="TT3" hidden>
                <thead><tr><th></th><th></th><th></th><th></th><th></th></tr></thead>
                <tbody><tr><td>Year</td><td>Total Number</td><td></td><td></td><td></td></tr>
                    <tr><td></td><td></td><td></td><td></td><td></td></tr></tbody>
            </table>
            <table id="TT4" hidden>
                <thead><tr><th></th><th></th><th></th><th></th><th></th></tr></thead>
                <tbody><tr><td>Year</td><td>Total Number</td><td></td><td></td><td></td></tr>
                    <tr><td></td><td></td><td></td><td></td><td></td></tr></tbody>
            </table>
            <table id="TT5" hidden>
                <thead><tr><th></th><th></th><th></th><th></th><th></th></tr></thead>
                <tbody><tr><td>Year</td><td>Total Number</td><td></td><td></td><td></td></tr>
                    <tr><td></td><td></td><td></td><td></td><td></td></tr></tbody>
            </table>
            <table id="TT6" hidden>
                <thead><tr><th></th><th></th><th></th><th></th><th></th></tr></thead>
                <tbody><tr><td>Year</td><td>Total Number</td><td></td><td></td><td></td></tr>
                    <tr><td></td><td></td><td></td><td></td><td></td></tr></tbody>
            </table>
            <table id="TT7" hidden>
                <thead><tr><th></th><th></th><th></th><th></th><th></th></tr></thead>
                <tbody><tr><td>Year</td><td>Total Number</td><td></td><td></td><td></td></tr>
                    <tr><td></td><td></td><td></td><td></td><td></td></tr></tbody>
            </table>
            <table id="TT8" hidden>
                <thead><tr><th></th><th></th><th></th><th></th><th></th></tr></thead>
                <tbody><tr><td>Year</td><td>Total Number</td><td></td><td></td><td></td></tr>
                    <tr><td></td><td></td><td></td><td></td><td></td></tr></tbody>
            </table>
            <table id="TT9" hidden>
                <thead><tr><th></th><th></th><th></th><th></th><th></th></tr></thead>
                <tbody><tr><td>Year</td><td>Total Number</td><td></td><td></td><td></td></tr>
                    <tr><td></td><td></td><td></td><td></td><td></td></tr></tbody>
            </table>
            <table id="TT10" hidden>
                <thead><tr><th></th><th></th><th></th><th></th><th></th></tr></thead>
                <tbody><tr><td>Year</td><td>Total Number</td><td></td><td></td><td></td></tr>
                    <tr><td></td><td></td><td></td><td></td><td></td></tr></tbody>
            </table>
            <table id="TT11" hidden>
                <thead><tr><th></th><th></th><th></th><th></th><th></th></tr></thead>
                <tbody><tr><td>Year</td><td>Total Number</td><td></td><td></td><td></td></tr>
                    <tr><td></td><td></td><td></td><td></td><td></td></tr></tbody>
            </table>
            <table id="TT12" hidden>
                <thead><tr><th></th><th></th><th></th><th></th><th></th></tr></thead>
                <tbody><tr><td>Year</td><td>Total Number</td><td></td><td></td><td></td></tr>
                    <tr><td></td><td></td><td></td><td></td><td></td></tr></tbody>
            </table>    
        </div>