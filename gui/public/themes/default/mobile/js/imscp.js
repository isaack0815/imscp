/**
 * i-MSCP - internet Multi Server Control Panel
 *
 * @copyright   2010-2012 by i-MSCP | http://i-mscp.net
 * @link        http://i-mscp.net
 * @author      Raisen <kontakt@raisen.pl>
 * @author      i-MSCP Team
 *
 * @license
 * The contents of this file are subject to the Mozilla Public License
 * Version 1.1 (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at
 * http://www.mozilla.org/MPL/
 *
 * Software distributed under the License is distributed on an "AS IS"
 * basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
 * License for the specific language governing rights and limitations
 * under the License.
 *
 * Copyright (C) 2010-2012 by i-MSCP a internet Multi Server Control Panel. All Rights Reserved.
 */

/**
 *
 * Javascript sprintf by http://jan.moesen.nu/
 * This code is in the public domain.
 *
 * %% - Returns a percent sign
 * %b - Binary number
 * %c - The character according to the ASCII value
 * %d - Signed decimal number
 * %u - Unsigned decimal number
 * %f - Floating-point number
 * %o - Octal number
 * %s - String
 * %x - Hexadecimal number (lowercase letters)
 * %X - Hexadecimal number (uppercase letters)
 *
 * @todo check use of radix parameter of parseInt for (pType == 'o')
 * @todo check use of radix parameter of parseInt for (pType == 'x')
 * @todo check use of radix parameter of parseInt for (pType == 'X')
 */
function sprintf() {
    if (!arguments || arguments.length < 1 || !RegExp) {
        return;
    }
    var str = arguments[0];
    var re = /([^%]*)%('.|0|\x20)?(-)?(\d+)?(\.\d+)?(%|b|c|d|u|f|o|s|x|X)(.*)/;
    var a = [], b = [], numSubstitutions = 0, numMatches = 0;
    while ((a = re.exec(str))) {
        var leftpart = a[1], pPad = a[2], pJustify = a[3], pMinLength = a[4];
        var pPrecision = a[5], pType = a[6], rightPart = a[7];

        //alert(a + '\n' + [a[0], leftpart, pPad, pJustify, pMinLength, pPrecision);

        numMatches++;
        var subst;
        if (pType == '%') {
            subst = '%';
        } else {
            numSubstitutions++;
            if (numSubstitutions >= arguments.length) {
                alert('Error! Not enough function arguments (' + (arguments.length - 1) + ', excluding the string)\nfor the number of substitution parameters in string (' + numSubstitutions + ' so far).');
            }
            var param = arguments[numSubstitutions];
            var pad = '';
            if 		(pPad && pPad.substr(0,1) == "'") {pad = leftpart.substr(1,1);}
            else if	(pPad) {pad = pPad;}
            var justifyRight = true;
            if (pJustify && pJustify === "-") {justifyRight = false;}
            var minLength = -1;
            if (pMinLength) {minLength = parseInt(pMinLength, 10);}
            var precision = -1;
            if (pPrecision && pType == 'f') {precision = parseInt(pPrecision.substring(1), 10);}
            subst = param;
            if 		(pType == 'b') {subst = parseInt(param, 10).toString(2);}
            else if	(pType == 'c') {subst = String.fromCharCode(parseInt(param, 10));}
            else if	(pType == 'd') {subst = parseInt(param, 10) ? parseInt(param, 10) : 0;}
            else if	(pType == 'u') {subst = Math.abs(param);}
            else if	(pType == 'f') {subst = (precision > -1) ? Math.round(parseFloat(param) * Math.pow(10, precision)) / Math.pow(10, precision): parseFloat(param);}
            else if	(pType == 'o') {subst = parseInt(param).toString(8);}
            else if	(pType == 's') {subst = param;}
            else if	(pType == 'x') {subst = ('' + parseInt(param).toString(16)).toLowerCase();}
            else if	(pType == 'X') {subst = ('' + parseInt(param).toString(16)).toUpperCase();}
        }
        str = leftpart + subst + rightPart;
    }
    return str;
}