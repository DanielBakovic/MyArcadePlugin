(function(){!function(a,b){return"function"==typeof define&&define.amd?define(function(){return b()}):"object"==typeof exports?module.exports=b():a.ifvisible=b()}(this,function(){var a,b,c,d,e,f,g,h,i,j,k,l,m,n;return i={},c=document,k=!1,l="active",g=6e4,f=!1,b=function(){var a,b,c,d,e,f,g;return a=function(){return(65536*(1+Math.random())|0).toString(16).substring(1)},e=function(){return a()+a()+"-"+a()+"-"+a()+"-"+a()+"-"+a()+a()+a()},f={},c="__ceGUID",b=function(a,b,d){return a[c]=void 0,a[c]||(a[c]="ifvisible.object.event.identifier"),f[a[c]]||(f[a[c]]={}),f[a[c]][b]||(f[a[c]][b]=[]),f[a[c]][b].push(d)},d=function(a,b,d){var e,g,h,i,j;if(a[c]&&f[a[c]]&&f[a[c]][b]){for(i=f[a[c]][b],j=[],g=0,h=i.length;h>g;g++)e=i[g],j.push(e(d||{}));return j}},g=function(a,b,d){var e,g,h,i,j;if(d){if(a[c]&&f[a[c]]&&f[a[c]][b])for(j=f[a[c]][b],g=h=0,i=j.length;i>h;g=++h)if(e=j[g],e===d)return f[a[c]][b].splice(g,1),e}else if(a[c]&&f[a[c]]&&f[a[c]][b])return delete f[a[c]][b]},{add:b,remove:g,fire:d}}(),a=function(){var a;return a=!1,function(b,c,d){return a||(a=b.addEventListener?function(a,b,c){return a.addEventListener(b,c,!1)}:b.attachEvent?function(a,b,c){return a.attachEvent("on"+b,c,!1)}:function(a,b,c){return a["on"+b]=c}),a(b,c,d)}}(),d=function(a,b){var d;return c.createEventObject?a.fireEvent("on"+b,d):(d=c.createEvent("HTMLEvents"),d.initEvent(b,!0,!0),!a.dispatchEvent(d))},h=function(){var a,b,d,e,f;for(e=void 0,f=3,d=c.createElement("div"),a=d.getElementsByTagName("i"),b=function(){return d.innerHTML="<!--[if gt IE "+ ++f+"]><i></i><![endif]-->",a[0]};b(););return f>4?f:e}(),e=!1,n=void 0,"undefined"!=typeof c.hidden?(e="hidden",n="visibilitychange"):"undefined"!=typeof c.mozHidden?(e="mozHidden",n="mozvisibilitychange"):"undefined"!=typeof c.msHidden?(e="msHidden",n="msvisibilitychange"):"undefined"!=typeof c.webkitHidden&&(e="webkitHidden",n="webkitvisibilitychange"),m=function(){var b,d;return b=!1,d=function(){return clearTimeout(b),"active"!==l&&i.wakeup(),f=+new Date,b=setTimeout(function(){return"active"===l?i.idle():void 0},g)},d(),a(c,"mousemove",d),a(c,"keyup",d),a(window,"scroll",d),i.focus(d),i.wakeup(d)},j=function(){var b;return k?!0:(e===!1?(b="blur",9>h&&(b="focusout"),a(window,b,function(){return i.blur()}),a(window,"focus",function(){return i.focus()})):a(c,n,function(){return c[e]?i.blur():i.focus()},!1),k=!0,m())},i={setIdleDuration:function(a){return g=1e3*a},getIdleDuration:function(){return g},getIdleInfo:function(){var a,b;return a=+new Date,b={},"idle"===l?(b.isIdle=!0,b.idleFor=a-f,b.timeLeft=0,b.timeLeftPer=100):(b.isIdle=!1,b.idleFor=a-f,b.timeLeft=f+g-a,b.timeLeftPer=(100-100*b.timeLeft/g).toFixed(2)),b},focus:function(a){return"function"==typeof a?this.on("focus",a):(l="active",b.fire(this,"focus"),b.fire(this,"wakeup"),b.fire(this,"statusChanged",{status:l}))},blur:function(a){return"function"==typeof a?this.on("blur",a):(l="hidden",b.fire(this,"blur"),b.fire(this,"idle"),b.fire(this,"statusChanged",{status:l}))},idle:function(a){return"function"==typeof a?this.on("idle",a):(l="idle",b.fire(this,"idle"),b.fire(this,"statusChanged",{status:l}))},wakeup:function(a){return"function"==typeof a?this.on("wakeup",a):(l="active",b.fire(this,"wakeup"),b.fire(this,"statusChanged",{status:l}))},on:function(a,c){return j(),b.add(this,a,c)},off:function(a,c){return j(),b.remove(this,a,c)},onEvery:function(a,b){var c,d;return j(),c=!1,b&&(d=setInterval(function(){return"active"===l&&c===!1?b():void 0},1e3*a)),{stop:function(){return clearInterval(d)},pause:function(){return c=!0},resume:function(){return c=!1},code:d,callback:b}},now:function(a){return j(),l===(a||"active")}}})}).call(this);

/*Copyright (c) 2015 Jason Zissman
Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
the Software, and to permit persons to whom the Software is furnished to do so,
subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

/*
  Notice!  This project requires ifvisible.js to run.  You can get a copy from
  the ifinvisible.js github (https://github.com/serkanyersen/ifvisible.js) or
  by running "bower install timeme.js", which will install both TimeMe.js and ifvisible.js.
*/

