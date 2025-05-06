import { Controller } from '@hotwired/stimulus';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = [
        'action_box',
        'action_container',
        'action_kebabicon',
        'action_close',
    ]


    initalize(){}
    connect(){

    }


    onClicAction(event){
        
        if(event.target === this.action_containerTarget){
            this.showActionBox()
            // target = event.target
            
            // this.action_kebabiconTarget.classList.toggle('visibility-hidden')
            // this.action_closeTarget.classList.toggle('visually-hidden')
        }else{
            
            this.hideActionBox()
        }
    }

    showActionBox(){
        this.action_boxTarget.classList.toggle('open')
        this.action_containerTarget.classList.toggle('box-open')
        this.action_kebabiconTarget.classList.toggle('visually-hidden')
        this.action_closeTarget.classList.toggle('visually-hidden')
    }
    
    hideActionBox(){
        this.action_boxTarget.classList.remove('open')
        this.action_containerTarget.classList.remove('box-open')
        this.action_kebabiconTarget.classList.remove('visually-hidden')
        this.action_closeTarget.classList.add('visually-hidden')

    }
    changeIcon(target){

    }

}
