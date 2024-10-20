let justJavaPrice = 2
let cafeAuLaitPriceS = 2
let cafeAuLaitPriceD = 3
let icedCappucinoS = 4.75
let icedCappucinoD = 5.75

let getJustJavaPrice = () => { return justJavaPrice; }
let getCafeAuLaitPriceS = () => { return cafeAuLaitPriceS; }
let getCafeAuLaitPriceD = () => { return cafeAuLaitPriceD; }
let getIcedCappucinoS = () => { return icedCappucinoS; }
let getIcedCappucinoD = () => { return icedCappucinoD; }

window.onload = function() {
    var products = document.querySelectorAll('.qty');
    products.forEach(function(product) {
        subTotalUpdate(product);

        product.addEventListener('keydown', function (event) {
            if (event.key === "Enter") {
                event.preventDefault(); // Prevent form submission
                subTotalUpdate(product);
            }
        });
    });
};

let getParentNode = (childType, ele) => {
    if (childType === "radio") {
        return ele.parentNode.parentNode.parentNode
    }
    if (childType === "number") {
        return ele.parentNode.parentNode
    }
}

function subTotalUpdate(event) {

    if (event.value < 0) {
        event.value = event.oldvalue
        return
    }

    const priceName = getParentNode(event.type, event).querySelector("tr td .priceOption input").name
    var priceRadioEle = document.getElementsByName(priceName)
    var price = 0

    var qty = getParentNode(event.type, event).querySelector("tr td .qty").value

    var subTotal = getParentNode(event.type, event).querySelector("tr td .subTotal")

    for (i = 0; i < priceRadioEle.length; i++) {
        if (priceRadioEle[i].checked) {
            price = priceRadioEle[i].value.split("_")[0]
            break
        }
    }

    subTotal.value = parseFloat(price * qty).toFixed(2)

    updateTotalPrice()
}

function updateTotalPrice() {
    var totalPrice = document.getElementById("totalPrice")
    var subTotal = document.getElementsByClassName("subTotal")

    const submitButton = document.getElementById("submit_order_button")
    
    var calculate = 0

    if (totalPrice !== null) {
        for (i = 0; i < subTotal.length; i++) {
            calculate += parseFloat(subTotal[i].value)
        }
    
        totalPrice.value = calculate.toFixed(2)
    }

    if (calculate > 0) {
        submitButton.disabled = false
    } else {
        submitButton.disabled = true
    }
}

function togglePriceInput(checkbox, productId) {
    const priceElements = document.querySelectorAll(`.priceText_${productId}`);
    const updateElements = document.querySelectorAll(`.updatePrice_${productId}`);

    const updateButton = document.getElementById("update_price_button")

    if (checkbox.checked) {
        priceElements.forEach(priceElement => priceElement.style.display = 'none');
        updateElements.forEach(updateElement => updateElement.style.display = 'inline-block');
    } else {
        priceElements.forEach(priceElement => priceElement.style.display = 'inline');
        updateElements.forEach(updateElement => {
            updateElement.style.display = 'none';
            updateElement.value = updateElement.previousElementSibling.innerHTML.split("$")[1];
        });
    }

    const checkboxes = document.getElementsByName(checkbox.name);
    updateButton.disabled = true

    for (i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].checked) {
            updateButton.disabled = false;
            break;
        }
    }
}

