/*jslint es6:true*/
/*jshint esversion: 6 */
function g2_log (text) {
    if (window.debugJS !== undefined) {
        if (window.debugJS == true) {
            console.log(text);
        } else {
            // do nothing...
        }
    } else {
        console.log(text);
    }
}
class gcAjax {
    constructor (inputData, link = false) {
        console.log('Build ajax request!');
        this.method = 'POST';
        this.url = link;
        this.putTo = null;
        this.button = null;
        this.color = '#FFFFFF';
        this.query = '';
        this.value = '';
        this.jsonTable = false;
        this.tempelate = null;
        this.putToDisplay = null;
        this.form = null;
        this.callback = null;
        this.response = null;
        this.error = null;
        if (inputData != null) {
            if (typeof inputData === 'string') {
                console.log('Set methot to ' + inputData);
                this.method = inputData;
            } else if (typeof inputData === 'object') {
                if (inputData.tagName !== 'undefined' && inputData.tagName === 'FORM') {
                    console.log('Set form to ' + inputData.tagName);
                    this.form = inputData;
                    if (inputData.attributes.action.value != 'undefined' && this.url == false) {
                        this.url = inputData.attributes.action.value;
                    }
                } else {
                    console.log('Prepare parameter.');
                    for (var parameter in inputData) {
                        if (!inputData.hasOwnProperty(parameter)) throw ('Error g2_ajax declare with wrong configuration!');
                        if (inputData[parameter].tagName !== 'undefined' && inputData[parameter].tagName === 'FORM') {
                            console.log('Set form to asign ' + inputData[parameter].tagName);
                            this.form = inputData[parameter];
                        }
                        this[parameter] = inputData[parameter];
                    }
                }
            } else {
                throw ('Error g2_ajax declare with wrong type parameter!');
            }
        }
        /* The variable that makes Ajax possible! */
        try {
            /* Opera 8.0+, Firefox, Safari */
            this._ajax = new XMLHttpRequest();
        } catch (e) {
            /* Internet Explorer Browsers */
            try {
                this._ajax = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                try {
                    this._ajax = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {
                    /* Something went wrong */
                    alert("Your browser broke!");
                    return false;
                }
            }
        }
        if (inputData.autoSend !== 'undefined' && inputData.autoSend === true) this.send();
    }
    setForm (form) {
        if (inputData.tagName !== 'undefined' && inputData.tagName === 'FORM') {
            this.form = form;
            return true;
        } else {
            throw ('Error form are in wrong element!');
        }
        return this;
    }
    setURL (data) {
        this.url = data;
        return this;
    }
    setQuery (data, add) {
        if (Array.isArray(data)) {
            for (i = 0; i <= data.leght; i++) {
                if (i == 0) {
                    this.query += data[i] + ',';
                } else {
                    this.query += '&' + data[i] + ',';
                }
            }
        } else if (add) {
            this.query = this.query + data;
        } else {
            this.query = data;
        }
        return this;
    }
    addValue (data) {
        if (Array.isArray(data)) {
            for (i = 0; i <= data.leght; i++) {
                if (this.value == '') {
                    this.value += data[i];
                } else {
                    this.value += '&' + data[i];
                }
            }
        } else {
            if (this.value == '') {
                this.value += data;
            } else {
                this.value += '&' + data;
            }
        }
        return this;
    }
    setButton (id, color = '#FFFFFF') {
        if (id != '' || id != null) {
            this.button = id;
        } else {
            throw ("Loading element id can't set empty!");
        }
        if (color != '' || color != null) {
            this.color = color;
        }
        return this;
    }
    setResultId (id, display = false) {
        if (id != null && id != '') {
            this.putTo = id;
        } else {
            throw ("result element id can't set empty!");
        }
        if (display !== false) {
            if (display != null && display != '') {
                this.putToDisplay = display;
            } else {
                throw ("result element display can't set empty!");
            }
        }
        return this;
    }
    setMethod (method) {
        if (method.toLowerCase() != 'get' || method.toLowerCase() != 'post') {
            this.method = method;
        } else {
            throw ("Wrong method, it must get or post!");
        }
        return this;
    }
    setResultAsTable (bol, tmpl = false) {
        this.jsonTable = bol;
        if (tmpl != false) {
            this.tempelate = tmpl;
        }
        return this;
    }
    setCallback (f) {
        this.callback = f;
        return this;
    }
    send (id, lod = null, color = null) {
        // console.log(this.form);
        if (lod != null && lod != '') {
            this.button = lod;
        }
        if (color != '' && color != null) {
            this.color = color;
        }
        if (id != null && id != '') {
            this.putTo = id;
        }

        var result;
        var btnLast;
        var parent = this;

        addtoBTN('<div class="g2ajax-load-timer"></div> <style type="text/css"> /* Timer*/ .g2ajax-load-timer{color: ' + this.color + '; width: 20px; height: 20px; background-color: transparent; box-shadow: inset 0px 0px 0px 2px ' + this.color + '; border-radius: 50%; position: relative;} .g2ajax-load-timer:after, .g2ajax-load-timer:before{position: absolute; content:""; background-color: ' + this.color + '; } .g2ajax-load-timer:after{width: 8px; height: 2px; top: 9px; left: 9px; -webkit-transform-origin: 1px 1px; -moz-transform-origin: 1px 1px; transform-origin: 1px 1px; -webkit-animation: minhand 2s linear infinite; -moz-animation: minhand 2s linear infinite; animation: minhand 2s linear infinite; } .g2ajax-load-timer:before{width: 7px; height: 2px; top: 9px; left: 9px; -webkit-transform-origin: 1px 1px; -moz-transform-origin: 1px 1px; transform-origin: 1px 1px; -webkit-animation: hrhand 8s linear infinite; -moz-animation: hrhand 8s linear infinite; animation: hrhand 8s linear infinite; } @-webkit-keyframes minhand{0%{-webkit-transform:rotate(0deg)} 100%{-webkit-transform:rotate(360deg)} } @-moz-keyframes minhand{0%{-moz-transform:rotate(0deg)} 100%{-moz-transform:rotate(360deg)} } @keyframes minhand{0%{transform:rotate(0deg)} 100%{transform:rotate(360deg)} } @-webkit-keyframes hrhand{0%{-webkit-transform:rotate(0deg)} 100%{-webkit-transform:rotate(360deg)} } @-moz-keyframes hrhand{0%{-moz-transform:rotate(0deg)} 100%{-moz-transform:rotate(360deg)} } @keyframes hrhand{0%{transform:rotate(0deg)} 100%{transform:rotate(360deg)} } </style>');
        if (this.form != null && this.form != '') {
            this.form.addEventListener("click", function (e) {
                e.preventDefault();
            });
            if (this.query == '') {
                console.log("Get value fron Form.");
                for (let i = 0; i < this.form.length; i++) {
                    let inputType = ['INPUT', 'SELECT', 'CHECKBOX'];
                    let fieldName; let fieldValue; let fieldTag; let cekform;
                    fieldName = this.form.elements[i].name;
                    fieldValue = this.form.elements[i].value;
                    fieldTag = this.form.elements[i].tagName;
                    if (inputType.includes(fieldTag)) {
                        if (fieldValue != '') {
                            try {
                                if (typeof cekform !== "undefined" && typeof cekform === "function") {
                                    cekform = cekform(this.form.elements[i]);
                                    if (!cekform) {
                                        addtoBTN(btnLast);
                                        return false;
                                    }
                                }
                            } catch (error) {
                                console.log(error);
                            }
                        } else {
                            console.log("Form fild" + fieldName + " is empty!");
                        }
                    }
                    if (fieldName != null && fieldName != '' && fieldValue != null && fieldValue != '') {
                        if (i > 0) { this.query += "&"; }
                        this.query += fieldName + "=" + fieldValue;
                    }
                }
            }
            if (this.url == '') {
                this.url = this.form.attributes.value;
            }
        }
        if (this.query != '') { this.query += '&'; }
        this.query += this.value;
        let input_variable = this.query.replace(/&+/g, ' ');
        if (this.method.toLowerCase() == 'post') {
            console.log('Do ajax method POST!');
            this._ajax.open(this.method, this.url, true);
            this._ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            if (this.query != '') { console.log("Send: " + input_variable); }
            this._ajax.send(this.query);
        } else if (this.method.toLowerCase() == 'get') {
            console.log('Do ajax method GET!');
            if (this.query != '') { console.log("Send: " + input_variable); }
            this._ajax.open(this.method, this.url + "?" + this.query, true);
            this._ajax.send(null);
        } else {
            console.log('Method not set!');
            return (false);
        }

        this._ajax.onreadystatechange = function () {
            if (this.readyState == 4) {
                console.log('Response recived!');
                if (this.status == 200) {
                    console.log('Puting response in page!');
                    if (parent.callback != null) {
                        console.log('Call callback function.');
                        parent.callback(this.responseText, document.getElementById(parent.putTo));
                    } else {
                        if (addtoHTML(this.responseText) == false) {
                            result = false;
                        }
                        else {
                            result = true;
                        }
                    }
                    addtoBTN(btnLast);
                    console.log('Ajax done!');
                    return result;
                } else if (this.status >= 500) {
                    // internal server error
                    addtoBTN(btnLast);
                    addtoHTML(this.responseText);
                    console.log('Ajax failed! internal server error (' + this.status + ')');
                    return false;
                } else if (this.status >= 402 && this.status <= 420) {
                    // error
                    addtoBTN(btnLast);
                    console.log('Ajax failed! something error (' + this.status + ')');
                    return false;
                } else if (this.status == 400 || this.statuss == 401) {
                    // bad request & unauthorized error
                    addtoBTN(btnLast);
                    console.log('Ajax failed! bad request & unauthorized error (' + this.status + ')');
                    return false;
                }
            }
            if (this.readyState == 3) {
                console.log('Waiting response!');
            }
            if (this.readyState == 2) {
                console.log('Request send!');
            }
        };
        function addtoBTN (TEXT) {
            if (parent.button != null && parent.button != '') {
                btnLast = parent.button.innerHTML;
                parent.button.innerHTML = TEXT;
            }
        }
        function addtoHTML (TEXT) {
            if (parent.putTo != null && parent.putTo != '') {
                if (parent.jsonTable == true) {
                    console.log('Build table');
                    var table = new gcTable(TEXT, parent.tempelate);
                    console.log('Put table to element id:"' + parent.putTo + '"');
                    document.getElementById(parent.putTo).innerHTML = '';
                    document.getElementById(parent.putTo).appendChild(table);
                }
                else {
                    console.log('Put result to element id:"' + parent.putTo + '"');
                    if (parent.tempelate != null) {
                        if (typeof parent.tempelate === 'function') {
                            TEXT = parent.tempelate(TEXT);
                        } else { console.error('Error tempelate must be a function!'); }
                    }
                    document.getElementById(parent.putTo).innerHTML = TEXT;
                }
                if (parent.putToDisplay != null) {
                    console.log('Set element display to "' + parent.putToDisplay + '"');
                    document.getElementById(parent.putTo).style.display = parent.putToDisplay;
                }
                return true;
            } else {
                console.error('Error id element for result not set!');
                return false;
            }
        }
        return result;
    }
}