import { Controller } from '@hotwired/stimulus';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = [
        'cancelbox'
    ]

    initialize(){}

    connect(){
        console.log('hello')
    }

    openCancelBox(e){
        e.preventDefault()
        this.cancelboxTarget.classList.replace('hidden', 'show')
    }
    closeCancelBox(){
        // e.preventDefault()
        this.cancelboxTarget.classList.replace('show','hidden' )
    }
}
