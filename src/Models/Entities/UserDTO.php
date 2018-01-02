<?php
/**
 * Created by PhpStorm.
 * User: yemei
 * Date: 15/12/2017
 * Time: 12:54
 */

namespace app\Entities;


class UserDTO
{
    private $userId;
    private $name;
    private $pass;
    private $realName;
    private $firstName;
    private $lastName;
    private $email;
    private $privs;
    private $lastAccess;
    private $nonce;
    private $address;
    private $phone;
    private $web;
    private $entreprise;
    private $detail;

    private static $columnMap = [
        'user_id' => 'userId',
        'name' => 'email',
        'pass' => 'pass',
        'RealName' => 'realName',
        'first_name' => 'firstName',
        'last_name' => 'lastName',
        'email' => 'email',
        'privs' => 'privs',
        'last_access' => 'lastAccess',
        'nonce' => 'nonce',
        'address' => 'address',
        'phone' => 'phone',
        'web' => 'web',
        'entreprise' => 'entreprise',
        'detail' => 'detail'
    ];

    public function __set($name, $value)
    {
        if(array_key_exists($name, self::$columnMap)){
            $attributeName = self::$columnMap[$name];
            $this->$attributeName = $value;
        }
    }

    public function hydrate(array $data)
    {
        foreach ($data as $key => $val) {
            $methodName = "set" . ucfirst($key);
            if (method_exists($this, $methodName)) {
                $this->$methodName($val);
            } else {
                if (array_key_exists($key, self::$columnMap)) {
                    $methodName = $methodName = "set" . ucfirst(self::$columnMap[$key]);
                    $this->$methodName($val);
                }
            }
        }
    }

    /**
     * @param mixed $userId
     * @return UserDTO
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @param mixed $name
     * @return UserDTO
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param mixed $pass
     * @return UserDTO
     */
    public function setPass($pass)
    {
        $this->pass = $pass;
        return $this;
    }

    /**
     * @param mixed $realName
     * @return UserDTO
     */
    public function setRealName($realName)
    {
        $this->realName = $realName;
        return $this;
    }

    /**
     * @param mixed $firstName
     * @return UserDTO
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @param mixed $lastName
     * @return UserDTO
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @param mixed $email
     * @return UserDTO
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @param mixed $privs
     * @return UserDTO
     */
    public function setPrivs($privs)
    {
        $this->privs = $privs;
        return $this;
    }

    /**
     * @param mixed $lastAccess
     * @return UserDTO
     */
    public function setLastAccess($lastAccess)
    {
        $this->lastAccess = $lastAccess;
        return $this;
    }

    /**
     * @param mixed $nonce
     * @return UserDTO
     */
    public function setNonce($nonce)
    {
        $this->nonce = $nonce;
        return $this;
    }

    /**
     * @param mixed $address
     * @return UserDTO
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @param mixed $phone
     * @return UserDTO
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @param mixed $web
     * @return UserDTO
     */
    public function setWeb($web)
    {
        $this->web = $web;
        return $this;
    }

    /**
     * @param mixed $entreprise
     * @return UserDTO
     */
    public function setEntreprise($entreprise)
    {
        $this->entreprise = $entreprise;
        return $this;
    }

    /**
     * @param mixed $detail
     * @return UserDTO
     */
    public function setDetail($detail)
    {
        $this->detail = $detail;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getPass()
    {
        return $this->pass;
    }

    /**
     * @return mixed
     */
    public function getRealName()
    {
        return $this->realName;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getPrivs()
    {
        return $this->privs;
    }

    /**
     * @return mixed
     */
    public function getLastAccess()
    {
        return $this->lastAccess;
    }

    /**
     * @return mixed
     */
    public function getNonce()
    {
        return $this->nonce;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @return mixed
     */
    public function getWeb()
    {
        return $this->web;
    }

    /**
     * @return mixed
     */
    public function getEntreprise()
    {
        return $this->entreprise;
    }

    /**
     * @return mixed
     */
    public function getDetail()
    {
        return $this->detail;
    }

}