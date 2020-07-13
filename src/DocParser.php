<?php
/**
 * Desc: 类文件描述
 * Date: 2020-07-13
 */
namespace TpInterfaceDoc;

/**
 * Parses the PHPDoc comments for metadata. Inspired by Documentor code base
 * @category   Framework
 * @package    restler
 * @subpackage helper
 * @author     Murray Picton <info@murraypicton.com>
 * @author     R.Arul Kumaran <arul@luracast.com>
 * @copyright  2010 Luracast
 * @license    http://www.gnu.org/licenses/ GNU General Public License
 * @link       https://github.com/murraypicton/Doqumentor
 */
class DocParser
{
    private $params = array();

    function parse($doc = '')
    {
        if ($doc == '') {
            return $this->params;
        }
        // Get the comment
        if (preg_match('#^/\*\*(.*)\*/#s', $doc, $comment) === false)
            return $this->params;
        $comment = trim($comment[1]);
        // Get all the lines and strip the * from the first character
        if (preg_match_all('#^\s*\*(.*)#m', $comment, $lines) === false)
            return $this->params;
        $this->parseLines($lines[1]);
        return $this->params;
    }

    private function parseLines($lines)
    {
        foreach ($lines as $line) {
            $parsedLine = $this->parseLine($line);
            if ($parsedLine === false && !isset($this->params ['description'])) {
                if (isset ($desc)) {
                    $this->params ['description'] = implode(PHP_EOL, $desc);
                }
                $desc = array();
            } elseif ($parsedLine !== false) {
                $desc [] = $parsedLine;
            }
        }
        $desc = isset($desc) ? implode(' ', $desc) : "";
        if (isset($desc) && !empty($desc)) $this->params['long_description'] = $desc;
    }

    private function parseLine($line)
    {
        // trim the whitespace from the line
        $line = trim($line);

        if (empty ($line))
            return false; // Empty line

        if (strpos($line, '@') === 0) {
            if (strpos($line, ' ') > 0) {
                // Get the parameter name
                $param = substr($line, 1, strpos($line, ' ') - 1);
                $value = substr($line, strlen($param) + 2); // Get the value
            } else {
                $param = substr($line, 1);
                $value = '';
            }
            // Parse the line and return false if the parameter is valid
            if ($this->setParam($param, $value))
                return false;
        }
        return $line;
    }

    private function setParam($param, $value)
    {
        if ($param == 'request' || $param == 'response')
            $value = $this->formatParamOrReturn($value);

        if ($param == 'class')
            list ($param, $value) = $this->formatClass($value);

        if (empty($this->params[$param])) {
            $this->params[$param] = $value;
        } else if ($param == 'request' || $param == 'response') {
            $this->params[$param] = array($this->params[$param], $value);
        } else {
            $this->params[$param] = $value?:$value + $this->params[$param];
        }
        return true;
    }

    private function formatClass($value)
    {
        $r = preg_split("[\(|\)]", $value);
        if (is_array($r)) {
            $param = $r [0];
            parse_str($r [1], $value);
            foreach ($value as $key => $val) {
                $val = explode(',', $val);
                if (count($val) > 1)
                    $value [$key] = $val;
            }
        } else {
            $param = 'Unknown';
        }
        return array(
            $param,
            $value
        );
    }

    private function formatParamOrReturn($string)
    {
        $param = explode(" ", trim($string));
        if (is_array($param)) {
            return [
                $param[0] ? $param[0] : 'unknow',
                $param[1] ? $param[1] : 'unknow',
                $param[2] ? $param[2] : 'unknow',
                $param[3] ? $param[3] : 'unknow',
            ];
        } else {
            return ['unknow', 'unknow', 'unknow', 'unknow'];
        }
    }
}
