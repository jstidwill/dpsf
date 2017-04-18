<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route(path="token-authentication")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tokenAuthentication(Request $request)
    {
        $jsonRequest = json_decode($request->getContent());
        $username = isset($jsonRequest->username) ? $jsonRequest->username : null;
        $password = isset($jsonRequest->password) ? $jsonRequest->password : null;

            // _password_crypt
        $user = $this->getDoctrine()->getRepository('AppBundle:User')
            ->findOneBy(['username' => $username]);

        if(!$user) {
            throw $this->createNotFoundException();
        }

        if($this->_password_crypt('sha512', $password, $user->getPassword()) != $user->getPassword()) {
            throw $this->createAccessDeniedException();
        }

        $roles  = [];

        if ($userRoles = $this->getDoctrine()->getRepository('AppBundle:UserRole')
            ->findBy(['user' => $user])) {
            foreach ($userRoles as $role) {
                $roles[$role->getRid()] = $role->getRole();

            }
        }

        $token = $this->get('lexik_jwt_authentication.encoder')
            ->encode(['username' => $user->getUsername(), 'roles' => $roles]);

        return $this->json(['token' => $token]);
    }

    /**
     * @param $algo
     * @param $password
     * @param $setting
     * @return bool|string
     */
    private function _password_crypt($algo, $password, $setting) {
        // The first 12 characters of an existing hash are its setting string.
        $setting = substr($setting, 0, 12);

        if ($setting[0] != '$' || $setting[2] != '$') {
            return FALSE;
        }
        $count_log2 = $this->_password_get_count_log2($setting);
        // Hashes may be imported from elsewhere, so we allow != DRUPAL_HASH_COUNT
        if ($count_log2 < 7 || $count_log2 > 30) {
            return FALSE;
        }
        $salt = substr($setting, 4, 8);
        // Hashes must have an 8 character salt.
        if (strlen($salt) != 8) {
            return FALSE;
        }

        // Convert the base 2 logarithm into an integer.
        $count = 1 << $count_log2;

        // We rely on the hash() function being available in PHP 5.2+.
        $hash = hash($algo, $salt . $password, TRUE);
        do {
            $hash = hash($algo, $hash . $password, TRUE);
        } while (--$count);

        $len = strlen($hash);
        $output =  $setting . $this->_password_base64_encode($hash, $len);
        // _password_base64_encode() of a 16 byte MD5 will always be 22 characters.
        // _password_base64_encode() of a 64 byte sha512 will always be 86 characters.
        $expected = 12 + ceil((8 * $len) / 6);
        return (strlen($output) == $expected) ? substr($output, 0, 55) : FALSE;
    }

    /**
     * Parse the log2 iteration count from a stored hash or setting string.
     */
    private function _password_get_count_log2($setting) {
        $itoa64 = $this->_password_itoa64();
        return strpos($itoa64, $setting[3]);
    }

    private function _password_base64_encode($input, $count) {
        $output = '';
        $i = 0;
        $itoa64 = $this->_password_itoa64();
        do {
            $value = ord($input[$i++]);
            $output .= $itoa64[$value & 0x3f];
            if ($i < $count) {
                $value |= ord($input[$i]) << 8;
            }
            $output .= $itoa64[($value >> 6) & 0x3f];
            if ($i++ >= $count) {
                break;
            }
            if ($i < $count) {
                $value |= ord($input[$i]) << 16;
            }
            $output .= $itoa64[($value >> 12) & 0x3f];
            if ($i++ >= $count) {
                break;
            }
            $output .= $itoa64[($value >> 18) & 0x3f];
        } while ($i < $count);

        return $output;
    }

    /**
     * Returns a string for mapping an int to the corresponding base 64 character.
     */
    private function _password_itoa64() {
        return './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    }
}
