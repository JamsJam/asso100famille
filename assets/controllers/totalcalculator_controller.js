import { Controller } from '@hotwired/stimulus';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = [
        'input1',
        'quantity1',
        'input2',
        'quantity2',
        'input3',
        'quantity3',
        'input4',
        'quantity4',
        'html1',
        'htmlquantity1',
        'html2',
        'htmlquantity2',
        'html3',
        'htmlquantity3',
        'html4',
        'htmlquantity4',
        'radio',
        "customDonation",
        'total'
    ]

    initialize(){}

    connect() {
        // Initialiser le calcul total
        this.calculateTotal();

        
    }
    
    radioTargetConnected(){
        
        this.radioTarget.addEventListener('change',()=> this.calculateTotal.bind(this))
        console.log('connect')
    }
    
    deconnect(){
        
        if(this.hasRadioTarget){
            this.radioTargets.forEach(element => {
                element.removeEventListener('change')
            });
        }
    }
    
    updateTotal(){
        this.calculateTotal();

    }
    
    
    calculateTotal() {
        const args = [];
    
        // Ajouter les valeurs et quantités uniquement pour les cibles présentes
        if (this.hasInput1Target && this.hasQuantity1Target) {
            args.push([this.input1Target.value || 0, this.quantity1Target.value || 1]);
        }
        if (this.hasInput2Target && this.hasQuantity2Target) {
            args.push([this.input2Target.value || 0, this.quantity2Target.value || 1]);
        }
        if (this.hasInput3Target && this.hasQuantity3Target) {
            args.push([this.input3Target.value || 0, this.quantity3Target.value || 1]);
        }
        if (this.hasInput4Target && this.hasQuantity4Target) {
            args.push([this.input4Target.value || 0, this.quantity4Target.value || 1]);
        }
    
        // Ajouter les éléments HTML si présents
        if (this.hasHtml1Target ) {
            args.push([
                this.html1Target.innerText.replace('€', '').trim() || 0,
                this.hasHtmlquantity1Target ? this.htmlquantity1Target.value : 1
            ]);
        }
        if (this.hasHtml2Target ) {
            args.push([
                this.html2Target.innerText.replace('€', '').trim() || 0,
                this.hasHtmlquantity2Target ? this.htmlquantity2Target.value : 1
            ]);
        }
        if (this.hasHtml3Target ) {
            args.push([
                this.html3Target.innerText.replace('€', '').trim() || 0,
                this.hasHtmlquantity3Target ? this.htmlquantity3Target.value : 1
            ]);
        }
        if (this.hasHtml4Target ) {
            args.push([
                this.html4Target.innerText.replace('€', '').trim() || 0,
                this.hasHtmlquantity4Target ? this.htmlquantity4Target.value : 1
            ]);
        }
    
        // // Ajouter les éléments HTML si présents
        // if (this.hasHtml1Target && this.hasHtmlquantity1Target) {
        //     args.push([
        //         this.html1Target.innerText.replace('€', '').trim() || 0,
        //         this.htmlquantity1Target.value || 1
        //     ]);
        // }
        // if (this.hasHtml2Target && this.hasHtmlquantity2Target) {
        //     args.push([
        //         this.html2Target.innerText.replace('€', '').trim() || 0,
        //         this.htmlquantity2Target.value || 1
        //     ]);
        // }
        // if (this.hasHtml3Target && this.hasHtmlquantity3Target) {
        //     args.push([
        //         this.html3Target.innerText.replace('€', '').trim() || 0,
        //         this.htmlquantity3Target.value || 1
        //     ]);
        // }
        // if (this.hasHtml4Target && this.hasHtmlquantity4Target) {
        //     args.push([
        //         this.html4Target.innerText.replace('€', '').trim() || 0,
        //         this.htmlquantity4Target.value || 1
        //     ]);
        // }
        if (this.hasRadioTarget ) {
            

            const checkRadio = this.radioTargets.find((radio)=>radio.checked)

            if(checkRadio.getAttribute('data-value') === "custom"){

                args.push([
                    (this.customDonationTarget.value)  || 0,
                    1
                ]);
            }else{

                args.push([
                    checkRadio.getAttribute('data-value')  || 0,
                    1
                ]);
            }
        }
    
        // Calcul du total
        const total = args.reduce((accu, [val, quantity]) => {
            val = parseInt(val) // Convertir en nombre (par défaut 0)
            quantity = parseInt(quantity); // Convertir en nombre (par défaut 1)
            return accu + val * quantity; // Ajouter au total
        }, 0);
    
        // Mettre à jour la cible `total` uniquement si elle existe
        if (this.hasTotalTarget) {
            this.totalTarget.innerText = `${total.toFixed(2)} €`; // Formater avec deux décimales
        }
    }
    
}
