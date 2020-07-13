<?php
/**
 * Desc: 文件扫描类
 * Date: 2020-07-13
 */
namespace TpInterfaceDoc;

class Scan
{
    // 需要扫描的文件夹数组
    protected $dirArray;

    public function __construct()
    {
        $arr = ['demo'];
        foreach ($arr as $value) $this->dirArray []= $this->getBaseDir() ."/". $value;
    }

    /**
     * 扫描
     */
    public function doScan()
    {
        $nodes = [];

        $this->eachFolder(function (\ReflectionClass $reflection, $prenode) use (&$nodes) {
            $docParserClass = new DocParser();
            $nodes[$prenode]['title'] = $docParserClass->parse($reflection->getDocComment())['title'];
            foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
                $action = strtolower($method->getName());
                list($node, $comment) = ["{$prenode}{$action}", $method->getDocComment()];
                if (stripos($comment, '@apitrue') !== false) { // 是否为接口
                    $docParserMethod = new DocParser();
                    $nodes[$prenode]['interfaces'] []= $docParserMethod->parse($comment); // 注释内容数组
                }
            }
        });
        return $nodes;
    }

    /**
     * 遍历指定文件夹,获取文件夹下所有的php文件
     * @param $callable
     * @throws \ReflectionException
     */
    protected function eachFolder($callable)
    {
        foreach ($this->dirArray as $dir) {
            foreach (self::scanPath($dir) as $file) {
                if (!preg_match("|/(\w+)/controller/(.+)\.php$|", $file, $matches)) continue;
                list($module, $controller) = [$matches[1], strtr($matches[2], '/', '.')];
                if (class_exists($class = substr(strtr($matches[0], '/', '\\'), 0, -4))) {
                    call_user_func($callable, new \ReflectionClass($class), self::parseString("{$module}/{$controller}/"));
                }
            }
        }
    }

    /**
     * 获取所有PHP文件列表
     * @param string $dirname 扫描目录
     * @param array $data 额外数据
     * @param string $ext 有文件后缀
     * @return array
     */
    protected static function scanPath($dirname, $data = [], $ext = 'php')
    {
        foreach (glob("{$dirname}*") as $file) {
            if (is_dir($file)) {
                $data = array_merge($data, self::scanPath("{$file}/"));
            } elseif (is_file($file) && pathinfo($file, 4) === $ext) {
                $data[] = str_replace('\\', '/', $file);
            }
        }
        return $data;
    }

    /**
     * 获取项目根路径
     * @return string
     */
    protected function getBaseDir()
    {
        return dirname(__DIR__);
    }

    /**
     * 驼峰转下划线规则
     * @param string $node 节点名称
     * @return string
     */
    protected static function parseString($node)
    {
        if (count($nodes = explode('/', $node)) > 1) {
            $dots = [];
            foreach (explode('.', $nodes[1]) as $dot) {
                $dots[] = trim(preg_replace("/[A-Z]/", "_\\0", $dot), "_");
            }
            $nodes[1] = join('.', $dots);
        }
        return strtolower(join('/', $nodes));
    }
}


