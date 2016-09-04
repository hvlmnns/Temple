;$(function(){var e=function(){$('.fullpage').css({minWidth:$(window).width(),minHeight:$(window).height()})};e();$(window).on('resize',function(){var n=('undefined'!=typeof window.fullpageTimer)?window.fullpageTimer:!1;clearTimeout(n);window.fullpageTimer=window.setTimeout(function(){e()},80)})});var _self=(typeof window!=='undefined')?window:((typeof WorkerGlobalScope!=='undefined'&&self instanceof WorkerGlobalScope)?self:{});var Prism=(function(){var i=/\blang(?:uage)?-(\w+)\b/i,r=0,e=_self.Prism={util:{encode:function(i){if(i instanceof n){return new n(i.type,e.util.encode(i.content),i.alias)}
else if(e.util.type(i)==='Array'){return i.map(e.util.encode)}
else{return i.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/\u00a0/g,' ')}},type:function(e){return Object.prototype.toString.call(e).match(/\[object (\w+)\]/)[1]},objId:function(e){if(!e['__id']){Object.defineProperty(e,'__id',{value:++r})};return e['__id']},clone:function(n){var r=e.util.type(n);switch(r){case'Object':var t={};for(var i in n){if(n.hasOwnProperty(i)){t[i]=e.util.clone(n[i])}};return t;case'Array':return n.map&&n.map(function(n){return e.util.clone(n)})};return n}},languages:{extend:function(n,i){var r=e.util.clone(e.languages[n]);for(var t in i){r[t]=i[t]};return r},insertBefore:function(n,t,i,r){r=r||e.languages;var o=r[n];if(arguments.length==2){i=arguments[1];for(var a in i){if(i.hasOwnProperty(a)){o[a]=i[a]}};return o};var l={};for(var s in o){if(o.hasOwnProperty(s)){if(s==t){for(var a in i){if(i.hasOwnProperty(a)){l[a]=i[a]}}};l[s]=o[s]}};e.languages.DFS(e.languages,function(e,i){if(i===r[n]&&e!=n){this[e]=l}});return r[n]=l},DFS:function(n,i,a,r){r=r||{};for(var t in n){if(n.hasOwnProperty(t)){i.call(n,t,n[t],a||t);if(e.util.type(n[t])==='Object'&&!r[e.util.objId(n[t])]){r[e.util.objId(n[t])]=!0;e.languages.DFS(n[t],i,null,r)}
else if(e.util.type(n[t])==='Array'&&!r[e.util.objId(n[t])]){r[e.util.objId(n[t])]=!0;e.languages.DFS(n[t],i,t,r)}}}}},plugins:{},highlightAll:function(n,i){var t={callback:i,selector:'code[class*="language-"], [class*="language-"] code, code[class*="lang-"], [class*="lang-"] code'};e.hooks.run('before-highlightall',t);var o=t.elements||document.querySelectorAll(t.selector);for(var a=0,r;r=o[a++];){e.highlightElement(r,n===!0,t.callback)}},highlightElement:function(n,r,a){var s,c,o=n;while(o&&!i.test(o.className)){o=o.parentNode};if(o){s=(o.className.match(i)||[,''])[1].toLowerCase();c=e.languages[s]};n.className=n.className.replace(i,'').replace(/\s+/g,' ')+' language-'+s;o=n.parentNode;if(/pre/i.test(o.nodeName)){o.className=o.className.replace(i,'').replace(/\s+/g,' ')+' language-'+s};var u=n.textContent,t={element:n,language:s,grammar:c,code:u};e.hooks.run('before-sanity-check',t);if(!t.code||!t.grammar){e.hooks.run('complete',t);return};e.hooks.run('before-highlight',t);if(r&&_self.Worker){var l=new Worker(e.filename);l.onmessage=function(n){t.highlightedCode=n.data;e.hooks.run('before-insert',t);t.element.innerHTML=t.highlightedCode;a&&a.call(t.element);e.hooks.run('after-highlight',t);e.hooks.run('complete',t)};l.postMessage(JSON.stringify({language:t.language,code:t.code,immediateClose:!0}))}
else{t.highlightedCode=e.highlight(t.code,t.grammar,t.language);e.hooks.run('before-insert',t);t.element.innerHTML=t.highlightedCode;a&&a.call(n);e.hooks.run('after-highlight',t);e.hooks.run('complete',t)}},highlight:function(i,t,r){var a=e.tokenize(i,t);return n.stringify(e.util.encode(a),r)},tokenize:function(n,i,l){var v=e.Token,a=[n],k=i.rest;if(k){for(var s in k){i[s]=k[s]};delete i.rest};tokenloop:for(var s in i){if(!i.hasOwnProperty(s)||!i[s]){continue};var d=i[s];d=(e.util.type(d)==='Array')?d:[d];for(var y=0;y<d.length;++y){var r=d[y],C=r.inside,A=!!r.lookbehind,w=!!r.greedy,b=0,O=r.alias;if(w&&!r.pattern.global){var x=r.pattern.toString().match(/[imuy]*$/)[0];r.pattern=RegExp(r.pattern.source,x+'g')};r=r.pattern||r;for(var o=0,u=0;o<a.length;u+=(a[o].matchedStr||a[o]).length,++o){var f=a[o];if(a.length>n.length){break tokenloop};if(f instanceof v){continue};r.lastIndex=0;var t=r.exec(f),T=1;if(!t&&w&&o!=a.length-1){r.lastIndex=u;t=r.exec(n);if(!t){break};var h=t.index+(A?t[1].length:0),m=t.index+t[0].length,c=o,g=u;for(var L=a.length;c<L&&g<m;++c){g+=(a[c].matchedStr||a[c]).length;if(h>=g){++o;u=g}};if(a[o]instanceof v||a[c-1].greedy){continue};T=c-o;f=n.slice(u,g);t.index-=u};if(!t){continue};if(A){b=t[1].length};var h=t.index+b,t=t[0].slice(b),m=h+t.length,P=f.slice(0,h),S=f.slice(m),p=[o,T];if(P){p.push(P)};var D=new v(s,C?e.tokenize(t,C):t,O,t,w);p.push(D);if(S){p.push(S)};Array.prototype.splice.apply(a,p)}}};return a},hooks:{all:{},add:function(n,i){var t=e.hooks.all;t[n]=t[n]||[];t[n].push(i)},run:function(n,i){var t=e.hooks.all[n];if(!t||!t.length){return};for(var a=0,r;r=t[a++];){r(i)}}}};var n=e.Token=function(e,n,i,t,r){this.type=e;this.content=n;this.alias=i;this.matchedStr=t||null;this.greedy=!!r};n.stringify=function(i,t,a){if(typeof i=='string'){return i};if(e.util.type(i)==='Array'){return i.map(function(e){return n.stringify(e,t,i)}).join('')};var r={type:i.type,content:n.stringify(i.content,t,a),tag:'span',classes:['token',i.type],attributes:{},language:t,parent:a};if(r.type=='comment'){r.attributes['spellcheck']='true'};if(i.alias){var l=e.util.type(i.alias)==='Array'?i.alias:[i.alias];Array.prototype.push.apply(r.classes,l)};e.hooks.run('wrap',r);var o='';for(var s in r.attributes){o+=(o?' ':'')+s+'="'+(r.attributes[s]||'')+'"'};return'<'+r.tag+' class="'+r.classes.join(' ')+'"'+(o?' '+o:'')+'>'+r.content+'</'+r.tag+'>'};if(!_self.document){if(!_self.addEventListener){return _self.Prism};_self.addEventListener('message',function(n){var i=JSON.parse(n.data),t=i.language,r=i.code,a=i.immediateClose;_self.postMessage(e.highlight(r,e.languages[t],t));if(a){_self.close()}},!1);return _self.Prism};var t=document.currentScript||[].slice.call(document.getElementsByTagName('script')).pop();if(t){e.filename=t.src;if(document.addEventListener&&!t.hasAttribute('data-manual')){if(document.readyState!=='loading'){if(window.requestAnimationFrame){window.requestAnimationFrame(e.highlightAll)}
else{window.setTimeout(e.highlightAll,16)}}
else{document.addEventListener('DOMContentLoaded',e.highlightAll)}}};return _self.Prism})();if(typeof module!=='undefined'&&module.exports){module.exports=Prism};if(typeof global!=='undefined'){global.Prism=Prism};Prism.languages.css={'comment':/\/\*[\w\W]*?\*\//,'atrule':{pattern:/@[\w-]+?.*?(;|(?=\s*\{))/i,inside:{'rule':/@[\w-]+/}},'url':/url\((?:(["'])(\\(?:\r\n|[\w\W])|(?!\1)[^\\\r\n])*\1|.*?)\)/i,'selector':/[^\{\}\s][^\{\};]*?(?=\s*\{)/,'string':{pattern:/("|')(\\(?:\r\n|[\w\W])|(?!\1)[^\\\r\n])*\1/,greedy:!0},'property':/(\b|\B)[\w-]+(?=\s*:)/i,'important':/\B!important\b/i,'function':/[-a-z0-9]+(?=\()/i,'punctuation':/[(){};:]/};Prism.languages.css['atrule'].inside.rest=Prism.util.clone(Prism.languages.css);if(Prism.languages.markup){Prism.languages.insertBefore('markup','tag',{'style':{pattern:/(<style[\w\W]*?>)[\w\W]*?(?=<\/style>)/i,lookbehind:!0,inside:Prism.languages.css,alias:'language-css'}});Prism.languages.insertBefore('inside','attr-value',{'style-attr':{pattern:/\s*style=("|').*?\1/i,inside:{'attr-name':{pattern:/^\s*style/i,inside:Prism.languages.markup.tag.inside},'punctuation':/^\s*=\s*['"]|['"]\s*$/,'attr-value':{pattern:/.+/i,inside:Prism.languages.css}},alias:'language-css'}},Prism.languages.markup.tag)};Prism.languages.clike={'comment':[{pattern:/(^|[^\\])\/\*[\w\W]*?\*\//,lookbehind:!0},{pattern:/(^|[^\\:])\/\/.*/,lookbehind:!0}],'string':{pattern:/(["'])(\\(?:\r\n|[\s\S])|(?!\1)[^\\\r\n])*\1/,greedy:!0},'class-name':{pattern:/((?:\b(?:class|interface|extends|implements|trait|instanceof|new)\s+)|(?:catch\s+\())[a-z0-9_\.\\]+/i,lookbehind:!0,inside:{punctuation:/(\.|\\)/}},'keyword':/\b(if|else|while|do|for|return|in|instanceof|function|new|try|throw|catch|finally|null|break|continue)\b/,'boolean':/\b(true|false)\b/,'function':/[a-z0-9_]+(?=\()/i,'number':/\b-?(?:0x[\da-f]+|\d*\.?\d+(?:e[+-]?\d+)?)\b/i,'operator':/--?|\+\+?|!=?=?|<=?|>=?|==?=?|&&?|\|\|?|\?|\*|\/|~|\^|%/,'punctuation':/[{}[\];(),.:]/};Prism.languages.php=Prism.languages.extend('clike',{'keyword':/\b(and|or|xor|array|as|break|case|cfunction|class|const|continue|declare|default|die|do|else|elseif|enddeclare|endfor|endforeach|endif|endswitch|endwhile|extends|for|foreach|function|include|include_once|global|if|new|return|static|switch|use|require|require_once|var|while|abstract|interface|public|implements|private|protected|parent|throw|null|echo|print|trait|namespace|final|yield|goto|instanceof|finally|try|catch)\b/i,'constant':/\b[A-Z0-9_]{2,}\b/,'comment':{pattern:/(^|[^\\])(?:\/\*[\w\W]*?\*\/|\/\/.*)/,lookbehind:!0,greedy:!0}});Prism.languages.insertBefore('php','class-name',{'shell-comment':{pattern:/(^|[^\\])#.*/,lookbehind:!0,alias:'comment'}});Prism.languages.insertBefore('php','keyword',{'delimiter':/\?>|<\?(?:php)?/i,'variable':/\$\w+\b/i,'package':{pattern:/(\\|namespace\s+|use\s+)[\w\\]+/,lookbehind:!0,inside:{punctuation:/\\/}}});Prism.languages.insertBefore('php','operator',{'property':{pattern:/(->)[\w]+/,lookbehind:!0}});if(Prism.languages.markup){Prism.hooks.add('before-highlight',function(e){if(e.language!=='php'){return};e.tokenStack=[];e.code=e.code.replace(/(?:<\?php|<\?)[\w\W]*?(?:\?>)/ig,function(n){e.tokenStack.push(n);return'{{{PHP'+e.tokenStack.length+'}}}'})});Prism.hooks.add('after-highlight',function(e){if(e.language!=='php'){return};for(var n=0,i;i=e.tokenStack[n];n++){e.highlightedCode=e.highlightedCode.replace('{{{PHP'+(n+1)+'}}}',Prism.highlight(i,e.grammar,'php').replace(/\$/g,'$$$$'))};e.element.innerHTML=e.highlightedCode});Prism.hooks.add('wrap',function(e){if(e.language==='php'&&e.type==='markup'){e.content=e.content.replace(/(\{\{\{PHP[0-9]+\}\}\})/g,'<span class="token php">$1</span>')}});Prism.languages.insertBefore('php','comment',{'markup':{pattern:/<[^?]\/?(.*?)>/,inside:Prism.languages.markup},'php':/\{\{\{PHP[0-9]+\}\}\}/})};Prism.languages.insertBefore('php','variable',{'this':/\$this\b/,'global':/\$(?:_(?:SERVER|GET|POST|FILES|REQUEST|SESSION|ENV|COOKIE)|GLOBALS|HTTP_RAW_POST_DATA|argc|argv|php_errormsg|http_response_header)/,'scope':{pattern:/\b[\w\\]+::/,inside:{keyword:/(static|self|parent)/,punctuation:/(::|\\)/}}});$(function(){var e=350,n=$('.editor');$.each(n,function(){var e=$(this),n=e[0];n.editorDimensions=n.editorDimensions||{};n.editorDimensions.orgWidth=e.width();n.editorDimensions.orgHeight=e.height();e.css({height:e.height()-e.find('.editor-header').outerHeight()}).css({maxHeight:'initial'})});$(document).on('click','.editor .action-button',function(){var e=$(this),n=e.closest('.editor');if(e.hasClass('close-btn')){window.editor.close(n)}
else if(e.hasClass('minimize-btn')){window.editor.minimize(n)}
else if(e.hasClass('fullscreen-btn')){window.editor.fullscreen(n)}});n.on('mouseenter',function(){window.currentScrollTop=$(window).scrollTop();window.currentScrollLeft=$(window).scrollTop();$(window).on('scroll.prevent',function(){$(window).scrollTop(window.currentScrollTop);$(window).scrollLeft(window.currentScrollLeft)})});n.on('mouseleave',function(){$(window).off('scroll.prevent')});window.editor={};window.editor.closeAnimation=function(n){n.addClass('closing');setTimeout(function(){n.remove()},e*2)};window.editor.close=function(n){if(!n.hasClass('fullscreen')&&!n.hasClass('minimized')){window.editor.minimize(n);setTimeout(function(){window.editor.closeAnimation(n)},e)}
else{window.editor.closeAnimation(n)}};window.editor.minimize=function(n){var t=n[0];if(n.hasClass('minimized')||n.hasClass('fullscreen')){n.removeClass('minimized').stop(!0).animate({height:t.editorDimensions.orgHeight},e)}
else if(!n.hasClass('fullscreen')){var i=n.find('.editor-header').outerHeight();n.addClass('minimized').stop(!0).animate({height:i},e)}};window.editor.fullscreen=function(n){var i=n[0];if(n.hasClass('fullscreen')){n.stop(!0).css({left:i.editorDimensions.newLeft,top:i.editorDimensions.newTop,position:'relative'}).animate({width:i.editorDimensions.orgWidth,height:i.editorDimensions.orgHeight,left:0,top:0},e);n.removeClass('fullscreen')}
else{i.editorDimensions=i.editorDimensions||{};i.editorDimensions.newLeft=0-(n.offset().left-$(window).scrollLeft());i.editorDimensions.newTop=0-(n.offset().top-$(window).scrollTop());n.addClass('fullscreen');window.editor.minimize(n);console.log(Math.abs(i.editorDimensions.newLeft));console.log(Math.abs(n.offset().left));n.css({position:'fixed',left:Math.abs(i.editorDimensions.newLeft),top:Math.abs(i.editorDimensions.newTop),height:i.editorDimensions.orgHeight,width:i.editorDimensions.orgWidth}).stop(!0).animate({height:$(window).height(),width:$(window).width(),left:0,top:0},e,function(){n.css({})})}}});