<?php
/**
 * memcacheSessionHandler class
 * @class			memcacheSessionHandler
 * @version			0.1
 * @date			2013-04-02
 * @author			Azitabh
 * This class is used to store session data with memcache, it store in json the session to be used more easily in Node.JS
 */

require_once($_SERVER['DOCUMENT_ROOT'].'/dbConfig.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/function/login.php');

class memcacheSessionHandler{
    private $host = "127.0.0.1";
    private $port = 11211;
    private $lifetime = 3600;
    private $memcache = null;

    /**
     * Constructor
     */
    public function __construct(){
		ini_set('session.name', 'SESSIONID');
        $this->memcache = new Memcache;
        $this->memcache->connect($this->host, $this->port) or die("Error : Memcache is not ready");
		//session_set_save_handler($handler, TRUE);
		session_set_save_handler(
					array($this, "open"),
					array($this, "close"),
					array($this, "read"),
					array($this, "write"),
					array($this, "destroy"),
					array($this, "gc")
		);
	}

    /**
     * Destructor
     */
    public function __destruct(){
        session_write_close();
        $this->memcache->close();
    }

    /**
     * Open the session handler, set the lifetime ot session.gc_maxlifetime
     * @return boolean True if everything succeed
     */
    public function open($a, $b){
        $this->lifetime = ini_get('session.gc_maxlifetime');
        return true;
    }

    /**
     * Read the id
     * @param string $id The SESSID to search for
     * @return string The session saved previously
     */
    public function read($id){
        $tmp = $_SESSION;
        $_SESSION = json_decode($this->memcache->get("SESSIONID:{$id}"), true);
        if(isset($_SESSION) && !empty($_SESSION) && $_SESSION != null){		
            if($_SESSION['proptiger_login']['USER_ID'] and !$_SESSION['cms_session']['AdminLogin']){
		$_SESSION['cms_session'] = getNewCmsSession($_SESSION['proptiger_login']['USER_ID']);
            }
            $_SESSION = $_SESSION['cms_session'];
            $new_data = session_encode();
            $_SESSION = $tmp;
            return $new_data;
        }else{
            return "";
        }
    }

    /**
     * Write the session data, convert to json before storing
     * @param string $id The SESSID to save
     * @param string $data The data to store, already serialized by PHP
     * @return boolean True if memcached was able to write the session data
     */
    public function write($id, $data){
        $tmp = $_SESSION;
		
	$_SESSION['cms_session']=$_SESSION;
	if ($_SESSION['cms_session']['AdminLogin'] == "Y"){
            $_SESSION['proptiger_login'] = array(
                'USER_ID' => $_SESSION['adminId']
            );
	}
	unset($_SESSION['cms_session']['cms_session']);
		
	$new_data = json_decode($this->memcache->get("SESSIONID:{$id}"), true);
	$new_data['cms_session'] = $_SESSION['cms_session'];
	$new_data['proptiger_login'] = $_SESSION['proptiger_login'];
		
        $_SESSION = $tmp;
        return $this->memcache->set("SESSIONID:{$id}", json_encode($new_data), 0, $this->lifetime);
    }

    /**
     * Delete object in session
     * @param string $id The SESSID to delete
     * @return boolean True if memcached was able delete session data
     */
    public function destroy($id){
        return $this->memcache->delete("SESSIONID:{$id}");
    }

    /**
     * Close gc
     * @return boolean Always true
     */
    public function gc($x){
        return true;
    }

    /**
     * Close session
     * @return boolean Always true
     */
    public function close(){
        return true;
    }
}

new memcacheSessionHandler();
?>
