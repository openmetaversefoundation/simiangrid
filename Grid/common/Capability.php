<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Simian grid services
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

class Capability
{
    public $CapabilityID;
    public $OwnerID;
    public $Resource;
    public $Expiration;

    // -----------------------------------------------------------------
    // -----------------------------------------------------------------
    function __construct($db,$id)
    {
        $CapabilityID = $id;
        $OwnerID = UUID::Zero;
        $Resource = 'failed';
        $Expiration = -1;

        $sql = "SELECT OwnerID,Resource,TIMESTAMPDIFF(SECOND,NOW(),ExpirationDate) AS Expiration FROM Capabilities WHERE ID=:ID";

        $sth = $db->prepare($sql);
        if ($sth->execute(array(':ID' => $id)))
        {
            if ($sth->rowCount() > 0)
            {
                $obj = $sth->fetchObject();
                $this->OwnerID = $obj->OwnerID;
                $this->Resource = $obj->Resource;
                $this->Expiration = $obj->Expiration;
            }
        }
        else
        {
            log_message('error',sprintf('Failed to find a valid capability for %s',$id));
        }
    }
}