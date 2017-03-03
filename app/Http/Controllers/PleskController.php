<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use GuzzleHttp\Client;


class PleskController extends Controller
{
    public $client;
    public $result;
    public $users;

    /**
     * Create a new controller instance.
     *
     * @return PleskController
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->client = new Client([
            'base_uri' => 'https://ustx2.safesecure.host:8443/',
//            'debug' => true,
            'headers' => [
                'KEY' => 'da5f7a1b-a687-a1f7-2637-0fe98f25af98',
                'HTTP_PRETTY_PRINT' => 'TRUE'
            ]
        ]);
    }

    /**
     * @return View
     */
    public function addUserForm()
    {
        return view('plesk.addUserForm');
    }

    /**
     * @return View
     */
    public function addUser()
    {
        if(strpos($_POST['username'], '@')) {
            $username = substr($_POST['username'], 0, strpos($_POST['username'], '@'));
        } else {
            $username = $_POST['username'];
        }
        $password = $_POST['password'];

        $xml = "<packet>\n\t<mail>\n\t\t<create>\n\t\t\t<filter>\n\t\t\t\t<site-id>56</site-id>\n\t\t\t\t<mailname>\n\t\t\t\t\t<name>$username</name>\n\t\t\t\t\t<mailbox>\n\t\t\t\t\t\t<enabled>true</enabled>\n\t\t\t\t\t\t<quota>-1</quota>\n\t\t\t\t\t</mailbox>\n\t\t\t\t\t<password>\n\t\t\t\t\t\t<value>$password</value>\n\t\t\t\t\t\t<type>plain</type>\n\t\t\t\t\t</password>\n\t\t\t\t\t<antivir>inout</antivir>\n\t\t\t\t\t<description/>\n\t\t\t\t</mailname>\n\t\t\t</filter>\n\t\t</create>\n\t</mail>\n</packet>";

        //\n\t<spamfilter>\n\t\t<set>\n\t\t\t<filter>\n\t\t\t\t<username>$username@epropertyrealty.com</username>\n\t\t\t</filter>\n\t\t\t<preferences>\n\t\t\t\t<action>move</action>\n\t\t\t</preferences>\n\t\t\t<enabled />\n\t\t</set>\n\t</spamfilter>

        $updateResult = $this->sendRequest($xml, true);
        if(!isset($updateResult->mail->create->result->errtext)) {
            $row = $updateResult->mail->create->result;
            $result[] = [
                'name' => (string) $row->mailname->name,
                'status' => (string) $row->status
            ];
            $this->enableSpamFilter([(string) $row->mailname->name]);
        } else {
            $result[] = [
                'name' => (string) $updateResult->mail->create->result->mailname->name,
                'status' => (string) $updateResult->mail->create->result->errtext
            ];

            $this->enableBoth([$username]);
        }

        return view('plesk.updateresults', ['results' => $result]);
    }

    /**
     * @return View
     */
    public function spamFilter()
    {
        $users = $this->getAllMailAccounts()->getUsers();
        $result = $this->enableSpamFilter($users);

        return view('plesk.updateresults', ['results' => $result]);
    }

    /**
     * @return View
     */
    public function antivirus()
    {
        $users = $this->getAllMailAccounts()->getUsers();
        $result = $this->enableAntivirus($users);

        return view('plesk.updateresults', ['results' => $result]);
    }

    /**
     * @return View
     */
    public function listUsers()
    {
        $this->getAllMailAccounts()->getUsers();

        return view('plesk.userList', ['users' => $this->users]);
    }

    public function homeView()
    {
        $this->getAllMailAccounts()->getUsers();

        return view('home', ['users' => $this->users]);
    }

    /**
     * @param array $users
     * @return array
     */
    public function enableSpamFilter(array $users)
    {
        $xml = "<packet>\n\t<spamfilter>\n\t\t<set>\n\t\t\t<filter>\n";

        foreach ($users as $user) {
            $xml .= "\t\t\t\t<username>$user@epropertyrealty.com</username>\n";
        }

        $xml .= "\t\t\t</filter>\n\t\t\t<preferences>\n\t\t\t\t<action>move</action>\n\t\t\t</preferences>\n\t\t\t<enabled />\n\t\t</set>\n\t</spamfilter>\n</packet>";

        $updateResult = $this->sendRequest($xml, true);
        foreach ($updateResult->spamfilter->set->result as $row) {
            $result[] = [
                'name' => (string) $row->{'filter-id'},
                'status' => (string) $row->status
            ];
        }

        return $result;
    }

