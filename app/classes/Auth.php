<?php declare(strict_types=1);

namespace App\classes;

class Auth
{
    private array $data;
    private DB $db;

    public function __construct(DB $db, array $data)
    {
        $this->db = $db;
        $this->data = $data;
    }

    /**
     * @return string
     *@throws \Exception
     */
    public function register(): string
    {
        $errorMessage = [];
        $data = $this->data;
        if(!empty($data['firstName'])) {
            $firstName = filter_var($data['firstName'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        } else {
            $errorMessage['firstName'] = 'Empty filed';
        }
        if(!empty($data['lastName'])) {
            $lastName = filter_var($data['lastName'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        } else {
            $errorMessage['lastName'] = 'Empty filed';
        }
        if(!empty($data['userName'])) {
            $userName = filter_var($data['userName'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        } else {
            $errorMessage['username'] = 'Empty filed';
        }
        if(!empty($data['email'])) {
            $email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
        } else {
            $errorMessage['email'] = 'Empty filed';
        }
        if(!empty($data['phone'])) {
            $phone = filter_var($data['phone'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        } else {
            $errorMessage['phone'] = 'Empty filed';
        }


        if(!$errorMessage) {
            $checkUserQuery = 'SELECT * FROM users WHERE email = ? OR mobile = ?';
            $checkUserParams = [$data['email'], $data['phone']];
            $isUserExist = $this->db->fetchRow($checkUserQuery, $checkUserParams);

            if (!$isUserExist) {
                $password = hash('sha512', $data['password']);
                $passwordSalt = hash( 'sha512', uniqid( openssl_random_pseudo_bytes( 16 ), TRUE ));
                $passwordEncrypt = hash( 'sha512', $password . $passwordSalt );

                $authToken = $this->generateCode(15);
                $apiKey = $this->generateCode(128);
                $refId = $this->generateCode(5);
                $insertUserQuery = "INSERT INTO users (first_name, last_name, user_name, email, password, salt, mobile, type, status, auth_token, api_key, ref_id, reg_date) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

                $insertUserParams = [
                    $firstName,
                    $lastName,
                    $userName,
                    $email,
                    $passwordEncrypt,
                    $passwordSalt,
                    $phone,
                    'admin',
                    'active',
                    $authToken,
                    $apiKey,
                    $refId
                ];
                $userSaved = $this->db->executeQuery($insertUserQuery, $insertUserParams);
                if($userSaved) {
                    // TODO: implement register email notification for user mail registration
                    // TODO: implement user wallet creation
                    $userId = $this->db->getLastInsertedId();
                    $insertWalletQuery = "INSERT INTO user_wallet (user_id, reg_date, modified_date) VALUES (?, NOW(), NOW())";
                    $insertWalletParams = [
                        $userId
                    ];
                    $this->db->executeQuery($insertWalletQuery, $insertWalletParams);

                    $message = "Registration successful.";
                    $responseData = [
                        'status' => true,
                        'server_response' => 'Success',
                        'server_message' => $message
                    ];
                    return Helper::jsonResponse($responseData);
                } else {
                    $responseData = [
                        'status' => false,
                        'server_response' => 'Failed',
                        'server_message' => 'Unable to register user'
                    ];
                    return Helper::jsonResponse($responseData, 400);
                }
            } else {
                $responseData = [
                    'status' => false,
                    'server_response' => 'Failed',
                    'server_message' => 'User with email already exists; Login with password'
                ];
                return Helper::jsonResponse($responseData, 400);
            }
        } else {
            $responseData = [
                'status' => false,
                'server_response' => 'Failed',
                'server_message' => 'Fields cannot be empty',
                'data' => $errorMessage
            ];
            return Helper::jsonResponse($responseData, 400);
        }

    }

    public function login(): string
    {
        $data = $this->data;
        if(!empty($data['userName'])) {
            $loginId = filter_var($data['userName'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        }
        if(!empty($data['email'])) {
            $loginId = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
        }

        if(!empty($loginId) && !empty($data['password'])) {
            $selectUserQuery = "SELECT first_name, last_name, user_name, email, password, salt, gender, mobile, type, status, api_key  FROM users WHERE user_name = ? OR email = ?";
            $selectUserParams = [$loginId, $loginId];
            $isUserExist = $this->db->fetchRow($selectUserQuery, $selectUserParams);

            if($isUserExist) {
                $loginPassword = filter_var($data['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $password = hash('sha512', $loginPassword);
                $dbPasswordSalt = $isUserExist['salt'];
                $passwordEncrypt = hash( 'sha512', $password . $dbPasswordSalt );
                $dbPassword = $isUserExist['password'];

                if($passwordEncrypt == $dbPassword) {
                    $firstName = $isUserExist['first_name'];
                    $lastName = $isUserExist['last_name'];
                    $userName = $isUserExist['user_name'];
                    $email = $isUserExist['email'];
                    $phone = $isUserExist['mobile'];
                    $userType = $isUserExist['type'];
                    $status = $isUserExist['status'];
                    $apiKey = $isUserExist['api_key'];

                    $message = "Login successful";
                    $responseData = [
                        'status' => true,
                        'server_response' => 'Success',
                        'server_message' => $message,
                        'data' => [
                            'firstName' => $firstName,
                            'lastname' => $lastName,
                            'userName' => $userName,
                            'email' => $email,
                            'phone' => $phone,
                            'userType' => $userType,
                            'status' => $status,
                            'apiKey' => $apiKey
                        ]
                    ];
                    return Helper::jsonResponse($responseData);
                } else {
                    $responseData = [
                        'status' => false,
                        'server_response' => 'Failed',
                        'server_message' => 'Invalid password',
                    ];
                    return Helper::jsonResponse($responseData, 400);
                }
            } else {
                $responseData = [
                    'status' => false,
                    'server_response' => 'Failed',
                    'server_message' => 'User not found',
                ];
                return Helper::jsonResponse($responseData, 400);
            }

        } else {
            $responseData = [
                'status' => false,
                'server_response' => 'Failed',
                'server_message' => 'Empty username or email or password',
            ];
            return Helper::jsonResponse($responseData, 400);
        }
    }

    /**
     * @throws \Exception
     */
    public function generateCode($length): string
    {
        if ($length>0) {
            $randId = "";
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            for ($i = 0; $i < $length; $i++) {
                $randId .= $characters[random_int(0, $charactersLength - 1)];
            }
            return $randId;
        }
        return '';
    }

    public function emailConfirm()
    {

    }

    public function phoneConfirm()
    {

    }

    public function passwordReset()
    {

    }

    public function savePassword()
    {

    }

    public function __destruct()
    {

    }

}