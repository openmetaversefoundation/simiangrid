/**
 * Jijawi
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to mail@dasprids.de so I can send you a copy immediately.
 *
 * @category   Jijawi
 * @copyright  Copyright (c) 2009 Ben Scholzen "DASPRiD" (http://www.dasprids.de)
 * @license    http://jijawi.org/license/new-bsd    New BSD License
 * @version    $Id$
 */

/**
 * Update a progressbar
 * 
 * @param  string id
 * @param  float  percent
 * @return void
 */
function updateProgressBar(id, percent)
{
    var progressBar = document.getElementById(id);
    var progress    = progressBar.firstChild;
    
    // We cannot just update the width, as it comes to redraw problems in e.g.
    // Firefox 3, thus we recreate the progress element with the new width.
    var newProgress         = document.createElement('div');
    newProgress.style.width = Math.round(percent / 100 * 298) + 'px';
    newProgress.className   = progress.className;
    
    progressBar.removeChild(progress);
    progressBar.appendChild(newProgress);
}

/**
 * Intermediate a progressbar
 * 
 * @param  string id
 * @return void
 */
function startIntermediateProgressBar(id)
{
    updateProgressBar(id, 10);
    
    var progressBar = document.getElementById(id);
    var progress    = progressBar.firstChild;
    
    progress.style.left    = '0px';
    intermediate.direction = 'right';
    intermediate.progress  = progress;
    intermediate.id        = id;

    intermediate.interval = window.setInterval(function()
    {
        var position = parseInt(intermediate.progress.style.left);
        var width    = parseInt(intermediate.progress.style.width);
        
        if (intermediate.direction == 'right') {
            intermediate.progress.style.left = (position + 10) + 'px';
            
            if (position + width >= 298) {
                intermediate.progress.style.left = (298 - width) + 'px';
                intermediate.direction = 'left';
            }
        } else {
            intermediate.progress.style.left = (position - 10) + 'px';
            
            if (position <= 0) {
                intermediate.progress.style.left = '0px';
                intermediate.direction = 'right';
            }
        }
    }, 40);
}

/**
 * Stop the intermediation of the progressbar
 * 
 * @return void
 */
function stopIntermediateProgressBar()
{
    window.clearInterval(intermediate.interval);
    updateProgressBar(intermediate.id, 100);
}

/**
 * Storage for intermedia data
 */
var intermediate = {};