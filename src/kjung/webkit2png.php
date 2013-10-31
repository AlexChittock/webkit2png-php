<?php
namespace kjung;

/**
* PHP wrapper class for the webkit2png application on OS X.
*
* @author Kevin Jung
*
*/
class webkit2png {
	/**
	 * Holds the options and contains the default directory path.
	 * @var array
	 */
	private $options = array(
		'dir' => 'images/',
	);

	/**
	 * Set flags vlaues for the options.
	 * @var array
	 */
	private $flags = array(
		'url'            => '',
		'width'          => '-W',
		'height'         => '-H',
		'zoom'           => '-z',
		'fullsize'       => '-F',
		'thumb'          => '-T',
		'clipped'        => '-C',
		'clipped-width'  => '--clipwidth',
		'clipped-height' => '--clipheight',
        'max-width'      => '--UNSAFE-max-width',
		'scale'          => '-s',
		'dir'            => '-D',
		'filename'       => '-o',
		'md5'            => '-m',
		'datestamp'      => '-d',
		'delay'          => '--delay',
		'js'             => '--js',
		'no-images'      => '--no-images',
		'no-js'          => '--no-js',
		'transparent'    => '--transparent',
		'user-agent'     => '--user-agent',
	);

	/**
	 * Holds the query to be executed.
	 * @var string
	 */
	const QUERY = 'webkit2png ';

	/**
	 * Initialize the class
	 * @param string $url Provied URL
	 */
	public function __construct($url)
	{
		// Set the environment path so you have access to webkit2png within PHP.
		// If you installed webkit2png via homebrew, include the following path.
		putenv('PATH=' . (@$_env['path'] ?: '') .':/usr/local/bin');
		$webkit2png = trim(shell_exec('type -P webkit2png'));

        if (empty($webkit2png)){
		    throw new \Exception('Unable to find webkit2png. Please check your environment paths to ensure that PHP has access to the webkit2png binary.');
        }
        
		$this->setUrl($url);
	}

	/**
	 * Set the $url variable
	 */
	private function setUrl($url)
	{
        var_dump($url);
		$this->options['url'] = $url;
	}

	/**
	 * Set the $options variable
	 * @param array $options Provided options
	 */
	public function setOptions($options = null)
	{
		$this->options = array_merge($this->options, $options);
	}
    
    /**
     * Get user defined options
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

	/**
	 * Generate the image(s)
	 */
	public function getImage()
	{
        $query = $this->getQuery();
        var_dump($query);
		return shell_exec($query);
	}

	/**
	 * Generate and return the created query
	 */
	public function getQuery()
	{
        
        $options = array();
        
        foreach ($this->options as $flag => $val) {
            if (isset($this->flags[$flag])) {
                $val = true === $val ? null : $val;
                $options[$flag] = array(
                    $this->flags[$flag],
                    $val ?: ''
                );
            }
        }

        $query = self::QUERY;

		foreach ($options as $key => $option) {
			$query .= $option[0] . ' ' . $option[1] . ' ';
		}
        
		return trim($query);
	}

}