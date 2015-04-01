<?php

namespace Vendor\Other;

/**
 * Amazon SES API wrapper
 *
 * @copyright  Lionite LTD.
 * @package    Lionite
 * @author     Eran Galperin
 */
class AmazonSes
{
    /**
     * Amazon SES actions
     */
    const GET_SEND_QUOTA = 'GetSendQuota';
    const LIST_VERIFIED_EMAILS = 'ListVerifiedEmailAddresses';
    const GET_SEND_STATISTICS = 'GetSendStatistics';
    const DELETE_VERIFIED_EMAIL = 'DeleteVerifiedEmailAddress';
    const VERIFY_EMAIL_ADDRESS = 'VerifyEmailAddress';
    const SEND_RAW_EMAIL = 'SendRawEmail';
    const SEND_EMAIL = 'SendEmail';

    /**
     * Current Amazon SES action
     * - Default is GetSendQuota
     * @see Action constants at beginning of class
     * @var string
     */
    protected $_action = self::GET_SEND_QUOTA;

    /**
     * Amazon SES endpoint
     * @var string
     */
    protected $_endpoint = 'https://email.us-east-1.amazonaws.com';

    /**
     * Amazon access Key
     * @var string
     */
    protected $_accessKey = '';

    /**
     * Amazon private key
     * @var string
     */
    protected $_privateKey = '';

    /**
     * Throw exceptions
     * @var boolean
     */
    protected $_throwExceptions = true;

    /**
     * @var string Error message
     */
    protected $_error;

    /**
     * Constructor
     * - Configuration array may override default keys for multiple SES accounts.
     *   Parameter keys are 'accessKey' and 'privateKey'
     * - Exception throwing can be turned off by settings the 'throwExceptions' parameter to false
     *
     * @param array $config
     */
    public function __construct($config = array())
    {
        if (is_array($config) && !empty($config)) {
            foreach (array('accessKey','privateKey','throwExcpetions') as $key) {
                if (isset($config[$key])) {
                    $this->{'_' . $key} = $config[$key];
                }
            }
        }
    }

    /**
     * Send an Email
     *
     * @param array / string $recipients To Email address(es)
     * @param array / string $from       From Email address(es)
     * To / From parameters can either be Email strings or arrays of $email => $name.
     * For advanced recipient types - to,cc,bcc - use the type as an index for an array.
     * Example:
     * <code>$recipients = array(
     *		'to' => array('johndoe@gmail.com' => 'John Doe') ,
     *		'bcc' => array('janejoe@gmail.com' => 'Jane Joe')
     * );</code>
     *
     * @param string         $subject The subject line
     * @param string / array $body
     * Body can be either a string for a text body or an array for html or multiple parts body.
     * Use 'text' or 'html' as the index array for the type.
     * Example:
     * <code>$body = array(
     *		'text' => 'This is a text version of the body',
     *		'html' => 'This is an <b>HTML</b> version of the body'
     * );</code>
     *
     * @return boolean / string Success or error message
     */
    public function sendEmail($recipients, $from, $subject, $body)
    {
        $this->setAction(self::SEND_EMAIL);

        //Normalize To addresses
        if (!is_array($recipients)) {
            $recipients = array('to' => array($recipients));
        } elseif (!isset($recipients['to'])) {
            $recipients = array('to' => $recipients);
        }

        foreach (array('to','cc','bcc') as $key) {
            if (isset($recipients[$key])) {
                $i = 1;
                foreach ($recipients[$key] as $email => $name) {
                    $email = is_int($email) ? $name : $email;
                    $params['Destination.' . ucwords($key) . 'Addresses.member.' . ($i)] = $this ->_formatAddress($email, $name);
                    $i++;
                }
            }
        }

        //Normalize From addresses
        if (!is_array($from)) {
            $from = array('source' => array($from));
        } elseif (!isset($from['source'])) {
            $from = array('source' => key($from),'reply' => $from);
        }

        $fromEmail = key($from['source']);
        $fromEmail = is_int($fromEmail) ? reset($from['source']) : $fromEmail;
        $params['Source'] = $this->_formatAddress($fromEmail, reset($from['source']));
        if (isset($from['reply']) && is_array($from['reply'])) {
            $i = 1;
            foreach ($from['reply'] as $email => $name) {
                $email = is_int($email) ? $name : $email;
                $params['ReplyToAddresses.member.' . ($i)] = $this ->_formatAddress($email, $name);
                $i++;
            }
        }

        $params['Message.Subject.Data'] = $subject;
        if (!is_array($body)) {
            $body = array('text' => $body);
        }
        foreach (array('text','html') as $key) {
            if (isset($body[$key])) {
                $params['Message.Body.' . ucwords($key) . '.Data'] = $body[$key];
            }
        }

        $result = $this->request($params);
        if (isset($result['MessageId'])) {
            return true;
        }

        return false;
    }

