/**
 * Feedback
 */
class Feedback {
    classIcon = {
        error: 'fi-rr-cross',
        success: 'fi-rr-check',
        fail: 'fi-rr-exclamation',
        blue: 'fi-rr-info',
        cooldown: 'fi-rr-alarm-clock',
        money: 'fi-rr-dollar'
    };

    /**
     * Genereates a new feedback msg
     * @param {string} msg Feedback msg
     * @param {string} parentSelector Parent selector of where to prepend the feedback 
     * @param {string} feedbackClass Feedback class (Defaults to success) 
     */
    constructor(msg, parentSelector, feedbackClass='success', delay) {
        this.msg = msg;
        this.feedbackClass = feedbackClass;
        this.parentSelector = parentSelector;
        this.feedbackElm = $(
            `<div class="feedback ${this.feedbackClass}">
                <i class="fi ${this.classIcon[this.feedbackClass]}"></i>
                <span>${this.msg}</span>
            </div>`);

        this.show();

        if(delay != null) {
            setTimeout(() => {
                this.feedbackElm.fadeOut(700);
            }, delay);
        }
    }

    show(){
        $(this.parentSelector).prepend(this.feedbackElm);
    }
};