<?php /** Created by ic on 04-Aug-18 at 20:43 */

class UserExistsException extends Exception{}
class UserManager
{
    /**
     * @var Redis
     */
    protected $r;

    /**
     * UserManager constructor.
     * @param Redis $r
     */
    public function __construct(Redis $r)
    {
        $this->r = $r;
    }

    /**
     * Creates new user
     *
     * @param array $user_data User data contains the following fields:
     *                                      - name
     *                                      - email
     *                                      - password_hash
     *
     * @return string                   Returns ID of created user
     *
     * @throws \UserExistsException     Throws exception if user with this email already exists
     *
     */
    public function create_user(array $user_data)
    {
        // Here we want to check array for data integrity and correctness... but laziness takes over...
        $newId = $this->r->eval('if redis.call("get", "email:" .. ARGV[2]) ~= false then return "exists" end local id = redis.call("incr","user:id") redis.call("hmset", "user:" .. id, unpack(ARGV)) redis.call("set", "email:" .. ARGV[2], id) return id', [
            'email', $user_data['email'],
            'name', $user_data['name'],
            'password_hash', $user_data['password_hash']
        ]);
        // We also want to check for additional errors but... another time
        if($newId === 'exists'){
            throw new UserExistsException('Already exists... :(');
        }
         return $newId;
    }

    /**
     * Finds user by combination of email and password hash
     *
     * @param string $email
     * @param string $password_hash
     *
     * @return string|null                   Returns ID of user or null if user not found
     */
    public function authorize_user($email, $password_hash)
    {
        // Again need to check if parameters not contain harmful lua code :)
        return $this->r->eval('local id = redis.call("get", "email:" .. ARGV[1]) if id == false or redis.call("hget", "user:" .. id, "password_hash") ~= ARGV[2] then return nil else return id end ', func_get_args()) ?: NULL;
    }
}

// Usage:
//$redis = new Redis;
//$redis->pconnect('127.0.0.1', 6379);
//
//$um = new UserManager($redis);

//try {
//    $um->create_user(['name' => 'The user', 'email' => 'The email', 'password_hash' => md5('The hash')]);
//}catch (UserExistsException $x){
//    echo 'Exists :(';
//}catch (Exception $x){
//    die('Whoops');
//}
//
//$res = $um->authorize_user('The email', md5('The hash'));
//var_dump($res);