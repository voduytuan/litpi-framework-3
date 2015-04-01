<?php

namespace Litpi;

class SendMail
{
    protected $registry;
    public $fromName = '';
    public $fromEmail = '';
    public $toName = '';
    public $toEmail = '';
    public $mailSubject = '';
    public $mailContents = '';
    public $usingSMTP = true;
    public $mailer;
    public $toArray = null;    // co dang array('email' => 'fullname', 'email' => 'fullname')

    public function __construct(
        $registry,
        $toEmail = '',
        $toName = '',
        $mailSubject = '',
        $mailContents = '',
        $fromEmail = '',
        $fromName = ''
    ) {
        $this->registry = $registry;
        $this->fromEmail = $fromEmail;
        $this->fromName = $fromName;
        $this->mailSubject = $mailSubject;
        $this->mailContents = $mailContents;
        $this->toEmail = $toEmail;
        $this->toName = $toName;

    }

    public function send()
    {
        //$this->mailer->SMTPDebug;
        // return $this->preview();
        // return false;

        $toList = array();
        $result = false;

        if ($this->registry->setting['mail']['usingAmazonses']) {

            $sesConf = array(
                'accessKey' => $this->registry->setting['amazon']['publickey'],
                'privateKey' => $this->registry->setting['amazon']['privatekey']
            );
            //cau hinh de goi mail bang amazon service
            $this->mailer = new \Vendor\Other\AmazonSes($sesConf);
            try {
                if ($this->toEmail != '') {
                    $toList = array($this->toEmail => $this->toName);
                }

                if (is_array($this->toArray)) {
                    if (count($this->toArray) == 1) {
                        $toList = array('to' => $this->toArray);
                    } elseif (count($this->toArray) > 1) {
                        //boi vi neu de bcc thi khong show email cua nguoi nhan
                        //ma lai show dia chi to (nguoi dau tien)
                        //do do set nguoi dau tien la he thong
                        //con lai de bcc
                        $toList = array('to' => array($this->fromEmail => 'Customer'), 'bcc' => $this->toArray);
                    }
                }

                if (count($toList) > 0) {
                    $result = $this->mailer->sendEmail(
                        $toList,
                        array('source' => array($this->fromEmail => $this->fromName)),
                        $this->mailSubject,
                        array('html' => $this->mailContents)
                    );
                } else {
                    $result = false;
                }

            } catch (\Exception $e) {
                $result = false;
                echo $e->getMessage() . '<br />Email: ';
                //var_dump($this->toArray);
                //die();
            }
        } else {
            //su dung co che goi mail thuong
            //neu enable smtp thi su dung smtp
            //ko thi su dung mail thuong
            $this->usingSMTP = $this->registry->setting['smtp']['enable'];
            $this->mailer = new \Vendor\Other\PhpMailer();

            $this->mailer->From = $this->fromEmail;
            $this->mailer->FromName = $this->fromName;
            $this->mailer->MsgHTML($this->mailContents);
            $this->mailer->Subject = $this->mailSubject;

            if ($this->toEmail != '') {
                $this->mailer->AddAddress($this->toEmail, $this->toName);
            }

            if (is_array($this->toArray)) {
                foreach ($this->toArray as $email => $fullname) {
                    $this->mailer->AddAddress($email, $fullname);
                }
            }

            $this->mailer->Port = 465;
            $this->mailer->AddReplyTo($this->fromEmail, $this->fromName);
            $this->mailer->CharSet = "utf-8";    //fix bug unicode in email sender, subject, text

            if ($this->usingSMTP) {
                $this->mailer->IsSMTP();
                $this->mailer->SMTPAuth = true;
                $this->mailer->SMTPSecure = 'ssl';

                if (is_array($this->registry->setting['smtp']['host'])
                    && is_array($this->registry->setting['smtp']['username'])
                    && is_array($this->registry->setting['smtp']['password'])) {
                    $robinCount = count($this->registry->setting['smtp']['host']);

                    //get random number to chose random host/username/pass group
                    $randomIndex = rand(0, $robinCount - 1);

                    $this->mailer->Host = $this->registry->setting['smtp']['host'][$randomIndex];
                    $this->mailer->Username = $this->registry->setting['smtp']['username'][$randomIndex];
                    $this->mailer->Password = $this->registry->setting['smtp']['password'][$randomIndex];
                } else {
                    //work with old site config
                    $this->mailer->Host = $this->registry->setting['smtp']['host'];
                    $this->mailer->Username = $this->registry->setting['smtp']['username'];
                    $this->mailer->Password = $this->registry->setting['smtp']['password'];
                }
            }
            $result = $this->mailer->Send();
        }

        return $result;
    }

    public function preview()
    {
        echo '<!doctype html>
                <html lang="en">
                    <head>
                        <meta charset="utf-8">
                        <title>[Email Preview] ' . $this->mailSubject . '</title>
                    </head>
                    <body>';

        echo '<div style="background:#fff;margin:20px;padding:10px;line-height:1.5;">
            <div>
                <div style="font-weight:bold;font-size:18px;">' . $this->mailSubject . '</div>
                <div><span style="color:#999;">From:</span> '
                    . $this->fromName . ' <span style="color:#999;">&lt;'
                    . $this->fromEmail . '&gt;</span></div>
                <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#999;">To:</span> '
                    . $this->toName . ' <span style="color:#999;">&lt;' . $this->toEmail . '&gt;</span></div>
                <div style="border-bottom:3px dotted #ddd;margin-bottom:20px;">&nbsp;</div>
            </div>
            <div>' . $this->mailContents . '</div>

        </div>';

        return true;
    }
}
