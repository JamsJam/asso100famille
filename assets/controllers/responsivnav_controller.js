import { Controller } from '@hotwired/stimulus';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['navDesktop', 'navMobile']
    static values = {
        breakpoint : { type : Number, default: 769},
    }



    initialize(){
        
    }

    connect(){
        this.getRightNavForm()
    }



    getRightNavForm(){

        if(this.isMobile()){
            this.navDesktopTargets.forEach(element => {
                
                element.classList.add("hide")
            });
            this.navMobileTargets.forEach(element => {
                
                element.classList.remove("hide")
            });
        }else{

            this.navDesktopTargets.forEach(element => {
                
                element.classList.remove("hide")
            });
            
            this.navMobileTargets.forEach(element => {
                
                element.classList.add("hide")
            });

        }
    }





    getNavModal(){

            this.dispatch("getModalEvent", { detail: { content: "" } })

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
