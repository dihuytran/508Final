"use strict";/*!--------------------------------------------------------
 * Copyright (C) Microsoft Corporation. All rights reserved.
 *--------------------------------------------------------*/const _amdLoaderGlobal=this,_commonjsGlobal=typeof global=="object"?global:{};var AMDLoader;(function(c){c.global=_amdLoaderGlobal;class v{get isWindows(){return this._detect(),this._isWindows}get isNode(){return this._detect(),this._isNode}get isElectronRenderer(){return this._detect(),this._isElectronRenderer}get isWebWorker(){return this._detect(),this._isWebWorker}get isElectronNodeIntegrationWebWorker(){return this._detect(),this._isElectronNodeIntegrationWebWorker}constructor(){this._detected=!1,this._isWindows=!1,this._isNode=!1,this._isElectronRenderer=!1,this._isWebWorker=!1,this._isElectronNodeIntegrationWebWorker=!1}_detect(){this._detected||(this._detected=!0,this._isWindows=v._isWindows(),this._isNode=typeof module<"u"&&!!module.exports,this._isElectronRenderer=typeof process<"u"&&typeof process.versions<"u"&&typeof process.versions.electron<"u"&&process.type==="renderer",this._isWebWorker=typeof c.global.importScripts=="function",this._isElectronNodeIntegrationWebWorker=this._isWebWorker&&typeof process<"u"&&typeof process.versions<"u"&&typeof process.versions.electron<"u"&&process.type==="worker")}static _isWindows(){return typeof navigator<"u"&&navigator.userAgent&&navigator.userAgent.indexOf("Windows")>=0?!0:typeof process<"u"?process.platform==="win32":!1}}c.Environment=v})(AMDLoader||(AMDLoader={}));var AMDLoader;(function(c){class v{constructor(n,f,d){this.type=n,this.detail=f,this.timestamp=d}}c.LoaderEvent=v;class E{constructor(n){this._events=[new v(1,"",n)]}record(n,f){this._events.push(new v(n,f,c.Utilities.getHighPerformanceTimestamp()))}getEvents(){return this._events}}c.LoaderEventRecorder=E;class _{record(n,f){}getEvents(){return[]}}_.INSTANCE=new _,c.NullLoaderEventRecorder=_})(AMDLoader||(AMDLoader={}));var AMDLoader;(function(c){class v{static fileUriToFilePath(_,g){if(g=decodeURI(g).replace(/%23/g,"#"),_){if(/^file:\/\/\//.test(g))return g.substr(8);if(/^file:\/\//.test(g))return g.substr(5)}else if(/^file:\/\//.test(g))return g.substr(7);return g}static startsWith(_,g){return _.length>=g.length&&_.substr(0,g.length)===g}static endsWith(_,g){return _.length>=g.length&&_.substr(_.length-g.length)===g}static containsQueryString(_){return/^[^\#]*\?/gi.test(_)}static isAbsolutePath(_){return/^((http:\/\/)|(https:\/\/)|(file:\/\/)|(\/))/.test(_)}static forEachProperty(_,g){if(_){let n;for(n in _)_.hasOwnProperty(n)&&g(n,_[n])}}static isEmpty(_){let g=!0;return v.forEachProperty(_,()=>{g=!1}),g}static recursiveClone(_){if(!_||typeof _!="object"||_ instanceof RegExp||!Array.isArray(_)&&Object.getPrototypeOf(_)!==Object.prototype)return _;let g=Array.isArray(_)?[]:{};return v.forEachProperty(_,(n,f)=>{f&&typeof f=="object"?g[n]=v.recursiveClone(f):g[n]=f}),g}static generateAnonymousModule(){return"===anonymous"+v.NEXT_ANONYMOUS_ID+++"==="}static isAnonymousModule(_){return v.startsWith(_,"===anonymous")}static getHighPerformanceTimestamp(){return this.PERFORMANCE_NOW_PROBED||(this.PERFORMANCE_NOW_PROBED=!0,this.HAS_PERFORMANCE_NOW=c.global.performance&&typeof c.global.performance.now=="function"),this.HAS_PERFORMANCE_NOW?c.global.performance.now():Date.now()}}v.NEXT_ANONYMOUS_ID=1,v.PERFORMANCE_NOW_PROBED=!1,v.HAS_PERFORMANCE_NOW=!1,c.Utilities=v})(AMDLoader||(AMDLoader={}));var AMDLoader;(function(c){function v(g){if(g instanceof Error)return g;const n=new Error(g.message||String(g)||"Unknown Error");return g.stack&&(n.stack=g.stack),n}c.ensureError=v;class E{static validateConfigurationOptions(n){function f(d){if(d.phase==="loading"){console.error('Loading "'+d.moduleId+'" failed'),console.error(d),console.error("Here are the modules that depend on it:"),console.error(d.neededBy);return}if(d.phase==="factory"){console.error('The factory function of "'+d.moduleId+'" has thrown an exception'),console.error(d),console.error("Here are the modules that depend on it:"),console.error(d.neededBy);return}}if(n=n||{},typeof n.baseUrl!="string"&&(n.baseUrl=""),typeof n.isBuild!="boolean"&&(n.isBuild=!1),typeof n.paths!="object"&&(n.paths={}),typeof n.config!="object"&&(n.config={}),typeof n.catchError>"u"&&(n.catchError=!1),typeof n.recordStats>"u"&&(n.recordStats=!1),typeof n.urlArgs!="string"&&(n.urlArgs=""),typeof n.onError!="function"&&(n.onError=f),Array.isArray(n.ignoreDuplicateModules)||(n.ignoreDuplicateModules=[]),n.baseUrl.length>0&&(c.Utilities.endsWith(n.baseUrl,"/")||(n.baseUrl+="/")),typeof n.cspNonce!="string"&&(n.cspNonce=""),typeof n.preferScriptTags>"u"&&(n.preferScriptTags=!1),n.nodeCachedData&&typeof n.nodeCachedData=="object"&&(typeof n.nodeCachedData.seed!="string"&&(n.nodeCachedData.seed="seed"),(typeof n.nodeCachedData.writeDelay!="number"||n.nodeCachedData.writeDelay<0)&&(n.nodeCachedData.writeDelay=1e3*7),!n.nodeCachedData.path||typeof n.nodeCachedData.path!="string")){const d=v(new Error("INVALID cached data configuration, 'path' MUST be set"));d.phase="configuration",n.onError(d),n.nodeCachedData=void 0}return n}static mergeConfigurationOptions(n=null,f=null){let d=c.Utilities.recursiveClone(f||{});return c.Utilities.forEachProperty(n,(t,e)=>{t==="ignoreDuplicateModules"&&typeof d.ignoreDuplicateModules<"u"?d.ignoreDuplicateModules=d.ignoreDuplicateModules.concat(e):t==="paths"&&typeof d.paths<"u"?c.Utilities.forEachProperty(e,(i,r)=>d.paths[i]=r):t==="config"&&typeof d.config<"u"?c.Utilities.forEachProperty(e,(i,r)=>d.config[i]=r):d[t]=c.Utilities.recursiveClone(e)}),E.validateConfigurationOptions(d)}}c.ConfigurationOptionsUtil=E;class _{constructor(n,f){if(this._env=n,this.options=E.mergeConfigurationOptions(f),this._createIgnoreDuplicateModulesMap(),this._createSortedPathsRules(),this.options.baseUrl===""&&this.options.nodeRequire&&this.options.nodeRequire.main&&this.options.nodeRequire.main.filename&&this._env.isNode){let d=this.options.nodeRequire.main.filename,t=Math.max(d.lastIndexOf("/"),d.lastIndexOf("\\"));this.options.baseUrl=d.substring(0,t+1)}}_createIgnoreDuplicateModulesMap(){this.ignoreDuplicateModulesMap={};for(let n=0;n<this.options.ignoreDuplicateModules.length;n++)this.ignoreDuplicateModulesMap[this.options.ignoreDuplicateModules[n]]=!0}_createSortedPathsRules(){this.sortedPathsRules=[],c.Utilities.forEachProperty(this.options.paths,(n,f)=>{Array.isArray(f)?this.sortedPathsRules.push({from:n,to:f}):this.sortedPathsRules.push({from:n,to:[f]})}),this.sortedPathsRules.sort((n,f)=>f.from.length-n.from.length)}cloneAndMerge(n){return new _(this._env,E.mergeConfigurationOptions(n,this.options))}getOptionsLiteral(){return this.options}_applyPaths(n){let f;for(let d=0,t=this.sortedPathsRules.length;d<t;d++)if(f=this.sortedPathsRules[d],c.Utilities.startsWith(n,f.from)){let e=[];for(let i=0,r=f.to.length;i<r;i++)e.push(f.to[i]+n.substr(f.from.length));return e}return[n]}_addUrlArgsToUrl(n){return c.Utilities.containsQueryString(n)?n+"&"+this.options.urlArgs:n+"?"+this.options.urlArgs}_addUrlArgsIfNecessaryToUrl(n){return this.options.urlArgs?this._addUrlArgsToUrl(n):n}_addUrlArgsIfNecessaryToUrls(n){if(this.options.urlArgs)for(let f=0,d=n.length;f<d;f++)n[f]=this._addUrlArgsToUrl(n[f]);return n}moduleIdToPaths(n){if(this._env.isNode&&this.options.amdModulesPattern instanceof RegExp&&!this.options.amdModulesPattern.test(n))return this.isBuild()?["empty:"]:["node|"+n];let f=n,d;if(!c.Utilities.endsWith(f,".js")&&!c.Utilities.isAbsolutePath(f)){d=this._applyPaths(f);for(let t=0,e=d.length;t<e;t++)this.isBuild()&&d[t]==="empty:"||(c.Utilities.isAbsolutePath(d[t])||(d[t]=this.options.baseUrl+d[t]),!c.Utilities.endsWith(d[t],".js")&&!c.Utilities.containsQueryString(d[t])&&(d[t]=d[t]+".js"))}else!c.Utilities.endsWith(f,".js")&&!c.Utilities.containsQueryString(f)&&(f=f+".js"),d=[f];return this._addUrlArgsIfNecessaryToUrls(d)}requireToUrl(n){let f=n;return c.Utilities.isAbsolutePath(f)||(f=this._applyPaths(f)[0],c.Utilities.isAbsolutePath(f)||(f=this.options.baseUrl+f)),this._addUrlArgsIfNecessaryToUrl(f)}isBuild(){return this.options.isBuild}shouldInvokeFactory(n){return!!(!this.options.isBuild||c.Utilities.isAnonymousModule(n)||this.options.buildForceInvokeFactory&&this.options.buildForceInvokeFactory[n])}isDuplicateMessageIgnoredFor(n){return this.ignoreDuplicateModulesMap.hasOwnProperty(n)}getConfigForModule(n){if(this.options.config)return this.options.config[n]}shouldCatchError(){return this.options.catchError}shouldRecordStats(){return this.options.recordStats}onError(n){this.options.onError(n)}}c.Configuration=_})(AMDLoader||(AMDLoader={}));var AMDLoader;(function(c){class v{constructor(e){this._env=e,this._scriptLoader=null,this._callbackMap={}}load(e,i,r,s){if(!this._scriptLoader)if(this._env.isWebWorker)this._scriptLoader=new g;else if(this._env.isElectronRenderer){const{preferScriptTags:a}=e.getConfig().getOptionsLiteral();a?this._scriptLoader=new E:this._scriptLoader=new n(this._env)}else this._env.isNode?this._scriptLoader=new n(this._env):this._scriptLoader=new E;let o={callback:r,errorback:s};if(this._callbackMap.hasOwnProperty(i)){this._callbackMap[i].push(o);return}this._callbackMap[i]=[o],this._scriptLoader.load(e,i,()=>this.triggerCallback(i),a=>this.triggerErrorback(i,a))}triggerCallback(e){let i=this._callbackMap[e];delete this._callbackMap[e];for(let r=0;r<i.length;r++)i[r].callback()}triggerErrorback(e,i){let r=this._callbackMap[e];delete this._callbackMap[e];for(let s=0;s<r.length;s++)r[s].errorback(i)}}class E{attachListeners(e,i,r){let s=()=>{e.removeEventListener("load",o),e.removeEventListener("error",a)},o=l=>{s(),i()},a=l=>{s(),r(l)};e.addEventListener("load",o),e.addEventListener("error",a)}load(e,i,r,s){if(/^node\|/.test(i)){let o=e.getConfig().getOptionsLiteral(),a=f(e.getRecorder(),o.nodeRequire||c.global.nodeRequire),l=i.split("|"),u=null;try{u=a(l[1])}catch(h){s(h);return}e.enqueueDefineAnonymousModule([],()=>u),r()}else{let o=document.createElement("script");o.setAttribute("async","async"),o.setAttribute("type","text/javascript"),this.attachListeners(o,r,s);const{trustedTypesPolicy:a}=e.getConfig().getOptionsLiteral();a&&(i=a.createScriptURL(i)),o.setAttribute("src",i);const{cspNonce:l}=e.getConfig().getOptionsLiteral();l&&o.setAttribute("nonce",l),document.getElementsByTagName("head")[0].appendChild(o)}}}function _(t){const{trustedTypesPolicy:e}=t.getConfig().getOptionsLiteral();try{return(e?self.eval(e.createScript("","true")):new Function("true")).call(self),!0}catch{return!1}}class g{constructor(){this._cachedCanUseEval=null}_canUseEval(e){return this._cachedCanUseEval===null&&(this._cachedCanUseEval=_(e)),this._cachedCanUseEval}load(e,i,r,s){if(/^node\|/.test(i)){const o=e.getConfig().getOptionsLiteral(),a=f(e.getRecorder(),o.nodeRequire||c.global.nodeRequire),l=i.split("|");let u=null;try{u=a(l[1])}catch(h){s(h);return}e.enqueueDefineAnonymousModule([],function(){return u}),r()}else{const{trustedTypesPolicy:o}=e.getConfig().getOptionsLiteral();if(!(/^((http:)|(https:)|(file:))/.test(i)&&i.substring(0,self.origin.length)!==self.origin)&&this._canUseEval(e)){fetch(i).then(l=>{if(l.status!==200)throw new Error(l.statusText);return l.text()}).then(l=>{l=`${l}
//# sourceURL=${i}`,(o?self.eval(o.createScript("",l)):new Function(l)).call(self),r()}).then(void 0,s);return}try{o&&(i=o.createScriptURL(i)),importScripts(i),r()}catch(l){s(l)}}}}class n{constructor(e){this._env=e,this._didInitialize=!1,this._didPatchNodeRequire=!1}_init(e){this._didInitialize||(this._didInitialize=!0,this._fs=e("fs"),this._vm=e("vm"),this._path=e("path"),this._crypto=e("crypto"))}_initNodeRequire(e,i){const{nodeCachedData:r}=i.getConfig().getOptionsLiteral();if(!r||this._didPatchNodeRequire)return;this._didPatchNodeRequire=!0;const s=this,o=e("module");function a(l){const u=l.constructor;let h=function(y){try{return l.require(y)}finally{}};return h.resolve=function(y,C){return u._resolveFilename(y,l,!1,C)},h.resolve.paths=function(y){return u._resolveLookupPaths(y,l)},h.main=process.mainModule,h.extensions=u._extensions,h.cache=u._cache,h}o.prototype._compile=function(l,u){const h=o.wrap(l.replace(/^#!.*/,"")),p=i.getRecorder(),y=s._getCachedDataPath(r,u),C={filename:u};let b;try{const D=s._fs.readFileSync(y);b=D.slice(0,16),C.cachedData=D.slice(16),p.record(60,y)}catch{p.record(61,y)}const R=new s._vm.Script(h,C),I=R.runInThisContext(C),P=s._path.dirname(u),m=a(this),w=[this.exports,m,this,u,P,process,_commonjsGlobal,Buffer],U=I.apply(this.exports,w);return s._handleCachedData(R,h,y,!C.cachedData,i),s._verifyCachedData(R,h,y,b,i),U}}load(e,i,r,s){const o=e.getConfig().getOptionsLiteral(),a=f(e.getRecorder(),o.nodeRequire||c.global.nodeRequire),l=o.nodeInstrumenter||function(h){return h};this._init(a),this._initNodeRequire(a,e);let u=e.getRecorder();if(/^node\|/.test(i)){let h=i.split("|"),p=null;try{p=a(h[1])}catch(y){s(y);return}e.enqueueDefineAnonymousModule([],()=>p),r()}else{i=c.Utilities.fileUriToFilePath(this._env.isWindows,i);const h=this._path.normalize(i),p=this._getElectronRendererScriptPathOrUri(h),y=!!o.nodeCachedData,C=y?this._getCachedDataPath(o.nodeCachedData,i):void 0;this._readSourceAndCachedData(h,C,u,(b,R,I,P)=>{if(b){s(b);return}let m;R.charCodeAt(0)===n._BOM?m=n._PREFIX+R.substring(1)+n._SUFFIX:m=n._PREFIX+R+n._SUFFIX,m=l(m,h);const w={filename:p,cachedData:I},U=this._createAndEvalScript(e,m,w,r,s);this._handleCachedData(U,m,C,y&&!I,e),this._verifyCachedData(U,m,C,P,e)})}}_createAndEvalScript(e,i,r,s,o){const a=e.getRecorder();a.record(31,r.filename);const l=new this._vm.Script(i,r),u=l.runInThisContext(r),h=e.getGlobalAMDDefineFunc();let p=!1;const y=function(){return p=!0,h.apply(null,arguments)};return y.amd=h.amd,u.call(c.global,e.getGlobalAMDRequireFunc(),y,r.filename,this._path.dirname(r.filename)),a.record(32,r.filename),p?s():o(new Error(`Didn't receive define call in ${r.filename}!`)),l}_getElectronRendererScriptPathOrUri(e){if(!this._env.isElectronRenderer)return e;let i=e.match(/^([a-z])\:(.*)/i);return i?`file:///${(i[1].toUpperCase()+":"+i[2]).replace(/\\/g,"/")}`:`file://${e}`}_getCachedDataPath(e,i){const r=this._crypto.createHash("md5").update(i,"utf8").update(e.seed,"utf8").update(process.arch,"").digest("hex"),s=this._path.basename(i).replace(/\.js$/,"");return this._path.join(e.path,`${s}-${r}.code`)}_handleCachedData(e,i,r,s,o){e.cachedDataRejected?this._fs.unlink(r,a=>{o.getRecorder().record(62,r),this._createAndWriteCachedData(e,i,r,o),a&&o.getConfig().onError(a)}):s&&this._createAndWriteCachedData(e,i,r,o)}_createAndWriteCachedData(e,i,r,s){let o=Math.ceil(s.getConfig().getOptionsLiteral().nodeCachedData.writeDelay*(1+Math.random())),a=-1,l=0,u;const h=()=>{setTimeout(()=>{u||(u=this._crypto.createHash("md5").update(i,"utf8").digest());const p=e.createCachedData();if(!(p.length===0||p.length===a||l>=5)){if(p.length<a){h();return}a=p.length,this._fs.writeFile(r,Buffer.concat([u,p]),y=>{y&&s.getConfig().onError(y),s.getRecorder().record(63,r),h()})}},o*Math.pow(4,l++))};h()}_readSourceAndCachedData(e,i,r,s){if(!i)this._fs.readFile(e,{encoding:"utf8"},s);else{let o,a,l,u=2;const h=p=>{p?s(p):--u===0&&s(void 0,o,a,l)};this._fs.readFile(e,{encoding:"utf8"},(p,y)=>{o=y,h(p)}),this._fs.readFile(i,(p,y)=>{!p&&y&&y.length>0?(l=y.slice(0,16),a=y.slice(16),r.record(60,i)):r.record(61,i),h()})}}_verifyCachedData(e,i,r,s,o){s&&(e.cachedDataRejected||setTimeout(()=>{const a=this._crypto.createHash("md5").update(i,"utf8").digest();s.equals(a)||(o.getConfig().onError(new Error(`FAILED TO VERIFY CACHED DATA, deleting stale '${r}' now, but a RESTART IS REQUIRED`)),this._fs.unlink(r,l=>{l&&o.getConfig().onError(l)}))},Math.ceil(5e3*(1+Math.random()))))}}n._BOM=65279,n._PREFIX="(function (require, define, __filename, __dirname) { ",n._SUFFIX=`
});`;function f(t,e){if(e.__$__isRecorded)return e;const i=function(s){t.record(33,s);try{return e(s)}finally{t.record(34,s)}};return i.__$__isRecorded=!0,i}c.ensureRecordedNodeRequire=f;function d(t){return new v(t)}c.createScriptLoader=d})(AMDLoader||(AMDLoader={}));var AMDLoader;(function(c){class v{constructor(t){let e=t.lastIndexOf("/");e!==-1?this.fromModulePath=t.substr(0,e+1):this.fromModulePath=""}static _normalizeModuleId(t){let e=t,i;for(i=/\/\.\//;i.test(e);)e=e.replace(i,"/");for(e=e.replace(/^\.\//g,""),i=/\/(([^\/])|([^\/][^\/\.])|([^\/\.][^\/])|([^\/][^\/][^\/]+))\/\.\.\//;i.test(e);)e=e.replace(i,"/");return e=e.replace(/^(([^\/])|([^\/][^\/\.])|([^\/\.][^\/])|([^\/][^\/][^\/]+))\/\.\.\//,""),e}resolveModule(t){let e=t;return c.Utilities.isAbsolutePath(e)||(c.Utilities.startsWith(e,"./")||c.Utilities.startsWith(e,"../"))&&(e=v._normalizeModuleId(this.fromModulePath+e)),e}}v.ROOT=new v(""),c.ModuleIdResolver=v;class E{constructor(t,e,i,r,s,o){this.id=t,this.strId=e,this.dependencies=i,this._callback=r,this._errorback=s,this.moduleIdResolver=o,this.exports={},this.error=null,this.exportsPassedIn=!1,this.unresolvedDependenciesCount=this.dependencies.length,this._isComplete=!1}static _safeInvokeFunction(t,e){try{return{returnedValue:t.apply(c.global,e),producedError:null}}catch(i){return{returnedValue:null,producedError:i}}}static _invokeFactory(t,e,i,r){return t.shouldInvokeFactory(e)?t.shouldCatchError()?this._safeInvokeFunction(i,r):{returnedValue:i.apply(c.global,r),producedError:null}:{returnedValue:null,producedError:null}}complete(t,e,i,r){this._isComplete=!0;let s=null;if(this._callback)if(typeof this._callback=="function"){t.record(21,this.strId);let o=E._invokeFactory(e,this.strId,this._callback,i);s=o.producedError,t.record(22,this.strId),!s&&typeof o.returnedValue<"u"&&(!this.exportsPassedIn||c.Utilities.isEmpty(this.exports))&&(this.exports=o.returnedValue)}else this.exports=this._callback;if(s){let o=c.ensureError(s);o.phase="factory",o.moduleId=this.strId,o.neededBy=r(this.id),this.error=o,e.onError(o)}this.dependencies=null,this._callback=null,this._errorback=null,this.moduleIdResolver=null}onDependencyError(t){return this._isComplete=!0,this.error=t,this._errorback?(this._errorback(t),!0):!1}isComplete(){return this._isComplete}}c.Module=E;class _{constructor(){this._nextId=0,this._strModuleIdToIntModuleId=new Map,this._intModuleIdToStrModuleId=[],this.getModuleId("exports"),this.getModuleId("module"),this.getModuleId("require")}getMaxModuleId(){return this._nextId}getModuleId(t){let e=this._strModuleIdToIntModuleId.get(t);return typeof e>"u"&&(e=this._nextId++,this._strModuleIdToIntModuleId.set(t,e),this._intModuleIdToStrModuleId[e]=t),e}getStrModuleId(t){return this._intModuleIdToStrModuleId[t]}}class g{constructor(t){this.id=t}}g.EXPORTS=new g(0),g.MODULE=new g(1),g.REQUIRE=new g(2),c.RegularDependency=g;class n{constructor(t,e,i){this.id=t,this.pluginId=e,this.pluginParam=i}}c.PluginDependency=n;class f{constructor(t,e,i,r,s=0){this._env=t,this._scriptLoader=e,this._loaderAvailableTimestamp=s,this._defineFunc=i,this._requireFunc=r,this._moduleIdProvider=new _,this._config=new c.Configuration(this._env),this._hasDependencyCycle=!1,this._modules2=[],this._knownModules2=[],this._inverseDependencies2=[],this._inversePluginDependencies2=new Map,this._currentAnonymousDefineCall=null,this._recorder=null,this._buildInfoPath=[],this._buildInfoDefineStack=[],this._buildInfoDependencies=[],this._requireFunc.moduleManager=this}reset(){return new f(this._env,this._scriptLoader,this._defineFunc,this._requireFunc,this._loaderAvailableTimestamp)}getGlobalAMDDefineFunc(){return this._defineFunc}getGlobalAMDRequireFunc(){return this._requireFunc}static _findRelevantLocationInStack(t,e){let i=o=>o.replace(/\\/g,"/"),r=i(t),s=e.split(/\n/);for(let o=0;o<s.length;o++){let a=s[o].match(/(.*):(\d+):(\d+)\)?$/);if(a){let l=a[1],u=a[2],h=a[3],p=Math.max(l.lastIndexOf(" ")+1,l.lastIndexOf("(")+1);if(l=l.substr(p),l=i(l),l===r){let y={line:parseInt(u,10),col:parseInt(h,10)};return y.line===1&&(y.col-=53),y}}}throw new Error("Could not correlate define call site for needle "+t)}getBuildInfo(){if(!this._config.isBuild())return null;let t=[],e=0;for(let i=0,r=this._modules2.length;i<r;i++){let s=this._modules2[i];if(!s)continue;let o=this._buildInfoPath[s.id]||null,a=this._buildInfoDefineStack[s.id]||null,l=this._buildInfoDependencies[s.id];t[e++]={id:s.strId,path:o,defineLocation:o&&a?f._findRelevantLocationInStack(o,a):null,dependencies:l,shim:null,exports:s.exports}}return t}getRecorder(){return this._recorder||(this._config.shouldRecordStats()?this._recorder=new c.LoaderEventRecorder(this._loaderAvailableTimestamp):this._recorder=c.NullLoaderEventRecorder.INSTANCE),this._recorder}getLoaderEvents(){return this.getRecorder().getEvents()}enqueueDefineAnonymousModule(t,e){if(this._currentAnonymousDefineCall!==null)throw new Error("Can only have one anonymous define call per script file");let i=null;this._config.isBuild()&&(i=new Error("StackLocation").stack||null),this._currentAnonymousDefineCall={stack:i,dependencies:t,callback:e}}defineModule(t,e,i,r,s,o=new v(t)){let a=this._moduleIdProvider.getModuleId(t);if(this._modules2[a]){this._config.isDuplicateMessageIgnoredFor(t)||console.warn("Duplicate definition of module '"+t+"'");return}let l=new E(a,t,this._normalizeDependencies(e,o),i,r,o);this._modules2[a]=l,this._config.isBuild()&&(this._buildInfoDefineStack[a]=s,this._buildInfoDependencies[a]=(l.dependencies||[]).map(u=>this._moduleIdProvider.getStrModuleId(u.id))),this._resolve(l)}_normalizeDependency(t,e){if(t==="exports")return g.EXPORTS;if(t==="module")return g.MODULE;if(t==="require")return g.REQUIRE;let i=t.indexOf("!");if(i>=0){let r=e.resolveModule(t.substr(0,i)),s=e.resolveModule(t.substr(i+1)),o=this._moduleIdProvider.getModuleId(r+"!"+s),a=this._moduleIdProvider.getModuleId(r);return new n(o,a,s)}return new g(this._moduleIdProvider.getModuleId(e.resolveModule(t)))}_normalizeDependencies(t,e){let i=[],r=0;for(let s=0,o=t.length;s<o;s++)i[r++]=this._normalizeDependency(t[s],e);return i}_relativeRequire(t,e,i,r){if(typeof e=="string")return this.synchronousRequire(e,t);this.defineModule(c.Utilities.generateAnonymousModule(),e,i,r,null,t)}synchronousRequire(t,e=new v(t)){let i=this._normalizeDependency(t,e),r=this._modules2[i.id];if(!r)throw new Error("Check dependency list! Synchronous require cannot resolve module '"+t+"'. This is the first mention of this module!");if(!r.isComplete())throw new Error("Check dependency list! Synchronous require cannot resolve module '"+t+"'. This module has not been resolved completely yet.");if(r.error)throw r.error;return r.exports}configure(t,e){let i=this._config.shouldRecordStats();e?this._config=new c.Configuration(this._env,t):this._config=this._config.cloneAndMerge(t),this._config.shouldRecordStats()&&!i&&(this._recorder=null)}getConfig(){return this._config}_onLoad(t){if(this._currentAnonymousDefineCall!==null){let e=this._currentAnonymousDefineCall;this._currentAnonymousDefineCall=null,this.defineModule(this._moduleIdProvider.getStrModuleId(t),e.dependencies,e.callback,null,e.stack)}}_createLoadError(t,e){let i=this._moduleIdProvider.getStrModuleId(t),r=(this._inverseDependencies2[t]||[]).map(o=>this._moduleIdProvider.getStrModuleId(o));const s=c.ensureError(e);return s.phase="loading",s.moduleId=i,s.neededBy=r,s}_onLoadError(t,e){const i=this._createLoadError(t,e);this._modules2[t]||(this._modules2[t]=new E(t,this._moduleIdProvider.getStrModuleId(t),[],()=>{},null,null));let r=[];for(let a=0,l=this._moduleIdProvider.getMaxModuleId();a<l;a++)r[a]=!1;let s=!1,o=[];for(o.push(t),r[t]=!0;o.length>0;){let a=o.shift(),l=this._modules2[a];l&&(s=l.onDependencyError(i)||s);let u=this._inverseDependencies2[a];if(u)for(let h=0,p=u.length;h<p;h++){let y=u[h];r[y]||(o.push(y),r[y]=!0)}}s||this._config.onError(i)}_hasDependencyPath(t,e){let i=this._modules2[t];if(!i)return!1;let r=[];for(let o=0,a=this._moduleIdProvider.getMaxModuleId();o<a;o++)r[o]=!1;let s=[];for(s.push(i),r[t]=!0;s.length>0;){let a=s.shift().dependencies;if(a)for(let l=0,u=a.length;l<u;l++){let h=a[l];if(h.id===e)return!0;let p=this._modules2[h.id];p&&!r[h.id]&&(r[h.id]=!0,s.push(p))}}return!1}_findCyclePath(t,e,i){if(t===e||i===50)return[t];let r=this._modules2[t];if(!r)return null;let s=r.dependencies;if(s)for(let o=0,a=s.length;o<a;o++){let l=this._findCyclePath(s[o].id,e,i+1);if(l!==null)return l.push(t),l}return null}_createRequire(t){let e=(i,r,s)=>this._relativeRequire(t,i,r,s);return e.toUrl=i=>this._config.requireToUrl(t.resolveModule(i)),e.getStats=()=>this.getLoaderEvents(),e.hasDependencyCycle=()=>this._hasDependencyCycle,e.config=(i,r=!1)=>{this.configure(i,r)},e.__$__nodeRequire=c.global.nodeRequire,e}_loadModule(t){if(this._modules2[t]||this._knownModules2[t])return;this._knownModules2[t]=!0;let e=this._moduleIdProvider.getStrModuleId(t),i=this._config.moduleIdToPaths(e),r=/^@[^\/]+\/[^\/]+$/;this._env.isNode&&(e.indexOf("/")===-1||r.test(e))&&i.push("node|"+e);let s=-1,o=a=>{if(s++,s>=i.length)this._onLoadError(t,a);else{let l=i[s],u=this.getRecorder();if(this._config.isBuild()&&l==="empty:"){this._buildInfoPath[t]=l,this.defineModule(this._moduleIdProvider.getStrModuleId(t),[],null,null,null),this._onLoad(t);return}u.record(10,l),this._scriptLoader.load(this,l,()=>{this._config.isBuild()&&(this._buildInfoPath[t]=l),u.record(11,l),this._onLoad(t)},h=>{u.record(12,l),o(h)})}};o(null)}_loadPluginDependency(t,e){if(this._modules2[e.id]||this._knownModules2[e.id])return;this._knownModules2[e.id]=!0;let i=r=>{this.defineModule(this._moduleIdProvider.getStrModuleId(e.id),[],r,null,null)};i.error=r=>{this._config.onError(this._createLoadError(e.id,r))},t.load(e.pluginParam,this._createRequire(v.ROOT),i,this._config.getOptionsLiteral())}_resolve(t){let e=t.dependencies;if(e)for(let i=0,r=e.length;i<r;i++){let s=e[i];if(s===g.EXPORTS){t.exportsPassedIn=!0,t.unresolvedDependenciesCount--;continue}if(s===g.MODULE){t.unresolvedDependenciesCount--;continue}if(s===g.REQUIRE){t.unresolvedDependenciesCount--;continue}let o=this._modules2[s.id];if(o&&o.isComplete()){if(o.error){t.onDependencyError(o.error);return}t.unresolvedDependenciesCount--;continue}if(this._hasDependencyPath(s.id,t.id)){this._hasDependencyCycle=!0,console.warn("There is a dependency cycle between '"+this._moduleIdProvider.getStrModuleId(s.id)+"' and '"+this._moduleIdProvider.getStrModuleId(t.id)+"'. The cyclic path follows:");let a=this._findCyclePath(s.id,t.id,0)||[];a.reverse(),a.push(s.id),console.warn(a.map(l=>this._moduleIdProvider.getStrModuleId(l)).join(` => 
`)),t.unresolvedDependenciesCount--;continue}if(this._inverseDependencies2[s.id]=this._inverseDependencies2[s.id]||[],this._inverseDependencies2[s.id].push(t.id),s instanceof n){let a=this._modules2[s.pluginId];if(a&&a.isComplete()){this._loadPluginDependency(a.exports,s);continue}let l=this._inversePluginDependencies2.get(s.pluginId);l||(l=[],this._inversePluginDependencies2.set(s.pluginId,l)),l.push(s),this._loadModule(s.pluginId);continue}this._loadModule(s.id)}t.unresolvedDependenciesCount===0&&this._onModuleComplete(t)}_onModuleComplete(t){let e=this.getRecorder();if(t.isComplete())return;let i=t.dependencies,r=[];if(i)for(let l=0,u=i.length;l<u;l++){let h=i[l];if(h===g.EXPORTS){r[l]=t.exports;continue}if(h===g.MODULE){r[l]={id:t.strId,config:()=>this._config.getConfigForModule(t.strId)};continue}if(h===g.REQUIRE){r[l]=this._createRequire(t.moduleIdResolver);continue}let p=this._modules2[h.id];if(p){r[l]=p.exports;continue}r[l]=null}const s=l=>(this._inverseDependencies2[l]||[]).map(u=>this._moduleIdProvider.getStrModuleId(u));t.complete(e,this._config,r,s);let o=this._inverseDependencies2[t.id];if(this._inverseDependencies2[t.id]=null,o)for(let l=0,u=o.length;l<u;l++){let h=o[l],p=this._modules2[h];p.unresolvedDependenciesCount--,p.unresolvedDependenciesCount===0&&this._onModuleComplete(p)}let a=this._inversePluginDependencies2.get(t.id);if(a){this._inversePluginDependencies2.delete(t.id);for(let l=0,u=a.length;l<u;l++)this._loadPluginDependency(t.exports,a[l])}}}c.ModuleManager=f})(AMDLoader||(AMDLoader={}));var define,AMDLoader;(function(c){const v=new c.Environment;let E=null;const _=function(d,t,e){typeof d!="string"&&(e=t,t=d,d=null),(typeof t!="object"||!Array.isArray(t))&&(e=t,t=null),t||(t=["require","exports","module"]),d?E.defineModule(d,t,e,null,null):E.enqueueDefineAnonymousModule(t,e)};_.amd={jQuery:!0};const g=function(d,t=!1){E.configure(d,t)},n=function(){if(arguments.length===1){if(arguments[0]instanceof Object&&!Array.isArray(arguments[0])){g(arguments[0]);return}if(typeof arguments[0]=="string")return E.synchronousRequire(arguments[0])}if((arguments.length===2||arguments.length===3)&&Array.isArray(arguments[0])){E.defineModule(c.Utilities.generateAnonymousModule(),arguments[0],arguments[1],arguments[2],null);return}throw new Error("Unrecognized require call")};n.config=g,n.getConfig=function(){return E.getConfig().getOptionsLiteral()},n.reset=function(){E=E.reset()},n.getBuildInfo=function(){return E.getBuildInfo()},n.getStats=function(){return E.getLoaderEvents()},n.define=_;function f(){if(typeof c.global.require<"u"||typeof require<"u"){const d=c.global.require||require;if(typeof d=="function"&&typeof d.resolve=="function"){const t=c.ensureRecordedNodeRequire(E.getRecorder(),d);c.global.nodeRequire=t,n.nodeRequire=t,n.__$__nodeRequire=t}}v.isNode&&!v.isElectronRenderer&&!v.isElectronNodeIntegrationWebWorker?module.exports=n:(v.isElectronRenderer||(c.global.define=_),c.global.require=n)}c.init=f,(typeof c.global.define!="function"||!c.global.define.amd)&&(E=new c.ModuleManager(v,c.createScriptLoader(v),_,n,c.Utilities.getHighPerformanceTimestamp()),typeof c.global.require<"u"&&typeof c.global.require!="function"&&n.config(c.global.require),define=function(){return _.apply(null,arguments)},define.amd=_.amd,typeof doNotInitLoader>"u"&&f())})(AMDLoader||(AMDLoader={})),define("vs/css",["require","exports"],function(c,v){"use strict";Object.defineProperty(v,"__esModule",{value:!0}),v.load=E;function E(d,t,e,i){if(i=i||{},(i["vs/css"]||{}).disabled){e({});return}const s=t.toUrl(d+".css");_(d,s,()=>{e({})},o=>{typeof e.error=="function"&&e.error("Could not find "+s+".")})}function _(d,t,e,i){if(g(d,t)){e();return}n(d,t,e,i)}function g(d,t){const e=window.document.getElementsByTagName("link");for(let i=0,r=e.length;i<r;i++){const s=e[i].getAttribute("data-name"),o=e[i].getAttribute("href");if(s===d||o===t)return!0}return!1}function n(d,t,e,i){const r=document.createElement("link");r.setAttribute("rel","stylesheet"),r.setAttribute("type","text/css"),r.setAttribute("data-name",d),f(d,r,e,i),r.setAttribute("href",t),(window.document.head||window.document.getElementsByTagName("head")[0]).appendChild(r)}function f(d,t,e,i){const r=()=>{t.removeEventListener("load",s),t.removeEventListener("error",o)},s=a=>{r(),e()},o=a=>{r(),i(a)};t.addEventListener("load",s),t.addEventListener("error",o)}}),define("vs/nls",["require","exports"],function(c,v){"use strict";Object.defineProperty(v,"__esModule",{value:!0}),v.localize=i,v.localize2=r,v.getConfiguredDefaultLocale=s,v.setPseudoTranslation=o,v.create=a,v.load=l;let E=typeof document<"u"&&document.location&&document.location.hash.indexOf("pseudo=true")>=0;const _="i-default";function g(u,h){let p;return h.length===0?p=u:p=u.replace(/\{(\d+)\}/g,(y,C)=>{const b=C[0],R=h[b];let I=y;return typeof R=="string"?I=R:(typeof R=="number"||typeof R=="boolean"||R===void 0||R===null)&&(I=String(R)),I}),E&&(p="\uFF3B"+p.replace(/[aouei]/g,"$&$&")+"\uFF3D"),p}function n(u,h){let p=u[h];return p||(p=u["*"],p)?p:null}function f(u){return u.charAt(u.length-1)==="/"?u:u+"/"}async function d(u,h,p){const y=f(u)+f(h)+"vscode/"+f(p),C=await fetch(y);if(C.ok)return await C.json();throw new Error(`${C.status} - ${C.statusText}`)}function t(u){return function(h,p){const y=Array.prototype.slice.call(arguments,2);return g(u[h],y)}}function e(u){return(h,p,...y)=>({value:g(u[h],y),original:g(p,y)})}function i(u,h,...p){return g(h,p)}function r(u,h,...p){const y=g(h,p);return{value:y,original:y}}function s(u){}function o(u){E=u}function a(u,h){return{localize:t(h[u]),localize2:e(h[u]),getConfiguredDefaultLocale:h.getConfiguredDefaultLocale??(p=>{})}}function l(u,h,p,y){const C=y["vs/nls"]??{};if(!u||u.length===0)return p({localize:i,localize2:r,getConfiguredDefaultLocale:()=>C.availableLanguages?.["*"]});const b=C.availableLanguages?n(C.availableLanguages,u):null,R=b===null||b===_;let I=".nls";R||(I=I+"."+b);const P=m=>{Array.isArray(m)?(m.localize=t(m),m.localize2=e(m)):(m.localize=t(m[u]),m.localize2=e(m[u])),m.getConfiguredDefaultLocale=()=>C.availableLanguages?.["*"],p(m)};typeof C.loadBundle=="function"?C.loadBundle(u,b,(m,w)=>{m?h([u+".nls"],P):P(w)}):C.translationServiceUrl&&!R?(async()=>{try{const m=await d(C.translationServiceUrl,b,u);return P(m)}catch(m){if(!b.includes("-"))return console.error(m),h([u+".nls"],P);try{const w=b.split("-")[0],U=await d(C.translationServiceUrl,w,u);return C.availableLanguages??={},C.availableLanguages["*"]=w,P(U)}catch(w){return console.error(w),h([u+".nls"],P)}}})():h([u+I],P,m=>{if(I===".nls"){console.error("Failed trying to load default language strings",m);return}console.error(`Failed to load message bundle for language ${b}. Falling back to the default language:`,m),h([u+".nls"],P)})}});

//# sourceMappingURL=https://ticino.blob.core.windows.net/sourcemaps/b58957e67ee1e712cebf466b995adf4c5307b2bd/core/vs/loader.js.map
