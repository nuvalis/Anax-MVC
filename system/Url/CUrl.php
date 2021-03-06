<?php

namespace Anax\Url;

/**
 * A helper to create urls.
 *
 */
class CUrl
{

    /**
     * Properties
     *
     */
    const URL_CLEAN  = 'clean';  // controller/action/param1/param2
    const URL_APPEND = 'append'; // index.php/controller/action/param1/param2

    private $urlType = self::URL_APPEND; // What type of urls to generate

    private $siteUrl = null; // Siteurl to prepend to all absolute urls created
    private $baseUrl = null; // Baseurl to prepend to all relative urls created
    private $scriptName = null; // Name of the frontcontroller script


    private $staticSiteUrl = null; // Siteurl to prepend to all absolute urls for assets
    private $staticBaseUrl = null; // Baseurl to prepend to all relative urls for assets



    /**
     * Create an url and prepending the baseUrl.
     *
     * @param string $uri part of uri to use when creating an url. "" or null means baseurl to  
     * current frontcontroller.
     *
     * @return string as resulting url.
     */
    public function create($uri = null)
    {
        if (empty($uri)) {
            
            // Empty uri means baseurl
            return $this->baseUrl
                . (($this->urlType == self::URL_APPEND)
                    ? "/$this->scriptName"
                    : null);

        } elseif (substr($uri, 0, 7) == "http://" || substr($uri, 0, 2) == "//") {
            
            // Fully qualified, just leave as is.
            return rtrim($uri, '/');

        } elseif ($uri[0] == '/') {

            // Absolute url, prepend with siteUrl
            return rtrim($this->siteUrl . rtrim($uri, '/'), '/');

        }

        $uri = rtrim($uri, '/');
        if ($this->urlType == self::URL_CLEAN) {
            return $this->baseUrl . '/' . $uri;
        } else {
            return $this->baseUrl . '/' . $this->scriptName . '/' . $uri;
        }
    }



    /**
     * Create an url for a static asset.
     *
     * @param string $uri part of uri to use when creating an url.
     *
     * @return string as resulting url.
     */
    public function asset($uri)
    {
        if (empty($uri)) {
            
            throw new \Exception("Asset can not be empty.");

        } elseif (substr($uri, 0, 7) == "http://" || substr($uri, 0, 2) == "//") {
            
            // Fully qualified, just leave as is.
            return rtrim($uri, '/');

        } elseif ($uri[0] == '/') {

            // Absolute url, prepend with staticSiteUrl
            return rtrim($this->staticSiteUrl . rtrim($uri, '/'), '/');

        }

        $baseUrl = isset($this->staticBaseUrl) ? $this->staticBaseUrl : $this->baseUrl;
        return $baseUrl . '/' . $uri;
    }



    /**
     * Set the siteUrl to prepend all absolute urls created.
     *
     * @param string $url part of url to use when creating an url.
     *
     * @return $this
     */
    public function setSiteUrl($url)
    {
        $this->siteUrl = rtrim($url, '/');
        return $this;
    }



    /**
     * Set the baseUrl to prepend all relative urls created.
     *
     * @param string $url part of url to use when creating an url.
     *
     * @return $this
     */
    public function setBaseUrl($url)
    {
        $this->baseUrl = rtrim($url, '/');
        return $this;
    }



    /**
     * Set the siteUrl to prepend absolute urls for assets.
     *
     * @param string $url part of url to use when creating an url.
     *
     * @return $this
     */
    public function setStaticSiteUrl($url)
    {
        $this->staticSiteUrl = rtrim($url, '/');
        return $this;
    }



    /**
     * Set the baseUrl to prepend relative urls for assets.
     *
     * @param string $url part of url to use when creating an url.
     *
     * @return $this
     */
    public function setStaticBaseUrl($url)
    {
        $this->staticBaseUrl = rtrim($url, '/');
        return $this;
    }



    /**
     * Set the scriptname to use when creating URL_APPEND urls.
     *
     * @param string $name as the scriptname, for example index.php.
     *
     * @return $this
     */
    public function setScriptName($name)
    {
        $this->scriptName = $name;
        return $this;
    }



    /**
     * Set the type of urls to be generated, URL_CLEAN, URL_APPEND.
     *
     * @param string $type what type of urls to create.
     *
     * @return $this
     */
    public function setUrlType($type)
    {
        if (!in_array($type, [self::URL_APPEND, self::URL_CLEAN])) {
            throw new \Exception("Unsupported Url type.");
        }

        $this->urlType = $type;
        return $this;
    }
    
    public function getCurrentUrl() 
    {

      $url = "http";
      $url .= (@$_SERVER["HTTPS"] == "on") ? 's' : '';
      $url .= "://";
      $serverPort = ($_SERVER["SERVER_PORT"] == "80") ? '' :
        (($_SERVER["SERVER_PORT"] == 443 && @$_SERVER["HTTPS"] == "on") ? '' : ":{$_SERVER['SERVER_PORT']}");
      $url .= $_SERVER["SERVER_NAME"] . $serverPort . htmlspecialchars($_SERVER["REQUEST_URI"]);
      return $url;

    }

    public function getBaseUrl() 
    {
        return $this->baseUrl;
    }
}
