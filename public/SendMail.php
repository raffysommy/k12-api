<?php
    
    function regmail($userArray){
            $url = 'https://api.sendgrid.com/';
            $username = 'chZe8myXW0';
            $password = 'KZbrejGKpo1Z7215';
            $request =  $url.'api/mail.send.json';
            $emailto=$userArray['email'];/*
            $template = file_get_contents("public/template.html");
            $template = str_replace('%name%', $userArray['name'], $template);
            $template = str_replace('%surname%',$userArray['surname'], $template);
            $template = str_replace('%user%', $userArray['user'], $template);
            $template = str_replace('%pass%', $userArray['password'], $template);*/
            $template = '<h1>Hi, '.$userArray['name'].' '.$userArray['surname'].'</h1>
                        <p class="lead">You have been registered to K12-Educationet Android App</p>
                        <p>Enjoy our application for learn everyday the math.</p>
                        <p>Credential:</p>
                        <p>Username: '.$userArray['user'].' <p>
                        <p>Password: '.$userArray['password'].' <p>
                        </p>';
            // Generate curl request
            $session = curl_init($request);
            $params = array(
                'api_user'  => $username,
                'api_key'   => $password,
                'to'        => $emailto,
                'subject'   => 'Educationet App Registration',
                'html'      => $template,
                'x-smtpapi' => '{
                                  "filters": {
                                    "templates": {
                                      "settings": {
                                        "enable": 1,
                                        "template_id": "1d975e78-f5a9-45b8-9037-d315b2b1c859"
                                      }
                                    }
                                  }
                                }',
                'from'      => 'admin@educationet.tk',
            );
            // Tell curl to use HTTP POST
            curl_setopt ($session, CURLOPT_POST, true);
            // Tell curl that this is the body of the POST
            curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
            // Tell curl not to return headers, but do return the response
            curl_setopt($session, CURLOPT_HEADER, false);
            // Tell PHP not to use SSLv3 (instead opting for TLS)
            curl_setopt($session, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
            curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
            
            // obtain response
            $response = curl_exec($session);
            curl_close($session);
            return($response);
        }

?>