(function(ifvisible) {

  TimeMe = {
    startStopTimes: {},

    idleTimeout: 30,

    currentPageName: "default-page-name",

    getIfVisibleHandle: function(){
      if (typeof ifvisible === 'object') {
        return ifvisible;
      } else {
        if (typeof console !== "undefined") {
          console.log("Required dependency (ifvisible.js) not found.  Make sure it has been included.");
        }
        throw {
          name: "MissingDependencyException",
          message: "Required dependency (ifvisible.js) not found.  Make sure it has been included."
        };
      }
    },

    startTimer: function() {
      var pageName = TimeMe.currentPageName;
      if (TimeMe.startStopTimes[pageName] === undefined){
        TimeMe.startStopTimes[pageName] = [];
      } else {
        var arrayOfTimes = TimeMe.startStopTimes[pageName];
        var latestStartStopEntry = arrayOfTimes[arrayOfTimes.length -1];
        if (latestStartStopEntry !== undefined && latestStartStopEntry.stopTime === undefined) {
          // Can't start new timer until previous finishes.
          return;
        }
      }
      TimeMe.startStopTimes[pageName].push({
        "startTime": new Date(),
        "stopTime": undefined
      });
    },

    stopTimer: function() {
      var pageName = TimeMe.currentPageName;
      var arrayOfTimes = TimeMe.startStopTimes[pageName];
      if (arrayOfTimes === undefined || arrayOfTimes.length === 0){
        // Can't stop timer before you've started it.
        return;
      }
      if (arrayOfTimes[arrayOfTimes.length -1].stopTime === undefined) {
        arrayOfTimes[arrayOfTimes.length -1].stopTime = new Date();
      }
    },

    getTimeOnCurrentPageInSeconds : function() {
      return TimeMe.getTimeOnPageInSeconds(TimeMe.currentPageName);
    },

    getTimeOnPageInSeconds: function(pageName) {

      var totalTimeOnPage = 0;

      var arrayOfTimes = TimeMe.startStopTimes[pageName];
      if (arrayOfTimes === undefined){
        // Can't get time on page before you've started the timer.
        return;
      }

      var timeSpentOnPageInSeconds = 0;
      for(var i=0; i < arrayOfTimes.length; i++) {
        var startTime = arrayOfTimes[i].startTime;
        var stopTime = arrayOfTimes[i].stopTime;
        if (stopTime === undefined){
          stopTime = new Date();
        }
        var difference = stopTime - startTime;
        timeSpentOnPageInSeconds += (difference / 1000);
      }

      totalTimeOnPage = Number(timeSpentOnPageInSeconds);
      return totalTimeOnPage;
    },

    getTimeOnAllPagesInSeconds: function() {
      var allTimes = [];
      var pageNames = Object.keys(TimeMe.startStopTimes);
      for (var i=0; i < pageNames.length; i++){
        var pageName = pageNames[i];
        var timeOnPage = TimeMe.getTimeOnPageInSeconds(pageName);
        allTimes.push({
          "pageName": pageName,
          "timeOnPage": timeOnPage
        });
      }
      return allTimes;
    },

    setIdleDurationInSeconds: function(duration) {
      var durationFloat = parseFloat(duration);
      if (isNaN(durationFloat) === false){
        TimeMe.getIfVisibleHandle().setIdleDuration(durationFloat);
        TimeMe.idleTimeout = durationFloat;
      } else {
        throw {
          name: "InvalidDurationException",
          message: "An invalid duration time (" + duration + ") was provided."
        };
      }
    },

    setCurrentPageName: function(pageName) {
      TimeMe.currentPageName = pageName;
    },

    resetRecordedPageTime: function(pageName) {
      delete TimeMe.startStopTimes[pageName];
    },

    resetAllRecordedPageTimes: function() {
      var pageNames = Object.keys(TimeMe.startStopTimes);
      for (var i=0; i < pageNames.length; i++){
        TimeMe.resetRecordedPageTime(pageNames[i]);
      }
    },

    listenForVisibilityEvents: function(){
      TimeMe.getIfVisibleHandle().on("blur", function(){
        TimeMe.stopTimer();
      });

      TimeMe.getIfVisibleHandle().on("focus", function(){
        TimeMe.startTimer();
      });

      TimeMe.getIfVisibleHandle().on("idle", function(){
        if (TimeMe.idleTimeout > 0){
          TimeMe.stopTimer();
        }
      });

      TimeMe.getIfVisibleHandle().on("wakeup", function(){
        if (TimeMe.idleTimeout > 0){
          TimeMe.startTimer();
        }
      });
    },

    initialize: function (){
      TimeMe.listenForVisibilityEvents();
      TimeMe.startTimer();
    }
  };

  if (typeof define === "function" && define.amd) {
    define(function() {
      return TimeMe;
    });
  } else {
    window.TimeMe = TimeMe;
  }
})(this.ifvisible);

// Init
TimeMe.setIdleDurationInSeconds(30);
TimeMe.setCurrentPageName( myarcade_stats_i18n.slug );
TimeMe.initialize();
window.onload = function() {
  setInterval( function() {
    var timeSpentOnPage = TimeMe.getTimeOnCurrentPageInSeconds();
  }, 1000);
}
window.addEventListener( "beforeunload", function(e) {
  if ( typeof( myarcade_stats_token ) !== 'undefined' ) {
    jQuery.ajax({
      type: 'POST',
      async: true,
      url: myarcade_stats_i18n.ajaxurl,
      data: {
        action: 'myarcade_stats_duration_do_ajax',
        duration: TimeMe.getTimeOnCurrentPageInSeconds().toFixed(0),
        token: myarcade_stats_token,
        nonce: myarcade_stats_i18n.nonce
      }
    });
  }
});