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
        counter: {type: Number, default:0},
        mincount : Number
    } 
    

    counterTargetConnected(element){
        this.counterValue = element.value
        console.log(this.mincountValue)
        this.capMinValue()
    }
    
    increaseCount(e){
        this.counterValue++ ;
        // this.capMinValue()
        this.updateValue()
    }
    
    decreaseCount(e){
        this.counterValue-- ;
        // this.capMinValue()
        this.updateValue()

    }
    
    updateValue(){
        this.counterTarget.value = this.counterValue
        this.capMinValue()
        this.updateTotal()

    }

    capMinValue(){
        if(this.mincountValue >= this.counterValue ){
            this.btndecreaseTarget.style.pointerEvents = "none"
        }else{
            this.btndecreaseTarget.removeAttribute('style')

        }
    }

    updateTotal(){
        this.dispatch(("totalchange"))
    }
}
