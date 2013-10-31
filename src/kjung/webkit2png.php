<?php
namespace kjung;

/**
* PHP wrapper class for the webkit2png application on OS X.
*
* @author Kevin Jung, et al.
*
*/
class webkit2png
{
	/**
	 * Holds the query to be executed.
	 * @var string
	 */
	private $binary = 'webkit2png';
    
	/**
	 * Holds the options and contains the default directory path.
	 * @var array
	 */
	private $options;

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
	 * Initialize the class
	 * @param string $url Provied URL
	 */
	public function __construct($defaults = array(), $path = '')
	{
		// Set the environment path so you have access to webkit2png within PHP.
		// If you installed webkit2png via homebrew, include the following path.
		
        $this->binary = rtrim($path, '/') . '/' . $this->binary;
        
		$webkit2png = trim(shell_exec('type -P ' . $this->binary));

        if (empty($webkit2png)){
		    throw new \Exception('Unable to find webkit2png. Please check your environment paths to ensure that PHP has access to the webkit2png binary.');
        }
        
		$this->options = $defaults;
	}

	/**
	 * Generate the image(s)
	 */
	public function grab($url = null, $options = null)
	{
        $query = $this->query($url, $options);
		return shell_exec($this->binary . $query);
	}

	/**
	 * Generate and return the created query
	 */
	private function query($url, $user_options)
	{
        $user_options = array_merge($user_options, $this->options);
        $user_options['url'] = $url;
        $options = array();
        foreach ($user_options as $flag => $val) {
            if (isset($this->flags[$flag])) {
                $val = true === $val ? null : $val;
                $options[$flag] = array(
                    $this->flags[$flag],
                    $val ?: ''
                );
            }
        }
        
        $query = '';
		foreach ($options as $key => $option) {
			$query .= ' ' . $option[0] . ' ' . $option[1];
		}
        
		return $query;
	}

}