<?php
/** Simian grid services
 *
 * PHP version 5
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 * 3. The name of the author may not be used to endorse or promote products
 *    derived from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE AUTHOR ``AS IS'' AND ANY EXPRESS OR
 * IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
 * OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
 * IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT
 * NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF
 * THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 *
 * @package    SimianGrid
 * @author     Jim Radford <http://www.jimradford.com/>
 * @copyright  Open Metaverse Foundation
 * @license    http://www.debian.org/misc/bsd.license  BSD License (3 Clause)
 * @link       http://openmetaverse.googlecode.com/
 */
require_once ('Interface.OSD.php');

class Vector3d implements IOSD
{
    public $X;
    public $Y;
    public $Z;
    const strpat = '/^[\<\[]?(?<X>[+-]?r?((([0-9]+(\.)?)|([0-9]*\.[0-9]+))([eE][+-]?[0-9]+)?)),(?<Y>[+-]?r?((([0-9]+(\.)?)|([0-9]*\.[0-9]+))([eE][+-]?[0-9]+)?)),(?<Z>[+-]?r?((([0-9]+(\.)?)|([0-9]*\.[0-9]+))([eE][+-]?[0-9]+)?))[\>\]]?$/';

    public function __construct($x = 0, $y = 0, $z = 0)
    {
        $this->X = $x;
        $this->Y = $y;
        $this->Z = $z;
    }

    public function __toString()
    {
        return sprintf("<%s, %s, %s>", $this->X, $this->Y, $this->Z);
    }

    public function toOSD()
    {
        return sprintf("[%s, %s, %s]", $this->X, $this->Y, $this->Z);
    }

    static function fromOSD($osdStr)
    {
        if (preg_match(self::strpat, preg_replace('/\s/', '', $osdStr), $matches))
        {
            return new Vector3d($matches["X"], $matches["Y"], $matches["Z"]);
        }
        throw new Exception("The input string does not appear to be valid: " + $osdStr);
    }

    static function Parse($str)
    {
        if (is_array($str) && count($str) == 3)
        {
            $str = '<' . implode(',', $str) . '>';
        }
        
        if (preg_match(self::strpat, preg_replace('/\s/', '', $str), $matches))
        {
            return new Vector3d($matches["X"], $matches["Y"], $matches["Z"]);
        }
        else
        {
            return NULL;
        }
    }

    static function TryParse($str, &$out)
    {
        if (is_array($str) && count($str) == 3)
        {
            $str = '<' . implode(',', $str) . '>';
        }
        else
        {
            $str = trim($str);
        }
        if (preg_match(self::strpat, preg_replace('/\s/', '', $str), $matches))
        {
            $out = new Vector3d($matches["X"], $matches["Y"], $matches["Z"]);
            return TRUE;
        }
        return FALSE;
    }

    static function Zero()
    {
        return new Vector3d(0, 0, 0);
    }
}
