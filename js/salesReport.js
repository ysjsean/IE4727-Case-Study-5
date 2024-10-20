var bestSellingProduct = ""
var bestSellingCategory = ""

// Function to fetch and insert content dynamically based on checkbox selection
// Version 1
function loadContentBasedOnCheckbox(checkbox, containerId, url) {
    const container = document.getElementById(containerId);

    if (checkbox.checked) {
        // Fetch data from the server and load it into the container
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                container.innerHTML = data.response;
                bestSellingCategory = data.bestSellingCategory ?? bestSellingCategory;
                bestSellingProduct = data.bestSellingProduct ?? bestSellingProduct;
                
                container.classList.remove('hidden');
                generatePopularProduct();
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
            });
    } else {
        container.innerHTML = ``;  // Clear the content if checkbox is unchecked
        container.classList.add('hidden');
    }
    return;
}

const generatePopularProduct = () => {
    const container = document.getElementById("bestProductAns");

    if (bestSellingProduct && bestSellingCategory) {
        container.innerHTML = bestSellingCategory + " of " + bestSellingProduct;
    }
}



// Version 2
function checkBoxRedirect(){
    const categoryReport = document.getElementById('categoryReport');
    const productReport = document.getElementById('productReport');

    if(categoryReport.checked){
        window.location.href='./salesByCategoriesv2.php';
        categoryReport.checked = false;
        return false;
    }

    if(productReport.checked){
        window.location.href ='./salesByProductv2.php';
        productReport.checked = false;
        return false;
    }

    return true;
}