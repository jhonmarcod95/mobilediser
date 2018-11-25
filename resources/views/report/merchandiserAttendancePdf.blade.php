<html>
    <head>


        <style>
            .page-break {
                page-break-after: always;
            }
        </style>
    </head>

    <body>

        @foreach($attendances as $attendance)
            <table>
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

            <br>

            <table style="font-size: 12px">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Store</th>
                        <th>Schedule</th>
                        <th>In/Out</th>
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
                        <td>{{ $log->timeRenderedText }}</td>
                        <td>{{ $log->overtime }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="page-break"></div>
        @endforeach

        {{--<table id="dataTable2" class="table table-bordered" style="width: 100%"><thead></thead><tbody><tr><td colspan="5"><b>MobileDiser Id: </b>1</td><td><b>Period: </b>01-Nov-18 to 22-Nov-18</td></tr><tr><td colspan="5"><b>Merchandiser: </b>JENNY  CARDENAS</td><td><b>Total Rendered: </b>14 hrs 06 min </td></tr><tr><td colspan="5"><b>Agency: </b>Aurinko  Marketing and Promotions Inc.</td><td><b>Total Overtime: </b>-</td></tr><tr><td colspan="5"><b>Working Days: </b>19</td><td><b>Days Present: </b>2</td></tr><tr><td colspan="5"><b>Total Working Hrs: </b>153 hrs 00 min </td><td><b>Days Absent: </b>17</td></tr><tr><td colspan="6">&nbsp;</td></tr><tr><td><b>Date</b></td><td><b>Store</b></td><td><b>Schedule</b></td><td><b>In/Out</b></td><td><b>Rendered</b></td><td><b>OT</b></td></tr><tr><td>01-Nov-18 (Thu)</td><td>PUREGOLD PRICE CLUB, INC. - 849–VISAYA</td><td>09:00 am - 01:00 pm</td><td> - </td><td>-</td><td>-</td></tr><tr><td>01-Nov-18 (Thu)</td><td>PUREGOLD PRICE CLUB, INC. - CE-KALAYAA</td><td>02:00 pm - 06:00 pm</td><td> - </td><td>-</td><td>-</td></tr><tr><td>02-Nov-18 (Fri)</td><td>PUREGOLD PRICE CLUB, INC. - 279-TERRAC</td><td>09:00 am - 01:00 pm</td><td> - </td><td>-</td><td>-</td></tr><tr><td>02-Nov-18 (Fri)</td><td>PUREGOLD PRICE CLUB, INC. - 135-N-CWEA</td><td>02:00 pm - 06:00 pm</td><td> - </td><td>-</td><td>-</td></tr><tr><td>03-Nov-18 (Sat)</td><td>PUREGOLD PRICE CLUB, INC. - 849–VISAYA</td><td>09:00 am - 01:00 pm</td><td> - </td><td>-</td><td>-</td></tr><tr><td>03-Nov-18 (Sat)</td><td>PUREGOLD PRICE CLUB, INC. - CE-KALAYAA</td><td>02:00 pm - 06:00 pm</td><td> - </td><td>-</td><td>-</td></tr><tr><td>04-Nov-18 (Sun)</td><td>PUREGOLD PRICE CLUB, INC. - 279-TERRAC</td><td>09:00 am - 01:00 pm</td><td> - </td><td>-</td><td>-</td></tr><tr><td>04-Nov-18 (Sun)</td><td>PUREGOLD PRICE CLUB, INC. - 135-N-CWEA</td><td>02:00 pm - 06:00 pm</td><td> - </td><td>-</td><td>-</td></tr><tr><td>05-Nov-18 (Mon)</td><td>PUREGOLD PRICE CLUB, INC. - 849–VISAYA</td><td>09:00 am - 01:00 pm</td><td> - </td><td>-</td><td>-</td></tr><tr><td>05-Nov-18 (Mon)</td><td>PUREGOLD PRICE CLUB, INC. - CE-KALAYAA</td><td>02:00 pm - 06:00 pm</td><td> - </td><td>-</td><td>-</td></tr><tr><td>06-Nov-18 (Tue)</td><td>PUREGOLD PRICE CLUB, INC. - 279-TERRAC</td><td>09:00 am - 01:00 pm</td><td> - </td><td>-</td><td>-</td></tr><tr><td>06-Nov-18 (Tue)</td><td>PUREGOLD PRICE CLUB, INC. - 135-N-CWEA</td><td>02:00 pm - 06:00 pm</td><td> - </td><td>-</td><td>-</td></tr><tr><td>07-Nov-18 (Wed)</td><td> - </td><td> - </td><td> - </td><td>-</td><td>-</td></tr><tr><td>08-Nov-18 (Thu)</td><td>PUREGOLD PRICE CLUB, INC. - 849–VISAYA</td><td>09:00 am - 01:00 pm</td><td> - </td><td>-</td><td>-</td></tr><tr><td>08-Nov-18 (Thu)</td><td>PUREGOLD PRICE CLUB, INC. - CE-KALAYAA</td><td>02:00 pm - 06:00 pm</td><td> - </td><td>-</td><td>-</td></tr><tr><td>09-Nov-18 (Fri)</td><td>PUREGOLD PRICE CLUB, INC. - 279-TERRAC</td><td>09:00 am - 01:00 pm</td><td> - </td><td>-</td><td>-</td></tr><tr><td>09-Nov-18 (Fri)</td><td>PUREGOLD PRICE CLUB, INC. - 135-N-CWEA</td><td>02:00 pm - 06:00 pm</td><td> - </td><td>-</td><td>-</td></tr><tr><td>10-Nov-18 (Sat)</td><td>PUREGOLD PRICE CLUB, INC. - 849–VISAYA</td><td>09:00 am - 01:00 pm</td><td> - </td><td>-</td><td>-</td></tr><tr><td>10-Nov-18 (Sat)</td><td>PUREGOLD PRICE CLUB, INC. - CE-KALAYAA</td><td>02:00 pm - 06:00 pm</td><td> - </td><td>-</td><td>-</td></tr><tr><td>11-Nov-18 (Sun)</td><td>PUREGOLD PRICE CLUB, INC. - 279-TERRAC</td><td>09:00 am - 01:00 pm</td><td> - </td><td>-</td><td>-</td></tr><tr><td>11-Nov-18 (Sun)</td><td>PUREGOLD PRICE CLUB, INC. - 135-N-CWEA</td><td>02:00 pm - 06:00 pm</td><td> - </td><td>-</td><td>-</td></tr><tr><td>12-Nov-18 (Mon)</td><td>PUREGOLD PRICE CLUB, INC. - 849–VISAYA</td><td>09:00 am - 01:00 pm</td><td> - </td><td>-</td><td>-</td></tr><tr><td>12-Nov-18 (Mon)</td><td>PUREGOLD PRICE CLUB, INC. - CE-KALAYAA</td><td>02:00 pm - 06:00 pm</td><td> - </td><td>-</td><td>-</td></tr><tr><td>13-Nov-18 (Tue)</td><td>PUREGOLD PRICE CLUB, INC. - 279-TERRAC</td><td>09:00 am - 06:00 pm</td><td> - </td><td>-</td><td>-</td></tr><tr><td>14-Nov-18 (Wed)</td><td> - </td><td> - </td><td> - </td><td>-</td><td>-</td></tr><tr><td>15-Nov-18 (Thu)</td><td>PUREGOLD PRICE CLUB, INC. - 849–VISAYA</td><td>09:00 am - 01:00 pm</td><td>08:37 am - 01:08 pm</td><td>4 hrs 31 min </td><td>-</td></tr><tr><td>15-Nov-18 (Thu)</td><td>PUREGOLD PRICE CLUB, INC. - CE-KALAYAA</td><td>02:00 pm - 06:00 pm</td><td>01:25 pm - 05:56 pm</td><td>4 hrs 31 min </td><td>-</td></tr><tr><td>16-Nov-18 (Fri)</td><td>PUREGOLD PRICE CLUB, INC. - 279-TERRAC</td><td>09:00 am - 01:00 pm</td><td>08:00 am - 01:04 pm</td><td>5 hrs 04 min </td><td>-</td></tr><tr><td>16-Nov-18 (Fri)</td><td>PUREGOLD PRICE CLUB, INC. - 135-N-CWEA</td><td>02:00 pm - 06:00 pm</td><td>01:35 pm - No Out</td><td>-</td><td>-</td></tr><tr><td>17-Nov-18 (Sat)</td><td>PUREGOLD PRICE CLUB, INC. - 849–VISAYA</td><td>09:00 am - 01:00 pm</td><td> - </td><td>-</td><td>-</td></tr><tr><td>17-Nov-18 (Sat)</td><td>PUREGOLD PRICE CLUB, INC. - CE-KALAYAA</td><td>02:00 pm - 06:00 pm</td><td> - </td><td>-</td><td>-</td></tr><tr><td>18-Nov-18 (Sun)</td><td>PUREGOLD PRICE CLUB, INC. - 279-TERRAC</td><td>09:00 am - 01:00 pm</td><td> - </td><td>-</td><td>-</td></tr><tr><td>18-Nov-18 (Sun)</td><td>PUREGOLD PRICE CLUB, INC. - 135-N-CWEA</td><td>02:00 pm - 06:00 pm</td><td> - </td><td>-</td><td>-</td></tr><tr><td>19-Nov-18 (Mon)</td><td>PUREGOLD PRICE CLUB, INC. - 849–VISAYA</td><td>09:00 am - 01:00 pm</td><td> - </td><td>-</td><td>-</td></tr><tr><td>19-Nov-18 (Mon)</td><td>PUREGOLD PRICE CLUB, INC. - CE-KALAYAA</td><td>02:00 pm - 06:00 pm</td><td> - </td><td>-</td><td>-</td></tr><tr><td>20-Nov-18 (Tue)</td><td>PUREGOLD PRICE CLUB, INC. - 279-TERRAC</td><td>09:00 am - 01:00 pm</td><td> - </td><td>-</td><td>-</td></tr><tr><td>20-Nov-18 (Tue)</td><td>PUREGOLD PRICE CLUB, INC. - 135-N-CWEA</td><td>02:00 pm - 06:00 pm</td><td> - </td><td>-</td><td>-</td></tr><tr><td>21-Nov-18 (Wed)</td><td> - </td><td> - </td><td> - </td><td>-</td><td>-</td></tr><tr><td>22-Nov-18 (Thu)</td><td>PUREGOLD PRICE CLUB, INC. - 849–VISAYA</td><td>09:00 am - 01:00 pm</td><td> - </td><td>-</td><td>-</td></tr><tr><td>22-Nov-18 (Thu)</td><td>PUREGOLD PRICE CLUB, INC. - CE-KALAYAA</td><td>02:00 pm - 06:00 pm</td><td> - </td><td>-</td><td>-</td></tr><tr><td colspan="6">&nbsp;</td></tr></tbody></table>--}}
    </body>
</html>