    /**
     * Send a Raw Email message
     *
     * Composing the raw Email headers allows a larger degree of control over advanced options
     * such as Cc, Bcc, attachments, HTML mail and more. To be used by Email abstractions such as Zend_Mail
     *
     * @param  array / string $to      To Email address(es)
     * @param  string         $from    From Email address
     * @param  string         $body
     * @param  string         $headers
     * @return boolean        / string Success or error message
     */
    public function sendRawEmail($to, $from, $body, $headers)
    {
        $this->setAction(self::SEND_RAW_EMAIL);
        $to = is_array($to) ? $to : array($to);
        $params['Source'] = $from;
        $i = 1;
        foreach ($to as $email) {
            $params['Destination.ToAddresses.member.' . ($i)] = $email;
            $i++;
        }

        $params['RawMessage.Data'] = base64_encode($headers . "\r\n\r\n" . $body);
        $result = $this->request($params);
        if (isset($result['MessageId'])) {
            return true;
        }

        return false;
    }

    /**
     * Verify Email Address
     * - 'To' and 'From' Email addresses must be verified before use. 'To' addresses verification
     *   can be skipped if production access is granted from AWS (see link below)
     *
     * @link https://aws-portal.amazon.com/gp/aws/html-forms-controller/contactus/SESAccessRequest
     * @param  string $emailAddress Email address to verify
     * @return array
     */
    public function verifyEmail($emailAddress)
    {
        $this->setAction(self::VERIFY_EMAIL_ADDRESS);

        return $this->request(array('EmailAddress' => $emailAddress));
    }

    /**
     * Get Amazon SES account usage quotas
     *
     * @link http://docs.amazonwebservices.com/ses/latest/APIReference/API_GetSendQuota.html
     * @return array
     */
    public function getQuota()
    {
        $this->setAction(self::GET_SEND_QUOTA);

        return $this->request();
    }

    /**
     * Get Amazon SES account usage statistics
     *
     * @link http://docs.amazonwebservices.com/ses/latest/APIReference/API_GetSendStatistics.html
     * @return array
     */
    public function getStats()
    {
        $this-> setAction(self::GET_SEND_STATISTICS);
        $response = $this->request();

        return isset($response['SendDataPoints']) ? $response['SendDataPoints'] : false;
    }

    /**
     * Remove verified Email address
     *
     * @link http://docs.amazonwebservices.com/ses/latest/APIReference/API_DeleteVerifiedEmailAddress.html
     * @return array
     */
    public function removeVerifiedMail($email)
    {
        $this->setAction(self::DELETE_VERIFIED_EMAIL);

        return $this->request(array('EmailAddress' => $email));

    }

    /**
     * Get list of verified Email addresses
     *
     * @link http://docs.amazonwebservices.com/ses/latest/APIReference/API_ListVerifiedEmailAddresses.html
     * @return array
     */
    public function getVerifiedEmails()
    {
        $this->setAction(self::LIST_VERIFIED_EMAILS);
        $response = $this->request();

        return isset($response['VerifiedEmailAddresses']) ? $response['VerifiedEmailAddresses'] : false;
    }

    /**
     * Set API action
     * - Use class constants for the various API actions
     *
     * @link http://docs.amazonwebservices.com/ses/latest/APIReference/API_Operations.html
     * @param string $action
     */
    public function setAction($action)
    {
        $this->_action = $action;
    }

