<?php

declare(strict_types = 1);

namespace StanfordTagger;

/**
 * Class StanfordTagger
 *
 * @author Patrick Schur <patrick_schur@outlook.de>
 * @package StanfordTagger
 */
abstract class StanfordTagger
{
    const OUTPUT_FORMAT_TSV = 1;

    const OUTPUT_FORMAT_SLASH_TAGS = 2;

    const OUTPUT_FORMAT_XML = 4;

    /**
     * @var string Maximum memory usage in Megabytes ("m") or Gigabytes ("g")
     */
    private $mx = '2g';

    /**
     * @var string The path of the java executable
     */
    private $java = 'java';

    /**
     * @var string The output format (tsv, slashTags or xml)
     */
    private $format = 'slashTags';

    /**
     * @var string The class path of the Java ARchive
     */
    private $jar;

    /**
     * Sets the path of the java executable
     *
     * @param string $path Path of the java executable
     */
    public function setJavaPath(string $path)
    {
        $this->java = $path;
    }

    public function getJavaPath()
    {
        return $this->java;
    }

    public function setOutputFormat(string $format)
    {
        switch ($format)
        {
            case self::OUTPUT_FORMAT_TSV:
                $this->format = 'tsv';
                break;
            case self::OUTPUT_FORMAT_SLASH_TAGS:
                $this->format = 'slashTags';
                break;
            case self::OUTPUT_FORMAT_XML:
                $this->format = 'xml';
                break;
            default:
                throw new \InvalidArgumentException('Wrong output format!');
        }
    }

    public function getOutputFormat()
    {
        return $this->format;
    }

    public function setJarArchive(string $jar)
    {
        if (!file_exists($jar))
        {
            throw new \InvalidArgumentException('Could not found any Java ARchives!');
        }

        $this->jar = $jar;
    }

    public function getJarArchive()
    {
        return $this->jar;
    }

    protected function getTmpFile($str)
    {
        $tmpf = tempnam(sys_get_temp_dir(), 'php');

        $fh = fopen($tmpf, 'w');

        fwrite($fh, $str);
        fclose($fh);

        return $tmpf;
    }

    /**
     * Sets the maximum memory usage
     *
     * @param string $mx Maxmimum memory usage in Megabytes or Gigabytes
     */
    public function setMaxMemoryUsage(string $mx)
    {
        if (preg_match('/^\d+[MG]$/i', $mx) === false)
        {
            throw new \InvalidArgumentException('Wrong format!');
        }

        $this->mx = $mx;
    }

    public function getMaxMemoryUsage()
    {
        return $this->mx;
    }
}