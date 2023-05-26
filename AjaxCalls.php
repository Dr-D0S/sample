<?php



class AjaxCalls extends Controller
{
    private $validation;
    public function __construct()
    {

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $this->validation  = true;
        }
    }
    public function index()
    {
    }
    public function checkOrderStatus()
    {
        try {
            if (!$this->validation || empty($_SERVER['HTTP_REFERER']) || $_SERVER['HTTP_REFERER'] != 'https://sharksdevelopment-demo1.xyz/order') {
                throw new Exception('Invalid request');
            }
    
            $jwt = new Jwt();
            $jwtToken = $jwt->getAuthorizationHeaderToken();
            $ip = $jwt->getUserIpAddr();
    
            if (!$jwt->isJwtValid($jwtToken, 'order', $ip)) {
                throw new Exception('Invalid JWT token');
            }
    
            require_once '../app/helpers/encrypt_helper.php';
            $encryptHelper = new EncryptHelper();
            $idString = $_POST['ID'];
    
            if (!isset($idString)) {
                $returnArray[] = ['empty' => 'empty'];
                exit(json_encode($returnArray));
            }
    
            $decArray = $encryptHelper->decrypt($idString);
    
            if (!$decArray) {
                // Handle decryption error
                exit('NULL_');
            }
    
            $this->ajaxModel = $this->model('_ajax');
            $ids = $this->ajaxModel->orderStatus($decArray);
    
            if ($ids) {
                $arrayId = [];
                foreach ($ids as $id) {
                    $arrayId[] = $id;
                }
    
                $encArray = $encryptHelper->encrypt($arrayId);
    
                $returnArray[] = [
                    'status' => 'true',
                    'id' => $arrayId,
                    'ID' => $encArray
                ];
    
                exit(json_encode($returnArray));
            } elseif (empty($ids)) {
                $returnArray[] = ['status' => 'empty'];
                exit(json_encode($returnArray));
            } else {
                $returnArray[] = ['status' => 'err'];
                exit(json_encode($returnArray));
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function statusChange()
{
    try {
        if (!$this->validation || empty($_SERVER['HTTP_REFERER']) || $_SERVER['HTTP_REFERER'] != 'https://sharksdevelopment-demo1.xyz/admin/orders') {
            throw new Exception('Invalid request');
        }

        $jwt = new Jwt();
        $jwtToken = $jwt->getAuthorizationHeaderToken();
        $ip = $jwt->getUserIpAddr();

        if (!$jwt->isJwtValid($jwtToken, 'admin', $ip)) {
            // Handle invalid JWT token
            throw new Exception('Invalid JWT token');
        }

        $sanitizer = new Sanitizer();
        $id = $sanitizer->San_HashNum($_POST['id']);
        $status = $sanitizer->San_HashNum($_POST['status']);

        $ajaxModel = $this->model('_ajax_');
        $result = $ajaxModel->statusChange($id, $status);

        if ($result) {
            $returnArray[] = ['status' => 'true'];
            exit(json_encode($returnArray));
        } else {
            // Handle result failure
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}


public function statusReservationChange()
{
    try {
        if (!$this->validation || empty($_SERVER['HTTP_REFERER']) || $_SERVER['HTTP_REFERER'] != 'https://sharksdevelopment-demo1.xyz/admin/reservation') {
            throw new Exception('Invalid request');
        }

        $jwt = new Jwt();
        $jwtToken = $jwt->getAuthorizationHeaderToken();
        $ip = $jwt->getUserIpAddr();

        if (!$jwt->isJwtValid($jwtToken, 'admin', $ip)) {
            // Handle invalid JWT token
            throw new Exception('Invalid JWT token');
        }

        $sanitizer = new Sanitizer();
        $id = $sanitizer->San_HashNum($_POST['id']);
        $status = $sanitizer->San_HashNum($_POST['status']);

        $ajaxModel = $this->model('_ajax_');
        $result = $ajaxModel->statusReservationChange($id, $status);

        if ($result) {
            $returnArray[] = ['status' => 'true'];
            exit(json_encode($returnArray));
        } else {
            // Handle result failure
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

public function checker()
{
    try {
        if (!$this->validation || empty($_SERVER['HTTP_REFERER']) || $_SERVER['HTTP_REFERER'] != 'https://sharksdevelopment-demo1.xyz/admin/dashboard') {
            throw new Exception('Invalid request');
        }

        $jwt = new Jwt();
        $jwtToken = $jwt->getAuthorizationHeaderToken();
        $ip = $jwt->getUserIpAddr();

        $isJwtValid = $jwt->isJwtValid($jwtToken, 'admin', $ip);

        if ($isJwtValid) {
            $ajaxModel = $this->model('_ajax_');
            $result = $ajaxModel->orderChecker();
            $result_ = $ajaxModel->reserveChecker();

            if ($result && $result_) {
                $id = $result->id;
                $id_ = $result_->id;

                $id = is_numeric($id) ? $id : 0;
                $id_ = is_numeric($id_) ? $id_ : 0;

                $returnArray[] = [
                    'status' => 'true',
                    'result' => $id,
                    'result_' => $id_,
                ];
                exit(json_encode($returnArray));
            } else {
                $returnArray[] = [
                    'error_' => 'true',
                ];
                exit(json_encode($returnArray));
            }
        } else {
            //report
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

public function checkNewOrderAjax()
{
    try {
        if (!$this->validation || empty($_SERVER['HTTP_REFERER']) || $_SERVER['HTTP_REFERER'] != 'https://sharksdevelopment-demo1.xyz/admin/orders') {
            throw new Exception('Invalid request');
        }

        $jwt = new Jwt();
        $jwtToken = $jwt->getAuthorizationHeaderToken();
        $ip = $jwt->getUserIpAddr();
        $isJwtValid = $jwt->isJwtValid($jwtToken, 'admin', $ip);

        if ($isJwtValid) {
            $ajaxModel = $this->model('_ajax_');
            $result = $ajaxModel->orderChecker();

            if ($result) {
                $id = $result->id;
                $id = is_numeric($id) ? $id : 0;

                $returnArray[] = [
                    'status' => 'true',
                    'result' => $id,
                ];
                exit(json_encode($returnArray));
            } else {
                $returnArray[] = [
                    'error_' => 'true',
                ];
                exit(json_encode($returnArray));
            }
        } else {
            //report
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}


public function openingHoursReservation()
{
    try {
        if (!$this->validation || empty($_SERVER['HTTP_REFERER']) || $_SERVER['HTTP_REFERER'] != 'https://sharksdevelopment-demo1.xyz/reservation') {
            echo "Not AJAX";
            throw new Exception('');
        }

        $jwt_ = new jwt;
        $jwt = $jwt_->getAuthorizationHeaderTkn();
        $ip = $jwt_->getUserIpAddr();
        $is_jwt_valid = $jwt_->is_jwt_valid($jwt, 'time', $ip);

        if (!$is_jwt_valid) {
            exit('NULL_');
        }

        $this->ajaxModel = $this->model('_ajax');
        $read = $this->ajaxModel->openingMainReservation();
        $return_arr = array();

        if ($read) {
            $opening = null;
            $status = null;

            foreach ($read as $val) {
                if ($val->configName == 'openingJson') {
                    $opening = $val->configValue;
                }
                if ($val->configName == 'reservationStatus') {
                    $status = $val->configValue;
                }
            }

            date_default_timezone_set('Europe/London');
            $return_arr[] = array(
                "status" => 'true',
                "time_" => date("H:i"),
                "date_" => date("d/m/Y"),
                "status_" => $status,
                "opening_" => $opening
            );

            // Encoding array in JSON format
            echo json_encode($return_arr);
        } else {
            $return_arr[] = array(
                "status" => 'false',
            );

            // Encoding array in JSON format
            echo json_encode($return_arr);
        }
    } catch (Exception $e) {
        echo 'something went wrong';
        // echo '',  $e->getMessage(), "\n";
    }
}

public function openingHoursOrder()
{
    try {
        if (!$this->validation || empty($_SERVER['HTTP_REFERER']) || $_SERVER['HTTP_REFERER'] != 'https://sharksdevelopment-demo1.xyz/order') {
            echo "Not AJAX";
            throw new Exception('');
        }

        $jwt_ = new jwt;
        $jwt = $jwt_->getAuthorizationHeaderTkn();
        $ip = $jwt_->getUserIpAddr();
        $is_jwt_valid = $jwt_->is_jwt_valid($jwt, 'time', $ip);

        if (!$is_jwt_valid) {
            exit('NULL_');
        }

        $this->ajaxModel = $this->model('_ajax');
        $read = $this->ajaxModel->openingMain();
        $return_arr = array();

        if ($read) {
            $opening = null;
            $status = null;

            foreach ($read as $val) {
                if ($val->configName == 'openingJson') {
                    $opening = $val->configValue;
                }
                if ($val->configName == 'orderingStatus') {
                    $status = $val->configValue;
                }
            }

            date_default_timezone_set('Europe/London');
            $return_arr[] = array(
                "time_" => date("H:i"),
                "date_" => date("d/m/Y"),
                "status_" => $status,
                "opening_" => $opening
            );

            // Encoding array in JSON format
            echo json_encode($return_arr);
        } else {
            $return_arr[] = array(
                "error_" => 'false',
            );

            // Encoding array in JSON format
            echo json_encode($return_arr);
        }
    } catch (Exception $e) {
        echo 'something went wrong';
        // echo '',  $e->getMessage(), "\n";
    }
}

public function orderAjax()
{
    try {
        if (!$this->validation || empty($_SERVER['HTTP_REFERER']) || $_SERVER['HTTP_REFERER'] != 'https://sharksdevelopment-demo1.xyz/order') {
            echo "Not AJAX";
            throw new Exception('');
        }

        require_once '../app/helpers/encrypt_helper.php';

        $jwt_ = new jwt;
        $enc_ = new enc_dec;
        $jwt = $jwt_->getAuthorizationHeaderTkn();
        $ip = $jwt_->getUserIpAddr();
        $is_jwt_valid = $jwt_->is_jwt_valid($jwt, 'order', $ip);

        if (!$is_jwt_valid) {
            exit('NULL_');
        }

        function isJson($string)
        {
            json_decode($string);
            return (json_last_error() == JSON_ERROR_NONE);
        }

        if (isJson($_POST['Customer']) && isJson($_POST['Cart'])) {
            $customer = json_decode($_POST['Customer']);
            $cart =  json_decode($_POST['Cart']);
            $this->ajaxModel = $this->model('_ajax');
            $this->San = new Sanitizer;
            $name_ = $this->San->San_HashNum($customer[0]->name);
            $tel_ = $this->San->San_HashNum($customer[0]->mobile);
            $note_ = $this->San->San_HashNum($customer[0]->notes);
            date_default_timezone_set('Europe/London');
            $today = date('Y-m-d');
            $day = date("l");
            $now = new DateTime(date("H:i"));
            $open = false;
            $date_ = '';
            $orderTime_ = '';

            if ($customer[0]->date == 'asap' || $customer[0]->orderTime == 'asap') {
                $date_ = $today;
                $orderTime_ =  date_format($now, 'H:i');

                $open = $this->ajaxModel->checkStatus($day, $now);
            } else {

                if ($this->San->San_Time_($customer[0]->orderTime) && $this->San->San_Date($customer[0]->date)) {

                    $date_ = filter_var($customer[0]->date, FILTER_SANITIZE_STRING);
                    $orderTime_ = filter_var($customer[0]->orderTime, FILTER_SANITIZE_STRING);

                    if (strtotime($date_) <= strtotime(date('Y-m-d', strtotime('+10 days'))) && strtotime($date_) >= strtotime($today)) {

                        if (strtotime($date_) == strtotime($today)) {

                            $closingT = $this->ajaxModel->closingHour($day);
                            $closingT = new DateTime($closingT);
                            $orderT = new DateTime($orderTime_);
                            if ($orderT >= $now && $orderT < $closingT) {
                                $open = $this->ajaxModel->checkStatus($day, $orderT);
                            } else {

                                $returnArray[] = array(
                                    'open' => 'closed'
                                );
                                exit(json_encode($returnArray));
                            }
                        } else {
                            $day_ = date("l", strtotime($date_));
                            $time_ = new DateTime($orderTime_);
                            $open = $this->ajaxModel->checkStatus($day_, $time_);
                        }
                    } else {
                        exit('closed'); //more than 10 days
                        $returnArray[] = array(
                            'dayEr' => 'null',

                        );
                        exit(json_encode($returnArray));
                        //report
                    }
                } else {

                    $returnArray[] = array(
                        'status' => 'false',
                    );
                    exit(json_encode($returnArray));
                    //report
                }
            }

            if ($open) {

                $len = count($cart);
                if (!empty($name_) && !empty($tel_) && !empty($date_) && !empty($orderTime_) && $len > 0) {

                    $idAr = array();
                    foreach ($cart as $item) {
                        $id = $this->San->San_HashNum($item->id);
                        array_push($idAr, $id);
                    }

                    $prices = $this->ajaxModel->getPrices($idAr);

                    foreach ($cart as &$item) {
                        foreach ($prices as $price) {
                            if ($item->id == $price->itemID) {
                                $item->tags = $this->San->San_HashNum($item->tags);
                                $item->count = $this->San->San_Float($item->count);
                                $item->name = $price->name_item;
                                $item->price = $price->price_item;
                            }
                        }
                    }

                    $id = $this->ajaxModel->orderId();
                    $idString = $_POST['id'];
                    $idArr = array();

                    if (empty($idString)) {
                        array_push($idArr, $id);
                        $encArray = $enc_->enc($idArr);
                    } else {
                        $decArray = $enc_->dec($idString);
                        if ($decArray) {
                            $decArray = json_decode($decArray);
                            array_push($decArray, $id);
                            $encArray = $enc_->enc($decArray);
                        } else {
                            //report
                            //change token value
                            exit('NULL_');
                        }
                    }

                    $csrOrderInsert = $this->ajaxModel->insertOrder($id, $name_, $tel_, $date_, $orderTime_, $note_, $cart);

                    if ($csrOrderInsert) {
                        $returnArray[] = array(
                            'id' => $id,
                            'ID' => $encArray,
                        );
                        exit(json_encode($returnArray));
                    } else {

                        $returnArray[] = array(
                            'status' => 'false',
                        );
                        exit(json_encode($returnArray));
                    }
                } else {

                    $returnArray[] = array(
                        'empty' => 'empty',
                    );
                    exit(json_encode($returnArray));
                }
            }

            if (!$open) {

                $returnArray[] = array(
                    'open' => 'closed'
                );
                exit(json_encode($returnArray));
            }

            if (is_null($open)) {

                $returnArray[] = array(
                    'status' => 'false',
                );
                exit(json_encode($returnArray));
                //report
            }
        } else {
            $error = json_last_error_msg();
            //report
        }
    } catch (Exception $e) {
        echo 'something went wrong';
        // echo '',  $e->getMessage(), "\n";
    }
}
public function reservationAjax()
{
    try {
        if ($this->validation && !empty($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == 'https://sharksdevelopment-demo1.xyz/reservation') {
            require_once '../app/helpers/encrypt_helper.php';

            $jwt_ = new jwt;
            $enc_ = new enc_dec;
            $jwt = $jwt_->getAuthorizationHeaderTkn();

            $ip = $jwt_->getUserIpAddr();
            $is_jwt_valid = $jwt_->is_jwt_valid($jwt, 'order', $ip);
            if ($is_jwt_valid) {
                function isJson($string)
                {
                    json_decode($string);
                    return (json_last_error() == JSON_ERROR_NONE);
                }
                if (isJson($_POST['Customer'])) {
                    $customer = json_decode($_POST['Customer']);
                    $this->ajaxModel = $this->model('_ajax');
                    $this->San = new Sanitizer;
                    $name_ = $this->San->San_HashNum($customer[0]->name);
                    $tel_ = $this->San->San_HashNum($customer[0]->mobile);
                    $email_ = $email = filter_var($customer[0]->email, FILTER_SANITIZE_EMAIL);
                    $cover_ = $this->San->San_HashNum($customer[0]->cover);
                    $note_ = $this->San->San_HashNum($customer[0]->notes);
                    date_default_timezone_set('Europe/London');
                    $today = date('Y-m-d');
                    $day = date("l");
                    $now = new DateTime(date("H:i"));
                    $open = false;
                    $date_ = '';

                    if ($this->San->San_Time_($customer[0]->reserveTime) && $this->San->San_Date($customer[0]->date)) {
                        $date_ = filter_var($customer[0]->date, FILTER_SANITIZE_STRING);
                        $reserveTime_ = filter_var($customer[0]->reserveTime, FILTER_SANITIZE_STRING);

                        if (strtotime($date_) <= strtotime(date('Y-m-d', strtotime('+10 days'))) && strtotime($date_) >= strtotime($today)) {
                            if (strtotime($date_) == strtotime($today)) {
                                $closingT = $this->ajaxModel->closingHour($day);
                                $closingT = new DateTime($closingT);
                                $orderT = new DateTime($reserveTime_);
                                if ($orderT >= $now && $orderT < $closingT) {
                                    $open = $this->ajaxModel->checkStatus($day, $orderT);
                                } else {
                                    $returnArray[] = array(
                                        'open' => 'closed'
                                    );
                                    exit(json_encode($returnArray));
                                }
                            } else {
                                $day_ = date("l", strtotime($date_));
                                $time_ = new DateTime($reserveTime_);
                                $open = $this->ajaxModel->checkStatus($day_, $time_);
                            }
                        } else {
                            exit('closed'); //more than 10 days
                            $returnArray[] = array(
                                'dayEr' => 'null',
                            );
                            exit(json_encode($returnArray));
                            //report
                        }
                    } else {
                        $returnArray[] = array(
                            'status' => 'false',
                        );
                        exit(json_encode($returnArray));
                        //report
                    }
                    if ($open) {
                        if (!empty($name_) && !empty($tel_) && !empty($date_) && !empty($reserveTime_)) {
                            $id = $this->ajaxModel->reservationId();
                            $idString = $_POST['id'];
                            $idArr = array();
                            if (empty($idString)) {
                                array_push($idArr, $id);
                                $encArray = $enc_->enc($idArr);
                            } else {
                                $decArray = $enc_->dec($idString);
                                if ($decArray) {
                                    $decArray = json_decode($decArray);
                                    array_push($decArray, $id);
                                    $encArray = $enc_->enc($decArray);
                                } else {
                                    //report
                                    //change token value
                                    exit('NULL_');
                                }
                            }
                            $reservationInsert = $this->ajaxModel->insertReservation($id, $name_, $tel_, $email_, $cover_, $date_, $reserveTime_, $note_);
                            if ($reservationInsert) {
                                $returnArray[] = array(
                                    'status' => 'true',
                                    'id' => $id,
                                    'ID' => $encArray,
                                );
                                exit(json_encode($returnArray));
                            } else {
                                $returnArray[] = array(
                                    'status' => 'false',
                                );
                                exit(json_encode($returnArray));
                            }
                        } else {
                            $returnArray[] = array(
                                'empty' => 'empty',
                            );
                            exit(json_encode($returnArray));
                        }
                    }
                    if (!$open) {
                        $returnArray[] = array(
                            'open' => 'closed'
                        );
                        exit(json_encode($returnArray));
                    }
                    if (is_null($open)) {
                        $returnArray[] = array(
                            'status' => 'false',
                        );
                        exit(json_encode($returnArray));
                        //report
                    }
                } else {
                    $error = json_last_error_msg();
                    //report not json
                }
            } else {
                //report
                //change token value
                exit('NULL_');
            }
        } else {
            echo "Not AJAX";
            throw new Exception('');
        }
    } catch (Exception $e) {
        echo 'something went wrong';
        // echo '',  $e->getMessage(), "\n";
    }
}
public function sendHireMsg()
{
    try {
        if ($this->validation && !empty($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == 'https://sharksdevelopment-demo1.xyz/services') {
            if (isset($_POST['key'])) {
                if ($_POST['key'] == 'key') {
                    $this->San = new Sanitizer;
                    $name = $this->San->San_HashNum($_POST['cName']);
                    $num = $this->San->San_HashNum($_POST['cNumber']);
                    $msg = $this->San->San_HashNum($_POST['cMesg']);
                    if (!empty($name_) && !empty($tel_) && !empty($date_)) {
                        $to = ownEMAIL_;
                        $subject = $name . " Hire Message";
                        $body = "<div>" . 'My name is ' . $name . ' and my phone number is ' . $num . ' Message : ' . $msg . "</div>";

                        $headers = 'From: YourLogoName' . cEMAIL_ . "\r\n";
                        $headers .= 'Reply-To: ' . $to . "\r\n";
                        $headers .= 'X-Mailer: PHP/' . phpversion();
                        $headers .= "MIME-Version: 1.0\r\n";
                        $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
                        if (mail($to, $subject, $body, $headers)) {
                            exit('true');
                        } else {
                            exit('false');
                        }
                    } else {
                        exit('empty');
                    }
                } else {
                    //report
                }
            }
        } else {
            echo "Not AJAX";
            throw new Exception('');
        }
    } catch (Exception $e) {
        echo '',  $e->getMessage(), "\n";
    }
}
public function fetchItemsAjax()
{
    try {
        if ($this->validation && !empty($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == 'https://sharksdevelopment-demo1.xyz/admin/orders') {
            $jwt_ = new jwt;
            $jwt = $jwt_->getAuthorizationHeaderTkn();
            $ip = $jwt_->getUserIpAddr();
            $is_jwt_valid = $jwt_->is_jwt_valid($jwt, 'admin', $ip);
            if ($is_jwt_valid) {
                $this->ajaxModel = $this->model('_ajax_');
                $result = $this->ajaxModel->fetchItem();
                if ($result) {
                    $output = array(
                        'status' => 'true',
                        "data"    => $result
                    );
                    exit(json_encode($output));
                } else {
                    $output = array(
                        'status' => 'false',
                    );
                    exit(json_encode($output));
                }
            } else {
                //report
            }
        } else {
            echo "Not AJAX";
            throw new Exception('');
        }
    } catch (Exception $e) {
        echo '',  $e->getMessage(), "\n";
    }
}


public function fetch_Main_Order_Customer()
{
    try {
        if ($this->validation && !empty($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == 'https://sharksdevelopment-demo1.xyz/admin/orders') {
            require_once '../app/helpers/encrypt_helper.php';

            $jwt_ = new jwt;
            $enc_ = new enc_dec;
            $jwt = $jwt_->getAuthorizationHeaderTkn();
            $ip = $jwt_->getUserIpAddr();
            $is_jwt_valid = $jwt_->is_jwt_valid($jwt, 'admin', $ip);
            if ($is_jwt_valid) {
                $this->ajaxModel = $this->model('_ajax_');

                $result = $this->ajaxModel->fetchMainOrder();
                $items = $this->ajaxModel->fetchItem();

                if ($result && $items || empty($result) || empty($items)) {
                    $data = array();
                    foreach ($result as $row) {
                        $data[] = $row;
                    }

                    $data_ = array();
                    foreach ($items as $row) {
                        $data_[] = $row;
                    }

                    $output = array(
                        'status' => 'true',
                        "data"    => $data,
                        "data_"    => $data_
                    );
                    exit(json_encode($output));
                } else {
                    $output = array(
                        'status' => 'false',
                    );
                    exit(json_encode($output));
                }
            } else {
                //report
            }
        } else {
            echo "Not AJAX";
            throw new Exception('');
        }
    } catch (Exception $e) {
        echo $e->getMessage() . "\n";
    }
}

public function fetchOrderAjax()
{
    try {
        if ($this->validation && !empty($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == 'https://sharksdevelopment-demo1.xyz/admin/orders') {
            $this->San = new Sanitizer;

            $query1 = '';
            $columns = array('order_d', 'order_t', 'order_ID', 'name_csr', 'notes_csr', 'tel_csr', 'order_Status', 'time_Stamp');

            $query = "SELECT * FROM orders_csr";

            if (isset($_POST["search"]["value"])) {
                $query .= ' WHERE name_csr LIKE "%' . $this->San->San_HashNum($_POST["search"]["value"]) . '%" OR order_ID LIKE "%' . $this->San->San_HashNum($_POST["search"]["value"]) . '%" OR tel_csr LIKE "%' . $this->San->San_HashNum($_POST["search"]["value"]) . '%" ';
            }

            if (isset($_POST["order"])) {
                $query .= 'ORDER BY order_d, order_t ASC';
            } else {
                $query .= 'ORDER BY order_d, order_t ASC';
            }

            if ($_POST["length"] != -1) {
                $query1 = 'LIMIT ' . $this->San->San_HashNum($_POST['start']) . ', ' . $this->San->San_HashNum($_POST['length']);
            }

            $this->ajaxModel = $this->model('_ajax_');
            $number_filter_row = $this->ajaxModel->countOrder($query . $query1);
            $result = $this->ajaxModel->fetchOrder($query);

            $data = array();
            foreach ($result as $row) {
                $data[] = $row;
            }

            $output = array(
                "draw"    => intval($_POST["draw"]),
                "recordsTotal"  =>  $this->ajaxModel->get_all_orders(),
                "recordsFiltered" => $number_filter_row,
                "data"    => $data
            );

            echo json_encode($output);
        } else {
            echo "Not AJAX";
            throw new Exception('');
        }
    } catch (Exception $e) {
        echo $e->getMessage() . "\n";
    }
}

public function fetchUpdateAjax()
{
    try {
        if ($this->validation && !empty($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == 'https://sharksdevelopment-demo1.xyz/admin/support') {
            $this->San = new Sanitizer;

            $this->ajaxModel = $this->model('_ajax_');
            $number_filter_row = $this->ajaxModel->countUpdate();
            $result = $this->ajaxModel->fetchUpdate();

            $data = array();
            foreach ($result as $row) {
                $data[] = $row;
            }

            $output = array(
                "draw"    => intval($_POST["draw"]),
                "recordsTotal"  =>  $this->ajaxModel->get_all_orders(),
                "recordsFiltered" => $number_filter_row,
                "data"    => $data
            );

            echo json_encode($output);
        } else {
            echo "Not AJAX";
            throw new Exception('');
        }
    } catch (Exception $e) {
        echo $e->getMessage() . "\n";
    }
}

public function cancelOrderAjax()
{
    try {
        if ($this->validation && !empty($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == 'https://sharksdevelopment-demo1.xyz/admin/orders') {
            $jwt_ = new jwt;
            $jwt = $jwt_->getAuthorizationHeaderTkn();
            $ip = $jwt_->getUserIpAddr();
            $is_jwt_valid = $jwt_->is_jwt_valid($jwt, 'admin', $ip);

            if ($is_jwt_valid) {
                $this->San = new Sanitizer;
                $id = $this->San->San_HashNum($_POST['id']);
                $this->ajaxModel = $this->model('_ajax_');
                $deleted = $this->ajaxModel->cancelOrder($id);

                if ($deleted) {
                    $returnArray[] = array(
                        'status' => 'true',
                    );
                    exit(json_encode($returnArray));
                } else {
                    $returnArray[] = array(
                        'status' => 'false',
                    );
                    exit(json_encode($returnArray));
                }
            } else {
                //report
            }
        } else {
            echo "Not AJAX";
            throw new Exception('');
        }
    } catch (Exception $e) {
        echo $e->getMessage() . "\n";
    }
}






public function cancelReservationAjax()
{
    try {
        if ($this->validation && !empty($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == 'https://sharksdevelopment-demo1.xyz/admin/reservation') {
            $jwt_ = new jwt;
            $jwt = $jwt_->getAuthorizationHeaderTkn();
            $ip = $jwt_->getUserIpAddr();
            $is_jwt_valid = $jwt_->is_jwt_valid($jwt, 'admin', $ip);

            if ($is_jwt_valid) {
                $this->San = new Sanitizer;
                $id = $this->San->San_HashNum($_POST['id']);
                $this->ajaxModel = $this->model('_ajax_');
                $deleted = $this->ajaxModel->cancelreservation($id);

                if ($deleted) {
                    $returnArray[] = array(
                        'status' => 'true',
                    );
                    exit(json_encode($returnArray));
                } else {
                    $returnArray[] = array(
                        'status' => 'false',
                    );
                    exit(json_encode($returnArray));
                }
            } else {
                //report
            }
        } else {
            echo "Not AJAX";
            throw new Exception('');
        }
    } catch (Exception $e) {
        echo $e->getMessage() . "\n";
    }
}

public function deleteMenuItemAjax()
{
    try {
        if ($this->validation && !empty($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == 'https://sharksdevelopment-demo1.xyz/admin/menu') {
            $jwt_ = new jwt;
            $jwt = $jwt_->getAuthorizationHeaderTkn();
            $ip = $jwt_->getUserIpAddr();
            $is_jwt_valid = $jwt_->is_jwt_valid($jwt, 'admin', $ip);

            if ($is_jwt_valid) {
                $this->ajaxModel = $this->model('_ajax_');
                $this->San = new Sanitizer;
                $id = $this->San->sanString($_POST['id']);
                $img = $this->San->sanString($_POST['img']);

                $updated = $this->ajaxModel->deleteMenuItem($id);

                if ($updated) {
                    $trimLink = str_replace('menu/', '', $img);
                    unlink($img);
                    unlink($trimLink);
                    $returnArray[] = array(
                        'status' => 'true',
                    );
                    exit(json_encode($returnArray));
                } else {
                    $returnArray[] = array(
                        'status' => 'false',
                    );
                    exit(json_encode($returnArray));
                }
            } else {
                //report
            }
        } else {
            echo "Not AJAX";
            throw new Exception('');
        }
    } catch (Exception $e) {
        echo $e->getMessage() . "\n";
    }
}

public function orderControl()
{
    try {
        if ($this->validation && !empty($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == 'https://sharksdevelopment-demo1.xyz/admin/dashboard') {
            $jwt_ = new jwt;
            $jwt = $jwt_->getAuthorizationHeaderTkn();
            $ip = $jwt_->getUserIpAddr();
            $is_jwt_valid = $jwt_->is_jwt_valid($jwt, 'admin', $ip);

            if ($is_jwt_valid) {
                $this->ajaxModel = $this->model('_ajax_');

                if ($_POST['status'] == 'checked') {
                    $updated = $this->ajaxModel->updateStatus('True');
                } elseif ($_POST['status'] == 'unchecked') {
                    $updated = $this->ajaxModel->updateStatus('False');
                } else {
                    //report
                    $returnArray[] = array(
                        'error' => 'true'
                    );
                    exit(json_encode($returnArray));
                }
                if ($updated) {
                    $returnArray[] = array(
                        'status' => 'true',
                        'option' => $_POST['status']
                    );
                    exit(json_encode($returnArray));
                } else {
                    $returnArray[] = array(
                        'status' => 'false',
                        'option' => $_POST['status']
                    );
                    exit(json_encode($returnArray));
                }
            } else {
                //report
            }
        } else {
            echo "Not AJAX";
            throw new Exception('');
        }
    } catch (Exception $e) {
        echo $e->getMessage() . "\n";
    }
}

public function reservingControl()
{
    try {
        if ($this->validation && !empty($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == 'https://sharksdevelopment-demo1.xyz/admin/dashboard') {
            $jwt_ = new jwt;
            $jwt = $jwt_->getAuthorizationHeaderTkn();
            $ip = $jwt_->getUserIpAddr();
            $is_jwt_valid = $jwt_->is_jwt_valid($jwt, 'admin', $ip);

            if ($is_jwt_valid) {
                $this->ajaxModel = $this->model('_ajax_');

                if ($_POST['status'] == 'checked') {
                    $updated = $this->ajaxModel->updateReservation('True');
                } elseif ($_POST['status'] == 'unchecked') {
                    $updated = $this->ajaxModel->updateReservation('False');
                } else {
                    //report
                    $returnArray[] = array(
                        'error' => 'true'
                    );
                    exit(json_encode($returnArray));
                }
                if ($updated) {
                    $returnArray[] = array(
                        'status' => 'true',
                        'option' => $_POST['status']
                    );
                    exit(json_encode($returnArray));
                } else {
                    $returnArray[] = array(
                        'status' => 'false',
                        'option' => $_POST['status']
                    );
                    exit(json_encode($returnArray));
                }
            } else {
                //report
            }
        } else {
            echo "Not AJAX";
            throw new Exception('');
        }
    } catch (Exception $e) {
        echo $e->getMessage() . "\n";
    }
}
public function editMenuCatAjax()
{
    try {
        if ($this->validation && !empty($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == 'https://sharksdevelopment-demo1.xyz/admin/menu') {
            $jwt_ = new jwt;
            $jwt = $jwt_->getAuthorizationHeaderTkn();
            $ip = $jwt_->getUserIpAddr();
            $is_jwt_valid = $jwt_->is_jwt_valid($jwt, 'admin', $ip);

            if ($is_jwt_valid) {
                $this->San = new Sanitizer;
                $oldCat = $this->San->San_HashNum($_POST['oldCat']);
                $newCat = $this->San->San_HashNum($_POST['newCat']);
                $this->ajaxModel = $this->model('_ajax_');
                $updated = $this->ajaxModel->updateCat($oldCat, $newCat);
                $updatedMenu = $this->ajaxModel->updateCatMenu($oldCat, $newCat);

                if ($updated && $updatedMenu) {
                    $returnArray[] = array(
                        'status' => 'true',
                    );
                    exit(json_encode($returnArray));
                } else {
                    $returnArray[] = array(
                        'status' => 'false',
                    );
                    exit(json_encode($returnArray));
                }
            } else {
                //report
            }
        } else {
            echo "Not AJAX";
            throw new Exception('');
        }
    } catch (Exception $e) {
        echo $e->getMessage() . "\n";
    }
}

public function mainMenu()
{
    try {
        if ($this->validation && !empty($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == 'https://sharksdevelopment-demo1.xyz/admin/menu') {
            $jwt_ = new jwt;
            $jwt = $jwt_->getAuthorizationHeaderTkn();
            $ip = $jwt_->getUserIpAddr();
            $is_jwt_valid = $jwt_->is_jwt_valid($jwt, 'admin', $ip);

            if ($is_jwt_valid) {
                $this->menuModel = $this->model('_ajax_');
                $items = $this->menuModel->mainMenu();
                $cat = $this->menuModel->mainCat();
                $meat = $this->menuModel->meatCat();
                $veg = $this->menuModel->vegCat();
                $this->orderFormat = new orderMenuFormat;
                $orderContents = $this->orderFormat->orderStyling($items, $cat, $meat, $veg);

                if ($orderContents) {
                    $returnArray[] = array(
                        'status' => 'true',
                    );
                    exit(json_encode($returnArray));
                } else {
                    $returnArray[] = array(
                        'status' => 'false',
                    );
                    exit(json_encode($returnArray));
                }
            } else {
                //report
            }
        } else {
            echo "Not AJAX";
            throw new Exception('');
        }
    } catch (Exception $e) {
        echo $e->getMessage() . "\n";
    }
}








public function addMenuCatAjax()
{
    try {
        if ($this->validation && !empty($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == 'https://sharksdevelopment-demo1.xyz/admin/menu') {
            $jwt_ = new jwt;
            $jwt = $jwt_->getAuthorizationHeaderTkn();
            $ip = $jwt_->getUserIpAddr();
            $is_jwt_valid = $jwt_->is_jwt_valid($jwt, 'admin', $ip);

            if ($is_jwt_valid) {
                if (!empty($_POST['category'])) {
                    $this->San = new Sanitizer;
                    $catName = $this->San->San_HashNum($_POST['category']);
                    $this->ajaxModel = $this->model('_ajax_');
                    $updated = $this->ajaxModel->addMenuCat($catName);

                    if ($updated) {
                        $returnArray[] = array(
                            'status' => 'true',
                        );
                        exit(json_encode($returnArray));
                    } else {
                        $returnArray[] = array(
                            'status' => 'false',
                        );
                        exit(json_encode($returnArray));
                    }
                }
            } else {
                //report
            }
        } else {
            echo "Not AJAX";
            throw new Exception('');
        }
    } catch (Exception $e) {
        echo $e->getMessage() . "\n";
    }
}

public function deleteCatAjax()
{
    try {
        if ($this->validation && !empty($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == 'https://sharksdevelopment-demo1.xyz/admin/menu') {
            $jwt_ = new jwt;
            $jwt = $jwt_->getAuthorizationHeaderTkn();
            $ip = $jwt_->getUserIpAddr();
            $is_jwt_valid = $jwt_->is_jwt_valid($jwt, 'admin', $ip);

            if ($is_jwt_valid) {
                $this->San = new Sanitizer;
                $catName = $this->San->San_HashNum($_POST['cat']);
                $this->ajaxModel = $this->model('_ajax_');
                $updated = $this->ajaxModel->deleteCat($catName);
                $updatedMenu = $this->ajaxModel->deleteMenuCat($catName);

                if ($updated && $updatedMenu) {
                    $returnArray[] = array(
                        'status' => 'true',
                    );
                    exit(json_encode($returnArray));
                } else {
                    $returnArray[] = array(
                        'status' => 'false',
                    );
                    exit(json_encode($returnArray));
                }
            } else {
                //report
            }
        } else {
            echo "Not AJAX";
            throw new Exception('');
        }
    } catch (Exception $e) {
        echo $e->getMessage() . "\n";
    }
}

public function updateMenuCatAjax()
{
    try {
        if ($this->validation && !empty($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == 'https://sharksdevelopment-demo1.xyz/admin/menu') {
            $jwt_ = new jwt;
            $jwt = $jwt_->getAuthorizationHeaderTkn();
            $ip = $jwt_->getUserIpAddr();
            $is_jwt_valid = $jwt_->is_jwt_valid($jwt, 'admin', $ip);

            if ($is_jwt_valid) {
                $this->San = new Sanitizer;
                $catNames = $this->San->San_HashNum($_POST['category']);
                $catNames = trim($catNames, '#');
                $catNames = explode("#", $catNames);
                $this->ajaxModel = $this->model('_ajax_');
                $updated = $this->ajaxModel->updateMenuCat($catNames);

                if ($updated) {
                    $returnArray[] = array(
                        'status' => 'true',
                    );
                    exit(json_encode($returnArray));
                } else {
                    $returnArray[] = array(
                        'status' => 'false',
                    );
                    exit(json_encode($returnArray));
                }
            } else {
                //report
            }
        } else {
            echo "Not AJAX";
            throw new Exception('');
        }
    } catch (Exception $e) {
        echo $e->getMessage() . "\n";
    }
}






public function addMenuMeatAjax()
{
    try {
        if ($this->validation && !empty($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == 'https://sharksdevelopment-demo1.xyz/admin/menu') {
            
            $jwt_ = new jwt;
            $jwt = $jwt_->getAuthorizationHeaderTkn();
            $ip = $jwt_->getUserIpAddr();
            $is_jwt_valid = $jwt_->is_jwt_valid($jwt, 'admin', $ip);
            if ($is_jwt_valid) {
                $this->San = new Sanitizer;
                $meatName = $this->San->San_HashNum($_POST['meat']);
                if (!empty($meatName)) {
                    $this->ajaxModel = $this->model('_ajax_');
                    $updated = $this->ajaxModel->addMeat($meatName);
                    if ($updated) {
                        $returnArray[] = array(
                            'status' => 'true',
                        );
                        exit(json_encode($returnArray));
                    } else {
                        $returnArray[] = array(
                            'status' => 'false',
                        );
                        exit(json_encode($returnArray));
                    }
                }
            } else {
                //report
            }
        } else {
            echo "Not AJAX";
            throw new Exception('');
        }
    } catch (Exception $e) {
        echo $e->getMessage() . "\n";
    }
}

public function deleteMeatCatAjax()
{
    try {
        if ($this->validation && !empty($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == 'https://sharksdevelopment-demo1.xyz/admin/menu') {

            
            $jwt_ = new jwt;
            $jwt = $jwt_->getAuthorizationHeaderTkn();
            $ip = $jwt_->getUserIpAddr();
            $is_jwt_valid = $jwt_->is_jwt_valid($jwt, 'admin', $ip);
            if ($is_jwt_valid) {
                $this->San = new Sanitizer;
                $meatName = $this->San->San_HashNum($_POST['meat']);
                if (!empty($meatName)) {
                    $this->ajaxModel = $this->model('_ajax_');
                    $updated = $this->ajaxModel->deleteMeat($meatName);

                    if ($updated) {
                        $returnArray[] = array(
                            'status' => 'true',
                        );
                        exit(json_encode($returnArray));
                    } else {
                        $returnArray[] = array(
                            'status' => 'false',
                        );
                        exit(json_encode($returnArray));
                    }
                }
            } else {
                //report
            }
        } else {
            echo "Not AJAX";
            throw new Exception('');
        }
    } catch (Exception $e) {
        echo $e->getMessage() . "\n";
    }
}

public function addMenuVegAjax()
{
    try {
        if ($this->validation && !empty($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == 'https://sharksdevelopment-demo1.xyz/admin/menu') {

            
            $jwt_ = new jwt;
            $jwt = $jwt_->getAuthorizationHeaderTkn();
            $ip = $jwt_->getUserIpAddr();
            $is_jwt_valid = $jwt_->is_jwt_valid($jwt, 'admin', $ip);
            if ($is_jwt_valid) {
                $this->San = new Sanitizer;
                $saladName = $this->San->San_HashNum($_POST['salad']);
                if (!empty($saladName)) {
                    $this->ajaxModel = $this->model('_ajax_');
                    $updated = $this->ajaxModel->addSalad($saladName);
                    if ($updated) {
                        $returnArray[] = array(
                            'status' => 'true',
                        );
                        exit(json_encode($returnArray));
                    } else {
                        $returnArray[] = array(
                            'status' => 'false',
                        );
                        exit(json_encode($returnArray));
                    }
                }
            } else {
                //report
            }
        } else {
            echo "Not AJAX";
            throw new Exception('');
        }
    } catch (Exception $e) {
        echo $e->getMessage() . "\n";
    }
}

public function deleteVegCatAjax()
{
    try {
        if ($this->validation && !empty($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == 'https://sharksdevelopment-demo1.xyz/admin/menu') {


            
            $jwt_ = new jwt;
            $jwt = $jwt_->getAuthorizationHeaderTkn();
            $ip = $jwt_->getUserIpAddr();
            $is_jwt_valid = $jwt_->is_jwt_valid($jwt, 'admin', $ip);
            if ($is_jwt_valid) {
                $this->San = new Sanitizer;
                $saladName = $this->San->San_HashNum($_POST['salad']);
                if (!empty($saladName)) {
                    $this->ajaxModel = $this->model('_ajax_');
                    $updated = $this->ajaxModel->deleteSalad($saladName);

                    if ($updated) {
                        $returnArray[] = array(
                            'status' => 'true',
                        );
                        exit(json_encode($returnArray));
                    } else {
                        $returnArray[] = array(
                            'status' => 'false',
                        );
                        exit(json_encode($returnArray));
                    }
                }
            } else {
                //report
            }
        } else {
            echo "Not AJAX";
            throw new Exception('');
        }
    } catch (Exception $e) {
        echo $e->getMessage() . "\n";
    }
}

public function addMenuItemAjax()
{
    try {
        if ($this->validation && !empty($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == 'https://sharksdevelopment-demo1.xyz/admin/menu') {

            
            $jwt_ = new jwt;
            $jwt = $jwt_->getAuthorizationHeaderTkn();
            $ip = $jwt_->getUserIpAddr();
            $is_jwt_valid = $jwt_->is_jwt_valid($jwt, 'admin', $ip);
            if ($is_jwt_valid) {
                if (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['price']) && isset($_POST['category'])) {
                    define('UPLOAD_DIR', 'img/');
                    $this->San = new Sanitizer;
                    $name = $this->San->sanString($_POST['name']);
                    $description = $this->San->sanString($_POST['description']);
                    $price = $this->San->sanString($_POST['price']);
                    $tags = $this->San->sanString($_POST['tags']);
                    $category = $this->San->sanString($_POST['category']);
                    $activate = $this->San->sanString($_POST['activate']);
                    $live = $this->San->sanString($_POST['live']);
                    $croped_image = $this->San->sanString($_POST['image']);
                    list($type, $croped_image) = explode(';', $croped_image);
                    list(, $croped_image)      = explode(',', $croped_image);
                    $croped_image = base64_decode($croped_image);
                    $image_name = 'img' . date('m-d-Y_hia') . '.png';
                    file_put_contents('img/' . $image_name, $croped_image);
                    $link = 'img/' . $image_name;
                    $link_ = 'img/menu/' . $image_name;
                    $this->resizer = new Resize($link);
                    $this->resizer->resizeImage(100, 100, 0);
                    $this->resizer->saveImage($link_, 100);
                    $this->ajaxModel = $this->model('_ajax_');
                    $updated = $this->ajaxModel->addMenuItem($name, $description, $price, $tags, $category, $activate, $link_, $live);
                    if ($updated) {
                        $returnArray[] = array(
                            'status' => 'true',
                        );
                        exit(json_encode($returnArray));
                    } else {
                        $returnArray[] = array(
                            'status' => 'false',
                        );
                        exit(json_encode($returnArray));
                    }
                }
            } else {
                //report
            }
        } else {
            echo "Not AJAX"; //report
            throw new Exception('');
        }
    } catch (Exception $e) {
        echo $e->getMessage() . "\n"; //report to admin
    }
}
public function updateMenuItemAjax()
{
    try {
        if ($this->validation && !empty($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == 'https://sharksdevelopment-demo1.xyz/admin/menu') {
            $jwt = new Jwt;
            $authorizationHeader = $jwt->getAuthorizationHeaderTkn();
            $ip = $jwt->getUserIpAddr();
            $isJwtValid = $jwt->isJwtValid($authorizationHeader, 'admin', $ip);
            if ($isJwtValid) {
                if (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['price']) && isset($_POST['category'])) {
                    define('UPLOAD_DIR', 'img/');
                    $sanitizer = new Sanitizer;
                    $id = $sanitizer->sanString($_POST['id']);
                    $name = $sanitizer->sanString($_POST['name']);
                    $oldImg = $sanitizer->sanString($_POST['oldImg']);
                    $description = $sanitizer->sanString($_POST['description']);
                    $price = $sanitizer->sanString($_POST['price']);
                    $tags = $sanitizer->sanString($_POST['tags']);
                    $category = $sanitizer->sanString($_POST['category']);
                    $activate = $sanitizer->sanString($_POST['activate']);
                    $live = $sanitizer->sanString($_POST['live']);
                    $cropedImage =  $sanitizer->sanString($_POST['image']);
                    list($type, $cropedImage) = explode(';', $cropedImage);
                    list(, $cropedImage) = explode(',', $cropedImage);
                    $cropedImage = base64_decode($cropedImage);

                    $imageName = 'img' . date('m-d-Y_hia') . '.png';
                    file_put_contents('img/' . $imageName, $cropedImage);
                    $link = 'img/' . $imageName;
                    $link_ = 'img/menu/' . $imageName;

                    $resizer = new Resize($link);
                    $resizer->resizeImage(100, 100, 0);
                    $resizer->saveImage($link_, 100);
                    $ajaxModel = $this->model('_ajax_');
                    $trimLink = str_replace('menu/', '', $oldImg);
                    unlink($oldImg);
                    unlink($trimLink);

                    $updated = $ajaxModel->updateMenuItem($id, $name, $description, $price, $tags, $category, $activate, $link_, $live);
                    if ($updated) {
                        $returnArray[] = array(
                            'status' => 'true',
                        );
                        exit(json_encode($returnArray));
                    } else {
                        $returnArray[] = array(
                            'status' => 'false',
                        );
                        exit(json_encode($returnArray));
                    }
                } else {
                    $returnArray[] = array(
                        'status' => 'empty',
                    );
                    exit(json_encode($returnArray));
                }
            } else {
                // Report
            }
        } else {
            echo "Not AJAX"; // Report
            throw new Exception('');
        }
    } catch (Exception $e) {
        echo '',  $e->getMessage(), "\n"; // Report to admin
    }
}
public function _updateMenuItemAjax()
{
    try {
        if ($this->validation && !empty($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == 'https://sharksdevelopment-demo1.xyz/admin/menu') {
            $jwt = new Jwt;
            $authorizationHeader = $jwt->getAuthorizationHeaderTkn();
            $ip = $jwt->getUserIpAddr();
            $isJwtValid = $jwt->isJwtValid($authorizationHeader, 'admin', $ip);
            if ($isJwtValid) {
                if (isset($_POST['name']) && isset($_POST['id']) && isset($_POST['description']) && isset($_POST['price']) && isset($_POST['category'])) {
                    define('UPLOAD_DIR', 'img/');
                    $sanitizer = new Sanitizer;
                    $id = $sanitizer->sanString($_POST['id']);
                    $name = $sanitizer->sanString($_POST['name']);
                    $description = $sanitizer->sanString($_POST['description']);
                    $price = $sanitizer->sanString($_POST['price']);
                    $tags = $sanitizer->sanString($_POST['tags']);
                    $category = $sanitizer->sanString($_POST['category']);
                    $activate = $sanitizer->sanString($_POST['activate']);
                    $live = $sanitizer->sanString($_POST['live']);
                    $ajaxModel = $this->model('_ajax_');
                    $updated = $ajaxModel->_updateMenuItem($id, $name, $description, $price, $tags, $category, $activate, $live);
                    if ($updated) {
                        $returnArray[] = array(
                            'status' => 'true',
                        );
                        exit(json_encode($returnArray));
                    } else {
                        $returnArray[] = array(
                            'status' => 'false',
                        );
                        exit(json_encode($returnArray));
                    }
                } else {
                    $returnArray[] = array(
                        'status' => 'empty',
                    );
                    exit(json_encode($returnArray));
                }
            } else {
                // Report
            }
        } else {
            echo "Not AJAX"; // Report
            throw new Exception('');
        }
    } catch (Exception $e) {
        echo '',  $e->getMessage(), "\n"; // Report to admin
    }
}
public function fetchMenuAjax()
{
    try {
        if ($this->validation && !empty($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == 'https://sharksdevelopment-demo1.xyz/admin/menu') {
            $jwt = new Jwt;
            $authorizationHeader = $jwt->getAuthorizationHeaderTkn();
            $ip = $jwt->getUserIpAddr();
            $isJwtValid = $jwt->isJwtValid($authorizationHeader, 'admin', $ip);
            if ($isJwtValid) {
                $sanitizer = new Sanitizer;

                $query1 = '';
                $columns = array('name_item', 'description_item', 'price_item', 'tags_item', 'category_item', 'photo_item');

                $query = "SELECT itemID AS id, name_item AS name, description_item AS description, price_item AS price, tags_item AS tags, activate_item AS activate, category_item AS category, photo_item AS photo, live_item AS live FROM menu_items ";

                if (isset($_POST["search"]["value"])) {
                    $searchValue = $sanitizer->San_HashNum($_POST["search"]["value"]);
                    $query .= ' WHERE name_item LIKE "%' . $searchValue . '%" OR description_item LIKE "%' . $searchValue . '%" OR tags_item LIKE "%' . $searchValue . '%" ';
                }
                if (isset($_POST["order"])) {
                    $orderColumn = $columns[$_POST['order']['0']['column']];
                    $orderDir = filter_var($_POST['order']['0']['dir'], FILTER_SANITIZE_STRING);
                    $query .= 'ORDER BY ' . $orderColumn . ' ' . $orderDir . ' ';
                } else {
                    $query .= 'ORDER BY id DESC ';
                }
                if ($_POST["length"] != -1) {
                    $start = $sanitizer->San_HashNum($_POST['start']);
                    $length = $sanitizer->San_HashNum($_POST['length']);
                    $query1 = 'LIMIT ' . $start . ', ' . $length;
                }

                $ajaxModel = $this->model('_ajax_');
                $numberFilterRow = $ajaxModel->countMenu($query . $query1);
                $result = $ajaxModel->fetchMenu($query);
                $data = array();
                foreach ($result as $row) {
                    $data[] = $row;
                }
                $output = array(
                    "draw"            => intval($_POST["draw"]),
                    "recordsTotal"    => $ajaxModel->get_all_data(),
                    "recordsFiltered" => $numberFilterRow,
                    "data"            => $data
                );
                echo json_encode($output);
            } else {
                $output = array(
                    "status" => 'false'
                );
                echo json_encode($output);
            }
        } else {
            echo "Not AJAX";
            throw new Exception('');
        }
    } catch (Exception $e) {
        echo '',  $e->getMessage(), "\n";
    }
}

public function fetchReservationAjax()
{
    try {
        if ($this->validation && !empty($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == 'https://sharksdevelopment-demo1.xyz/admin/reservation') {
            $jwt = new Jwt;
            $authorizationHeader = $jwt->getAuthorizationHeaderTkn();
            $ip = $jwt->getUserIpAddr();
            $isJwtValid = $jwt->isJwtValid($authorizationHeader, 'admin', $ip);
            if ($isJwtValid) {
                $sanitizer = new Sanitizer;

                $query1 = '';
                $columns = array('reservation_ID', 'name_csr', 'tel_csr', 'email_csr', 'cover_csr', 'reservation_date', 'reservation_time', 'notes_csr', 'reservation_Status');

                $query = "SELECT reservation_ID AS id, name_csr AS name, tel_csr AS tel, email_csr AS email, cover_csr AS cover, reservation_date AS date, reservation_time AS time, notes_csr AS note, reservation_Status AS status, time_Stamp AS stamp FROM reservation_csr ";

                if (isset($_POST["search"]["value"])) {
                    $searchValue = $sanitizer->San_HashNum($_POST["search"]["value"]);
                    $query .= ' WHERE name_csr LIKE "%' . $searchValue . '%" OR reservation_ID LIKE "%' . $searchValue . '%" OR tel_csr LIKE "%' . $searchValue . '%" ';
                }
                if (isset($_POST["order"])) {
                    $orderColumn = $columns[$_POST['order']['0']['column']];
                    $orderDir = filter_var($_POST['order']['0']['dir'], FILTER_SANITIZE_STRING);
                    $query .= 'ORDER BY ' . $orderColumn . ' ' . $orderDir . ' ';
                } else {
                    $query .= 'ORDER BY id DESC ';
                }
                if ($_POST["length"] != -1) {
                    $start = $sanitizer->San_HashNum($_POST['start']);
                    $length = $sanitizer->San_HashNum($_POST['length']);
                    $query1 = 'LIMIT ' . $start . ', ' . $length;
                }

                $ajaxModel = $this->model('_ajax_');
                $numberFilterRow = $ajaxModel->countMenu($query . $query1);
                $result = $ajaxModel->fetchMenu($query);
                $data = array();
                foreach ($result as $row) {
                    $data[] = $row;
                }
                $output = array(
                    "draw"            => intval($_POST["draw"]),
                    "recordsTotal"    => $ajaxModel->get_all_data_reservation(),
                    "recordsFiltered" => $numberFilterRow,
                    "data"            => $data
                );
                echo json_encode($output);
            } else {
                $output = array(
                    "status" => 'false'
                );
                echo json_encode($output);
            }
        } else {
            echo "Not AJAX";
            throw new Exception('');
        }
    } catch (Exception $e) {
        echo '',  $e->getMessage(), "\n";
    }
}

public function updateMenuAjax()
{
    try {
        if ($this->validation && !empty($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == 'https://sharksdevelopment-demo1.xyz/admin/menu') {
            if (isset($_POST["id"])) {
                $sanitizer = new Sanitizer;
                $id = $sanitizer->San_HashNum($_POST["id"]);
                $ajaxModel = $this->model('_ajax_');
                if (empty($_POST["value"])) {
                    echo 'empty value not accepted';
                } else {
                    if ($_POST["column_name"] == 'price') {
                        $val = $sanitizer->San_Float($_POST["value"]);
                        if ($val > 0) {
                            $updated = $ajaxModel->updatePrice($val, $id);
                            if ($updated) {
                                echo 'Price Updated, Thank you';
                            }
                        } else {
                            echo 'Price must be numbers only and greater than 0';
                        }
                    } else {
                        $val = $sanitizer->San_HashNum($_POST["value"]);
                        if ($_POST["column_name"] == 'name' && strlen($val) > 0) {
                            $updated = $ajaxModel->updateName($val, $id);
                        }
                        if ($_POST["column_name"] == 'description' && strlen($val) > 0) {
                            $updated = $ajaxModel->updateDesc($val, $id);
                        }
                        if ($updated) {
                            echo 'Data Updated';
                        } else {
                            echo 'Something went wrong, please try again';
                        }
                    }
                }
            }
        } else {
            echo "Not AJAX";
            throw new Exception('');
        }
    } catch (Exception $e) {
        echo '', $e->getMessage(), "\n";
    }
}

//done
public function sendMsgAjax()
{
    try {
        if ($this->validation && !empty($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == 'https://sharksdevelopment-demo1.xyz/admin/support') {
            $jwt_ = new jwt;
            $jwt = $jwt_->getAuthorizationHeaderTkn();
            $ip = $jwt_->getUserIpAddr();
            $is_jwt_valid = $jwt_->is_jwt_valid($jwt, 'admin', $ip);

            if ($is_jwt_valid) {
                $msg = filter_var($_POST['msg'], FILTER_SANITIZE_STRING);
                $to = myEMAIL_;
                $subject = "Customer Message";
                $body = "<div>" . $msg . "</div>";
                $headers = 'From: YourLogoName' . cEMAIL_ . "\r\n";
                $headers .= 'Reply-To: ' . $to . "\r\n";
                $headers .= 'X-Mailer: PHP/' . phpversion();
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
                if (mail($to, $subject, $body, $headers)) {
                    $returnArray[] = array(
                        'status' => 'true',
                    );
                    exit(json_encode($returnArray));
                } else {
                    $returnArray[] = array(
                        'status' => 'false',
                    );
                    exit(json_encode($returnArray));
                }
            } else {
                //report
            }
        } else {
            echo "Not AJAX";
            throw new Exception('');
        }
    } catch (Exception $e) {
        echo '', $e->getMessage(), "\n";
    }
}

//done
public function pubMsgAjax()
{
    try {
        if ($this->validation && !empty($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == 'https://sharksdevelopment-demo1.xyz/admin/marketing') {
            $jwt_ = new jwt;
            $jwt = $jwt_->getAuthorizationHeaderTkn();
            $ip = $jwt_->getUserIpAddr();
            $is_jwt_valid = $jwt_->is_jwt_valid($jwt, 'admin', $ip);

            if ($is_jwt_valid) {
                $sub = filter_var($_POST['subject'], FILTER_SANITIZE_STRING);
                $msg = filter_var($_POST['message'], FILTER_SANITIZE_STRING);

                $sub = str_replace(
                    array('0#01', '0#02', '0#03', '0#04', '0#05', '0#06', '0#07', '0#08', '0#15', '0#09', '0#10', '0#11', '0#12', '0#13', '0#14'),
                    array(
                        '<img src="<?php echo URLROOT; ?>/img/emo/fire.gif" alt="0#01" />',
                        '<img src="<?php echo URLROOT; ?>/img/emo/hearteye.gif" alt="0#02" />',
                        '<img src="<?php echo URLROOT; ?>/img/emo/emheart.png" alt="0#03" />',
                        '<img src="<?php echo URLROOT; ?>/img/emo/heart.png" alt="0#04" />',
                        '<img src="<?php echo URLROOT; ?>/img/emo/celebrity.gif" alt="0#05" />',
                        '<img src="<?php echo URLROOT; ?>/img/emo/facekiss.png" alt="0#06" />',
                        '<a href="https://www.instagram.com/" target="_blank"><img src="<?php echo URLROOT; ?>/img/emo/inst.gif" alt="0#07" /></a>',
                        '<a href="https://fb.com" target="_blank"><img src="<?php echo URLROOT; ?>/img/emo/fb.png" alt="0#08" /></a>',
                        ' <a href="https://api.whatsapp.com/send?phone=4477123456789" target="_blank"><img src="<?php echo URLROOT; ?>/img/emo/wa.png" alt="0#15" /></a>',
                        '<img src="<?php echo URLROOT; ?>/img/emo/british1.png" alt="0#09" />',
                        '<img src="<?php echo URLROOT; ?>/img/emo/british2.png" alt="0#10" />',
                        '<img src="<?php echo URLROOT; ?>/img/emo/aubergine.png" alt="0#11" />',
                        '<img src="<?php echo URLROOT; ?>/img/emo/cucumber.png" alt="0#12" />',
                        '<img src="<?php echo URLROOT; ?>/img/emo/tomato.png" alt="0#13" />',
                        '<img src="<?php echo URLROOT; ?>/img/emo/rose.png" alt="0#14" />'
                    ),
                    $sub
                );

                define('menuPart', '../app/views/mainControl/message.php');
                $message = "
                    <div class=\"overlay_\">
                        <div class=\"borderLay\">
                            <div class=\"inner-cutout\">
                                <div class=\"knockout\">
                                    <a class=\"close\" href=\"#\"><i class=\"far fa-times-circle\"></i></a>
                                    <div class='psub'>" . $sub . "</div>
                                    <div class=\"content_ text-center\">" . $msg . " </div>
                                </div>
                            </div>
                        </div>
                    </div>
                ";

                if (file_put_contents(menuPart, $message)) {
                    $returnArray[] = array(
                        'status' => 'true',
                    );
                    exit(json_encode($returnArray));
                } else {
                    $returnArray[] = array(
                        'status' => 'false',
                    );
                    exit(json_encode($returnArray));
                }
            } else {
                //report
            }
        } else {
            echo "Not AJAX";
            throw new Exception('');
        }
    } catch (Exception $e) {
        echo '', $e->getMessage(), "\n";
    }
}

//done
public function deleteMsgAjax()
{
    try {
        if ($this->validation && !empty($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == 'https://sharksdevelopment-demo1.xyz/admin/marketing') {
            $jwt_ = new jwt;
            $jwt = $jwt_->getAuthorizationHeaderTkn();
            $ip = $jwt_->getUserIpAddr();
            $is_jwt_valid = $jwt_->is_jwt_valid($jwt, 'admin', $ip);

            if ($is_jwt_valid) {
                define('menuPart', '../app/views/mainControl/message.php');
                $message = "&nbsp;&nbsp;";

                if (file_put_contents(menuPart, $message)) {
                    $returnArray[] = array(
                        'status' => 'true',
                    );
                    exit(json_encode($returnArray));
                } else {
                    $returnArray[] = array(
                        'status' => 'false',
                    );
                    exit(json_encode($returnArray));
                }
            } else {
                //report
            }
        } else {
            echo "Not AJAX";
            throw new Exception('');
        }
    } catch (Exception $e) {
        echo '', $e->getMessage(), "\n";
    }
}

//done
public function themeAjax()
{
    try {
        if ($this->validation && !empty($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == 'https://sharksdevelopment-demo1.xyz/admin/marketing') {
            $jwt_ = new jwt;
            $jwt = $jwt_->getAuthorizationHeaderTkn();
            $ip = $jwt_->getUserIpAddr();
            $is_jwt_valid = $jwt_->is_jwt_valid($jwt, 'admin', $ip);

            if ($is_jwt_valid) {
                $theme = $_POST['theme'];

                if ($theme == 'default') {
                    copy(APPROOT . '/views/mainControl/index.php', APPROOT . '/views/pages/index.php');
                    $this->ajaxModel = $this->model('_ajax_');
                    $updated = $this->ajaxModel->themeUpdate('default');

                    if ($updated) {
                        $returnArray[] = array(
                            'status' => 'true',
                        );
                        exit(json_encode($returnArray));
                    } else {
                        $returnArray[] = array(
                            'status' => 'false',
                        );
                        exit(json_encode($returnArray));
                    }
                } else if ($theme == 'christmas') {
                    copy(APPROOT . '/views/mainControl/Xmas.php', APPROOT . '/views/pages/index.php');
                    $this->ajaxModel = $this->model('_ajax_');
                    $updated = $this->ajaxModel->themeUpdate('christmas');

                    if ($updated) {
                        $returnArray[] = array(
                            'status' => 'true',
                        );
                        exit(json_encode($returnArray));
                    } else {
                        $returnArray[] = array(
                            'status' => 'false',
                        );
                        exit(json_encode($returnArray));
                    }
                } else {
                    //report
                }
            } else {
                //report
            }
        } else {
            echo "Not AJAX";
            throw new Exception('');
        }
    } catch (Exception $e) {
        echo '', $e->getMessage(), "\n";
    }
}
}
