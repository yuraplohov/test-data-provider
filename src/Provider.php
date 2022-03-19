<?php

namespace Yuraplohov\TestDataProvider;

class Provider
{
    /**
     * @param string $path (Examples: 'Service', 'Service/request', 'Service/request.input')
     * The path must be in the subdirectory 'data', that must be at the same level as your test.
     * The path can be the name of a directory, file (php or any text file: xml, json, txt ...) or element in php array
     * @return mixed
     * A content of the specific file or php array element will be returned
     */
    public function get(string $path): mixed
    {
        $dataPath = $this->getDataPath(debug_backtrace(8)[0]['file']);

        if (is_dir($dataPath . $path)) {
            return $this->getDirData($dataPath . $path);
        }

        $firstDotPos = stripos($path, '.');

        if ($firstDotPos) {

            $elementsString = substr($path, $firstDotPos + 1);

            $elements = explode('.', $elementsString);

            $path = substr($path, 0, $firstDotPos);
        }

        $filePath = $dataPath . $path . '.php';

        if (file_exists($filePath)) {

            $result = $this->getPHPFile($filePath);

            if (isset($elements)) {
                foreach ($elements as $element) {
                    $result = $result[$element];
                }
            }

            return $result;
        }

        return $this->getAnyTextFile($dataPath, $path);
    }

    /**
     * @param array $caseDirs (Example: ['FirstCase', 'SecondCase'])
     * Case directories must be in the subdirectory 'data', that must be at the same level as your test
     * @return array
     * An array of the contents of all files in specific directories will be returned.
     * The array structure fits the Codeception framework
     */
    public function getCodeceptionCases(array $caseDirs): array
    {
        $dataPath = $this->getDataPath(debug_backtrace(8)[0]['file']);

        return $this->getCases($dataPath, $caseDirs);
    }

    /**
     * @param array $caseDirs (Example: ['FirstCase', 'SecondCase'])
     * Case directories must be in the subdirectory 'data', that must be at the same level as your test
     * @return array
     * An array of the contents of all files in specific directories will be returned.
     * The array structure fits the PHPUnit framework
     */
    public function getPHPUnitCases(array $caseDirs): array
    {
        $dataPath = $this->getDataPath(debug_backtrace(8)[0]['file']);

        $result = $this->getCases($dataPath, $caseDirs);

        foreach ($result as $key => $item) {
            $result[$key] = [$item];
        }

        return $result;
    }

    private function getDataPath(string $testFilePath)
    {
        return substr($testFilePath, 0, strrpos($testFilePath, DIRECTORY_SEPARATOR)) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR;
    }

    private function getAnyTextFile(string $dataPath, string $path): string
    {
        $pos = strripos($dataPath . $path, DIRECTORY_SEPARATOR);

        $dirPath = substr($dataPath . $path, 0, $pos);

        $files = scandir($dirPath);

        foreach ($files as $file) {

            $filePath = $dirPath . DIRECTORY_SEPARATOR . $file;

            if (is_file($filePath)) {

                list($key, $ext) = explode('.', $file);

                if ($key === $path) {
                    return $this->getFileContent($filePath, $ext);
                }
            }
        }

        throw new \InvalidArgumentException('File ' . $dataPath . $path . ' does not exist with any extension');
    }

    private function getCases(string $dataPath, array $caseDirs): array
    {
        $result = [];

        foreach ($caseDirs as $caseDir) {

            $casePath = $dataPath . $caseDir;

            $result[$caseDir] = $this->getDirData($casePath);
        }

        return $result;
    }

    private function getDirData(string $casePath): array
    {
        $files = scandir($casePath);

        $result = [];

        foreach ($files as $file) {

            $filePath = $casePath . DIRECTORY_SEPARATOR . $file;

            if (is_file($filePath)) {

                list($key, $ext) = explode('.', $file);

                $result[$key] = $this->getFileContent($filePath, $ext);
            }
        }

        return $result;
    }

    private function getFileContent(string $filePath, string $ext): array|string
    {
        if ('php' === $ext) {
            return $this->getPHPFile($filePath);
        }

        return $this->getTextFile($filePath);
    }

    private function getPHPFile($path): array
    {
        return include($path);
    }

    private function getTextFile($path): string
    {
        return file_get_contents($path);
    }
}
