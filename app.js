//Navbar and theme toggle.
let previousY = window.scrollY;
const navbar = document.getElementById("navbar");

const themeToggle = document.getElementById("themeToggle");
const SVGLight = document.getElementById("SVGLight");
const SVGDark = document.getElementById("SVGDark");
const ThreeLineMenuImgLight = document.getElementById("ThreeLineMenuImgLight");
const ThreeLineMenuImgDark = document.getElementById("ThreeLineMenuImgDark");
const cart = document.getElementById("cart");
const cartSVGLight = document.getElementById("cartSVGLight");
const cartSVGDark = document.getElementById("cartSVGDark");




//Side bar
const dropDownMenu = document.getElementById("dropDownMenu");
const sidebar = document.getElementById("sidebar");

//Category bar
const categoryList = document.getElementById("categoryList");
const categoryMenu = document.getElementById("categoryMenu");
const categoryMenuLight = document.getElementById("categoryMenuLight");
const categoryMenuDark = document.getElementById("categoryMenuDark");


window.addEventListener("scroll", () =>{
    if(sidebar && sidebar.classList.contains("active") ){
        //to stop nav from hiding when side bar or category menu is open.
        return;
    }
    if(categoryList && categoryList.classList.contains("active")){
        return;
    }
    else if(previousY < window.scrollY){
        navbar.classList.add("navhide");
        if(categoryMenu) categoryMenu.classList.add("categoryMenuHide");
    }
    else{
        navbar.classList.remove("navhide");
        if(categoryMenu) categoryMenu.classList.remove("categoryMenuHide");
    }
    previousY = window.scrollY;
});


//For Dark and Light Mode change
function changeMode(){
    const current = document.documentElement.getAttribute("web-theme");
    setTheme(current === "dark" ? "light" : "dark");
    toggleSVGImages();
}

function setTheme(theme) {
    document.documentElement.setAttribute("web-theme", theme);
    localStorage.setItem("theme", theme);
}
function toggleSVGImages(){
    const current = document.documentElement.getAttribute("web-theme");
    if(current === "dark"){
        SVGLight.style.opacity = 1;
        SVGLight.style.transform = "translateX(0px)";
        SVGDark.style.opacity = 0;
        SVGDark.style.transform = "translateX(0px)";

        if(dropDownMenu){
            ThreeLineMenuImgLight.style.opacity = 1;
            ThreeLineMenuImgDark.style.opacity = 0;
        }

        if(cartSVGLight){
            cartSVGLight.style.opacity = 1;
            cartSVGDark.style.opacity = 0;
        }

        if(categoryMenu){
            categoryMenuLight.style.opacity = 1;
            categoryMenuDark.style.opacity = 0;
        }
    }
    else{
        SVGLight.style.opacity = 0;
        SVGLight.style.transform = "translateX(22px)";
        SVGDark.style.opacity = 1;
        SVGDark.style.transform = "translateX(22px)";
    
        if(dropDownMenu){
            ThreeLineMenuImgLight.style.opacity = 0;
            ThreeLineMenuImgDark.style.opacity = 1;
        }

        if(cartSVGLight){
            cartSVGLight.style.opacity = 0;
            cartSVGDark.style.opacity = 1;
        }

        if(categoryMenu){
            categoryMenuLight.style.opacity = 0;
            categoryMenuDark.style.opacity = 1;
        }
    }
}
themeToggle.addEventListener("click",changeMode);
themeToggle.addEventListener("keydown",function(event){
    if(event.key == "Enter") changeMode();
});
toggleSVGImages();



//Side bar
if(dropDownMenu){
    function sidebarToggle(){
        const active = sidebar.classList.toggle("active");
        sidebar.toggleAttribute("inert",!active);
    }
    dropDownMenu.addEventListener("click",sidebarToggle);
    dropDownMenu.addEventListener("keydown",function(event){
        if(event.key == "Enter") sidebarToggle();
    });
    document.addEventListener("click", (event) => {
        if (sidebar.classList.contains("active") && 
            !sidebar.contains(event.target) && 
            !dropDownMenu.contains(event.target)) {
            sidebarToggle();
        }
    });

    
}



//category bar
if(categoryMenu){
    function categoryListToggle(){
        const active = categoryList.classList.toggle("active");
        categoryList.toggleAttribute("inert",!active);
    }
    categoryMenu.addEventListener("click",categoryListToggle);
    categoryMenu.addEventListener("keydown",function(event){
        if(event.key == "Enter") categoryListToggle();
    });
    document.addEventListener("click", (event) => {
        if (categoryList.classList.contains("active") && 
            !categoryList.contains(event.target) && 
            !categoryMenu.contains(event.target)) {
            categoryListToggle();
        }
    });
}




