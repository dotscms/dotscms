<?php
namespace Dots\File;
use Zend\Filter\File\Rename as RenameFilter;
/**
 * File Upload class for handling file uploads with ease
 */
class Upload
{
    protected $options = array();

    public function __construct($options)
    {
        $default = array(
            'unique_prefix' => true,
            'path' => '',
            'overwrite' => true,
            'destination' => null,
        );

        $options = array_merge($default, $options);
        $this->setOptions($options);
    }

    public function process($files)
    {
        // Make sure a proper array of files has been provided
        if (!is_array($files)){
            throw new \Exception('Invalid files array provided');
        }
        $destination = $this->options['destination'];
        // Make sure there is a destination set for the files
        if (empty($destination)) {
            throw new \Exception('Invalid file destination specified');
        }

        if (!is_writable($destination.$this->options['path'])){
            throw new \Exception('Unable to write to destination');
        }

        $paths = array();

        foreach($files as $key=>$info){
            $name = $this->options['path'];
            if ($this->options['unique_prefix']){
                $name = uniqid($name, true);
            }
            $name .= '_' . $info['name'];
            $rename = new RenameFilter(array(
                'source' => $info['tmp_name'],
                'target' => $destination . $name,
                'overwrite' => $this->options['overwrite']
            ));

            $rename->filter($info['tmp_name']);
            $paths[$key] = $name;
        }
        return $paths;
    }

    public function setOptions($options)
    {
        $this->options = $options;
    }

    public function getOptions()
    {
        return $this->options;
    }
}
