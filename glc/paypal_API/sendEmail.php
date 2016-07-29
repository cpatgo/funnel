<?php
/**
* class:         sendmail.class.php
* description:    class for sending HTML mails with attachments
* created:        21.02.2003
* last change:    19.12.2003
* author:        Günther Bauer <guenni1981@lycos.de>
* copyright:    Günther Bauer
*/
    class sendmail
    {
        // Variable deklarieren
        var $emailheader = "";
        var $textheader = "";
        var $textboundary = "";
        var $emailboundary = "";
        var $charset = "";
        var $betreff = "";
        var $empfaenger = "";
        var $attachment = array();
        var $cc = array();
        var $bcc = array();
        // Konstruktor
        function sendmail()
        {
            $this->textboundary = uniqid(time());
            $this->emailboundary = uniqid(time());
            $this->charset = "ISO-8859-1";
        }
        // Funktion zum setzen des CharSet´s
        function SetCharSet($char)
        {
            $this->charset = $char;
        }

        // Funktion die überprüft ob die E-Mailadresse korrekt ist
        function Validate_email($mailadresse)
        {
             if(!preg_match("/[a-z0-9_-]+(\.[a-z0-9_-]+)*@([0-9a-z][0-9a-z-]*[0-9a-z]\.)+([a-z]{2,4})/i",$mailadresse))
            {
                die('Die E-Mailadresse '.$mailadresse.' ist nicht gültig!!!');
            } 
            return $mailadresse;
        }
        // Von wem die Email kommt in den Header setzen
        function from($name,$email)
        {
            $this->emailheader .= 'From: '.$name.'<'.$email.'>'."\r\n";
        }
        // Funktion um den Adressaten anzugeben
        function to($to)
        {
            $this->empfaenger = $this->Validate_email($to);
        }
        // Funktion zum senden einer Kopie an Cc Empfänger
        function cc($kopie_an_empfaenger)
        {
            $this->cc[] = $kopie_an_empfaenger;
        }
        // Funktion zum senden einer Kopie an Bcc Empfänger
        function bcc($kopie_an_empfaenger)
        {
            $this->bcc[] = $kopie_an_empfaenger;
        }
        // Erstellt den Header der Mime-Mail
        function makeMimeMail()
        {
            if(count($this->cc) > 0)
            {
                $this->emailheader .= 'Cc: ';
                for($i=0;$i<count($this->cc);$i++)
                {
                    if($i > 0) $this->emailheader .= ',';
                    $this->emailheader .= $this->Validate_email($this->cc[$i]);
                }
                $this->emailheader .= "\r\n";
            }
            if(count($this->bcc) > 0)
            {
                $this->emailheader .= 'Bcc: ';
                for($j=0;$j<count($this->bcc);$j++)
                {
                    if($j > 0) $this->emailheader .= ',';
                    $this->emailheader .= $this->Validate_email($this->bcc[$j]);
                }
                $this->emailheader .= "\r\n";
            }
            $this->emailheader .= 'MIME-Version: 1.0'."\r\n";
        }
        // Funktion für den Betreff anzugeben
        function subject($subject)
        {
            $this->betreff = $subject;
        }
        // Textdaten in Email Header packen
        function text($text)
        {
            $this->textheader .= 'Content-Type: multipart/alternative; boundary="'.$this->textboundary.'"'."\r\n\r\n";
            $this->textheader .= '--'.$this->textboundary."\r\n";
            $this->textheader .= 'Content-Type: text/plain; charset="'.$this->charset.'"'."\r\n";
            $this->textheader .= 'Content-Transfer-Encoding: quoted-printable'."\r\n\r\n";
            $this->textheader .= strip_tags($text)."\r\n\r\n";
            $this->textheader .= '--'.$this->textboundary."\r\n";
            $this->textheader .= 'Content-Type: text/html; charset="'.$this->charset.'"'."\r\n";
            $this->textheader .= 'Content-Transfer-Encoding: quoted-printable'."\r\n\r\n";
            $this->textheader .= '<html><body>'.$text.'</body></html>'."\r\n\r\n";
            $this->textheader .= '--'.$this->textboundary.'--'."\r\n\r\n";
        }
        // Funktion zum anhängen für Attachments in der Email
        function attachment($datei)
        {
            // Überprüfen ob File Existiert
            if(is_file($datei))
            {
                // Header für Attachment erzeugen
                $attachment_header = '--'.$this->emailboundary."\r\n" ;
                $attachment_header .= 'Content-Type: application/octet-stream; name="'.basename($datei).'"'."\r\n";
                $attachment_header .= 'Content-Transfer-Encoding: base64'."\r\n";
                $attachment_header .= 'Content-Disposition: attachment; filename="'.basename($datei).'"'."\r\n\r\n";
                // Daten der Datei einlesen, in das BASE64 Format formatieren und auf max 72 Zeichen pro Zeile
                // aufteilen
                $file['inhalt'] = fread(fopen($datei,"rb"),filesize($datei));
                $file['inhalt'] = base64_encode($file['inhalt']);
                $file['inhalt'] = chunk_split($file['inhalt'],72);
                // Attachment mit Header in der Klassenvariable speichern
                $this->attachment[] = $attachment_header.$file['inhalt']."\r\n";
            }
            else
            {
                die('Die Datei "'.$datei.'" existiert nicht...'."\r\n");
            }
        }
        // Funktion zum erstellen des Kompletten Headers der Email
        // Senden der Email
        function send()
        {
            $this->makeMimeMail();
            $header = $this->emailheader;
            // Überprüfen ob Attachments angehängt wurden
            if(count($this->attachment)>0)
            {
                $header .= 'Content-Type: multipart/mixed; boundary="'.$this->emailboundary.'"'."\r\n\r\n";
                $header .= '--'.$this->emailboundary."\r\n";
                $header .= $this->textheader;
                if(count($this->attachment) > 0) $header .= implode("",$this->attachment);
                $header .= '--'.$this->emailboundary.'--';
            }
            else
            {
                $header .= $this->textheader;
            }
            // Versenden der Mail
            mail("$this->empfaenger",$this->betreff,"",$header);
            $this->deletememory();
        }
        // Diese Funktion ist nur zum testen
        function deletememory()
        {
            unset($this->emailheader);
            unset($this->textheader);
            unset($this->attachment);
        }
    }
?> 