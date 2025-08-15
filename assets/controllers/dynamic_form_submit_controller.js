import { Controller } from '@hotwired/stimulus';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://symfony.com/bundles/StimulusBundle/current/index.html#lazy-stimulus-controllers
*/

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['input','sacrifacedInput']
    static values = {

        idToSearch: Array,

    }

    
    initialize() {
        this.sacrifacedInputValue = ''
        // Called once when the controller is first instantiated (per element)

        // Here you can initialize variables, create scoped callables for event
        // listeners, instantiate external libraries, etc.
        // this._fooBar = this.fooBar.bind(this)
    }

    connect() {
        // Called every time the controller is connected to the DOM
        // (on page load, when it's added to the DOM, moved in the DOM, etc.)

        // Here you can add event listeners on the element or target elements,
        // add or remove classes, attributes, dispatch custom events, etc.
        // this.fooTarget.addEventListener('click', this._fooBar)
        
        // 
        // this.inputTargets.forEach(input => {
            
        //     input.addEventListener("change",()=>{
        //         this.onDynamicChange()
        //     })
        // });
    }

    // Add custom controller actions here
    // fooBar() { this.fooTarget.classList.toggle(this.bazClass) }

    disconnect() {
        // Called anytime its element is disconnected from the DOM
        // (on page change, when it's removed from or moved in the DOM, etc.)

        // Here you should remove all event listeners added in "connect()" 
        // this.fooTarget.removeEventListener('click', this._fooBar)
    }

    async onDynamicChange(){
        
        this.sacrifacedInputValue = this.sacrifacedInputTarget && this.sacrifacedInputTarget.value
        this.sacrifacedInputTarget.value = ""
        const formData = this.getFormData()
        

        const response = await this.submitForm(formData)
        const data = await response.text()

        this.getNewFormField(data)
        this.sacrifacedInputTarget.value = this.sacrifacedInputValue

    }
    getFormData(){
        const formData = new FormData(this.element)
        
        for (let [key, value] of formData.entries()) {
            
    
        }
        return formData
    }

    async submitForm(body){

        const response = await fetch(this.element.action, {
            method: "POST",

            body: body
        })

        return response;
    }

    getNewFormField(data){
        const parser = new DOMParser();
        const fetchedDocument = parser.parseFromString(data, 'text/html');
        this.idToSearchValue.forEach((field) => {

            console.log(field)
            const containerId = Object.keys(field)[0]
            const rowId = Object.values(field)[0]
            console.log(containerId, rowId)
            
            const newField = fetchedDocument.querySelector("#" + rowId);
            const currentField = document.querySelector("#" + rowId);
            console.log(newField, currentField)
            
            


            if (newField) {
                if (currentField) {
                    // Si le champ existe déjà, on remplace son contenu
                    // currentField.innerHTML = newField.innerHTML;
                    document.querySelector("#" + containerId).insertAdjacentElement('afterend',newField);
                } else {
                    
                    
                    document.querySelector("#" + containerId).insertAdjacentElement('afterend',newField);
                }
            }else{
                if (currentField) {
                    // Si le champ existe déjà, on remplace son contenu
                    currentField.remove()
                } 

            }
        });


    }

    getNewForm(data){
        const parser = new DOMParser();
        const fetchedDocument = parser.parseFromString(data, 'text/html');
        return fetchedDocument.querySelector("form[name=\"" + this.element.getAttribute('name') +"\"]")
    }


}
