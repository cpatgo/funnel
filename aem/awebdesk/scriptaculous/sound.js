/*
// Copyright (c) 2005-2007 Thomas Fuchs (http://script.aculo.us, http://mir.aculo.us)
//
// Based on code created by Jules Gravinese (http://www.webveteran.com/)
//
// script.aculo.us is freely distributable under the terms of an MIT-style license.
// For details, see the script.aculo.us web site: http://script.aculo.us/
*/
Sound={tracks:{},_enabled:true,template:new Template('<embed style="height:0" id="sound_#{track}_#{id}" src="#{url}" loop="false" autostart="true" hidden="true"/>'),enable:function(){Sound._enabled=true},disable:function(){Sound._enabled=false},play:function(a){if(!Sound._enabled){return}var b=Object.extend({track:"global",url:a,replace:false},arguments[1]||{});if(b.replace&&this.tracks[b.track]){$R(0,this.tracks[b.track].id).each(function(d){var c=$("sound_"+b.track+"_"+d);c.Stop&&c.Stop();c.remove()});this.tracks[b.track]=null}if(!this.tracks[b.track]){this.tracks[b.track]={id:0}}else{this.tracks[b.track].id++}b.id=this.tracks[b.track].id;$$("body")[0].insert(Prototype.Browser.IE?new Element("bgsound",{id:"sound_"+b.track+"_"+b.id,src:b.url,loop:1,autostart:true}):Sound.template.evaluate(b))}};if(Prototype.Browser.Gecko&&navigator.userAgent.indexOf("Win")>0){if(navigator.plugins&&$A(navigator.plugins).detect(function(a){return a.name.indexOf("QuickTime")!=-1})){Sound.template=new Template('<object id="sound_#{track}_#{id}" width="0" height="0" type="audio/mpeg" data="#{url}"/>')}else{Sound.play=function(){}}};