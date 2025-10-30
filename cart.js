document.querySelectorAll(".addToCart, .cartRemoveOne, .cartAddOne, .removeFromCart").forEach(button =>{
    button.addEventListener("click", updateCart);
    button.addEventListener("keydown",function(event){
        if(event.key === "Enter") updateCart(event);
    })
})

if(document.getElementById("Cart")){
    document.getElementById("Cart").addEventListener("click", redirectToCart);
    document.getElementById("Cart").addEventListener("keydown",function(event){
        if(event.key === "Enter") redirectToCart();
    })
}
function redirectToCart(){
    window.location.pathname = "cart.php";
}

function updateCart(event){
    event.preventDefault();
    const element = event.currentTarget;
    const productId = element.dataset.id;

    let quantity = 0;
    if(element.classList.contains("cartAddOne")) quantity = 1;
    else if(element.classList.contains("cartRemoveOne")) quantity = -1;
    else if(element.classList.contains("removeFromCart")) quantity = "remove";
    else if(element.classList.contains("addToCart")){
        let productName = element.parentElement.querySelector(".productName").textContent;
        
        let productAlreadyInCart = document.createElement("div");
        productAlreadyInCart.className = "productAlreadyInCart";
        productAlreadyInCart.innerText = "Already In Cart";
        
        element.parentElement.appendChild(productAlreadyInCart);
        element.parentElement.querySelector(".addToCart").remove();

        alert("Added " + productName + " to cart");
    }
    
    let cartOptions = element.closest(".cartOptions");

    fetch("updateCart.php", {
        method: "POST",
        headers: {
           "Content-Type": "application/json",
           "X-Requested-With": "XMLHttpRequest"
        },
        body: JSON.stringify({
           "productId": productId,
           "ProductQuantity":quantity
        })
     })
     .then(response => response.json())
     .then(data => {
        if(cartOptions) cartOptions.querySelector(".cartQuantityNumber").innerText = data.Quantity;
        if(data.productID === "removed") element.closest(".productCard").remove();
        if(window.location.pathname.includes("cart.php")){

            if(document.querySelector(".productCard")){//to check if there are products in cart or not

                const totalProducts = document.getElementById("totalProducts");
                const totalPrice = document.getElementById("totalPrice");

                totalPrice.innerHTML = 0;
                let totalPriceInt = 0;
                document.querySelectorAll(".productPrice").forEach(function(element){
                    totalPriceInt += (parseInt(element.innerHTML.slice(0,-2)) * parseInt(element.parentElement.querySelector(".cartOptions").querySelector(".cartQuantityNumber").innerHTML));
                })
                totalProducts.innerHTML = "Total products : " + data.totalProducts;
                totalPrice.innerHTML = "Total price : " + totalPriceInt + " ₹";

            }
            else{
                document.querySelector(".cartResult").remove();
                renderEmptyCart();
            }
        }
     });
}


if(document.querySelector(".buyCart")){
    document.querySelector(".buyCart").addEventListener("click", handlePayment);
    document.querySelector(".buyCart").addEventListener("keydown",function(event){
        if(event.key === "Enter") handlePayment();
    })
}

function handlePayment(){
    fetch("payment.php",{
        method: "POST",
        headers:{
            "Content-Type" : "application/json",
            "X-Requested-With": "XMLHttpRequest"
        },
        body: JSON.stringify({
            "purchased" : "true"
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
        if(data.OrderPlaced == "true"){
            document.querySelectorAll(".productCard").forEach(element => element.remove());
            document.querySelector(".cartResult").remove();

            alert("Payment successful");

            renderEmptyCart();

        }
        else
            alert("Can not currently palce oreder!");
    });
}

function renderEmptyCart(){
    let emptyCart = document.createElement("div");
        emptyCart.className = "emptyCart";
        emptyCart.innerHTML = `<h2>Cart is Empty</h2><h3>Visit <a href='products.php' style='text-decoration:none;color:rgba(0, 115, 255)'>Products Page</a> to browse products</h3>`;
        document.body.appendChild(emptyCart);
}

