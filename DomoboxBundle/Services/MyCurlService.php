<?php

namespace Domotique\DomoboxBundle\Services;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;


class MyCurlService {

    private $container;
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getToUrl($restUrl,$proxy=false)
    {
      //  $proxyAdress = $this->container->getParameter('ext_proxy');

        // Forbid every request but jquery's XHR
//        if (!$request->isXmlHttpRequest()) {// isn't it an Ajax request?
//            return new Response('', 404,
//                array('Content-Type' => 'application/json'));
//        }
//
//        $restUrl = $request->request->get('restUrl');
//        $method = $request->request->get('method');
//        $params = $request->request->get('params');
//        $contentType = $request->request->get('contentType');

//        if ($contentType == null) {
//            $contentType = 'application/json';
//        }

//        if ($restUrl == null || $method == null ||
//            !in_array($method, array('GET', 'POST', 'DELETE'))) {
//            return new Response('', 404, array('Content-Type' => $contentType));
//        }

        session_write_close();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $restUrl);
        curl_setopt($ch, CURLOPT_HEADER, true);
//        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
//        if ($params != null) {
//            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
//        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

//        $requestCookies = $request->cookies->all();

//        $cookieArray = array();
//        foreach ($requestCookies as $cookieName => $cookieValue) {
//            $cookieArray[] = "{$cookieName}={$cookieValue}";
//        }
//        $cookie_string = implode('; ', $cookieArray);
//        curl_setopt($ch, CURLOPT_COOKIE, $cookie_string);

        $response = curl_exec($ch);
        curl_close($ch);

//        list($headers, $response) = explode("\r\n\r\n",$response,2);
//        preg_match_all('/Set-Cookie: (.*)\b/', $headers, $cookies);
//        $cookies = $cookies[1];

//        if ($response === false) {
//            return new Response('', 404, array('Content-Type' => $contentType));
//        } else {
//            $response = new Response($response, 200,
//                array('Content-Type' => $contentType));
//            foreach ($cookies as $rawCookie) {
//                $cookie = \Symfony\Component\BrowserKit\Cookie::fromString($rawCookie);
//                $value = $cookie->getValue();
//                if (!empty($value)) {
//                    $value = str_replace(' ', '+', $value);
//                }
//                $customCookie = new Cookie($cookie->getName(), $value, $cookie->getExpiresTime() == null ? 0 : $cookie->getExpiresTime(), $cookie->getPath());
//                $response->headers->setCookie($customCookie);
//            }
            return $response;
//        }

    }
}