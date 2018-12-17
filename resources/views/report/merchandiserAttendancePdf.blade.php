<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-1">
    </head>

    <body>
        @foreach($attendances as $key => $attendance)
            <div style="display: none">
                <table id="info-{{ $key }}" style="font-size: 8px">
                    <tr>
                        <td colspan="5"><b>MobileDiser Id: </b>{{ $attendance->mobilediserId }}</td>
                        <td><b>Period: </b>{{ $attendance->period }}</td>
                    </tr>
                    <tr>
                        <td colspan="5"><b>Merchandiser: </b>{{ $attendance->merchandiserName }}</td>
                        <td><b>Total Rendered: </b>{{ $attendance->totalRendered }}</td>
                    </tr>
                    <tr>
                        <td colspan="5"><b>Agency: </b>{{ $attendance->agency }}</td>
                        <td><b>Total Overtime: </b>{{ $attendance->totalOvertime }}</td>
                    </tr>
                    <tr>
                        <td colspan="5"><b>Working Days: </b>{{ $attendance->workingDays }}</td>
                        <td><b>Days Present: </b>{{ $attendance->daysPresent }}</td>
                    </tr>
                    <tr>
                        <td colspan="5"><b>Total Working Hrs: </b>{{ $attendance->totalWorkingHours }}</td>
                        <td><b>Days Absent: </b>{{ $attendance->daysAbsent }}</td>
                    </tr>
                </table>

                <table id="attendance-{{ $key }}">
                    <thead>
                        <tr>
                            <th width="400">Date</th>
                            <th>Store</th>
                            <th>Schedule</th>
                            <th>In/Out</th>
                            <th>Working Hrs</th>
                            <th>Rendered</th>
                            <th>OT</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($attendance->logs as $log)
                        <tr>
                            <td>{{ $log->date }}</td>
                            <td>{{ $log->store }}</td>
                            <td>{{ $log->schedule }}</td>
                            <td>{{ $log->timeInOut }}</td>
                            <td>{{ $log->workingHrs }}</td>
                            <td>{{ $log->timeRenderedText }}</td>
                            <td>{{ $log->overtime }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach

        {{-- jspdf --}}
        <script src="{{  asset('adminLTE/bower_components/jquery/dist/jquery.min.js') }}"></script>
        <script src="{{  asset('jsPDF/jspdf.min.js') }}"></script>
        <script src="{{  asset('jsPDF/jsPdf_Plugins.js') }}"></script>
        <script src="{{  asset('jsPDF/jspdf.plugin.autotable.js') }}"></script>

        {{-- moment --}}
        <script src="{{ asset('/calendar/lib/moment.min.js') }}"></script>

        <script>
            let doc = new jsPDF('p', 'pt', 'a4');
            let doc2 = new jsPDF('p', 'pt', 'a4'); // use to get totalpage
            let totalPage;

            function generatePdf() {
                let attendanceLoops = 0;
                let res;


                /* get total page count *************************************/
                // adjust this once table content changes
                @foreach($attendances as $key => $attendance)
                    /* body ******************************/
                    // attendance
                    res = doc2.autoTableHtmlToJson(document.getElementById("attendance-{{ $key }}"));
                    doc2.autoTable(res.columns, res.data, {margin: {bottom: 200},
                        theme: 'striped',
                        startY: 200,
                        styles: {
                            fontSize: 9,
                            overflow: 'linebreak',
                            cellPadding: 2,
                            halign: 'left'
                        },
                        columnStyles: {
                            0: {columnWidth: 80},
                            1: {columnWidth: 130},
                            2: {columnWidth: 55},
                            3: {columnWidth: 55},
                            4: {columnWidth: 60},
                            5: {columnWidth: 60},
                            6: {columnWidth: 60},
                        }});
                    /* **********************************/

                    // adding pages
                    // avoid blank page at last page
                    attendanceLoops++;
                    if(attendanceLoops < parseInt('{{ count($attendances) }}')){
                        doc2.addPage();
                    }
                @endforeach
                totalPage = Object.keys(doc2.internal.pages).length;
                /* ***********************************************************/

                attendanceLoops = 0;
                @foreach($attendances as $key => $attendance)
                    /* header ***************************/
                    // title
                    doc.setFontSize(14);
                    doc.text('Daily Time Record', 240, 60);

                    // date
                    doc.setFontSize(7);
                    doc.text( moment().format('YYYY-MM-DD hh:mm a'), 480, 40);
                    /* ***********************************/

                    /* body ******************************/
                    // infos
                    res = doc.autoTableHtmlToJson(document.getElementById("info-{{ $key }}"));
                    doc.autoTable(res.columns, res.data, tblInfoOption);

                    //rectangle
                    doc.setDrawColor(0);
                    doc.rect(40, 100, 500, 80); // filled red square with black borders

                    // attendance
                    res = doc.autoTableHtmlToJson(document.getElementById("attendance-{{ $key }}"));
                    doc.autoTable(res.columns, res.data, tblAttendanceOption);
                    /* **********************************/

                    // adding pages
                    // avoid blank page at last page
                    attendanceLoops++;
                    if(attendanceLoops < parseInt('{{ count($attendances) }}')){
                        doc.addPage();
                    }
                @endforeach

                doc.save('DTR.pdf');

                // close a tab once downloaded
                setInterval(function() {
                    close();
                }, 1000);
            }

            let tblInfoOption = {
                theme: 'plain',
                startY: 100,
                styles: {
                    fontSize: 9,
                    overflow: 'linebreak',
                    cellPadding: 2,
                    halign: 'left'
                }
            };

            let tblAttendanceOption = {
                margin: {bottom: 75},
                theme: 'striped',
                startY: 200,
                styles: {
                    fontSize: 9,
                    overflow: 'linebreak',
                    cellPadding: 2,
                    halign: 'left'
                },
                columnStyles: {
                    0: {columnWidth: 80},
                    1: {columnWidth: 130},
                    2: {columnWidth: 55},
                    3: {columnWidth: 55},
                    4: {columnWidth: 60},
                    5: {columnWidth: 60},
                    6: {columnWidth: 60},
                },

                addPageContent: function(data) {
                    // //rectangle
                    // doc.setDrawColor(255, 0, 0);
                    // doc.rect(40, 650, 500, 90); // filled red square with black borders
                    //
                    // //disclaimer
                    // doc.setFontSize(9);
                    // doc.text("*Disclaimer ", 45, 670);

                    //page number
                    doc.setFontSize(9);
                    doc.text('Page ' + doc.internal.getNumberOfPages() + ' of ' + totalPage, 500, 800);
                }
            };

            generatePdf();
        </script>
    </body>
</html>
