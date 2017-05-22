(function (factory) {
    'use strict';
    // Browser globals:
    factory(window.jQuery);
}(function ($) {
    'use strict';
    $.widget("flinnt.htmlEditor", {
        options: {
            bgcolor: '#FFFFFF',
            skin: null,
            contentsCss: null,
            inline: false,
            toolbar: 'minimal',
            resizable: false,
            autoGrowStartUp: false,
            link: {
                showAdvanced: true,
                showTarget: true
            },
            extraPlugins: null,
            patchBootstrapModal: false
        },
        positions: {
            current: 0,
            beginning: 1,
            end: 2
        },
        toolbars: {
            'minimal':
            {
                "buttons": [
                    { name: 'format', items: ['Bold','Italic'] },
                    { name: 'bullets', items: ['BulletedList','NumberedList'] }
                ],
                "helptext": '<p class="marginL5 fs12 dark2">Keyboard Shortcuts: <strong>Enter (Return)</strong> - Start new Paragraph,&nbsp;&nbsp;<strong>Shift + Enter</strong> - Start new Line,&nbsp;&nbsp;<strong>Ctrl + B</strong> - Bold,&nbsp;&nbsp;<strong>Ctrl + I</strong> - Italics</p>'
            },
            'course_description':
            {
                "buttons": [
                    { name: 'format', items: ['Bold','Italic'] },
                    { name: 'bullets', items: ['BulletedList','NumberedList'] },
                    { name: 'links', items: ['Link', 'Unlink'] }
                ],
                "helptext": '<p class="marginL5 fs12 dark2">Keyboard Shortcuts: <strong>Enter (Return)</strong> - Start new Paragraph,&nbsp;&nbsp;<strong>Shift + Enter</strong> - Start new Line,&nbsp;&nbsp;<strong>Ctrl + B</strong> - Bold,&nbsp;&nbsp;<strong>Ctrl + I</strong> - Italics</p>'
            }
        },
        editor: {
            instance: null,
            config: {}
        },
        _create: function() {
            // map options mentioned as html attribute to widget
            for (var t in this.options)
                typeof this.element.data(t.toLowerCase()) != "undefined" && (this.options[t] = this.element.data(t.toLowerCase()));

            this.editor_helptext = "";

            $.proxy(this._configEditor, this)();

            // bind html editor
            $.proxy(this._bindEditor, this)();
        },
        _configEditor: function() {
            CKEDITOR.editorConfig = function( config ) {
                config.disableAutoInline = true;
            };

            // set skin
            this.options.skin && this.options.skin != '' ? this.editor.config.skin = this.options.skin : null;

            // set background color
            this.options.bgcolor && this.options.bgcolor != '' ? this.editor.config.uiColor = this.options.bgcolor : null;

            // set custom toolbar and help text
            if(this.options.toolbar) {
                if(typeof this.options.toolbar === "string") {
                    this.editor.config.toolbar = this.toolbars[this.options.toolbar].buttons;
                    this.editor_helptext = this.toolbars[this.options.toolbar].helptext || "";
                } else if (typeof this.options.toolbar === "object") {
                    this.editor.config.toolbar = this.options.toolbar.buttons;
                    this.editor_helptext = this.options.toolbar.helptext || "";
                }
            }
            this.editor.config.resize_enabled = this.options.resizable;
            this.editor.config.autoGrow_onStartup = this.options.autoGrowStartUp;
            this.editor.config.linkShowAdvancedTab = this.options.link.showAdvanced;
            this.editor.config.linkShowTargetTab = this.options.link.showTarget;
            this.options.extraPlugins != null ? this.editor.config.extraPlugins = this.options.extraPlugins : null;
        },
        _bindEditor: function() {
            var that = this;
            var edt;
            if(this.options.inline === true) {
                edt = CKEDITOR.inline("#" + this.element.id, this.editor.config);
            } else {
                edt = this.element.ckeditor(this.editor.config);
            }
            edt.on("instanceReady.ckeditor", $.proxy(this._editorReady, this)).on("destroy.ckeditor", $.proxy(this._editorDestroy, this)).on("setData.ckeditor", $.proxy(this._editorSetData, this)).on("dataReady.ckeditor", $.proxy(this._editorSetData, this)).on("getData.ckeditor", $.proxy(this._editorGetData, this));
            this.editor.instance = this.element.ckeditor().editor;
            if(this.options.patchBootstrapModal) {
                $.proxy(this.bootstrapModal, this)();
            }
        },
        _editorReady: function(e, editor) {
            if(this.editor_helptext != "") {
                var helptext = '<div class="cke_1 cke_bottom cke_inner cke_reset" style="background-image: none; background-color: #F5F5B5">' + this.editor_helptext + '</div>';
                $(editor.container.$).append(helptext);
            }
            /*console.log("instanceReady.ckeditor");
            console.log(editor);*/
        },
        _editorDestroy: function(e, editor) {
            /*console.log("destroy.ckeditor");
            console.log(editor);*/
        },
        _editorSetData: function(e, editor, data) {
            /*console.log("setData.ckeditor");
            console.log(editor);
            console.log(data);*/
        },
        _editorDataReady: function(e, editor) {
            /*console.log("dataReady.ckeditor");
            console.log(editor);*/
        },
        _editorGetData: function(e, editor, data) {
            /*console.log("getData.ckeditor");
            console.log(editor);
            console.log(data);*/
        },
        getEditorInstance: function() {
            return this.editor.instance;
        },
        appendHTML: function(position, htmlval) {
            var editor = CKEDITOR.instances[this.element.attr("id")];
            switch(position) {
                case this.positions.beginning:
                    var range = editor.createRange();
                    range.moveToElementEditStart( range.root );
                    editor.getSelection().selectRanges( [ range ] );
                    editor.insertHtml(htmlval);
                    break;
                case this.positions.end:
                    var range = editor.createRange();
                    range.moveToElementEditEnd( range.root );
                    editor.getSelection().selectRanges( [ range ] );
                    editor.insertHtml(htmlval);
                    break;
                case this.positions.current:
                    editor.insertHtml(htmlval);
                    break;
            }
        },
        destroyEditor: function() {
            if(typeof CKEDTOR.instaces["#" + this.element.id] !== "undefined") {
                CKEDTOR.instaces["#" + this.element.id].destroy();
                this.editor.instance = null;
            }
        },
        bootstrapModal: function() {
            $.fn.modal.Constructor.prototype.enforceFocus = function() {
                var $modalElement = this.$element;
                $(document).on('focusin.modal',function(e) {
                    var $parent = $(e.target.parentNode);
                    if ($modalElement[0] !== e.target
                        && !$modalElement.has(e.target).length
                        && $(e.target).parentsUntil('*[role="dialog"]').length === 0) {
                        $modalElement.focus();
                    }
                });
            };
        }
    });
}))