    /**
     * @param array $users
     * @return array
     */
    public function enableAntivirus(array $users)
    {
        $xml = "<packet>\n\t<mail>\n\t\t<update>\n\t\t\t<set>\n\t\t\t\t<filter>\n\t\t\t\t\t<site-id>56</site-id>\n";
        foreach ($users as $user) {
            $xml .= "\t\t\t\t\t<mailname>\n\t\t\t\t\t\t<name>$user</name>\n\t\t\t\t\t\t<antivir>inout</antivir>\n\t\t\t\t\t</mailname>\n";
        }
        $xml .= "\t\t\t\t</filter>\n\t\t\t</set>\n\t\t</update>\n\t</mail>\n</packet>";

        $updateResult = $this->sendRequest($xml, true);
        foreach ($updateResult->mail->update->set->result as $row) {
            $result[] = [
                'name' => (string) $row->mailname->name,
                'status' => (string) $row->status
            ];
        }

        return $result;
    }

    /**
     * @param array $users
     * @return array
     */
    public function enableBoth(array $users)
    {
        $xml = "<packet>\n\t<mail>\n\t\t<update>\n\t\t\t<set>\n\t\t\t\t<filter>\n\t\t\t\t\t<site-id>56</site-id>\n";
        foreach ($users as $user) {
            $xml .= "\t\t\t\t\t<mailname>\n\t\t\t\t\t\t<name>$user</name>\n\t\t\t\t\t\t<antivir>inout</antivir>\n\t\t\t\t\t</mailname>\n";
        }
        $xml .= "\t\t\t\t</filter>\n\t\t\t</set>\n\t\t</update>\n\t</mail>\n\t<spamfilter>\n\t\t<set>\n\t\t\t<filter>\n";

        foreach ($users as $user) {
            $xml .= "\t\t\t\t<username>$user@epropertyrealty.com</username>\n";
        }

        $xml .= "\t\t\t</filter>\n\t\t\t<preferences>\n\t\t\t\t<action>move</action>\n\t\t\t</preferences>\n\t\t\t<enabled />\n\t\t</set>\n\t</spamfilter>\n</packet>";

        $updateResult = $this->sendRequest($xml, true);
        foreach ($updateResult->mail->update->set->result as $row) {
            $result[] = [
                'name' => (string) $row->mailname->name,
                'status' => (string) $row->status
            ];
        }

        return $result;
    }

    /**
     * @return $this
     */
    public function getAllMailAccounts()
    {
        $xml = "<packet>\n\t<mail>\n\t\t<get_info>\n\t\t\t<filter>\n\t\t\t\t<site-id>56</site-id>\n\t\t\t</filter>\n\t\t\t<mailbox /><mailbox-usage /><forwarding /><aliases />\n\t\t</get_info>\n\t</mail>\n</packet>";

        $this->result = $this->sendRequest($xml, true);
        return $this;
    }

    /**
     * @return $this
     */
    public function getUsers()
    {
        foreach ($this->result->mail->get_info->result as $row) {
            $name = (string) $row->mailname->name;
            $this->users[$name]['enabled'] = (string) $row->mailname->mailbox->enabled;
            $this->users[$name]['quota'] = (string) $row->mailname->mailbox->quota;
            $this->users[$name]['usage'] = (string) $row->mailname->mailbox->usage;
            $this->users[$name]['forwarding'] = (string) $row->mailname->forwarding->enabled;
            $this->users[$name]['address'] = (string) $row->mailname->forwarding->address;
            $this->users[$name]['alias'] = (array) $row->mailname->alias;
            $this->users[$name]['antivir'] = (string) $row->mailname->antivir;
        }
        return $this;
    }

    /**
     * @param string $xml
     * @param bool $returnXml
     * @return mixed
     */
    public function sendRequest($xml, $returnXml = false)
    {
        $result = $this->client->post('enterprise/control/agent.php', ['body' => $xml])->getBody()->getContents();

        if($returnXml) {
            $result = new \SimpleXMLElement($result);
        }
        return $result;
    }
}