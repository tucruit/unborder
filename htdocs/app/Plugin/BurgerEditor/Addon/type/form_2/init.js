"use strict";
/// <reference path="../../@types/BgE.d.ts" />
BgE.registerTypeModule('Form',  {
    open: function (editorDialog, type) {
       var $brandSet = type.$el.find('div.formShortCode');
       var $descrip =  type.$el.find('p.bge-title-h3');
       $brandSet.text('[InstantPage.getElement'+ ' lp_form2' +']');
       $descrip.remove();
    },
});

