import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = [
        'customDonation',
        'radioCustom'
    ];
    
    static values = {
        isCustom: { type: Boolean, default: false }
    };

    connect() {
        // Check if customDonationTarget exists before trying to display it
        if (this.hasCustomDonationTarget) {
            this.displayerChanger(this.isCustomValue);
        }

        this.verifyFormStep()
    }

    isCustomValueChanged(value) {
        // Only attempt to change display if customDonationTarget exists
        if (this.hasCustomDonationTarget) {
            this.displayerChanger(value);
        }
    }

    displayerChanger(isDisplay) {
        // Check if customDonationTarget exists before modifying its style
        if (this.hasCustomDonationTarget) {
            isDisplay 
                ? this.customDonationTarget.removeAttribute('style')
                : this.customDonationTarget.style.display = 'none';
        }
    }

    isCustomHandle() {
        // Update isCustomValue based on the radio button's state if radioCustomTarget exists
        if (this.hasRadioCustomTarget) {
            this.isCustomValue = this.radioCustomTarget.checked;
        }
    }

    nextStep({params}){
        const {step} = params
        this.dispatch('updateStep',{detail : { step }})
    }

    previusStep({params}){
        const {step} = params
        this.dispatch('updateStep',{detail : {step}})
    }

    verifyFormStep(){
        const step = this.element.getAttribute('data-step')

        this.dispatch('updateStep',{detail : {step}})
    }
}
