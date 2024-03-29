<?php

namespace App\Http\Controllers;
use App\Mail\SendMail;
use Illuminate\Http\Request;

class SendMailController extends Controller
{
    // private $hr = 'satyadeep.neupane@deerwalk.edu.np';
    public $details = [];

    public function sendMail($to, $from, $name, $subject, $message, $cc = false, $bcc = false)
    {
        $details = [
            'to' => $to,
            'from' => $from,
            'name'=>$name,
            'subject' =>$subject,
            'body' => $message,
            'bcc' =>$bcc,
            'cc' => $cc,
        ];
     
        \Mail::send('admin.emails.sendMail',$details, function($message) use ($details) {
            $message->to($details['to']);
            $message->subject($details['subject']);
            $message->from($details['from'],'DRM System');    //from HR
            if($details['cc'])
                $message->cc($details['cc']);
            if($details['bcc'])
                $message->bcc($details['bcc']);
        });

        return true;
    }

    public function punchOutMail(){
        //receive employee_id
        //fetch_manager_email 

        //employee by mnager_id to hr , cc manager, bcc employee
        
        $to = 'pratyush.acharya@deerwalk.edu.np';
        $cc = ['asim.poudel@deerwalk.edu.np'];
        // $bcc = ['deenasitikhu@gmail.com'];
        $name = 'pratyush';
        $message = 'still not punch out';
        // $regards ='HR';
        $subject = 'Punch In Subject';
        return $this->sendMail($to, $name, $subject, $message, $cc);
    }
}
