/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/bootstrap.scss');
require("bootstrap");
const hljs = require('highlight.js');
// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// const $ = require('jquery');

document.addEventListener('DOMContentLoaded', function () {
    if (typeof CKEDITOR !== 'undefined') {
        CKEDITOR.on('instanceReady', function (evt) {
            evt.editor.dataProcessor.htmlFilter.addRules({
                elements: {
                    img: function (el) {
                        el.addClass('img-fluid');
                    }
                }
            });
        });
    }
    hljs.initHighlightingOnLoad();
    var pre = document.getElementsByTagName('code');
    for (var i = 0; i < pre.length; i++) {
        pre[i].innerHTML = `<span class="line-number pl-1"></span>${pre[i].innerHTML}<span class="cl"></span>`;
        let arr = pre[i].className.split("-");
        if (typeof arr[1] !== 'undefined') {
            if (arr[1] === 'cpp') {
                arr[1] = 'c++';
            }
            if (arr[1] === 'cs') {
                arr[1] = 'c#';
            }
            pre[i].innerHTML = `<div class="bg-dark text-white language p-2">` + arr[1].toUpperCase() + `</div>${pre[i].innerHTML}`;
        }
        var num = pre[i].innerHTML.split(/\n/).length;
        for (var j = 0; j < num; j++) {
            var line_num = pre[i].getElementsByTagName('span')[0];
            line_num.innerHTML += '<span>' + (j + 1) + '</span>';
        }
    }
});