<?php

declare(strict_types = 1);

namespace StanfordTagger;

use LanguageDetection\Language;

/**
 * Class CRFClassifier
 *
 * @author Patrick Schur <patrick_schur@outlook.de>
 * @package StanfordTagger
 */
class CRFClassifier extends StanfordTagger
{
    /**
     * @var Language
     */
    private $lang;

    /**
     * @var string
     */
    private $classfier;

    public function __construct()
    {
        $this->lang = new Language(['de', 'en', 'es', 'zh-Hans', 'zh-Hant']);
    }

    /**
     * @param $str
     * @return null|string
     */
    public function tag($str)
    {
        $str = trim($str);

        if (empty($str))
        {
            return null;
        }

        $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator('.'));

        if (empty($this->getClassifier()))
        {
            $lookupTable = [
                'de' => 'german.conll.hgc_175m_600.crf.ser.gz',
                'en' => 'english.all.3class.distsim.crf.ser.gz',
                'es' => 'spanish.ancora.distsim.s512.crf.ser.gz',
                'zh-Hans' => 'chinese.misc.distsim.crf.ser.gz',
                'zh-Hant' => 'chinese.misc.distsim.crf.ser.gz',
            ];

            $lang = $this->lang->detect($str)->bestResults()->close();

            if (1 === count($lang))
            {
                $lang = $lookupTable[array_keys($lang)[0]];
            }
            else
            {
                $lang = $lookupTable['en'];
            }

            $regex = new \RegexIterator($it, '/^.+\.crf\.ser\.gz/i', \RecursiveRegexIterator::GET_MATCH);

            foreach ($regex as $value)
            {
                if (stripos($value[0], $lang) !== false)
                {
                    $this->setClassifier($value[0]);
                    break;
                }
            }

            if (empty($this->getClassifier()))
            {
                throw new \RuntimeException('No classifier was found!');
            }
        }

        if (empty($this->getJarArchive()))
        {
            $regex = new \RegexIterator($it, '/^.+stanford-ner\.jar$/i', \RecursiveRegexIterator::GET_MATCH);

            foreach ($regex as $value)
            {
                $this->setJarArchive($value[0]);
                break;
            }

            if (empty($this->getJarArchive()))
            {
                throw new \RuntimeException('Could not found any .jar files!');
            }
        }

        $cmd = escapeshellcmd(
            $this->getJavaPath()
            . ' -mx' . $this->getMaxMemoryUsage()
            . ' -cp "' . $this->getJarArchive() . PATH_SEPARATOR . '" edu.stanford.nlp.ie.crf.CRFClassifier'
            . ' -loadClassifier ' . $this->getClassifier()
            . ' -textFile ' . $this->getTmpFile($str)
            . ' -outputFormat ' . $this->getOutputFormat()
        );

        $descriptorspec = [
            0 => ["pipe", "r"],
            1 => ["pipe", "w"],
            2 => ["pipe", "r"]
        ];

        $process = proc_open($cmd, $descriptorspec, $pipes);

        $output = null;

        if (is_resource($process))
        {
            fclose($pipes[0]);

            $output = stream_get_contents($pipes[1]);
            fclose($pipes[1]);
            fclose($pipes[2]);

            proc_close($process);
        }

        return trim($output);
    }

    public function setClassifier(string $classifier)
    {
        if (!file_exists($classifier))
        {
            throw new \InvalidArgumentException('No classifier was found!');
        }

        $this->classfier = $classifier;
    }

    public function getClassifier()
    {
        return $this->classfier;
    }
}