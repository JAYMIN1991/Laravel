(function (factory) {
    'use strict';
    // Browser globals:
    factory(window.jQuery);
}(function ($) {
    $.widget("flinnt.back_office_form", {
        options: {
        },
        _create: function() {
            for (var t in this.options)
                typeof this.element.data(t.toLowerCase()) != "undefined" && (this.options[t] = this.element.data(t.toLowerCase()));

            this._initEditor();
            this._loadValidators();
        },
        _initEditor: function() {
            if(typeof CKEDITOR !== "undefined") {
                CKEDITOR.config.disableNativeSpellChecker = false;
            }
        },
        _loadValidators: function() {
            var that = this;
            if(jQuery().validate) {

                if(typeof CKEDITOR !== "undefined") {
                    $.validator.addMethod("validate_htmleditor", function (value, element) {
                        var editor_id = $(element).attr("id"),
                            editor = CKEDITOR.instances[editor_id];
                        editor.updateElement();
                        editor_value = that.getTextFromHTML(editor.getData()).replace(/<[^>]*>/gi, '').trim();
                        if (editor_value.length === 0) {
                            $(element).val(editor_value);
                        } else {
                            $(element).val(editor.getData());
                        }
                        return $(element).val().length > 0;
                    }, "This field is required");
                }

                $.validator.addMethod("pattern", function(value, element, param) {
                    if ( this.optional( element ) ) {
                        return true;
                    }
                    if ( typeof param === "string" ) {
                        param = new RegExp( "^(?:" + param + ")$" );
                    }
                    return param.test( value );
                });
            }
        },
        _shuffle:function(array) {
            var currentIndex = array.length, temporaryValue, randomIndex;

            // While there remain elements to shuffle...
            while (0 !== currentIndex) {

                // Pick a remaining element...
                randomIndex = Math.floor(Math.random() * currentIndex);
                currentIndex -= 1;

                // And swap it with the current element.
                temporaryValue = array[currentIndex];
                array[currentIndex] = array[randomIndex];
                array[randomIndex] = temporaryValue;
            }

            return array;
        },
        _reverse: function(string) {
            var regexSymbolWithCombiningMarks = /(<%= allExceptCombiningMarks %>)(<%= combiningMarks %>+)/g;
            var regexSurrogatePair = /([\uD800-\uDBFF])([\uDC00-\uDFFF])/g;

            var frev = function(string) {
                // Step 1: deal with combining marks and astral symbols (surrogate pairs)
                string = string
                // Swap symbols with their combining marks so the combining marks go first
                    .replace(regexSymbolWithCombiningMarks, function ($0, $1, $2) {
                        // Reverse the combining marks so they will end up in the same order
                        // later on (after another round of reversing)
                        return frev($2) + $1;
                    })
                    // Swap high and low surrogates so the low surrogates go first
                    .replace(regexSurrogatePair, '$2$1');
                // Step 2: reverse the code units in the string
                var result = '';
                var index = string.length;
                while (index--) {
                    result += string.charAt(index);
                }
                return result;
            }
            result = frev(string);
            return result;
        },
        _strRot13: function(value) {
            return (value + '')
                .replace(/[a-z]/gi, function (s) {
                    return String.fromCharCode(s.charCodeAt(0) + (s.toLowerCase() < 'n' ? 13 : -13))
                })
        },
        _base64Encode: function(value) {
            if (typeof window !== 'undefined') {
                if (typeof window.btoa !== 'undefined') {
                    return window.btoa(value)
                }
            } else {
                return new Buffer(value).toString('base64')
            }
        },
        _e: function(value) {
            var self = this;
            var numbers = _.range(0, 9);
            numbers = self._shuffle(numbers);
            var num = '', num2 = '';
            for(var i=0; i<4; i++) {
                num = num + numbers[i] + "";
                numbers = self._shuffle(numbers);
                num2 = num2 + numbers[i] + "";
            }
            var out = num + value + num2;
            out = self._reverse(self._strRot13(out));
            out = self._base64Encode(out);

            return out;
        },
        parseISTDate: function(value) {
            var date = value.split("/");
            var d = parseInt(date[0], 10),
                m = parseInt(date[1], 10),
                y = parseInt(date[2], 10);
            return new Date(y, m - 1, d);
        },
        getTextFromHTML: function(htmltext) {
            var text = htmltext || "";
            var dv = document.createElement("DIV");
            dv.innerHTML = text;
            return dv.textContent || dv.innerText || "";
        }
    });
}));
