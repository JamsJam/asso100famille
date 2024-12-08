import { Controller } from '@hotwired/stimulus';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = [
        'btnincrease', 
        'btndecrease',
        'counter'
    ]

    static values = {
        counter: {type: Number, default:0}
    } 
    

    counterTargetConnected(element){
        this.counterValue = element.value
    }
    
    increaseCount(){
        this.counterValue++ ;
        this.updateValue()
    }
    
    decreaseCount(){
        this.counterValue-- ;
        this.updateValue()

    }
    
    updateValue(){
        this.counterTarget.value = this.counterValue
    }
}
