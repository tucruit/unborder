"use strict";
/// <reference path="../../@types/BgE.d.ts" />
BgE.registerTypeModule('Ckeditor', {
    data: {
        editor: null,
    },
    open: function (editorDialog, type) {
        var CKEDITOR_STYLE_SET_NAME = 'bgeditor';
        var $editor = editorDialog.$el.find('[name="bge-ckeditor"]');
        if ($editor.val() === '本文を入力してください') {
            $editor.val('');
        }
        var defaultConfig = {
            toolbar: [
                ['Source', 'ShowBlocks'],
                ['Templates'],
                ['NumberedList', 'BulletedList', 'Outdent', 'Indent', 'Blockquote'],
                ['Bold', 'Underline', 'Strike', 'Subscript', 'Superscript'],
                ['JustifyLeft', 'JustifyCenter', 'JustifyRight'],
                ['Link', 'Unlink', 'Anchor'],
                ['Table', 'HorizontalRule', 'Nbsp'],
                ['Styles'],
                ['FontSize'],
                ['TextColor', 'BGColor'],
            ],
            contentsCss: BgE.cssListForCKEditor.split(','),
            bodyClass: 'bge_content bge-contents',
            stylesSet: [
                { name: '標準', element: 'p' },
                { name: '中見出し(Lv.3)', element: 'h3' },
                { name: '小見出し(Lv.4)', element: 'h4' },
                { name: '小見出し(Lv.5)', element: 'h5' },
                { name: '小見出し(Lv.6)', element: 'h6' },
            ],
            allowedContent: true,
            basicEntities: false,
            entities: false,
            forcePasteAsPlainText: true,
            language: 'ja',
            skin: 'moono',
            customConfig: '',
            templates_replaceContent: false,
        };
        /**
         * @deprecated
         */
        // @ts-ignore
        defaultConfig['autoParagraph'] = false;
        var config = $.extend({}, defaultConfig, BgE.config.ckeditorConfig);
        var dtd = CKEDITOR.dtd;
        dtd.a.address = 1;
        dtd.a.article = 1;
        dtd.a.aside = 1;
        dtd.a.audio = 0;
        dtd.a.blockquote = 1;
        dtd.a.button = 0;
        dtd.a.details = 0;
        dtd.a.div = 1;
        dtd.a.dl = 1;
        dtd.a.embed = 0;
        dtd.a.fieldset = 1;
        dtd.a.figure = 1;
        dtd.a.footer = 1;
        dtd.a.form = 1;
        dtd.a.h1 = 1;
        dtd.a.h2 = 1;
        dtd.a.h3 = 1;
        dtd.a.h4 = 1;
        dtd.a.h5 = 1;
        dtd.a.h6 = 1;
        dtd.a.header = 1;
        dtd.a.hgroup = 1;
        dtd.a.hr = 1;
        dtd.a.iframe = 0;
        dtd.a.input = 0;
        dtd.a.keygen = 0;
        dtd.a.label = 0;
        dtd.a.math = 1;
        dtd.a.menu = 0;
        dtd.a.nav = 1;
        dtd.a.object = 0;
        dtd.a.ol = 1;
        dtd.a.p = 1;
        dtd.a.pre = 1;
        dtd.a.select = 0;
        dtd.a.svg = 1;
        dtd.a.table = 1;
        dtd.a.textarea = 0;
        dtd.a.ul = 1;
        dtd.a.video = 0;
        dtd.$removeEmpty["i"] = false;
        var height = $(window).height() || 0;
        height = height > 600 ? 600 : height - 50;
        height -= 220;
        CKEDITOR.config.height = height + 'px';
        if (!CKEDITOR.stylesSet.get(CKEDITOR_STYLE_SET_NAME)) {
            CKEDITOR.stylesSet.add(CKEDITOR_STYLE_SET_NAME, config.stylesSet);
        }
        CKEDITOR.config.stylesCombo_stylesSet = CKEDITOR_STYLE_SET_NAME;
        config.templates_files = ['/admin/editor_templates/js'];
        if (config.protectedSource) {
            config.protectedSource.push(/<\?[\s\S]*?\?>/g);
        }
        var cke = CKEDITOR.replace($editor.get(0), config);
        type.module.setData('editor', cke);
        /**
         * TODO: アップローダー連携
         */
        // cke.on('pluginsLoaded', (ev) => {
        // 	cke.addCommand('baserUploader', new CKEDITOR.dialogCommand('baserUploaderDialog'));
        // 	cke.ui.addButton('BaserUploader', {
        // 		label: 'アップローダー',
        // 		command : 'baserUploader',
        // 		toolbar: 'toolbar',
        // 	});
        // });
    },
    beforeExtract: function (editorDialog, type) {
        var cke = type.module.getData('editor');
        if (cke) {
            cke.updateElement();
        }
    },
});