    /**
     * Perform an API request
     *
     * @param  array $params Additional request parameters
     * @return array API response
     */
    public function request($params = array())
    {
        $host = explode('://', $this->_endpoint);

        $params = array('Action' => $this->_action) + $params;
        $body = '';
        $i = 0;
        foreach ($params as $key => $val) {
            $i++;
            $body .= ($i > 1 ? '&' : '') . $key . '=' . rawurlencode($val);
        }

        $date = gmdate('D, d M Y H:i:s O');
        $headers = array(
            'Host' => $host[1],
            'Connection' => 'close',
            'Date' => $date,
            'X-Amzn-Authorization' => $this->_headerSignature($date),
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Content-Length' => strlen($body)
        );
        $request = "POST / HTTP/1.1\r\n";
        foreach ($headers as $key => $val) {
            $request .= $key . ': ' . $val . "\r\n";
        }

        $request .= "\r\n" . $body;
        $response = $this->_sendRequest($request, $host[1]);

        return $response !== false ? $this->toArray($response) : $response;
    }

    /**
     * Send request using socket streams
     * - Error supression is used to prevent the default behavior of socket functions
     *   throwing PHP errors when something goes wrong
     *
     * @param  string $request HTTP Request
     * @param  string $address Server name
     * @return string / false
     */
    protected function _sendRequest($request, $address)
    {
        $context = stream_context_create();
        $socket = @stream_socket_client('ssl://' . $address . ':443', $errno, $errstr, 10, STREAM_CLIENT_CONNECT, $context);
        if (is_resource($socket)) {
            if (@fwrite($socket, $request) === false) {
                return $this->_handleError('Error sending request to server');
            }

            $status = 200;
            $responseStart = false;
            $response = '';

            //Concatenate non-header content into a response string
            while (($line = @fgets($socket)) !== false) {
                if (strpos($line, 'HTTP') !== false) {
                    $parts = explode(' ', $line);
                    $status = $parts[1];
                }
                if ($responseStart) {
                    $response .= $line;
                }
                if (trim($line) == '') {
                    $responseStart = true;
                }
            }

            // HTTP status other than 200 indicates an API error
            if ($status != 200) {
                return $this->_handleError('API Request failed with the following error: ' . $response);
            }
            @fclose($socket);

            return $response;
        } else {
            return $this->_handleError('Could not create socket connection: ' . $errstr);
        }
    }

    /**
     * Format Amazon SES XML response as an array
     *
     * @param  string $response
     * @return array
     */
    public function toArray($response)
    {
        $doc = new \SimpleXMLElement($response);
        $node = $doc->{$this->_action . 'Result'};
        $response = $this->decomposeXml($node);

        return isset($response[$this->_action . 'Result']) ? $response[$this->_action . 'Result'] : $response;
    }

    /**
     * Get error message
     * - If silent mode is active (no Exceptions) this can be used to retreive the error message
     * @return string
     */
    public function getError()
    {
        return $this->_error;
    }

    /**
     * Recursively decompose Amazon SES response XML
     *
     * @param  mixed $node
     * @return mixed
     */
    protected function decomposeXml($node)
    {
        $name = $node->getName();
        if (is_array($node) || (is_object($node) && count($node) > 0)) {
            $data = array();
            foreach ($node as $key => $val) {
                $val = is_object($val) ?
                    $this ->decomposeXml($val) :
                    (string) $val;
                if (isset($data[$key])) {
                    if (!isset($data[0])) {
                        $data[0] = $data[$key];
                        unset($data[$key]);
                    }
                    $data[] = $val;
                } else {
                    if ($key == 'member') {
                        $data[] = $val;
                    } else {
                        $data[$key] = $val;
                    }
                }
            }

            return $data;
        } else {
            return (string) $node;
        }
    }

    /**
     * Handle error during API request
     * @param  string    $error
     * @throws Exception if exceptions are active
     * @return boolean
     */
    protected function _handleError($error)
    {
        $this->_error = $error;
        if ($this->_throwExceptions) {
            throw new \Exception('Exception: ' . $this->_error);
        } else {
            return false;
        }
    }

    /**
     * Return request header signature
     *
     * @param  string $date
     * @return string
     */
    protected function _headerSignature($date)
    {
        return 'AWS3-HTTPS AWSAccessKeyId=' . $this->_accessKey . ',Algorithm=HmacSHA256,Signature='
                . base64_encode(hash_hmac('sha256', $date, $this->_privateKey, true));
    }

    /**
     * Format Email address header
     *
     * @param  string $email
     * @param  string $name
     * @return string
     */
    protected function _formatAddress($email, $name = '')
    {
        if (empty($name) || $name == $email) {
            return $email;
        } else {
            return sprintf('"%s" <%s>', $name, $email); //utf8 bug
        }
    }
}
