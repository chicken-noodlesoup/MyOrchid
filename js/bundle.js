
var MyOrchid = (function ( win, doc) {
    "use strict";
    var MyOrchid = { 
        
    }

    function docReady(callback){
        document.addEventListener('DOMContentLoaded', callback, false);
    }
    
    function winLoad(callback){
        window.addEventListener('load', callback, false);
    }
    
    function onResize(callback,selector){
        selector = (typeof selector === typeof undefined) ? window : selector;
        selector.addEventListener('resize', callback)
    }
    
    MyOrchid.docReady = docReady;
    MyOrchid.winLoad = winLoad;
    MyOrchid.onResize = onResize;

    return MyOrchid;
}(window, document));

MyOrchid = function (MyOrchid) {
    "use strict";


    MyOrchid.BS = {};
    MyOrchid.Addons = {};
    MyOrchid.Custom = {};
    MyOrchid.Toggle = {};
    MyOrchid.body = document.querySelector('body');
    MyOrchid.Win = { height: window.innerHeight, width: window.innerWidth };
    MyOrchid.Break = { mb: 420, sm: 576, md: 768, lg: 992, xl: 1200, xxl: 1400, any: Infinity };
    MyOrchid.isDark = (MyOrchid.body.classList.contains('dark-mode')) ? true : false;
    MyOrchid.monthList = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"]
    MyOrchid.docStyle = getComputedStyle(document.documentElement);


    
    // State @v1.0
    MyOrchid.State = {
        isRTL: (MyOrchid.body.classList.contains('has-rtl') || MyOrchid.body.getAttribute('dir') === 'rtl') ? true : false,
        isTouch: (("ontouchstart" in document.documentElement)) ? true : false,
        isMobile: (navigator.userAgent.match(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini|Windows Phone|/i)) ? true : false,
        asMobile: (MyOrchid.Win.width < MyOrchid.Break.md) ? true : false
    };

    // State Update @v1.1
    MyOrchid.StateUpdate = function () {
        MyOrchid.Win = { height: window.innerHeight, width: window.innerWidth };
        MyOrchid.State.asMobile = (MyOrchid.Win.width < MyOrchid.Break.md) ? true : false;
    };


    MyOrchid.hexRGB = function (hex, op) {
        var color, colorRGB; var opc = (op) ? op : 1;
        hex = hex.replace(/\s/g, '');
        if (/^#([A-Fa-f0-9]{3}){1,2}$/.test(hex)) {
            color = hex.substring(1).split('');
            if (color.length === 3) {
                color = [color[0], color[0], color[1], color[1], color[2], color[2]];
            }
            color = '0x' + color.join('');
            colorRGB = [(color >> 16) & 255, (color >> 8) & 255, color & 255].join(',');
            return (opc >= 1) ? 'rgba(' + colorRGB + ')' : 'rgba(' + colorRGB + ',' + opc + ')';
        }
        throw new Error('bad hex');
    }

    //Time Converter @v1.0
    MyOrchid.to12 = function(time) {
        time = time.toString ().match (/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];
        if (time.length > 1) { 
            time = time.slice (1);
            time.pop();
            time[5] = +time[0] < 12 ? ' AM' : ' PM'; // Set AM/PM
            time[0] = +time[0] % 12 || 12;
        }
        time = time.join ('');
        return time;
    }

    // attribute maker
    MyOrchid.attr = function(selector,property,value) {
        const att = document.createAttribute(property);
            att.value = value;
            selector.setAttributeNode(att);
    }
    
    //slide up
    MyOrchid.SlideUp = function (target, duration=500) {
        target.style.transitionProperty = 'height, margin, padding';
        target.style.transitionDuration = duration + 'ms';
        target.style.boxSizing = 'border-box';
        target.style.height = target.offsetHeight + 'px';
        target.offsetHeight; target.style.overflow = 'hidden'; target.style.height = 0;
        target.style.paddingTop = 0; target.style.paddingBottom = 0;
        target.style.marginTop = 0; target.style.marginBottom = 0;
        window.setTimeout( () => {
            target.style.display = 'none';
            target.style.removeProperty('height');
            target.style.removeProperty('padding-top');
            target.style.removeProperty('padding-bottom');
            target.style.removeProperty('margin-top');
            target.style.removeProperty('margin-bottom');
            target.style.removeProperty('overflow');
            target.style.removeProperty('transition-duration');
            target.style.removeProperty('transition-property');
        }, duration);
    };

    //side down
    MyOrchid.SlideDown = function (target, duration=500) {
        target.style.removeProperty('display');
        let display = window.getComputedStyle(target).display;
        if (display === 'none') display = 'block';
        target.style.display = display;
        let height = target.offsetHeight; 
        target.style.overflow = 'hidden'; target.style.height = 0; target.style.paddingTop = 0;
        target.style.paddingBottom = 0; target.style.marginTop = 0;
        target.style.marginBottom = 0; target.offsetHeight;
        target.style.boxSizing = 'border-box';
        target.style.transitionProperty = "height, margin, padding";
        target.style.transitionDuration = duration + 'ms';
        target.style.height = height + 'px';
        target.style.removeProperty('padding-top'); target.style.removeProperty('padding-bottom');
        target.style.removeProperty('margin-top'); target.style.removeProperty('margin-bottom');
        window.setTimeout( () => {
          target.style.removeProperty('height');
          target.style.removeProperty('overflow');
          target.style.removeProperty('transition-duration');
          target.style.removeProperty('transition-property');
        }, duration);
    };

    //slide toggle
    MyOrchid.SlideToggle = function (target, duration=500) {
        if (window.getComputedStyle(target).display === 'none') {
            return MyOrchid.SlideDown(target, duration);
          } else {
            return MyOrchid.SlideUp(target, duration);
        }
    };

    // to min
    MyOrchid.toMin = function(input) {
        let value = input.split(':');
        let hour = parseInt(value[0]);
        let min = (value[1] !== undefined) ? parseInt(value[1]) : 0;
        let time = (hour*60) + min
        return time;
    }

    //to time
    MyOrchid.toTime = function(totalMinutes) {
        const minutes = totalMinutes % 60;
        const hours = Math.floor(totalMinutes / 60);
        return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;
    }

    //generate random text
    MyOrchid.randomId = function(length) {
        var result           = '';
        var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        var charactersLength = characters.length;
        for ( var i = 0; i < length; i++ ) {
          result += characters.charAt(Math.floor(Math.random() * 
     charactersLength));
       }
       return result;
    }

    //get parent Elements
    MyOrchid.getParents = function(el, selector, filter) {
        // If no selector defined will bubble up all the way to *document*
        let parentSelector = (selector === undefined) ? document : document.querySelector(selector);
        var parents = [];
        var pNode = el.parentNode;
        
        while (pNode !== parentSelector) {
            var element = pNode;

            if(filter === undefined){
                parents.push(element); // Push that parentSelector you wanted to stop at
            }else{
                element.classList.contains(filter) && parents.push(element);
            }
            pNode = element.parentNode;
        }
        
        return parents;
    }

    //Extend Object
    MyOrchid.extendObject = function(obj, ext) {
        Object.keys(ext).forEach(function (key) { obj[key] = ext[key]; });
        return obj;
    }

    //Toggle
    MyOrchid.Toggle = {
        class: function(selector, selfActive = 'active', targetActive = 'active', parentActive = 'active'){
            let elm = document.querySelectorAll(selector);
            let overlayClass = 'nk-overlay';
            elm.forEach(item => {
                let target = item.dataset.target;
                let parent = item.dataset.parent;
                let targetElm = document.getElementById(target);
                let thisBreak = targetElm.dataset.break;
                let thisVisiable = targetElm.dataset.visiable;
                let parentElm = document.getElementById(parent);
                let trigger = document.querySelectorAll(`[data-target="${target}"]`);
                item.addEventListener('click', function(e){
                    e.preventDefault();
                    //trigger Toggle
                    trigger.forEach(elm =>{
                        !elm.classList.contains(selfActive) ? elm.classList.add(selfActive) : elm.classList.remove(selfActive);
                    });
                    //target Toggle
                    !targetElm.classList.contains(targetActive) ? targetElm.classList.add(targetActive) : targetElm.classList.remove(targetActive);

                    //overlay (if true)
                    let overlay = targetElm.dataset.overlay ? targetElm.dataset.overlay : false;

                    if(overlay){
                        let overlayTemplate = `${parent ? `<div class="${overlayClass}" data-target="${target}" data-parent="${parent}"></div>` : `<div class="${overlayClass}" data-target="${target}"></div>`}`;
                        if(targetElm.classList.contains(targetActive) && (thisVisiable ? window.innerWidth < eval(`MyOrchid.Break.${thisVisiable}`) : true) ){
                            targetElm.insertAdjacentHTML('beforebegin', overlayTemplate);
                        }else if(window.innerWidth < eval(`MyOrchid.Break.${thisVisiable}`)){
                           let thisOverlay = document.querySelector(`.${overlayClass}[data-target="${target}"]`);
                           thisOverlay.remove();
                        }
                    }
                    //parent Toggle (if defined)
                    if(parent){
                        !parentElm.classList.contains(parentActive) ? parentElm.classList.add(parentActive) : parentElm.classList.remove(parentActive);
                    }
                    //close overlay click
                    if(overlay && targetElm.classList.contains(targetActive) && (thisVisiable ? window.innerWidth < eval(`MyOrchid.Break.${thisVisiable}`) : true)){
                        let thisOverlay = document.querySelector(`.${overlayClass}[data-target="${target}"]`);
                        thisOverlay.addEventListener('click', function(e){
                            e.preventDefault();
                            //trigger Toggle
                            trigger.forEach(elm =>{
                                elm.classList.remove(selfActive);
                            });
                            targetElm.classList.remove(targetActive);
                            parent && parentElm.classList.remove(parentActive);
                            thisOverlay.remove();
                        })
                    }
                    // if(window.innerWidth < eval(`MyOrchid.Break.${thisBreak}`)){
                    //     targetElm.classList.add('toggle-collapsed');
                    // }
                    console.log(window.innerWidth > eval(`MyOrchid.Break.${thisVisiable}`));
                    if(window.innerWidth > eval(`MyOrchid.Break.${thisVisiable}`)){
                        targetElm.classList.contains(targetActive) ? targetElm.classList.add('toggle-visiable') : targetElm.classList.remove('toggle-visiable');
                    }
                })

                if(window.innerWidth <= eval(`MyOrchid.Break.${thisBreak}`)){
                    trigger.forEach(elm =>{
                        elm.classList.remove(selfActive);
                    });
                    let thisOverlay = document.querySelector(`.${overlayClass}[data-target="${target}"]`);
                    thisOverlay && thisOverlay.remove();
                    targetElm.classList.remove(targetActive);
                    parent && parentElm.classList.remove(parentActive);
                    targetElm.classList.add('toggle-collapsed');
                }

                //resize event 
                window.addEventListener('resize', function(){

                    if(window.innerWidth <= eval(`MyOrchid.Break.${thisBreak}`)){
                        setTimeout(() => {
                            targetElm.classList.add('toggle-collapsed');
                        }, 500);
                    }else{
                        targetElm.classList.remove('toggle-collapsed');
                    }
                    if(window.innerWidth >= eval(`MyOrchid.Break.${thisBreak}`)){
                        let thisOverlay = document.querySelector(`.${overlayClass}[data-target="${target}"]`);
                        thisOverlay && thisOverlay.remove();
                        if(!thisVisiable){
                            trigger.forEach(elm =>{
                                elm.classList.remove(selfActive);
                            });
                            targetElm.classList.remove(targetActive);
                            parent && parentElm.classList.remove(parentActive);
                        }
                    }else{
                        if(thisVisiable && targetElm.classList.contains('toggle-visiable') && !targetElm.classList.contains('toggle-collapsed')){
                            trigger.forEach(elm =>{
                                elm.classList.remove(selfActive);
                            });
                            let thisOverlay = document.querySelector(`.${overlayClass}[data-target="${target}"]`);
                            thisOverlay && thisOverlay.remove();
                            targetElm.classList.remove(targetActive);
                            parent && parentElm.classList.remove(parentActive);
                        }
                    }

                })
            });
        }
    }

    ///////////////////////////////
    //TimePicker 1.0
    /////////////////////////////
    MyOrchid.Custom.timePicker = function (selector,opt) {

        let options = {
            format: opt.format ? opt.format : 24,
            interval : opt.interval ? opt.interval : 30,
            start : opt.start ? opt.start : '00:00',
            end : opt.end ? opt.end : '23:59',
            class: {
                dropdown: 'nk-timepicker-dropdown',
                dropdownItem: 'nk-timepicker-time',
            }
        }

        let timeInterval = options.interval;
        let timeFormat = options.format;
        let timeStart = options.start;
        let timeEnd = options.end;
        let totalTime = MyOrchid.toMin(timeEnd) - MyOrchid.toMin(timeStart);
        let timeSlot = Math.floor(totalTime / timeInterval);
        let items = []

        let startTime = MyOrchid.toMin(timeStart);
      
        for (let i = 0; i < timeSlot+1; i++) {
            let currentTime = startTime;
            let timeString = function(){
                if(timeFormat == 12){
                    return MyOrchid.to12(MyOrchid.toTime(currentTime));
                }else{
                    return MyOrchid.toTime(currentTime)
                }
            };
            items.push(`<li><button class="dropdown-item ${options.class.dropdownItem}" data-picker-text="${timeString()}" type="button">
                ${timeString()}
            </button></li>`
            )
            startTime = currentTime + timeInterval;
        }

        let itemsMarkups = items.join('');
        MyOrchid.attr(selector,'data-bs-toggle','dropdown');
        MyOrchid.attr(selector,'data-bs-offset','0,5');

        let id = selector.id ? selector.id : MyOrchid.randomId(8);
        
        if(!selector.id){
            MyOrchid.attr(selector,'id',id);
        }

        let dropdownTemplate = `
        <ul class="dropdown-menu ${options.class.dropdown}" data-picker-id="${id}" style="max-height:320px;overflow:auto">
            ${itemsMarkups}
        </ul>
        `
        selector.insertAdjacentHTML('afterend', dropdownTemplate);

        let timeSelector = document.querySelectorAll(`.${options.class.dropdownItem}`);
        timeSelector.forEach(item => {
            item.addEventListener("click", function(e){
                e.preventDefault();
                let itemtext = item.dataset.pickerText;
                let input = document.getElementById(item.closest(`.${options.class.dropdown}`).dataset.pickerId);
                input.value = itemtext;
                //set active slot
                let allItems = item.closest(`.${options.class.dropdown}`).querySelectorAll(`.${options.class.dropdownItem}`);
                allItems.forEach(otherItem=>{
                    otherItem.classList.remove('active');
                })
                item.classList.add('active');
              });
        })
    }


    //Bootstrap
    MyOrchid.BS.tooltip = function (selector) {
        let tooltipEl = document.querySelectorAll(selector);
        let tooltipTriggerList = [].slice.call(tooltipEl);
        let tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    };

    MyOrchid.BS.popover = function (selector) {
        const popoverTriggerList = [].slice.call(document.querySelectorAll(selector));
        if(popoverTriggerList !== null){
            const popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl)
            });
        }
    };

    MyOrchid.BS.toast = function(selector) {
        const toastTrigger = document.querySelectorAll(selector);
        if(toastTrigger.length > 0){
            toastTrigger.forEach(item => {
                let target = item.dataset.bsTarget;
                const toastLive = document.getElementById(target);
                item.addEventListener('click', function () {
                    const toast = new bootstrap.Toast(toastLive);
                    toast.show()
                })
            })
        }
    };

    MyOrchid.BS.alertTemplate = function(selector, message, variant) {
        const target = document.getElementById(selector)
        const wrapper = document.createElement('div')
        wrapper.innerHTML = `<div class="alert alert-${variant ? variant : 'primary'} alert-dismissible" role="alert">
                <div>${message}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>`
        target.append(wrapper)
    };

    MyOrchid.BS.alert = function(selector, options) {
        const alertTrigger = document.querySelectorAll(selector);
        if(alertTrigger.length > 0){
            alertTrigger.forEach(item => {
                const target = item.dataset.bsTarget ? item.dataset.bsTarget : options.target;
                const variant = item.dataset.bsVariant ? item.dataset.bsVariant : options.variant;
                const content = item.dataset.bsContent ? item.dataset.bsContent : options.content; 
                item.addEventListener('click', function () {
                    MyOrchid.BS.alertTemplate(target, content, variant)
                })
            })
        }
    }

    MyOrchid.BS.validate = function(selector) {
        const forms = document.querySelectorAll(selector);
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
            }, false)
        })
    }

    ///////////////////////////////
    // Initial by default
    /////////////////////////////
    MyOrchid.onResize(MyOrchid.StateUpdate);

    return MyOrchid;
}(MyOrchid);
