var isUserAgentExcluded;
var Common = {};
Common.isCrmplusFocused = false;
Common.productUrl = '//'+window.location.host;
var Services = {};
Services.allServiceUrl = {};
Services.nameArray = [];
Services.currentLoadingServices = [];
Services.queuedServicesToLoadInBackGround = [];
Services.focusedService = "crmplus";//No I18N
Services.currentView = null;
Services.canBeTracked = {};
Services.addFrameCookie = function(serviceName){
if(serviceName === "motivator" || hasUserAgentExcluded()){
var con = console;
con.log('saddframe-->'+new Date().toGMTString());	
var path = "/"+serviceName+"/";
if(serviceName === "projects"){
path = "/portal/";//No I18N
}
document.cookie = 'crmplusframe=true;path='+path+';secure;';
}
}
Common.addEventListener = function(element, eventName, eventListener, useCapture) {
if (window.addEventListener) {
element.addEventListener(eventName, eventListener, useCapture);
} else {
element.attachEvent("on" + eventName, eventListener);
}
}
Common.jsonParse = function(toJson) {
try {
toJson = JSON.parse(toJson);
return toJson;
} catch (e) {
}
return undefined;
}
function processFrameURL(serviceName,URL,isServiceUrl,isFrameOriginNeeded,isWithoutDomain){
if(isServiceUrl == undefined){
isServiceUrl = false;
}
if(isWithoutDomain == undefined){
isWithoutDomain = false;
}
if(isFrameOriginNeeded === undefined){
isFrameOriginNeeded = true;
}
var processedURL = URL.replace("/getcpdata/","#");//No I18N;
var urlArray = processedURL.split("#");
processedURL = urlArray[0];
var hashString = urlArray[1];
if(processedURL.indexOf("salesinbox/") === 0){
processedURL = processedURL.replace("salesinbox/", "crm/salesinbox/");
}
if(processedURL.indexOf("projects/") === 0){
processedURL = processedURL.replace("projects/", "portal/");
}
var domain;
if(!isWithoutDomain){
if(isServiceUrl){
domain = Services.serviceUrl.get(serviceName, true);
}else{
domain = Services.serviceUrl.get(serviceName);
}
if(processedURL.indexOf(domain) !== 0){
processedURL = domain +"/"+ processedURL;
}
}else{
processedURL = "/"+ processedURL;
}
if(hashString !== undefined && hashString !== null){
processedURL = processedURL + "#" + hashString;
}
return processedURL;
} 
/*creating iframe*/
function createServiceIframe(serviceName,url){
var nameForIframe = serviceName + Date.now();
var idForIframe = serviceName+"LoadFrame";//No I18N
var frameElement  = document.createElement("iframe"); //No I18N
frameElement.setAttribute("onmouseover","onMouseOverOnFrame(event,\'"+serviceName+"\')");//No I18N
frameElement.setAttribute("onmouseleave","onMouseLeaveFromFrame(event)");//No I18N
frameElement.setAttribute("name",nameForIframe);//No I18N
frameElement.setAttribute("id",idForIframe);//No I18N
frameElement.setAttribute("allowfullscreen",true);//No I18N
frameElement.setAttribute("src",url);//No I18N
frameElement.setAttribute("class","serviceFrames");//No I18N
frameElement.setAttribute("style","position:absolute;width:100%;");//No I18N
var parentForIframe = document.getElementById("serviceFrameContainer");//No I18N
parentForIframe.appendChild(frameElement);
if(Services.currentLoadingServices.indexOf(serviceName) === -1){
Services.currentLoadingServices.push(serviceName);
}
Services.addFrameCookie(serviceName);
/*
temp logging whether internet is available at the time of iframe creation
*/
try{
tempLoggingForOnline[serviceName] = navigator.onLine;
}catch(e){
}
}
/**/
function onMouseOverOnFrame(event,serviceName){
event.stopPropagation();
Services.focusedService = serviceName;
Common.isCrmplusFocused=true;
}
function onMouseLeaveFromFrame(event){
event.stopPropagation();
Services.focusedService = "";
}
function handleInlineScript(scriptFunctionName){
if((window.isScriptLoaded === undefined) || (isScriptLoaded && isDomLoaded)){/*for zcadminlayout these varialbles will be defined for other it will be undefined*/
var functionReference = window[scriptFunctionName];
functionReference();
}else{
scriptExecutionArray.push(scriptFunctionName);
}
}
function hasUserAgentExcluded(){
if(isUserAgentExcluded === undefined){
var uaExcludeRegex = /iPad|iPhone|Edge\/|Trident\//;
isUserAgentExcluded = uaExcludeRegex.test(navigator.userAgent);
}
return isUserAgentExcluded;
}