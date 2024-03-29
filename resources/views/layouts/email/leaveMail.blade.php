<div>
    <p>Hello All,</p>
    
    @if($deerwalk_sifal != 0 && $deerwalk_compware !=0 && $deerwalk_group != 0)
    <p>This message is to inform you that the following team members of DSS, DWG and DWC are on leave today.

    @elseif($deerwalk_sifal != 0 && $deerwalk_compware!=0)
    <p>This message is to inform you that the following team members of DSS, and DWC are on leave today. According to DRM, no one is on leave from DWG.
   
    @elseif($deerwalk_sifal != 0 && $deerwalk_group!=0)
    <p>This message is to inform you that the following team members of DSS, and DWG are on leave today. According to DRM, no one is on leave from DWC.
    
    @elseif($deerwalk_compware != 0 && $deerwalk_group!=0)
    <p>This message is to inform you that the following team members of DWC, and DWG are on leave today. According to DRM, no one is on leave from DSS.
    
    @elseif($deerwalk_compware != 0)
    <p>This message is to inform you that the following team members of DWC are on leave today. According to DRM, no one is on leave from DSS and DWG.
    
    @elseif($deerwalk_group != 0)
    <p>This message is to inform you that the following team members of DWG are on leave today. According to DRM, no one is on leave from DSS and DWC.
    
    @elseif($deerwalk_sifal != 0)
    <p>This message is to inform you that the following team members of DSS are on leave today. According to DRM, no one is on leave from DWG and DWC.
    
    @else
    <p>This message is to inform you that the there is no one on leave today.
        
    @endif

    @if($deerwalk_sifal != 0 || $deerwalk_compware !=0 || $deerwalk_group != 0)
    <div >
        <table border=1 style=" border-collapse: collapse;">
            <tr>
                <th style="padding:7px">
                    <center>S.N.</center>
                </th>
                <th style="padding:7px">Name of Employee</th>
                <th style="padding:7px">Leave Type</th>
                <th style="padding:7px">Unit</th>
            </tr>
            @forelse($leaveList as $onLeave)
            <tr>
                <td style="padding:7px">
                    <center>{{ $loop->iteration }}</center>
                </td>
                <td style="padding:7px">{{ $onLeave->employee->first_name." ".$onLeave->employee->last_name}}</td>
                <td style="padding:7px">{{ $onLeave->full_leave==1?'Whole Day': ucfirst($onLeave->half_leave).' Half' }}</td>
                <td style="padding:7px">{{ $onLeave->employee->unit->unit_name }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7">
                    <center>No Employees On Leave Today.</center>
                </td>
            </tr>
            @endforelse
        </table>
    </div>
    @endif
   
    </p>
    <p>Regards,</p>
    <br>
    <div id="signature">
        --<br>
        HR | <span style="color:#0b5394;"><b>DRM SYSTEM</b></span><br>
        Deerwalk Education Group<br>
        Sifal, Kathmandu<br>
        Nepal<br>
        <a href="deerwalk.edu.np">deerwalk.edu.np</a>
        <br>
        <p style="color:888888; font-family: ui-monospace;">
            DISCLAIMER:<br>
            This is an automatically generated email - please do not reply to it. If you have any queries please contact HR.
        </p>
    </div>
</div>