Common.isNewUI = true;
Common.isAdmin = true;
Common.myOrgId = '60000366540';//NO OUTPUTENCODING 

var isTPScriptLoaded = false;
var isCPScriptLoaded = false;
var preLoadedService = null;
var pendingServicesToSendUIReady = [];
var isDomLoaded = false;
var isScriptLoaded = false;
var isDomAndScriptLoaded = false;
var scriptExecutionArray = [];
var tempLoggingForInitCheck = {};
var tempLoggingForURLChange = {};
var tempLoggingForUIReady = {};
var tempLoggingForOnline = {};
function init(){
try{
Common.addEventListener(document,"DOMContentLoaded",OnDOMContentLoaded);
Common.addEventListener(window,"message",onMessageReceive);
Common.addEventListener(window,"load",onWindowLoad);

Services.allServiceUrl['crm'] = '\x2F\x2Fcrm.zoho.in';//No I18N
Services.nameArray.push('crm');

Services.allServiceUrl['salesiq'] = '\x2F\x2Fsalesiq.zoho.in';//No I18N
Services.nameArray.push('salesiq');

Services.allServiceUrl['support'] = '\x2F\x2Fdesk.zoho.in';//No I18N
Services.nameArray.push('support');

Services.allServiceUrl['projects'] = '\x2F\x2Fprojects.zoho.in';//No I18N
Services.nameArray.push('projects');

Services.allServiceUrl['campaigns'] = '\x2F\x2Fcampaigns.zoho.in';//No I18N
Services.nameArray.push('campaigns');

Services.allServiceUrl['social'] = '\x2F\x2Fsocial.zoho.in';//No I18N
Services.nameArray.push('social');

Services.allServiceUrl['survey'] = '\x2F\x2Fsurvey.zoho.in';//No I18N
Services.nameArray.push('survey');

Services.allServiceUrl['motivator'] = '\x2F\x2Fmotivator.zoho.in';//No I18N
Services.nameArray.push('motivator');

Services.allServiceUrl['reports'] = '\x2F\x2Freports.zoho.in';//No I18N
Services.nameArray.push('reports');

if(Common.isNewUI == true && window.location.host.indexOf(".csez.zohocorpin.com") == -1){
handleForFirstFrame();
}
}catch(e){}
}
function onWindowLoad(){
setTimeout(loadWmsResources,0);
}
function OnDOMContentLoaded(){
isDomLoaded = true;
if(isScriptLoaded){
executeInitialJavascript();
}
}
function onMainScriptsLoaded(type){
if(type === undefined || type === "TP"){
isTPScriptLoaded = true;
}
if(type === undefined || type === "CP"){
isCPScriptLoaded = true;
}
if(isTPScriptLoaded && isCPScriptLoaded){
isScriptLoaded = true;
}
if(isScriptLoaded && isDomLoaded){
executeInitialJavascript();
}
}
function handleForFirstFrame(){
var hash = window.location.hash.trim();
var hashValue = hash.substring(1);
var hashArr = hashValue.split("/");
var module = hashArr[0];
if (module === "portal") {
module = "projects"; // No I18N
}
if(hashArr != undefined && hashArr.length>1 && Services.nameArray.indexOf(module) != -1){
hashValue = Common.productUrl + processFrameURL(module,hashValue,false,true,true);
preLoadedService = module;
createServiceIframe(module,hashValue);
}
}
function onMessageReceive(event){
if(!isDomAndScriptLoaded){
var eventOrigin = event.origin;
/* 
Check the event origin whether it is coming from crmplus services Note: All services have same url in localzoho and idc.
*/
var curdomain = window.location.host;
if( curdomain.indexOf(".zohocorpin.com") === -1 && eventOrigin !== location.protocol+Common.productUrl){
return;
}
var eventData;
if(typeof event.data !== "object"){
eventData = Common.jsonParse(event.data);
}else{
eventData = event.data;
}
if(eventData === undefined || !eventData.isfromcrmpluslibrary){
return;
}
var eventType = eventData.type;
if(eventType === "InitCheck"){
var serviceName = eventData.fromService;
tempLoggingForInitCheck[serviceName] = true;
var toPostMessage = {};
toPostMessage.isfromcrmplus = true;
toPostMessage.type="InitReply";
if(event != undefined){
var pendingServiceJson = {};
pendingServiceJson.serviceName = serviceName;
pendingServiceJson.windowObject = event.source;
pendingServicesToSendUIReady.push(pendingServiceJson);
event.source.postMessage(JSON.stringify(toPostMessage),event.origin);
}
}
}else{
ServiceCommunicator.action.receiveMessage(event);
}
}
function executeInitialJavascript(){
isDomAndScriptLoaded = true;
registerHandleBarHelper();
Security.init("cmcsrfparam");
Common.crmplusPortalCount = parseInt("1");
Common.versionForMultipleTabCommunication = 'b0e475a5f7f8cbe98a3da5053661511b';

Common.currentPortalName = 'rdrahulgupta';

Common.initializaApp();
Common.isAdmin = true;
Common.currentUserDisplayName = 'Dharam\x20Dharam';
Common.setMyZUId('60000343234');
Common.setMyId('89000000002013');
Common.staticImgUrl = 'https\x3A\x2F\x2Fimg.zohostatic.in\x2Fcrm_plus\x2FVFILES\x2Fimages';

Common.staticHashedFileNames['loading.gif'] = 'loading.7a8c438e101bef3978fd2f1668c33f60.gif';//NO OUTPUTENCODING

Common.staticHashedFileNames['zoho_busy.gif'] = 'zoho_busy.0627af70d8ac7f00bfba0ac19599c9b1.gif';//NO OUTPUTENCODING

Common.staticHashedFileNames['favicon.ico'] = 'favicon.71614dde1bcbbb568620d4cc11e216c4.ico';//NO OUTPUTENCODING

Common.staticHashedFileNames['favcrm.ico'] = 'favcrm.71614dde1bcbbb568620d4cc11e216c4.ico';//NO OUTPUTENCODING

Common.staticHashedFileNames['favsupport.ico'] = 'favsupport.008c83e778aa58385b2643b0e3b3e72d.ico';//NO OUTPUTENCODING

Common.staticHashedFileNames['favsalesiq.ico'] = 'favsalesiq.fd73b95282e9d16506d9f40871a8a8ec.ico';//NO OUTPUTENCODING

Common.staticHashedFileNames['favcampaigns.ico'] = 'favcampaigns.72d691ebd4de3a1d9c479bc982d74454.ico';//NO OUTPUTENCODING

Common.staticHashedFileNames['favprojects.ico'] = 'favprojects.b6a2962c0646ff979b256f69e49688ef.ico';//NO OUTPUTENCODING

Common.staticHashedFileNames['favsocial.ico'] = 'favsocial.6979406d3d16b6cee18312284d60bf5d.ico';//NO OUTPUTENCODING

Common.staticHashedFileNames['favsurvey.ico'] = 'favsurvey.25ce47a2e7e64d17481e1f9dabc44e56.ico';//NO OUTPUTENCODING

Common.staticHashedFileNames['favreports.ico'] = 'favreports.28528e6fcd65189b6883efa9f80a14f2.ico';//NO OUTPUTENCODING

Common.staticHashedFileNames['favsalesinbox.ico'] = 'favsalesinbox.a36029c16509b68cd64d61da378f20d6.ico';//NO OUTPUTENCODING

Common.staticHashedFileNames['favmotivator.ico'] = 'favmotivator.58dcc5483f40109c6ff67523ec040800.ico';//NO OUTPUTENCODING

Common.userOptions = {"isleftpanelvisible":true};//NO OUTPUTENCODING

Common.ownerEmailID = 'rdrahulgupta\x40gmail.com';
Common.userEmailID = 'rdrahulgupta\x40gmail.com';
 Common.timezone= 'Asia\x2FKolkata'; Common.country= 'United\x20States'; Common.language= 'English';
if (window.addEventListener){
window.addEventListener("resize", Common.handleWindowSize, false);//No I18N
window.addEventListener("keydown", Common.handleKeyBoardShortCutKeyCode, true);//No I18N
window.addEventListener("keyup", Common.handleKeyBoardShortCutKeyCode, true);//No I18N
} else {
window.attachEvent("onresize", Common.handleWindowSize);//No I18N
window.addEventListener("onkeydown", Common.handleKeyBoardShortCutKeyCode);//No I18N
window.addEventListener("onkeyup", Common.handleKeyBoardShortCutKeyCode);//No I18N
}
Services.allServiceUrl[ZCPConstants.SERVICE_SALESINBOX] = Services.allServiceUrl[ZCPConstants.SERVICE_CRM];
Services.allServiceUrl['product'] = '\x2F\x2Fcrmplus.zoho.in';//No I18N
Services.allServiceUrl['userphotourl'] = '\x2F\x2Fcontacts.zoho.in';//No I18N
Services.allServiceUrl['accounts'] = '\x2F\x2Fadmin.zoho.in';//No I18N
Services.allServiceUrl['onezoho'] = '\x2F\x2Fadmin.zoho.in';//No I18N
Services.allServiceUrl['paymentsurl'] = 'https\x3A\x2F\x2Fpayments.zoho.in\x2Fhtml\x2Fstore\x2Findex.html\x23subscription\x3FserviceId\x3D180000\x26customId\x3Db5d46e30fe3a20682b1f7f68ce6f49f2930c60ad28818f599c6009cc61c81844';//No I18N
CRMPlusLocalStorage.init();
Common.setScroll('productsidepanelscroll');//No I18N
Common.handleWindowSize();
Common.hideWmsAnnouncement();
Common.isPushToSpotLight = false;
Feedback.addEventListener();
Common.showCommonNotification("excessusers", Common.excessUsers);//No I18N
ServiceCommunicator.handlePendingUIReadyCalls();
Services.showCompanyFetchPopUp = false;
if(Services.showCompanyFetchPopUp){
Services.showCompanyFetchPopUp = Common.isShowCompanyPopUpClientCheck();
}
// ZohoOne Changes

executePendingScripts();
}
function loadWmsResources(){
var script = document.createElement("script");//No I18N
script.src = "//"+"js.zohostatic.in\x2Fichat\x2Fv288_https\x2Fjs\x2Fwmsbar.js";//No I18N
script.onload = onWmsLoad;
document.head.appendChild(script);
var style = document.createElement("link");//No I18N
style.href = "//"+"css.zohostatic.in\x2Fichat\x2Fv288_https\x2Fcss\x2Fwmsbar.css";//No I18N
style.rel = "stylesheet";//No I18N
document.head.appendChild(style);
}

