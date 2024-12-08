import { Controller } from '@hotwired/stimulus';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['step']
    static values = {
        step: Number,
    }

    connect() {

        // document.querySelector('#nextStep').addEventListener('click',(e)=>{
        //     e.preventDefault()
        //     this.increaseStep()
        //     document.querySelector('#nextStep').closest('form').submit()
        // })
        // document.querySelector('#previusStep').addEventListener('click',(e)=>{
        //     e.preventDefault()
        //     this.increaseStep()
        //     // document.querySelector('#previusStep').closest('form').submit()
        // })
        this.updateProgressBar();
    }

    // disconnect() {
    //     document.querySelector('#nextStep').removeEventListener('click',(e)=>{
    //         e.preventDefault()
    //         this.increaseStep()
    //         document.querySelector('#nextStep').closest('form').submit()
    //     })
    //     document.querySelector('#previusStep').removeEventListener('click',(e)=>{
    //         e.preventDefault()
    //         this.increaseStep()
    //         // document.querySelector('#previusStep').closest('form').submit()
    //     })
    // }

    updateProgressBar() {
        console.log(this.stepTargets);
        this.stepTargets.forEach((stepElement, index) => {
            if (index < this.stepValue) {
                // Mark the step as completed or active based on the current step value
                console.log(index, this.stepValue, 'add');
                stepElement.classList.add('active');
                
  

            } else {
                console.log(index, this.stepValue, 'remove');
                stepElement.classList.remove('active');
            }
        });
    }

    // Method to increase the step
    increaseStep(e) {
        
        if (this.stepValue < this.stepTargets.length) {
            this.stepValue++;
            console.log('up')
            this.updateProgressBar();  // Reflect the step change in the UI
        }
    }

    // Method to decrease the step
    decreaseStep() {
        if (this.stepValue > 1) {
            this.stepValue--;
            this.updateProgressBar();  // Reflect the step change in the UI
        }
    }
}
