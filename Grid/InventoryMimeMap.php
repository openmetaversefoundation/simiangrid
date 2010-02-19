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

global $InventoryMimeMap;
$InventoryMimeMap = array('image/x-j2c'=>0,
                          'image/jp2'=>0,
                          'application/ogg'=>1,
                          'application/vnd.ll.callingcard'=>2,
                          'application/x-metaverse-callingcard'=>2,
                          'application/vnd.ll.landmark'=>3,
                          'application/x-metaverse-landmark'=>3,
                          'application/vnd.ll.clothing'=>5,
                          'application/x-metaverse-clothing'=>5,
                          'application/vnd.ll.primitive'=>6,
                          'application/x-metaverse-primitive'=>6,
                          'application/vnd.ll.notecard'=>7,
                          'application/x-metaverse-notecard'=>7,
                          'application/vnd.ll.folder'=>8,
                          'application/vnd.ll.rootfolder'=>9,
                          'application/vnd.ll.lsltext'=>10,
                          'application/x-metaverse-lsl'=>10,
                          'application/vnd.ll.lslbyte'=>11,
                          'application/x-metaverse-lso'=>11,
                          'image/tga'=>12,
                          'application/vnd.ll.bodypart'=>13,
                          'application/x-metaverse-bodypart'=>13,
                          'application/vnd.ll.trashfolder'=>14,
                          'application/vnd.ll.snapshotfolder'=>15,
                          'application/vnd.ll.lostandfoundfolder'=>16,
                          'audio/x-wav'=>17,
                          'image/jpeg'=>19,
                          'application/vnd.ll.animation'=>20,
                          'application/x-metaverse-animation'=>20,
                          'application/vnd.ll.gesture'=>21,
                          'application/x-metaverse-gesture'=>21,
                          'application/x-metaverse-simstate'=>22,
                          'application/octet-stream'=>-1);
