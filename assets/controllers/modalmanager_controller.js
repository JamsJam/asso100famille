import { Controller } from '@hotwired/stimulus';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['modal']
    static values = {
        breakpoint : { type : Number, default: 769},
    }
    // ...
    initialize(){
        
    }

    connect(){
        this.showOnlyOnMobile()
    }

    
    openModal(e){
    console.log(e)
        this.modalTarget.classList.add('open')
    }


    closeModal(){
        this.modalTarget.classList.remove('open')
    }
    hideModal(){
        
        this.modalTarget.classList.add('hide')
    }
    showModal(){
        this.modalTarget.classList.remove('hide')

    }

    showOnlyOnMobile(){
        if(this.isMobile()){
            this.showModal()

        }else{
            this.closeModal()
            this.hideModal()

        }
    }


        /**
     * return true if viewport width under breakpointValue 
     * @returns {bool}
     */
        isMobile()
        {
            return window.innerWidth < this.breakpointValue
        }
}
